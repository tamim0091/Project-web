<?php
// Start the session
session_start();

// Destroy all session variables
$_SESSION = array();

// If you want to completely destroy the session, clear the session cookie as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page after logging out
header("Location: index.php");
exit;
?>
