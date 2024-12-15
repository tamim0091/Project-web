<?php
session_start();
include __DIR__ . '../../Controller/UserController.php';

$userController = new UserController();

// Check if user is already logged in
if (isset($_SESSION["id"])) {
    if (strtolower($_SESSION['role']) === 'admin') {
        header("Location: AdminDashboard.php");
    } else {
        header("Location: UserDashboard.php");
    }
    exit();
}

// Check remember_me cookie
if (!isset($_SESSION["id"]) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $user = $userController->getUserByToken($token);
    if ($user) {
        $_SESSION["Fullname"] = $user['FullName'];
        $_SESSION["id"] = $user['id'];
        $_SESSION["email"] = $user['Email'];
        $_SESSION["logged_in"] = true;
        $_SESSION["role"] = $user['Role'];

        if (strtolower($user['Role']) === 'admin') {
            header("Location: AdminDashboard.php");
        } else {
            header("Location: UserDashboard.php");
        }
        exit();
    }
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $rememberMe = isset($_POST['remember_me']);

    // --- RECAPTCHA VERIFICATION START ---
    $secret = "6LderpwqAAAAANrxFfJyyFVLdK5w3F7txz3wvFyN"; // Your secret key
    $response = $_POST['g-recaptcha-response'] ?? '';
    $remoteIP = $_SERVER['REMOTE_ADDR'];

    // Verify the response with Google
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
      'secret' => $secret,
      'response' => $response,
      'remoteip' => $remoteIP
    ];

    $options = [
      'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
      ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultJson = json_decode($result, true);

    if (!($resultJson && $resultJson['success'] === true)) {
        // reCAPTCHA failed
        echo "<script>alert('Please verify that you are not a robot.');</script>";
    } else {
        // reCAPTCHA passed, proceed with login checks
        $user = $userController->login($email, $password, $useEmail = true);

        if ($user) {
            $_SESSION["Fullname"] = $user['FullName'];
            $_SESSION["id"] = $user['id'];
            $_SESSION["email"] = $user['Email'];
            $_SESSION["logged_in"] = true;
            $_SESSION["role"] = $user['Role'];

            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));
                $userController->setRememberMeToken($user['id'], $token);
                setcookie('remember_me', $token, time() + (86400 * 30), "/", "", false, true);
            }

            if (strtolower($user['Role']) === 'admin') {
              header("Location: AdminDashboard.php");
            } else {
              header("Location: UserDashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid email or password');</script>";
        }
    }
    // --- RECAPTCHA VERIFICATION END ---
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>Sign In</title>
  <link rel="stylesheet" href="style2.css">
  <!-- Load the reCAPTCHA v2 script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <div class="content">
      <div class="title">Sign In</div>
      <form class="form" action="login.php" method="POST">
        <!-- Input for Email -->
        <div class="input-box">
          <span class="details">Email</span>
          <input type="email" placeholder="Enter your email" name="email" required>
        </div>
        <!-- Input for Password -->
        <div class="input-box">
          <span class="details">Password</span>
          <input type="password" placeholder="Enter your password" name="password" required>
        </div>

        <!-- Remember Me checkbox -->
        <div class="input-box" style="display:flex; align-items:center;">
          <input type="checkbox" name="remember_me" id="remember_me" style="width:auto; margin-right:5px;">
          <label for="remember_me" style="margin:0; padding:0;">Remember Me</label>
        </div>

        <!-- reCAPTCHA widget -->
        <div class="input-box">
          <div class="g-recaptcha" data-sitekey="6LderpwqAAAAAKeqsS03dt7bN2RVpLz1RqqlGe8K"></div>
        </div>

        <!-- Submit button -->
        <div class="button">
          <input type="submit" value="Sign In">
        </div>
      </form>
      <li></li>
      <div class="signup-link">
        <p>Don't have an account? <a href="createuser.php">Sign up!</a></p>
        <p><a href="ForgetPassword.php">Forgot your Password?</a></p>
      </div>
    </div>
  </div>
</body>
</html>
