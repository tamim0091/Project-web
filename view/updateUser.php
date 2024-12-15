<?php
include '../controller/usercontroller.php';

session_start();

$error = "";
$success = "";


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    die('User ID is not set in session.');
}

$id = $_SESSION['id'];
$userc = new UserController();

// Fetch current user details
$user = $userc->showUser($id);
if ($user === false) {
    die('User not found.');
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $FullName        = trim($_POST["FullName"] ?? '');
    $Email           = trim($_POST["Email"] ?? '');
    $PhoneNumber     = trim($_POST["PhoneNumber"] ?? '');
    $Password        = trim($_POST["Password"] ?? '');
    $ConfirmPassword = trim($_POST["ConfirmPassword"] ?? '');
    $Gender          = trim($_POST["Gender"] ?? '');

    if (!empty($FullName) && !empty($Email) && !empty($PhoneNumber) && !empty($Password) && !empty($ConfirmPassword) && !empty($Gender)) {
        if ($Password === $ConfirmPassword) {
            // If no new password provided, keep the old one
            $passwordToSave = !empty($Password) ? $Password : $user['Password'];

            $role = $user['Role']; // Keep the same role

            // Create user object
            $updatedUser = new User(
                $id,
                $FullName,
                $Email,
                $passwordToSave,
                $PhoneNumber,
                $Gender,
                $role
            );

            // Update the user in the database
            $userc->updateUser($updatedUser, $id);

            // Fetch updated user data
            $updatedUserData = $userc->showUser($id);
            if ($updatedUserData) {
                // Update session variables with the latest data
                $_SESSION["Fullname"] = $updatedUserData['FullName'];
                $_SESSION["Email"] = $updatedUserData['Email'];
                // ... update other session variables if needed
            }

            $success = "Profile updated successfully!";
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
    <div class="title">Edit Your Profile</div>
    <div class="content">
      <form action="" method="POST">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="FullName" required
                   value="<?php echo htmlspecialchars($user['FullName'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="Email" required
                   value="<?php echo htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="PhoneNumber" required
                   value="<?php echo htmlspecialchars($user['PhoneNumber'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="Password" placeholder="Leave blank to keep current">
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="ConfirmPassword" placeholder="Confirm new password">
          </div>
          <div class="input-box">
            <span class="details">Gender</span>
            <input type="text" name="Gender" required
                   value="<?php echo htmlspecialchars($user['Gender'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>

        <button type="submit">Submit</button>
      </form>
    </div>
  </div>

  <?php if (!empty($success)): ?>
  <script>
    // If the profile update was successful, update localStorage
    localStorage.setItem('FullName', '<?php echo addslashes($updatedUserData['FullName']); ?>');
    localStorage.setItem('Email', '<?php echo addslashes($updatedUserData['Email']); ?>');
    localStorage.setItem('PhoneNumber', '<?php echo addslashes($updatedUserData['PhoneNumber']); ?>');
    // ... set other fields if needed

    // After updating localStorage, redirect to dashboard
    window.location.href = 'UserDashboard.php';
  </script>
  <?php endif; ?>
</body>
</html>
