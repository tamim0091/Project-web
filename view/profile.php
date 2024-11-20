<?php

include '../../voyage-master/controller/usercontroller.php';

$error = "";
$success = "";

// Create an instance of the controller
$userc = new UserController();

// Assuming a session is being used for authentication
session_start();

// Check if user is logged in, if not, redirect to login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Ensure id is set in session
if (!isset($_SESSION['id'])) {
    die('User ID is not set in session.');
}

$id = $_SESSION['id'];

// Fetch user details from the database
$user = $userc->showUser($id);

// Check if the user was found in the database
if ($user === false) {
    die('User not found.');
}

// Process form submission to update the user
if (
    isset($_POST["FullName"]) &&
    isset($_POST["Email"]) &&
    isset($_POST["PhoneNumber"]) &&
    isset($_POST["Password"]) &&
    isset($_POST["ConfirmPassword"]) 
    

) {
    // Check if all required fields are filled
    if (
        !empty($_POST["FullName"]) &&
        !empty($_POST["Email"]) &&
        !empty($_POST["PhoneNumber"]) &&
        !empty($_POST["Password"]) &&
        !empty($_POST["ConfirmPassword"]) 
  


    ) { 
        // Validate password and confirm password match
        if ($_POST["Password"] == $_POST["ConfirmPassword"]) {
            // If password is provided, hash it; otherwise, keep the old password
            $password = !empty($_POST["Password"]) ? password_hash($_POST["Password"], PASSWORD_DEFAULT) : $user->Password;
            
            // Create a user object with the updated data
            $updatedUser = new User(
                $id,  // Use the current user's ID
                $_POST["FullName"],
                $_POST["Email"],
                $_POST["PhoneNumber"],
                $password,  // Save the hashed password
                $_POST["ConfirmPassword"],  // This is redundant, as password is already hashed
                "male",
                "User"  // Keep the user's role the same
            );
            
     

            // Update the user in the database
            $userc->updateUser($updatedUser, $id);  // Pass the user ID explicitly
            
            // Show success message
            $success = "Profile updated successfully!";

            // Redirect to home page after successful update
            header("Location: index1.php");
            exit();
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "All fields are required.";
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Update | Chronovoyage</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <!-- Title section -->
    <div class="title">Edit Your Profile</div>
    <div class="content">
      <!-- Registration form -->
      <form action="" method="POST">
        <!-- Display error or success messages -->
        <?php if ($error): ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- User details fields -->
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" placeholder="Enter your name" name="FullName" id="FullName" value="<?php echo htmlspecialchars($user['FullName']); ?>" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" placeholder="Enter your email" name="Email" id="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" placeholder="Enter your phone number" name="PhoneNumber" id="PhoneNumber" value="<?php echo htmlspecialchars($user['PhoneNumber']); ?>" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" placeholder="Enter new password (leave blank to keep current)" name="Password" id="Password">
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" placeholder="Confirm new password" name="ConfirmPassword" id="ConfirmPassword">
          </div>
        </div>
        
        <!-- Gender selection -->
      

        <!-- Submit button -->
       
          <button type="submit">Submit</button>
        

      </form>
    </div>
  </div>
</body>
</html>
