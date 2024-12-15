<?php
session_start();
include __DIR__ . '../../Controller/UserController.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['code'])) {
        $code = trim($_POST['code']);
        $user = $userController->getUserByResetToken($code);
        if ($user && strtotime($user['password_reset_expires']) > time()) {
            // Code correct and not expired
            $_SESSION['reset_user_id'] = $user['id'];
            header("Location: ResetPassword.php");
            exit();
        } else {
            echo "<script>alert('Invalid or expired code');</script>";
        }
    }

    if (isset($_POST['resend'])) {
        if (isset($_SESSION['reset_user_id'])) {
            $user = $userController->showUser($_SESSION['reset_user_id']);
            if ($user) {
                // Check if the current code is expired before resending
                if (strtotime($user['password_reset_expires']) > time()) {
                    // Code is not yet expired, cannot resend
                    echo "<script>alert('You cannot resend the code yet. Please wait until the current code expires.');</script>";
                } else {
                    // Previous code expired, generate and send a new one
                    $resetCode = substr(bin2hex(random_bytes(4)), 0, 8); 
                    $expires = date('Y-m-d H:i:s', time() + 900); // 15 more minutes
                    $userController->setResetToken($user['id'], $resetCode, $expires);

                    // Resend email with the code
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'choeurproject@gmail.com'; 
                        $mail->Password = 'oabw kzbc bghm mgeb';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('choeurproject@gmail.com', 'Chronovoyage');
                        $mail->addAddress($user['Email']);

                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Code (Resent)';
                        $emailBody = 'Dear ' . htmlspecialchars($user['FullName'], ENT_QUOTES, 'UTF-8') . ',<br><br>';
                        $emailBody .= 'Here is your reset code again: <b>' . $resetCode . '</b><br>';
                        $emailBody .= 'This code will expire in 15 minutes.<br><br>';
                        $emailBody .= 'Best regards,<br>Chronovoyage Team';
                        $mail->Body = $emailBody;

                        if ($mail->send()) {
                            echo "<script>alert('Code resent! Check your email.');</script>";
                        } else {
                            echo '<p style="color: red;">There was a problem sending the email.</p>';
                        }
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
        } else {
            echo "<script>alert('No user session found to resend code.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>Confirm Reset Password</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <div class="content">
      <div class="title">Confirm Reset Password</div>
      <form class="form" action="" method="POST">
        <div class="input-box">
          <span class="details">Enter the code you received</span>
          <input type="text" name="code" placeholder="Enter reset code" required>
        </div>
        <div class="button">
          <input type="submit" value="Confirm">
        </div>
      </form>
      <form action="" method="POST" style="margin-top:10px;">
        <input type="hidden" name="resend" value="1">
        <input type="submit" value="Resend Code">
      </form>
    </div>
  </div>
</body>
</html>
