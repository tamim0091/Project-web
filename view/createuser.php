<?php
include '../../voyage-master/controller/usercontroller.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$error = "";

// create formation
$user = null;
$role = "user";

// create an instance of the controller
$userc = new UserController();

if (isset(
    $_POST["FullName"], 
    $_POST["Username"], 
    $_POST["Email"], 
    $_POST["Password"], 
    $_POST["PhoneNumber"], 
    $_POST["ConfirmPassword"], 
    $_POST["Gender"]
)) {
    if (
        !empty($_POST["FullName"]) && 
        !empty($_POST["Username"]) && 
        !empty($_POST["Email"]) && 
        !empty($_POST["Password"]) && 
        !empty($_POST["PhoneNumber"]) && 
        !empty($_POST["ConfirmPassword"]) && 
        !empty($_POST["Gender"])
    ) {
        // Check if passwords match
        if ($_POST["Password"] !== $_POST["ConfirmPassword"]) {
            $error = "Passwords do not match!";
        } else {
            $user = new User(
                null,
                $_POST["FullName"],
                $_POST["Username"],
                $_POST["Email"],
                $_POST["Password"],
                $_POST["PhoneNumber"],
                $_POST["ConfirmPassword"],
                $_POST["Gender"],
                "User"
            );

            if ($userc->addUser($user)) {
                // Create the PHPMailer instance
                $mail = new PHPMailer(true);
                try {
                    // Setup the SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'choeurproject@gmail.com'; // Your Gmail address
                    $mail->Password = 'oabw kzbc bghm mgeb'; // Your Gmail password or app-specific password
                    $mail->SMTPSecure = 'tls'; // Use TLS encryption
                    $mail->Port = 587; // SMTP Port for Gmail

                    // Sender and receiver details
                    $mail->setFrom('choeurproject@gmail.com', 'Mailer');
                    $mail->addAddress($_POST["Email"]);

                    // HTML email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Account Registration Confirmation';
                    $mail->Body    = 'Dear ' . $_POST["FullName"] . ',<br><br>Thank you for registering with us.<br>Your account is now created.<br><br>Best regards,<br>Chronovoyage Team';

                    // Debugging: Print the email content to the console
                    echo "<pre>Email Content: </pre>";
                    echo "<pre>To: " . $_POST["Email"] . "</pre>";
                    echo "<pre>Subject: Account Registration Confirmation</pre>";
                    echo "<pre>Body: $emailBody</pre>";

                    // PHPMailer debugging output
                    $mail->SMTPDebug = 2;  // Set to 2 to show detailed debug output

                    // Send the email
                    if ($mail->send()) {
                        echo '<p style="color: green;">A confirmation email has been sent to your email address.</p>';
                    } else {
                        echo '<p style="color: red;">There was a problem sending the email.</p>';
                    }

                    // Redirect after successful registration
                    header('Location: ../index.php'); // Correct the redirect path
                    exit(); // Always call exit after header redirect
                } catch (Exception $e) {
                    // Catch PHPMailer exceptions and display the error
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }
    } else {
        $error = "Missing information. Please fill out all fields.";
    }
}

if ($error) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Registration Form | Chronovoyage</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <!-- Logo Image in the Top-Left Corner -->
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <!-- Title section -->
    <div class="title">Registration</div>
    <div class="content">
      <!-- Registration form -->
      <form action="" method="POST">
        <!-- User details fields -->
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" placeholder="Enter your name"name="FullName" id="FullName" required>
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" placeholder="Enter your username" name="Username" id="Username" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" placeholder="Enter your email" name="Email" id="Email" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" placeholder="Enter your number" name="PhoneNumber" id="PhoneNumber" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="text" placeholder="Enter your password" name="Password" id="Password" required>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="text" placeholder="Confirm your password" name="ConfirmPassword" id="ConfirmPassword" required>
          </div>
        </div>
        
         <!-- Gender selection -->
         <div class="gender-details">
          <span class="gender-title">Gender</span>
          <div class="category">
            <label for="dot-1">
            <input type="radio" name="Gender" id="dot-1" value="Male" checked required>
              <span class="dot one"></span>
              <span class="gender">Male</span>
            </label>
            <label for="dot-2">
              <input type="radio" name="Gender" id="dot-2" value="Female" required>
              <span class="dot two"></span>
              <span class="gender">Female</span>
            </label>
            <label for="dot-3">
              <input type="radio" name="Gender" id="dot-3" value="Prefer not to say" required>
              <span class="dot three"></span>
              <span class="gender">Prefer not to say</span>
            </label>
          </div>
        </div>
        <!-- Submit button -->
        <div class="button">
          <input type="submit" value="Register">
        </div>
      </form>
    </div>
  </div>
</body>
</html>
