<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Moneyserver API Tester</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-light-grey">

<div class="w3-container w3-padding w3-card-4 w3-margin w3-white">
  <h2 class="w3-text-blue">Moneyserver API Test</h2>
  <form class="w3-container" method="post">
    <label class="w3-text-blue">API URL</label>
    <input class="w3-input w3-border w3-margin-bottom" name="url" value="<?= htmlspecialchars($_POST['url'] ?? 'http://localhost:8008/api/json') ?>" required>
    <label class="w3-text-blue">API Key</label>
    <input class="w3-input w3-border w3-margin-bottom" name="apiKey" value="<?= htmlspecialchars($_POST['apiKey'] ?? '123456789') ?>" required>
    <label class="w3-text-blue">Allowed User</label>
    <input class="w3-input w3-border w3-margin-bottom" name="allowedUser" value="<?= htmlspecialchars($_POST['allowedUser'] ?? 'myadminuser') ?>" required>
    <label class="w3-text-blue">User ID</label>
    <input class="w3-input w3-border w3-margin-bottom" name="userID" value="<?= htmlspecialchars($_POST['userID'] ?? 'test-user-id') ?>" required>
    <button class="w3-button w3-blue w3-margin-top" type="submit">Tests starten</button>
  </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'):
$url = $_POST['url'] ?? 'http://localhost:8008/api/json';
$apiKey = $_POST['apiKey'] ?? '123456789';
$allowedUser = $_POST['allowedUser'] ?? 'myadminuser';
$userID = $_POST['userID'] ?? 'test-user-id';

function assertEquals($expected, $actual, $message) {
    if ($expected === $actual) {
        echo "<span class='w3-tag w3-green w3-round w3-small'>✔ $message</span><br>";
    } else {
        echo "<span class='w3-tag w3-red w3-round w3-small'>✘ $message (Erwartet: " . htmlspecialchars(var_export($expected, true)) . ", Bekommen: " . htmlspecialchars(var_export($actual, true)) . ")</span><br>";
    }
}

function sendRequest($data, $check = null) {
    global $url, $apiKey, $allowedUser;
    $data['apiKey'] = $apiKey;
    $data['allowedUser'] = $allowedUser;

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 10
        ]
    ];
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    echo "<div class='w3-card w3-margin w3-padding'>";
    echo "<b class='w3-text-blue'>➡️ Aktion:</b> <code>" . htmlspecialchars($data['action']) . "</code><br>";

    if ($result === FALSE) {
        echo "<span class='w3-tag w3-red w3-round'>❌ Fehler beim Zugriff auf die API!</span><br>";
        global $http_response_header;
        if (isset($http_response_header)) {
            echo "<b>HTTP-Header:</b> <pre class='w3-small'>" . htmlspecialchars(implode("\n", $http_response_header)) . "</pre>";
        }
        $error = error_get_last();
        if ($error) {
            echo "<b>Fehlertext:</b> <pre class='w3-small'>" . htmlspecialchars($error['message']) . "</pre>";
        }
        echo "</div>";
        return;
    }

    $response = json_decode($result, true);
    echo "<b>Antwort:</b><br>";
    echo "<pre class='w3-small'>" . htmlspecialchars(print_r($response, true)) . "</pre>";

    if ($check) {
        $check($response);
    }

    echo "</div>";
}

// Tests für alle wichtigen API-Aktionen:
sendRequest(['action' => 'getbalance', 'userID' => $userID], function($res) {
    assertEquals(true, isset($res['balance']), 'Balance-Feld vorhanden');
    assertEquals(true, is_numeric($res['balance'] ?? null), 'Balance ist numerisch');
});

sendRequest(['action' => 'getTransactionNum', 'userID' => $userID, 'startTime' => 0, 'endTime' => time()], function($res) {
    assertEquals(true, isset($res['num']), 'num-Feld vorhanden');
});

sendRequest(['action' => 'UserExists', 'userID' => $userID], function($res) {
    assertEquals(true, isset($res['exists']), 'Exists-Feld vorhanden');
    assertEquals(true, is_bool($res['exists'] ?? null), 'Exists ist boolean');
});

sendRequest([
    'action' => 'withdrawMoney',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000',
    'amount' => 100
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (withdrawMoney)');
});

sendRequest([
    'action' => 'giveMoney',
    'transactionID' => '00000000-0000-0000-0000-000000000000',
    'receiverID' => 'some-receiver-id',
    'amount' => 50
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (giveMoney)');
});

sendRequest([
    'action' => 'BuyMoney',
    'transactionID' => '00000000-0000-0000-0000-000000000000',
    'amount' => 75
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (BuyMoney)');
});

sendRequest([
    'action' => 'addTransaction',
    'transaction' => [
        'TransactionID' => '00000000-0000-0000-0000-000000000000',
        'UserID' => $userID
    ]
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (addTransaction)');
});

sendRequest([
    'action' => 'addUser',
    'userID' => $userID,
    'balance' => 1000,
    'status' => 1,
    'type' => 0
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (addUser)');
});

sendRequest([
    'action' => 'BuyCurrency',
    'userID' => $userID,
    'amount' => 50
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (BuyCurrency)');
});

sendRequest([
    'action' => 'SetTransExpired',
    'deadTime' => 600
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (SetTransExpired)');
});

sendRequest([
    'action' => 'ValidateTransfer',
    'secureCode' => 'abc123',
    'transactionID' => '00000000-0000-0000-0000-000000000000'
], function($res) {
    assertEquals(true, isset($res['valid']), 'valid-Feld vorhanden (ValidateTransfer)');
});

sendRequest([
    'action' => 'FetchUserInfo',
    'userID' => $userID
], function($res) {
    assertEquals(true, isset($res['userID']), 'userID-Feld vorhanden (FetchUserInfo)');
});

sendRequest([
    'action' => 'updateTransactionStatus',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000',
    'status' => 2,
    'description' => 'Updated via test script'
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (updateTransactionStatus)');
});

sendRequest([
    'action' => 'DoTransfer',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000'
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (DoTransfer)');
});

sendRequest([
    'action' => 'DoAddMoney',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000'
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (DoAddMoney)');
});

sendRequest([
    'action' => 'FetchTransaction',
    'userID' => $userID,
    'startTime' => 0,
    'endTime' => time(),
    'lastIndex' => 0
], function($res) {
    assertEquals(true, isset($res['transactions']), 'transactions-Feld vorhanden (FetchTransaction)');
});

sendRequest([
    'action' => 'TryAddUserInfo',
    'user' => [
        'UserID' => $userID,
        'Name' => 'Test User',
        'Email' => 'test@example.com',
        'Status' => 1,
        'Type' => 0
    ]
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (TryAddUserInfo)');
});

sendRequest([
    'action' => 'UpdateUserInfo',
    'userID' => $userID,
    'user' => [
        'UserID' => $userID,
        'Name' => 'Updated Name',
        'Email' => 'updated@example.com',
        'Status' => 2,
        'Type' => 1
    ]
], function($res) {
    assertEquals(true, isset($res['success']), 'Success-Feld vorhanden (UpdateUserInfo)');
});

endif;
?>
</body>
</html>
