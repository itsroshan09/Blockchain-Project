<?php
include "db.php";

$u = $_POST["loan_id"];
$user = $_POST['user_id'];

$sql = "SELECT * FROM users WHERE user_id = '$user'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    

    $updateStmt = $conn->prepare("UPDATE loans SET status = 'Disbursed' WHERE loan_id = ? ");
    $updateStmt->bind_param("s", $u);
    $updateStmt->execute();

    $sql1 = "SELECT * FROM loans WHERE loan_id = '$u'";
    $result1 = $conn->query($sql1);
    $loans = $result1->fetch_assoc();

    $api_key = 'pk_live_29bd0d18-b620-4a86-8b17-14ecaa6c445c';
    $smart_contract_address = '0xD3E89D6a85DD6b0F338E8a9D212890D612E44c7A';
    $verbwire_url = 'https://api.verbwire.com/v1/nft/mint';

    $data = [
        'contract_address' => $smart_contract_address,
        'method' => 'disbursedloan',
        'params' => [
            'loan_id' => $loans['loan_id'],
            'user_id' => $loans['user_id'],
            'loan_amount' => $loans['loan_amount'],
            'loan_term' => $loans['loan_term'],
            'loan_purpose' => $loans['loan_purpose'],
            'loan_status' => 'Disbursed',
           
        ]
    ];

    // Initialize cURL request
    $ch = curl_init($verbwire_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $json_response = json_decode($response, true);

        if (isset($json_response['transactionHash'])) {
            echo "Loan Disbursed successfully. Blockchain Transaction Hash: " . $json_response['transactionHash'];
        } else {
            echo "Loan Disbursed. Response: " . json_encode($json_response);
        }
    }

    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $updateStmt1 = $conn->prepare("UPDATE accounts SET loan_status = 'Disbursed' WHERE first_name = ? AND last_name = ?");
    $updateStmt1->bind_param("ss", $first_name, $last_name); 
    $updateStmt1->execute();
    echo "Loan Approved By Manager";
}

?>