<?php

include '../../voyage-master/controller/usercontroller.php';

$error = "";

// create formation
$user = null;
$role="user";
// create an instance of the controller
$userc = new UserController();
if (
    isset($_POST["FullName"]) &&
    isset($_POST["Username"]) &&
    isset($_POST["Email"]) &&
    isset($_POST["PhoneNumber"])&&
    isset($_POST["Password"]) &&
    isset($_POST["ConfirmPassword"])&&
    isset($_POST["Gender"]) 
  
 
    
) {
    if (
        !empty($_POST["FullName"]) &&
        !empty($_POST["Username"]) &&
        !empty($_POST["Email"]) &&
        !empty($_POST["PhoneNumber"])&&
        isset($_POST["Password"]) &&
        !empty($_POST["ConfirmPassword"]) &&
        !empty($_POST["Gender"])
    ) {
        $user = new User(
            null,
            $_POST["FullName"],
            $_POST["Username"],
            $_POST["Email"],
            $_POST["PhoneNumber"],
            $_POST["Password"],
            $_POST["ConfirmPassword"],
            $_POST["Gender"],
            "User"
         
        );
        $userc->addUser($user);
     header('Location:index.php');
    } else
        $error = "Missing information";
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
