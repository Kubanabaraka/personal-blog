<?php
// edit.php
// Allows updating an existing blog post.

require_once __DIR__ . '/includes/auth_check.php'; // Restrict editing to authenticated users.
require_once __DIR__ . '/connection.php';

$pageTitle = 'Edit Post';
$currentPage = '';
$errors = [];
$postId = null;
$title = '';
$author = '';
$content = '';
$postLoaded = false;
$connection = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['id'] ?? null;

    if ($postId === null || !ctype_digit($postId)) {
        $errors['id'] = 'Invalid post identifier.';
    }

    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Title cannot be empty.';
    }

    if ($author === '') {
        $errors['author'] = 'Author cannot be empty.';
    }

    if ($content === '') {
        $errors['content'] = 'Content cannot be empty.';
    }

    if (empty($errors)) {
        $postIdInt = (int) $postId;
        $stmt = $connection->prepare('UPDATE posts SET title = ?, author = ?, content = ? WHERE id = ?');
        $stmt->bind_param('sssi', $title, $author, $content, $postIdInt);
        $stmt->execute();

        header('Location: view.php?id=' . urlencode($postId));
        exit;
    } else {
        $postLoaded = true; // Keep user-entered data in the form.
    }
} else {
    $postId = $_GET['id'] ?? null;

    if ($postId !== null && ctype_digit($postId)) {
        $postIdInt = (int) $postId;
        $stmt = $connection->prepare('SELECT id, title, author, content FROM posts WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $postIdInt);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        if ($post) {
            $title = $post['title'];
            $author = $post['author'];
            $content = $post['content'];
            $postLoaded = true;
            $postId = (string) $post['id'];
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <a href="view.php?id=<?php echo $postId !== null ? urlencode($postId) : '0'; ?>" class="btn btn-secondary">&larr; Back to InspireHub story</a>
        <h1>Refine your inspiration</h1>
        <p>Polish your message so it continues to uplift, educate, and empower young creators.</p>
        <p class="tagline">Empowering Africa&rsquo;s Youth to Dream, Learn, and Build a Better Future.</p>
    </div>
</section>

<?php if (!$postLoaded): ?>
    <div class="empty-state" role="alert">
        <h2>Post not found</h2>
        <p>We couldn't locate the post you're trying to edit. It may have been deleted.</p>
        <a class="btn btn-primary" href="index.php">Return home</a>
    </div>
<?php else: ?>
    <div class="form-wrapper">
        <?php if (!empty($errors)): ?>
            <div class="error-message" role="alert">
                <strong>Let's fix these issues:</strong>
                <ul>
                    <?php foreach ($errors as $message): ?>
                        <li><?php echo htmlspecialchars($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="post" novalidate>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($postId); ?>">

            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>

            <div>
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
            </div>

            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save inspiration</button>
                <a class="btn btn-secondary" href="view.php?id=<?php echo urlencode($postId); ?>">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
