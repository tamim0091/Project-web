<?php
require_once '../controller/UserController.php';
require_once '../Model/user.php';
session_start();

// Redirect if not logged in or not Admin
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

$utilisateursC = new UserController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'addUser':
            try {
                $fullName = $_POST['fullname'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];
                // Use formatted phone number from hidden input
                $phone = $_POST['FormattedPhoneNumber'] ?? '';
                $gender = $_POST['gender'];
                $role = $_POST['role'];

                if ($password !== $confirmPassword) {
                    $message = 'Error: Passwords do not match!';
                } else {
                    $user = new User(null, $fullName, $email, $password, $phone, $gender, $role);
                    if ($utilisateursC->addUser($user)) {
                        $message = 'User added successfully!';
                    } else {
                        $message = 'Error: Failed to add user.';
                    }
                }
            } catch (Exception $e) {
                $message = 'Error: ' . $e->getMessage();
            }
            break;

        case 'delete':
            if ($utilisateursC->deleteUser($_POST['id'])) {
                $message = 'User deleted successfully!';
            } else {
                $message = 'Error: Failed to delete user.';
            }
            break;

        case 'makeAdmin':
            if ($utilisateursC->makeAdmin($_POST['id'])) {
                $message = 'User promoted to Admin!';
            } else {
                $message = 'Error: Failed to promote user.';
            }
            break;

        case 'makeUser':
            if ($utilisateursC->makeUser($_POST['id'])) {
                $message = 'User demoted to User!';
            } else {
                $message = 'Error: Failed to demote user.';
            }
            break;

        default:
            $message = 'Invalid action.';
            break;
    }

    header('Location: AdminDashboard.php?message=' . urlencode($message));
    exit();
}

