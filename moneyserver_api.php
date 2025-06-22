<?php
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
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "❌ Fehler beim Zugriff auf die API!\n";
        return;
    }

    $response = json_decode($result, true);
    echo "➡️ Aktion: {$data['action']}\n";
    echo "Antwort:\n";
    print_r($response);
    echo str_repeat("=", 50) . "\n\n";
}

// Liste aller Aktionen testen
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

// Beispiel für TryAddUserInfo
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

// Beispiel für UpdateUserInfo
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
