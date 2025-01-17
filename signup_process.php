<?php
include('db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader or include PHPMailer manually
require 'vendor2/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $lname = trim($_POST['lname']);
    $selected = trim($_POST['choice']);
    $employee_id = trim($_POST['employee_id']);

    // Validate password match
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Error: Passwords do not match.');
                window.history.back();
              </script>";
        exit;
    }

    if($selected == "Employee"){
        if($employee_id != "BOI_2025_Miraj"){
            echo "<script>
                    alert('Error: Employee Id not Match Please Collect from the the Manager');
                    window.history.back();
                  </script>";
            exit;
    }

    
    }

    // Generate a unique verification token
    $token = bin2hex(random_bytes(16)); // 32-character random token

    // Insert user data into the database with the token
    $stmt = $conn->prepare("
    INSERT INTO users 
    (first_name, email, password, token, is_verified, last_name, type, created_at) 
    VALUES (?, ?, ?, ?, 0, ?, ?, CURRENT_TIMESTAMP)
");
$stmt->bind_param("ssssss", $name, $email, $password, $token, $lname, $selected);


    if ($stmt->execute()) {
        // Generate the verification link
        $verificationLink = "http://localhost/blockchain/verify.php?token=" . $token."& email=". $email."& name=".$name."& type=".$selected;

        // Send verification email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                    // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'heartvibesoul@gmail.com';           // Your email
            $mail->Password   = 'yycc oadm imji ejpg';               // Your email password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // SSL encryption
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('heartvibesoul@gmail.com', 'HearVibe');   // Sender
            $mail->addAddress($email, $name);                       // Recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email Address';
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        .writing-animation {
                            display: block;
                            margin: 20px auto;
                            width: 150px; /* Adjust size as needed */
                            height: auto;
                        }
                        .cta-button {
                            display: inline-block;
                            background-color: #ff6347;
                            color: white;
                            padding: 10px 20px;
                            text-decoration: none;
                            font-size: 18px;
                            border-radius: 50px;
                            text-align: center;
                            margin: 20px auto;
                            display: block;
                            width: 200px;
                        }
                        .cta-button:hover {
                            background-color: #e55337;
                        }
                        p {
                            font-family: Arial, sans-serif;
                            font-size: 16px;
                            color: #333;
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <img src='https://media.giphy.com/media/WSBcKoSD7RG3xBRHea/giphy.gif' alt='Writing Animation' class='writing-animation' />

                    <p>Hi $name,</p>
                    <p>Thank you for registering on Our Bank!</p>
                    <p>Please click the button below to verify your email address:</p>
                    <a href='$verificationLink' class='cta-button'>Verify Email</a>
                    <p>If you did not register for Our bank, please ignore this email.</p>
                </body>
                </html>
                ";


            $mail->AltBody = "Hi $name,\n\nThank you for registering on Our bank! Please visit the following link to verify your email: $verificationLink";

            $mail->send();

            // Show pop-up message and redirect to login page
            
                echo "<script>
                alert('Verification email sent successfully. Please check your inbox.');
                window.location.href = 'login_m.php';
              </script>";
            
            
        } catch (Exception $e) {
            echo "<script>
                    alert('Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error: {$stmt->error}');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
</body>
