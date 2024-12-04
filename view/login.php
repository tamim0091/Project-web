<?php
session_start();  // Start the session to manage user login state

// Assuming you have a database or array of users (for simplicity, let's use hardcoded values for this example)
$valid_username = "AKJZEHAZJEA";
$valid_password = "KAZJEHZUEII";
$valid_user_id = 17; 

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $username = $_POST["Fullname"];
    $password = $_POST["password"];

    // Check if the entered username and password match the valid credentials
    if ($username === $valid_username && $password === $valid_password) {
        // Valid login, set the session variables
        $_SESSION["Fullname"] = $username;
        $_SESSION["id"] = $valid_user_id;
        $_SESSION["logged_in"] = true;

        // Redirect to home page
        header("Location: index1.php");
        exit();  // Stop further script execution after redirect
    } else {
        // Invalid login, display an error message
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <!-- Title section -->
    <div class="content">
    <!-- Title section -->
    <div class="title">Sign In</div>
    <!-- Form section -->
    <form class="form" action="login.php" method="POST">
      <!-- Input for Username -->
      <div class="input-box">
        <span class="details">Username</span>
        <input type="text" placeholder="Enter your username" name="Fullname" required>
      </div>
      <!-- Input for Password -->
      <div class="input-box">
        <span class="details">Password</span>
        <input type="password" placeholder="Enter your password" name="password" required>
      </div>
      <!-- Submit button -->
      <div class="button">
        <input type="submit" value="Sign In">
      </div>
    </form>
  </div>
</body>
</html>