$users = $utilisateursC->listUsers();
$currentUserId = $_SESSION['id'];
$filteredUsers = array_filter($users, fn($user) => $user['id'] !== $currentUserId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Chronovoyage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style2.css">

    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.css" />

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f5f7;
        }

        .navbar-nav {
        display: flex;
        justify-content: flex-end; /* Align navbar items to the right */
        list-style: none; /* Remove bullet points */
        }

        .navbar-nav > li {
        margin-left: 20px; /* Space out navbar items */
        }

        .navbar-nav > li > a {
        color: #fff; /* Ensure navbar text is white */
        padding: 10px 15px; /* Add some padding around the links */
        }

        .navbar-nav > li > a:hover {
        color: #ff6600; /* Highlight color on hover */
        }

        /* Optional: Aligning the dropdown button on the right */
        .nav-user-wrapper {
        display: flex;
        justify-content: flex-end;
        }

      .navbar {
        background-color: #333; /* Or any other color you prefer */
      }

      .navbar-brand {
        color: white; /* Make sure the text stands out */
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
        .password-requirements li i { margin-right: 5px; }
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
            width: 15px; height: 15px;
            margin-right: 5px; accent-color: #555;
        }
        #user-table th, #user-table td {
            vertical-align: middle;
        }

        .welcome-card {
            border: none;
            background: #f4f5f7;
            margin-bottom: 20px;
        }

        .welcome-card .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 15px 20px;
        }

        .welcome-card .welcome-text {
            font-weight: 600;
            font-size: 1.25rem;
            margin: 0;
        }

        .welcome-card .logout-btn {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    
    <?php if (isset($_SESSION['Fullname'])): ?>
    <div class="card welcome-card">
        <div class="card-body">
            <h5 class="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['Fullname']); ?></h5>
            <a href="logout.php" class="btn btn-secondary btn-sm logout-btn">Logout</a>
        </div>
    </div>
    <?php endif; ?>

    <h2 class="mb-4">Admin Dashboard</h2>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info"><?= htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Add New User</h5>
        </div>
        <div class="card-body">
            <form action="AdminDashboard.php" method="POST" id="addUserForm">
                <input type="hidden" name="action" value="addUser">
                <!-- Hidden input for formatted phone number -->
                <input type="hidden" name="FormattedPhoneNumber" id="FormattedPhoneNumber">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" id="FullName" class="form-control" placeholder="Enter Full Name" required>
                        <div id="nameMessage" class="validation-message"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="Email" class="form-control" placeholder="Enter Email" required>
                        <div id="emailMessage" class="validation-message"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" id="PhoneNumber" class="form-control" placeholder="e.g. +1 650-253-0000" required>
                        <div id="phoneMessage" class="validation-message"></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="Password" class="form-control" placeholder="Enter Password" required>
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" id="ConfirmPassword" class="form-control" placeholder="Confirm Password" required>
                        <div id="confirmPasswordMessage"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Don't wanna specify">Don't wanna specify</option>
                        </select>
                        <label class="form-label mt-3">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add User</button>
            </form>
        </div>
    </div>

    <!-- Sort & Search Row -->
    <div class="row mb-3">
        <div class="col-md-8 d-flex align-items-center">
            <label for="sort-column" class="form-label mb-0 me-2">Sort by:</label>
            <select id="sort-column" class="form-select d-inline w-auto me-2">
                <option value="1">Full Name</option>
                <option value="2">Email</option>
                <option value="4">Gender</option>
                <option value="5">Role</option>
            </select>
            <button id="sort-button" class="btn btn-primary">
                <i class="fas fa-sort"></i> Sort
            </button>
        </div>
        <div class="col-md-4 d-flex justify-content-end">
            <input type="text" id="search-input" class="form-control" placeholder="Search users...">
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="user-table">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($filteredUsers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']); ?></td>
                    <td><?= htmlspecialchars($user['FullName']); ?></td>
                    <td><?= htmlspecialchars($user['Email']); ?></td>
                    <td><?= htmlspecialchars($user['PhoneNumber']); ?></td>
                    <td><?= htmlspecialchars($user['Gender']); ?></td>
                    <td><?= htmlspecialchars($user['Role']); ?></td>
                    <td>
                        <div class="btn-group">
                            <form action="AdminDashboard.php" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            <form action="AdminDashboard.php" method="POST">
                                <input type="hidden" name="action" value="<?= $user['Role'] === 'User' ? 'makeAdmin' : 'makeUser'; ?>">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                <button type="submit" class="btn <?= $user['Role'] === 'User' ? 'btn-success' : 'btn-warning'; ?> btn-sm">
                                    <i class="fas <?= $user['Role'] === 'User' ? 'fa-user-shield' : 'fa-user'; ?>"></i>
                                    <?= $user['Role'] === 'User' ? 'Make Admin' : 'Make User'; ?>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
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
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    if (iti.isValidNumber()) {
        formattedPhoneInput.value = iti.getNumber(); // E.164 format
    } else {
        e.preventDefault();
        phoneMessage.textContent = 'Invalid phone number';
        phoneMessage.style.color = 'red';
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

// Search Functionality
document.getElementById('search-input').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#user-table tbody tr');
    rows.forEach(row => {
        const cells = Array.from(row.cells).map(cell => cell.textContent.toLowerCase());
        row.style.display = cells.some(cell => cell.includes(searchValue)) ? '' : 'none';
    });
});

// Sorting Functionality
const sortButton = document.getElementById('sort-button');
const sortColumnSelect = document.getElementById('sort-column');
let sortOrder = 'asc';

sortButton.addEventListener('click', () => {
    const table = document.getElementById('user-table');
    const rows = Array.from(table.tBodies[0].rows);
    const columnIndex = parseInt(sortColumnSelect.value, 10);

    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim().toLowerCase();
        const bValue = b.cells[columnIndex].textContent.trim().toLowerCase();

        return sortOrder === 'asc'
            ? aValue.localeCompare(bValue, undefined, { numeric: true })
            : bValue.localeCompare(aValue, undefined, { numeric: true });
    });

    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    rows.forEach(row => table.tBodies[0].appendChild(row));
});
</script>

</body>
</html>
