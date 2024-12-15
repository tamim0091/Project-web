<?php
include '../controller/usercontroller.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$error = "";

$userc = new UserController();
$role = "User";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $FullName        = trim($_POST["FullName"] ?? '');
    $Email           = trim($_POST["Email"] ?? '');
    $Password        = trim($_POST["Password"] ?? '');
    $ConfirmPassword = trim($_POST["ConfirmPassword"] ?? '');
    $PhoneNumber     = trim($_POST["FormattedPhoneNumber"] ?? ''); // We'll use the formatted number from hidden input
    $Gender          = trim($_POST["Gender"] ?? '');

    // Validate required fields
    if (empty($FullName) || empty($Email) || empty($Password) || empty($ConfirmPassword) || empty($PhoneNumber) || empty($Gender)) {
        $error = "Missing information. Please fill out all fields.";
    } else {
        // Full name validation: start with capital letter and only letters/spaces
        if (!preg_match("/^[A-Z][a-zA-Z ]*$/", $FullName)) {
            $error = "Full Name should start with a capital letter and contain only letters and spaces.";
        }

        // Email validation: Proper email format
        if (empty($error) && !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        }

        // Phone number validation (E.164 format check, optional)
        // Assuming the plugin gives us a proper E.164 format like +123456789
        if (empty($error) && !preg_match("/^\+\d{6,15}$/", $PhoneNumber)) {
            $error = "Invalid phone number format.";
        }

        // Passwords match
        if (empty($error) && $Password !== $ConfirmPassword) {
            $error = "Passwords do not match!";
        }

        // Password complexity check
        $uppercase = preg_match('@[A-Z]@', $Password);
        $number    = preg_match('@[0-9]@', $Password);

        if (empty($error) && (strlen($Password) < 8 || !$uppercase || !$number)) {
            $error = "Password must be at least 8 characters long, contain at least one uppercase letter, and one number.";
        }

        // If no errors so far, proceed with user creation
        if (empty($error)) {
            // Hash the password
            $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

            $user = new User(
                null,
                $FullName,
                $Email,
                $hashedPassword,
                $PhoneNumber,
                $Gender,
                $role
            );

            if ($userc->addUser($user)) {
                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'choeurproject@gmail.com'; 
                    $mail->Password = 'oabw kzbc bghm mgeb';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Sender and recipient
                    $mail->setFrom('choeurproject@gmail.com', 'Chronovoyage');
                    $mail->addAddress($Email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Account Registration Confirmation';
                    $emailBody = 'Dear ' . htmlspecialchars($FullName, ENT_QUOTES, 'UTF-8') . ',<br><br>Thank you for registering with us.<br>Your account is now created.<br><br>Best regards,<br>Chronovoyage Team';
                    $mail->Body = $emailBody;

                    if ($mail->send()) {
                        echo '<p style="color: green;">A confirmation email has been sent to your email address.</p>';
                    } else {
                        echo '<p style="color: red;">There was a problem sending the email.</p>';
                    }

                    // Redirect after successful registration
                    header('Location: login.php');
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "There was a problem adding the user.";
            }
        }
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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dKy2q+OgnA4TfIn2yfwgCM0XiaY+xCz6cnJeuEF8r+F1FaFOc5KV0p1QMmFqdnS6PrU0uW+nD9wOrPUkl6zMA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- intl-tel-input CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.css" />

  <style>
    .strength-bar-container {
      width: 100%;
      height: 5px;
      background: #ddd;
      border-radius: 3px;
      margin-top: 5px;
      display: none; /* hidden by default */
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
      display: none; /* hidden until user types */
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
    .validation-message {
      font-size: 0.8em;
      margin-top: 5px;
    }

    #confirmPasswordMessage {
      font-size: 0.8em;
      margin-top: 5px;
    }

    /* Dropdown for Gender */
    .gender-details select {
      padding: 5px;
      font-size: 14px;
      margin-top: 5px;
      width: 100%;
    }

    /* Phone validation message */
    #phoneMessage {
      font-size: 0.8em;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="chrono.png" alt="Chronovoyage Logo" class="logo">
    <div class="title">Registration</div>
    <div class="content">
      <form action="" method="POST" id="registrationForm">
        <div class="user-details">
          <!-- Full Name -->
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" placeholder="Enter your name" name="FullName" id="FullName" required>
            <div id="nameMessage" class="validation-message"></div>
          </div>

          <!-- Email -->
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" placeholder="Enter your email" name="Email" id="Email" required>
            <div id="emailMessage" class="validation-message"></div>
          </div>

          <!-- Phone Number with intl-tel-input -->
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="tel" id="PhoneNumber" name="PhoneNumber" placeholder="e.g. +1 650-253-0000" required>
            <div id="phoneMessage" class="validation-message"></div>
          </div>

          <!-- Password -->
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" placeholder="Enter your password" name="Password" id="Password" required>
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

          <!-- Confirm Password -->
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" placeholder="Confirm your password" name="ConfirmPassword" id="ConfirmPassword" required>
            <div id="confirmPasswordMessage"></div>
          </div>
        </div>
        
        <!-- Gender Dropdown -->
        <div class="gender-details">
          <span class="details">Gender</span>
          <select name="Gender" id="Gender" required>
            <option value="" disabled selected>Select your gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Prefer not to say">Prefer not to say</option>
          </select>
        </div>
        
        <!-- Hidden input for final formatted phone number -->
        <input type="hidden" name="FormattedPhoneNumber" id="FormattedPhoneNumber" />

        <div class="button">
          <input type="submit" value="Register">
        </div>
      </form>
    </div>
  </div>

  <!-- intl-tel-input JS -->
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/intlTelInput.min.js"></script>

  <script>
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

    const fullNameInput = document.getElementById('FullName');
    const nameMessage = document.getElementById('nameMessage');
    const emailInput = document.getElementById('Email');
    const emailMessage = document.getElementById('emailMessage');
    const phoneInput = document.getElementById('PhoneNumber');
    const phoneMessage = document.getElementById('phoneMessage');
    const formattedPhoneInput = document.getElementById('FormattedPhoneNumber');

    // Initialize intl-tel-input
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

    // Validate phone number on input
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
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      if (iti.isValidNumber()) {
        formattedPhoneInput.value = iti.getNumber(); // E.164 format
      } else {
        // If invalid, prevent submission
        e.preventDefault();
        phoneMessage.textContent = 'Invalid phone number';
        phoneMessage.style.color = 'red';
      }
    });

    // Show/hide password
    showPasswordCheckbox.addEventListener('change', function() {
      passwordInput.type = this.checked ? 'text' : 'password';
    });

    // Password strength and requirements
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

    // Confirm password match check
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

    // Name: On blur, capitalize first letter of each word
    fullNameInput.addEventListener('blur', function() {
      let value = fullNameInput.value.trim();
      value = value.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
      fullNameInput.value = value;

      // Validate name: must start with capital letter and only letters/spaces
      if (/^[A-Z][a-zA-Z ]*$/.test(value)) {
        nameMessage.textContent = 'Looks good!';
        nameMessage.style.color = 'green';
      } else {
        nameMessage.textContent = 'Name should start with a capital letter and contain only letters and spaces.';
        nameMessage.style.color = 'red';
      }
    });

    // Email: real-time validation
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
  </script>
</body>
</html>
