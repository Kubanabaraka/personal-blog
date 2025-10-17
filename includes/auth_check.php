<?php
// includes/auth_check.php
// Ensures that only authenticated users can access protected pages.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Determine the current request URI to redirect the user back after login.
$currentUrl = $_SERVER['REQUEST_URI'] ?? 'index.php';

if (empty($_SESSION['user_id'])) {
    // Redirect unauthenticated visitors to the login page with the intended destination.
    header('Location: login.php?redirect=' . urlencode($currentUrl));
    exit;
}
?>
