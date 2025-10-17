<?php
// includes/header.php
// Provides the HTML document head, top navigation, and opening layout markup.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'InspireHub Blog';
$currentPage = $currentPage ?? '';
$isLoggedIn = !empty($_SESSION['user_id']);
$displayName = $isLoggedIn ? ($_SESSION['full_name'] ?? $_SESSION['username']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#007CF0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | InspireHub Blog</title>
    <!-- Google Font for modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a class="logo" href="index.php">InspireHub Blog</a>
            <nav class="site-nav" aria-label="Main navigation">
                <a href="index.php" class="nav-link<?php echo $currentPage === 'home' ? ' active' : ''; ?>">Home</a>
                <a href="add.php" class="nav-link nav-link--cta<?php echo $currentPage === 'add' ? ' active' : ''; ?>">Add Post</a>
            </nav>
            <div class="auth-actions">
                <?php if ($isLoggedIn): ?>
                    <span class="auth-greeting">Hello, <?php echo htmlspecialchars($displayName); ?></span>
                    <a class="btn btn-secondary btn-logout" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-primary btn-login" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="container page-content">
