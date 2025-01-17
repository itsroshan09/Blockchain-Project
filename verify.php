<?php
include('db.php');

$status = ""; // Default status

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader or include PHPMailer manually
require 'vendor2/autoload.php';
$mail = new PHPMailer(true);
if (isset($_GET['token']) && isset($_GET['email']) && isset($_GET['name'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $name = $_GET['name'];
    $type = $_GET['type'];

    // Check if the token exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ? AND is_verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the user's verification status
        if($type == "User"){
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1, token = NULL, approve = 0 WHERE token = ? ");
        }else{
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1, token = NULL, approve = 1 WHERE token = ?");
        }
        $updateStmt->bind_param("s", $token);
        $updateStmt->execute();
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
                      p {
                          font-family: Arial, sans-serif;
                          font-size: 16px;
                          color: #333;
                          text-align: center;
                      }
                  </style>
              </head>
              <body>
                  <p>Hi $name,</p>
                  <img src='thanks.png' alt='Writing Animation' class='writing-animation' />
                  <p>Verifing your email on HearVibe!</p>
                  <p>Please go to the login page and login it and enjoy your day and write how's your day.</p>
              </body>
              </html>
              ";


          $mail->send();
         $status = "success";
        }catch (Exception $e) {
          echo "<script>
                  alert('Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}');
                  window.history.back();
                </script>";
      }
    } else {
        $status = "failed";
    }

    $stmt->close();
    $conn->close();
} else {
  echo "hello";
    $status = "failed";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo ($status === "success") ? "Verification Successful" : "Verification Failed"; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #FFC0CB);
      color: #fff;
      font-family: 'Arial', sans-serif;
    }
    .card {
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 15px;
      animation: fadeIn 1.5s ease-in-out;
    }
    .btn-primary {
      background: linear-gradient(90deg, #007bff, #6610f2);
      border: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-primary:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    .text-success, .text-danger {
      animation: popIn 1s ease-out;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes popIn {
      0% {
        transform: scale(0.5);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center shadow-lg p-4" style="max-width: 500px;">
      <div class="card-body">
        <?php if ($status === "success"): ?>
          <div class="text-success mb-4">
            <i class="bi bi-check-circle-fill" style="font-size: 3.5rem;"></i>
          </div>
          <h2 class="card-title">Verification Successful!</h2>
          <p class="card-text mt-3">Your account has been successfully verified. You can now enjoy all our features and services.</p>
        <?php else: ?>
          <div class="text-danger mb-4">
            <i class="bi bi-x-circle-fill" style="font-size: 3.5rem;"></i>
          </div>
          <h2 class="card-title">Verification Failed</h2>
          <p class="card-text mt-3">The verification link is invalid or has expired. Please try registering again or contact support for help.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
