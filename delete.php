<?php
// delete.php
// Handles deletion of a blog post and redirects back to the homepage.

require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$postId = $_POST['id'] ?? null;

if ($postId === null || !ctype_digit($postId)) {
    header('Location: index.php');
    exit;
}

$connection = get_db_connection();
$postIdInt = (int) $postId;

$stmt = $connection->prepare('DELETE FROM posts WHERE id = ?');
$stmt->bind_param('i', $postIdInt);
$stmt->execute();

header('Location: index.php');
exit;
