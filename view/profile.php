<?php
include '../controller/usercontroller.php';

$error = "";
$success = "";

session_start();

// Ensure user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    die('User ID is not set in session.');
}

$id = $_SESSION['id'];
$userc = new UserController();

// Fetch user details
$user = $userc->showUser($id);
if ($user === false) {
    die('User not found.');
}

// Handle form submission
if (
    isset($_POST["FullName"]) &&
    isset($_POST["Email"]) &&
    isset($_POST["PhoneNumber"]) &&
    isset($_POST["Password"]) &&
    isset($_POST["ConfirmPassword"])
) {
    $FullName        = trim($_POST["FullName"]);
    $Email           = trim($_POST["Email"]);
    $Password        = trim($_POST["Password"]);
    $ConfirmPassword = trim($_POST["ConfirmPassword"]);
    // Use formatted phone number from hidden input
    $PhoneNumber     = trim($_POST["FormattedPhoneNumber"] ?? '');
    
    // Validate required fields
    if (empty($FullName) || empty($Email) || empty($PhoneNumber)) {
        $error = "All fields (except password) are required.";
    } else {
        // If password fields are empty, keep old password
        if (empty($Password) && empty($ConfirmPassword)) {
            $hashedPassword = $user['Password'];
        } else {
            // Validate password match
            if ($Password !== $ConfirmPassword) {
                $error = "Passwords do not match.";
            } else {
                // Password complexity check
                $uppercase = preg_match('@[A-Z]@', $Password);
                $number    = preg_match('@[0-9]@', $Password);
                if (strlen($Password) < 8 || !$uppercase || !$number) {
                    $error = "Password must be at least 8 characters long, contain at least one uppercase letter, and one number.";
                } else {
                    // Hash the new password
                    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
                }
            }
        }

        // If no errors so far, proceed
        if (empty($error)) {
            $gender = $user['Gender']; // keep same
            $role = $user['Role'];     // keep same

            $updatedUser = new User(
                $id,
                $FullName,
                $Email,
                $hashedPassword,
                $PhoneNumber,
                $gender,
                $role
            );

            $userc->updateUser($updatedUser, $id);

            $success = "Profile updated successfully!";
            header("Location: UserDashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Profile Update | Chronovoyage</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="style.css">

  <!-- intl-tel-input CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.css" />

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f5f7;
    }
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
    .strength-weak { background-color: red; width: 33%; }
    .strength-medium { background-color: orange; width: 67%; }
    .strength-strong { background-color: green; width: 100%; }

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
    .valid { color: green; }
    .invalid { color: red; }
    #confirmPasswordMessage {
      font-size: 0.8em;
      margin-top: 5px;
    }

    .show-password-container {
      margin-top: 5px;
      display: flex;
      align-items: center;
      font-size: 0.9em;
    }
    .show-password-container input[type="checkbox"] {
      width: 15px;
      height: 15px;
      margin-right: 5px;
      accent-color: #555;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h2>Edit Your Profile</h2>
    <!-- Display error or success messages -->
    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="card mt-4">
      <div class="card-header">
        Update Profile
      </div>
      <div class="card-body">
        <form action="" method="POST" id="updateProfileForm">
          <input type="hidden" name="FormattedPhoneNumber" id="FormattedPhoneNumber">
          
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" placeholder="Enter your name" name="FullName" id="FullName" class="form-control"
                   value="<?php echo htmlspecialchars($user['FullName'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <div id="nameMessage" class="validation-message"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" placeholder="Enter your email" name="Email" id="Email" class="form-control"
                   value="<?php echo htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <div id="emailMessage" class="validation-message"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel" placeholder="e.g. +1 650-253-0000" name="PhoneNumber" id="PhoneNumber" class="form-control"
                   value="<?php echo htmlspecialchars($user['PhoneNumber'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <div id="phoneMessage" class="validation-message"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" placeholder="Enter new password (leave blank to keep current)" name="Password" id="Password" class="form-control">
            <div class="show-password-container">
              <input type="checkbox" id="showPasswordCheckbox">
              <label for="showPasswordCheckbox">Show Password</label>
            </div>
            <div class="strength-bar-container">
              <div class="strength-bar" id="strengthBar"></div>
            </div>
            <ul class="password-requirements" id="passwordRequirements">
              <li id="lengthRequirement" class="invalid"><i class="fas fa-times"></i> At least 8 characters</li>
              <li id="uppercaseRequirement" class="invalid"><i class="fas fa-times"></i> At least one uppercase letter</li>
              <li id="numberRequirement" class="invalid"><i class="fas fa-times"></i> At least one number</li>
            </ul>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" placeholder="Confirm new password" name="ConfirmPassword" id="ConfirmPassword" class="form-control">
            <div id="confirmPasswordMessage"></div>
          </div>

          <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
      </div>
    </div>
  </div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/intlTelInput.min.js"></script>

<script>
// Auto-uppercase Full Name on blur
const fullNameInput = document.getElementById('FullName');
const nameMessage = document.getElementById('nameMessage');
fullNameInput.addEventListener('blur', function() {
    let value = fullNameInput.value.trim();
    value = value.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
    fullNameInput.value = value;

    if (/^[A-Z][a-zA-Z ]*$/.test(value)) {
        nameMessage.textContent = 'Looks good!';
        nameMessage.style.color = 'green';
    } else {
        nameMessage.textContent = 'Name should start with a capital letter and contain only letters and spaces.';
        nameMessage.style.color = 'red';
    }
});

// Email validation on input
const emailInput = document.getElementById('Email');
const emailMessage = document.getElementById('emailMessage');
emailInput.addEventListener('input', function() {
    const emailVal = emailInput.value;
    if (emailVal.length === 0) {
        emailMessage.textContent = '';
        return;
    }
    if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
        emailMessage.textContent = 'Valid email';
        emailMessage.style.color = 'green';
    } else {
        emailMessage.textContent = 'Invalid email';
        emailMessage.style.color = 'red';
    }
});

// Initialize intl-tel-input for phone field
const phoneInput = document.getElementById('PhoneNumber');
const formattedPhoneInput = document.getElementById('FormattedPhoneNumber');
const phoneMessage = document.getElementById('phoneMessage');

const iti = window.intlTelInput(phoneInput, {
    initialCountry: "auto",
    geoIpLookup: function(success, failure) {
        fetch("https://ipapi.co/json")
          .then(res => res.json())
          .then(data => success(data.country_code))
          .catch(() => success("us"));
    },
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/utils.js"
});

phoneInput.addEventListener('blur', function() {
    if (phoneInput.value.trim().length === 0) {
        phoneMessage.textContent = '';
        return;
    }
    if (iti.isValidNumber()) {
        phoneMessage.textContent = 'Valid phone number';
        phoneMessage.style.color = 'green';
    } else {
        phoneMessage.textContent = 'Invalid phone number';
        phoneMessage.style.color = 'red';
    }
});

// Before form submit, get the full number in E.164 format
document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
    if (phoneInput.value.trim().length > 0) {
        if (iti.isValidNumber()) {
            formattedPhoneInput.value = iti.getNumber(); // E.164 format
        } else {
            e.preventDefault();
            phoneMessage.textContent = 'Invalid phone number';
            phoneMessage.style.color = 'red';
        }
    }
});

// Password strength and show password
const passwordInput = document.getElementById('Password');
const confirmPasswordInput = document.getElementById('ConfirmPassword');
const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
const strengthBarContainer = document.querySelector('.strength-bar-container');
const strengthBar = document.getElementById('strengthBar');
const passwordRequirements = document.getElementById('passwordRequirements');
const lengthReq = document.getElementById('lengthRequirement');
const uppercaseReq = document.getElementById('uppercaseRequirement');
const numberReq = document.getElementById('numberRequirement');
const confirmPasswordMessage = document.getElementById('confirmPasswordMessage');

showPasswordCheckbox.addEventListener('change', function() {
    passwordInput.type = this.checked ? 'text' : 'password';
    confirmPasswordInput.type = this.checked ? 'text' : 'password';
});

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
