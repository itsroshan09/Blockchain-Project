<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader or include PHPMailer manually
require 'vendor/autoload.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);

}
$mail = new PHPMailer(true);
    try {
        $verification_code = rand(111111,999999);
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
        $mail->Subject = 'Reset your password';
        $mail->Body = "
             Hi, 
    Please use the following verification code to complete your registration:
    
    Verification Code: {$verification_code}
    
    If you did not request this, please ignore this email.
    
    Regards,
    Your Application Team
            ";


        $mail->send();
       $status = "success";
       $_SESSION['verify']=$verification_code;
       $_SESSION['em']=$email;
       $_SESSION['nm']=$name;
       echo "<script>
        alert('Verification code Sent Successfully');
                window.history.back();
              </script>";
      }catch (Exception $e) {
        echo "<script>
                alert('Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}');
                window.history.back();
              </script>";
    }
?>