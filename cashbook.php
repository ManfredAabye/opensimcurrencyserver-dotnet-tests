<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Cashbook</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">

<div class="w3-card w3-padding w3-margin w3-light-grey" style="max-width:500px;">
    <h2>Cashbook abfragen</h2>
    <form id="cashbookForm" class="w3-container" method="post" autocomplete="off">
        <label class="w3-text-grey">MoneyServer-URL:</label>
        <input class="w3-input w3-border" type="text" name="url" required
               value="<?= htmlspecialchars($_POST['url'] ?? 'http://localhost:8008/api/cashbook') ?>">

        <label class="w3-text-grey">API Key:</label>
        <input class="w3-input w3-border" type="text" name="apiKey" required
               value="<?= htmlspecialchars($_POST['apiKey'] ?? '') ?>">

        <label class="w3-text-grey">Allowed User:</label>
        <input class="w3-input w3-border" type="text" name="allowedUser" required
               value="<?= htmlspecialchars($_POST['allowedUser'] ?? '') ?>">

        <label class="w3-text-grey">UserID:</label>
        <input class="w3-input w3-border" type="text" name="userID" required
               value="<?= htmlspecialchars($_POST['userID'] ?? '') ?>">

        <label class="w3-text-grey">Limit (optional):</label>
        <input class="w3-input w3-border" type="number" name="limit" min="1"
               value="<?= htmlspecialchars($_POST['limit'] ?? '20') ?>">

        <button class="w3-button w3-green w3-margin-top" type="submit" name="start" id="startBtn">
            Start
        </button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start'])) {
    $url         = trim($_POST['url']);
    $apiKey      = trim($_POST['apiKey']);
    $allowedUser = trim($_POST['allowedUser']);
    $userID      = trim($_POST['userID']);
    $limit       = isset($_POST['limit']) ? intval($_POST['limit']) : 20;

    $data = [
        "action"      => "getCashbook",
        "apiKey"      => $apiKey,
        "allowedUser" => $allowedUser,
        "userID"      => $userID,
        "limit"       => $limit
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 8,
        ]
    ];
    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    echo '<div class="w3-card w3-margin-top w3-padding w3-white">';
    if ($result === FALSE) {
        echo '<div class="w3-text-red">Fehler beim Abrufen der Daten!<br>';
        echo 'URL: ' . htmlspecialchars($url) . '</div>';
    } else {
        $json = json_decode($result, true);
        if (isset($json['success']) && $json['success'] && isset($json['cashbook']) && is_array($json['cashbook'])) {
            echo "<h3>Cashbook für UserID: <span class='w3-text-blue'>" . htmlspecialchars($userID) . "</span></h3>";
            echo '<div class="w3-responsive">';
            echo '<table class="w3-table-all w3-small w3-hoverable">';
            echo '<tr class="w3-grey">';
            echo '<th>Datum</th><th>Beschreibung</th><th>Einnahme</th><th>Ausgabe</th><th>Saldo</th>';
            echo '</tr>';
            foreach ($json['cashbook'] as $entry) {
                // Case-insensitive Mapping
                $entry = array_change_key_case($entry, CASE_LOWER);
                echo '<tr>';
                echo '<td>' . htmlspecialchars($entry['date'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($entry['description'] ?? '') . '</td>';
                echo '<td>' . (isset($entry['income']) ? number_format($entry['income'], 0, ',', '.') : '') . '</td>';
                echo '<td>' . (isset($entry['expense']) ? number_format($entry['expense'], 0, ',', '.') : '') . '</td>';
                echo '<td>' . (isset($entry['balance']) ? number_format($entry['balance'], 0, ',', '.') : '') . '</td>';
                echo '</tr>';
            }
            echo '</table></div>';
        } else {
            echo '<div class="w3-text-red">Fehler: ' . htmlspecialchars($json['error'] ?? 'Unbekannter Fehler oder ungültige Antwort') . '</div>';
        }
    }
    echo '</div>';
}
?>

</body>
</html>
