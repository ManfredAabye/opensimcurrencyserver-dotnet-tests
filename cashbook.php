<!DOCTYPE html>
<html>
<head>
    <title>Cashbook Test</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">

<div class="w3-card w3-padding w3-margin w3-light-grey" style="max-width:500px;">
    <h2>Cashbook abfragen</h2>
    <form id="cashbookForm" class="w3-container" method="post" autocomplete="off">
        <label class="w3-text-grey">UserID:</label>
        <input class="w3-input w3-border" type="text" name="userID" required>
        <label class="w3-text-grey">Limit (optional):</label>
        <input class="w3-input w3-border" type="number" name="limit" min="1" value="20">
        <button class="w3-button w3-green w3-margin-top" type="submit" name="start" id="startBtn">
            Start
        </button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start'])) {
    $userID = trim($_POST['userID']);
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 20;

    // Trage hier die URL deines MoneyServer ein:
    $url = "http://DEIN-SERVER:8008/XoopenSimMoney";

    $data = [
        "method" => "getCashbook",
        "userID" => $userID,
        "limit"  => $limit
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
        echo '<div class="w3-text-red">Fehler beim Abrufen der Daten!</div>';
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
                echo '<tr>';
                echo '<td>' . htmlspecialchars($entry['date'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($entry['description'] ?? '') . '</td>';
                echo '<td>' . ($entry['income'] !== null ? number_format($entry['income'], 0, ',', '.') : '') . '</td>';
                echo '<td>' . ($entry['expense'] !== null ? number_format($entry['expense'], 0, ',', '.') : '') . '</td>';
                echo '<td>' . number_format($entry['balance'], 0, ',', '.') . '</td>';
                echo '</tr>';
            }
            echo '</table></div>';
        } else {
            echo '<div class="w3-text-red">Fehler: ' . htmlspecialchars($json['error'] ?? 'Unbekannter Fehler') . '</div>';
        }
    }
    echo '</div>';
}
?>

</body>
</html>