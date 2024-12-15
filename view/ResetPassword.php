<?php
session_start();
include __DIR__ . '../../Controller/UserController.php';
$userController = new UserController();

if (!isset($_SESSION['reset_user_id'])) {
    // No user to reset password for
    header("Location: ForgetPassword.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    // Basic password checks (mirroring the complexity requirements)
    $uppercase = preg_match('@[A-Z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
    } elseif (strlen($password) < 8 || !$uppercase || !$number) {
        echo "<script>alert('Password must be at least 8 characters long, contain at least one uppercase letter, and one number.');</script>";
    } else {
        // Update password in DB
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $userController->updatePassword($_SESSION['reset_user_id'], $hashed);

        // Clear reset token
        $userController->clearResetToken($_SESSION['reset_user_id']);

        // Clear session var
        unset($_SESSION['reset_user_id']);

        echo "<script>alert('Password reset successful! Please login.');</script>";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>Reset Password</title>
  <link rel="stylesheet" href="style2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

  <style>
    .strength-bar-container {
      width: 100%;
      height: 5px;
      background: #ddd;
      border-radius: 3px;
      margin-top: 5px;
      display: none;
    }
    .strength-bar {
      height: 100%;
      width: 0%;
      border-radius: 3px;
      transition: width 0.3s ease, background-color 0.3s ease;
    }
    .strength-weak {
      background-color: red;
      width: 33%;
    }
    .strength-medium {
      background-color: orange;
      width: 67%;
    }
    .strength-strong {
      background-color: green;
      width: 100%;
    }
    .password-requirements {
      margin-top: 10px;
      font-size: 0.9em;
      display: none;
    }
    .password-requirements li {
      list-style: none;
      margin-bottom: 5px;
      display: flex;
      align-items: center;
    }
    .password-requirements li i {
      margin-right: 5px;
    }
    .valid {
      color: green;
    }
    .invalid {
      color: red;
    }
    #confirmPasswordMessage {
      font-size: 0.8em;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <div class="content">
      <div class="title">Reset Password</div>
      <form class="form" action="" method="POST">
        <div class="input-box">
          <span class="details">New Password</span>
          <input type="password" name="password" id="Password" placeholder="Enter new password" required>
          <div class="strength-bar-container">
            <div class="strength-bar" id="strengthBar"></div>
          </div>
          <ul class="password-requirements" id="passwordRequirements">
            <li id="lengthRequirement" class="invalid"><i class="fas fa-times"></i> At least 8 characters</li>
            <li id="uppercaseRequirement" class="invalid"><i class="fas fa-times"></i> At least one uppercase letter</li>
            <li id="numberRequirement" class="invalid"><i class="fas fa-times"></i> At least one number</li>
          </ul>
        </div>
        <div class="input-box">
          <span class="details">Confirm Password</span>
          <input type="password" name="confirm_password" id="ConfirmPassword" placeholder="Confirm new password" required>
          <div id="confirmPasswordMessage"></div>
        </div>
        <div class="button">
          <input type="submit" value="Reset Password">
        </div>
      </form>
    </div>
  </div>

  <script>
    const passwordInput = document.getElementById('Password');
    const confirmPasswordInput = document.getElementById('ConfirmPassword');
    const strengthBarContainer = document.querySelector('.strength-bar-container');
    const strengthBar = document.getElementById('strengthBar');
    const passwordRequirements = document.getElementById('passwordRequirements');
    const lengthReq = document.getElementById('lengthRequirement');
    const uppercaseReq = document.getElementById('uppercaseRequirement');
    const numberReq = document.getElementById('numberRequirement');
    const confirmPasswordMessage = document.getElementById('confirmPasswordMessage');

    passwordInput.addEventListener('input', function() {
      const password = passwordInput.value;

      if (password.length > 0) {
        passwordRequirements.style.display = 'block';
      } else {
        passwordRequirements.style.display = 'none';
        strengthBarContainer.style.display = 'none';
        strengthBar.className = 'strength-bar';
        strengthBar.style.width = '0%';
        return;
      }

      const lengthCheck = password.length >= 8;
      const uppercaseCheck = /[A-Z]/.test(password);
      const numberCheck = /[0-9]/.test(password);

      updateRequirement(lengthReq, lengthCheck);
      updateRequirement(uppercaseReq, uppercaseCheck);
      updateRequirement(numberReq, numberCheck);

      let strength = 0;
      if (lengthCheck) strength++;
      if (uppercaseCheck) strength++;
      if (numberCheck) strength++;

      strengthBarContainer.style.display = 'block';
      if (strength === 1) {
        strengthBar.className = 'strength-bar strength-weak';
      } else if (strength === 2) {
        strengthBar.className = 'strength-bar strength-medium';
      } else if (strength === 3) {
        strengthBar.className = 'strength-bar strength-strong';
      }
    });

    function updateRequirement(element, condition) {
      const textContent = element.textContent.replace(/^\s*(\u2714|\u2716)?\s*/, '');
      if (condition) {
        element.classList.remove('invalid');
        element.classList.add('valid');
        element.innerHTML = '<i class="fas fa-check"></i> ' + textContent;
      } else {
        element.classList.remove('valid');
        element.classList.add('invalid');
        element.innerHTML = '<i class="fas fa-times"></i> ' + textContent;
      }
    }

    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    passwordInput.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
      if (confirmPasswordInput.value.length === 0) {
        confirmPasswordMessage.textContent = '';
        return;
      }
      if (passwordInput.value === confirmPasswordInput.value) {
        confirmPasswordMessage.textContent = 'Passwords match';
        confirmPasswordMessage.style.color = 'green';
      } else {
        confirmPasswordMessage.textContent = 'Passwords do not match';
        confirmPasswordMessage.style.color = 'red';
      }
    }
  </script>
</body>
</html>
