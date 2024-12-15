<?php
session_start();
include __DIR__ . '../../Controller/UserController.php';

$userController = new UserController();

if (isset($_SESSION['id'])) {
    // Clear remember token
    $userController->setRememberMeToken($_SESSION['id'], null);
}

// Clear session
$_SESSION = array();
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/", "", false, true);
}

header("Location: MainPage.php");
exit;
