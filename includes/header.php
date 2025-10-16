<?php
// includes/header.php
// Provides the HTML document head, top navigation, and opening layout markup.

$pageTitle = $pageTitle ?? 'InspireHub Blog';
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#7C3AED">
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
        </div>
    </header>
    <main class="container page-content">
