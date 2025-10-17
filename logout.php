<?php
// logout.php
// Destroys the current session and returns the user to the homepage.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Clear all session data and destroy the session cookie.
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

header('Location: index.php');
exit;
