<?php
session_start();
include __DIR__ . '../../Controller/UserController.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $user = $userController->getUserByEmail($email);
    if ($user) {
        // Generate random 8-char code
        $resetCode = substr(bin2hex(random_bytes(4)), 0, 8); 
        $expires = date('Y-m-d H:i:s', time() + 900); // 15 minutes from now

        $userController->setResetToken($user['id'], $resetCode, $expires);

        // Send email with the code using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'choeurproject@gmail.com'; 
            $mail->Password = 'oabw kzbc bghm mgeb';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('choeurproject@gmail.com', 'Chronovoyage');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $emailBody = 'Dear ' . htmlspecialchars($user['FullName'], ENT_QUOTES, 'UTF-8') . ',<br><br>';
            $emailBody .= 'You requested a password reset. Here is your reset code: <b>' . $resetCode . '</b><br>';
            $emailBody .= 'This code will expire in 15 minutes.<br><br>';
            $emailBody .= 'Best regards,<br>Chronovoyage Team';
            $mail->Body = $emailBody;

            if ($mail->send()) {
                echo "<script>alert('Email sent! Please check your email for the code.');</script>";
                header("Location: ConfirmResetPassword.php");
                exit();
            } else {
                echo '<p style="color: red;">There was a problem sending the email.</p>';
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "<script>alert('No account found with that email.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <div class="content">
      <div class="title">Forgot Password</div>
      <form class="form" action="" method="POST">
        <div class="input-box">
          <span class="details">Email</span>
          <input type="email" placeholder="Enter your account email" name="email" required>
        </div>
        <div class="button">
          <input type="submit" value="Send">
        </div>
      </form>
    </div>
  </div>
</body>
</html>
