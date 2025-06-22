<?php
// UTF-8 für Sonderzeichen (z.B. Emojis)
header('Content-Type: text/html; charset=utf-8');

// Fehlerausgabe aktivieren
ini_set('display_errors', 1);
error_reporting(E_ALL);

$url = 'http://localhost:8008/api/json'; // ggf. anpassen
$apiKey = '123456789';
$allowedUser = 'myadminuser';
$userID = 'test-user-id'; // Ersetze ggf. durch gültige UUID oder Namen

function sendRequest($data)
{
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

    echo "<div style='margin-bottom:1em;border-bottom:1px solid #ccc;'>";
    echo "<b>➡️ Aktion:</b> <code>{$data['action']}</code><br>";

    if ($result === FALSE) {
        // Fehlerausgabe im Browser
        echo "<span style='color:red;'>❌ Fehler beim Zugriff auf die API!</span><br>";
        // HTTP-Header anzeigen, falls vorhanden
        global $http_response_header;
        if (isset($http_response_header)) {
            echo "<b>HTTP-Header:</b> <pre>" . htmlspecialchars(implode("\n", $http_response_header)) . "</pre>";
        }
        $error = error_get_last();
        if ($error) {
            echo "<b>Fehlertext:</b> <pre>" . htmlspecialchars($error['message']) . "</pre>";
        }
        echo "</div>";
        return;
    }

    $response = json_decode($result, true);
    echo "<b>Antwort:</b><br>";
    echo "<pre>" . htmlspecialchars(print_r($response, true)) . "</pre>";
    echo "</div>";
}

// Aktionen testen
sendRequest(['action' => 'getbalance', 'userID' => $userID]);
sendRequest(['action' => 'getTransactionNum', 'userID' => $userID, 'startTime' => 0, 'endTime' => time()]);
sendRequest(['action' => 'UserExists', 'userID' => $userID]);

sendRequest([
    'action' => 'addUser',
    'userID' => $userID,
    'balance' => 1000,
    'status' => 1,
    'type' => 0
]);

sendRequest([
    'action' => 'BuyCurrency',
    'userID' => $userID,
    'amount' => 50
]);

sendRequest([
    'action' => 'SetTransExpired',
    'deadTime' => 600
]);

sendRequest([
    'action' => 'ValidateTransfer',
    'secureCode' => 'abc123',
    'transactionID' => '00000000-0000-0000-0000-000000000000'
]);

sendRequest([
    'action' => 'FetchUserInfo',
    'userID' => $userID
]);

sendRequest([
    'action' => 'updateTransactionStatus',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000',
    'status' => 2,
    'description' => 'Updated via test script'
]);

sendRequest([
    'action' => 'DoTransfer',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000'
]);

sendRequest([
    'action' => 'DoAddMoney',
    'userID' => $userID,
    'transactionID' => '00000000-0000-0000-0000-000000000000'
]);

sendRequest([
    'action' => 'FetchTransaction',
    'userID' => $userID,
    'startTime' => 0,
    'endTime' => time(),
    'lastIndex' => 0
]);

sendRequest([
    'action' => 'TryAddUserInfo',
    'user' => [
        'UserID' => $userID,
        'Name' => 'Test User',
        'Email' => 'test@example.com',
        'Status' => 1,
        'Type' => 0
    ]
]);

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
]);
?>
