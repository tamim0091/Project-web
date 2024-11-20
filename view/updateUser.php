<?php

include '../../voyage-master/controller/usercontroller.php';

$error = "";

$id = $_SESSION['id'];
// Fetch user details from the database
$user = $userc->showUser($id);

// Check if the user was found in the database
if ($user === false) {
    die('User not found.');
}

echo("000");

// Process form submission to update the user
if (
    isset($_POST["FullName"]) &&
    isset($_POST["Email"]) &&
    isset($_POST["PhoneNumber"]) &&
    isset($_POST["Password"]) &&
    isset($_POST["ConfirmPassword"]) &&
    isset($_POST["Gender"])


) {    echo("1");
    // Check if all required fields are filled
    if (
        !empty($_POST["FullName"]) &&
        !empty($_POST["Email"]) &&
        !empty($_POST["PhoneNumber"]) &&
        !empty($_POST["Password"]) &&
        !empty($_POST["ConfirmPassword"]) &&
        !empty($_POST["Gender"])


    ) {        echo("2");
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
                $_POST["Gender"],
                "User"  // Keep the user's role the same
            );
            
            echo("3");

            // Update the user in the database
            $userc->updateUser($updatedUser, $id);  // Pass the user ID explicitly
            
            echo("4");
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