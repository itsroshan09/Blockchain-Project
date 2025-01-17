<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor2/autoload.php';
include "db.php";

// Start the session to get user ID
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Get form data
$loan_amount = $_POST['loan_amount'];
$loan_term = $_POST['loan_term'];
$loan_purpose = $_POST['loan_purpose'];
$ac = $_POST['ac1'];

$randomNumber = mt_rand(1000, 9999);
$loan_id = "BOI".$randomNumber;
// Handle document upload
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
}
$target_file = $target_dir . basename($_FILES["document"]["name"]);
if (!move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
    die("Error: Unable to upload the document.");
}



// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $name = $user['first_name'];
    $lname = $user['last_name'];
    $email = $user['email'];


    // Check account details
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE account_no = ? AND first_name = ? AND last_name = ?");
    $stmt->bind_param("sss", $ac, $name, $lname);
    $stmt->execute();
    $account_result = $stmt->get_result();
    
    if ($account_result->num_rows == 0) {
        die("Error: Account number not found.");
    }
    
} else {
    die("Error: User not found.");
}

// Save loan application in the database
$stmt = $conn->prepare("INSERT INTO loans (loan_id, user_id, loan_amount, loan_term, loan_purpose, document_hash) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sidsss", $loan_id, $user_id, $loan_amount, $loan_term, $loan_purpose, $target_file);


if ($stmt->execute()) {
    // $loan_id = $stmt->insert_id;

    // Update account details
    $stmt = $conn->prepare("UPDATE accounts SET loan_type = ?, loan_amount = ?, loan_status = 'apply', loan_term = ? WHERE first_name = ? AND last_name = ? AND account_no = ?");
    $stmt->bind_param("sdssss", $loan_purpose, $loan_amount, $loan_term, $name, $lname, $ac);
    $stmt->execute();

    // Prepare API request data
    $api_key = 'pk_live_29bd0d18-b620-4a86-8b17-14ecaa6c445c';
    $smart_contract_address = '0xD3E89D6a85DD6b0F338E8a9D212890D612E44c7A';
    $verbwire_url = 'https://api.verbwire.com/v1/nft/mint';

    $data = [
        'contract_address' => $smart_contract_address,
        'method' => 'applyForLoan',
        'params' => [
            'loan_id' => $loan_id,
            'user_id' => $user_id,
            'loan_amount' => $loan_amount,
            'loan_term' => $loan_term,
            'loan_purpose' => $loan_purpose,
            'loan_status' => 'Submitted',
            'account_no' => $ac,
            'document_hash' => $target_file
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
            echo "Loan application submitted successfully. Blockchain Transaction Hash: " . $json_response['transactionHash'];
        } else {
            echo "Loan application submitted.";
        }

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';                    // SMTP server
          $mail->SMTPAuth   = true;
          $mail->Username   = 'heartvibesoul@gmail.com';           // Your email
          $mail->Password   = 'yycc oadm imji ejpg';               // Your email password or app password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // SSL encryption
          $mail->Port       = 465;

          $mail->setFrom('heartvibesoul@gmail.com', 'HeartVibe');   // Sender
          $mail->addAddress($email, $name);   

            $mail->isHTML(true);
            $mail->Subject = 'Loan Application Submitted';
            $mail->Body = "
                <p>Hi $name,</p>
                <p>Thank you for submitting your loan application on HeartVibe! Loan ID: $loan_id</p>
                <p>We will review your application shortly.</p>
            ";

            $mail->send();
            echo "Verification email sent successfully.";
        } catch (Exception $e) {
            echo "Error: Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error during blockchain transaction.";
    }
} else {
    echo "Error: Could not submit loan application.";
}

$conn->close();
?>
