<?php
// add.php
// Presents a form for creating a new blog post and saves it to the database upon submission.

require_once __DIR__ . '/includes/auth_check.php'; // Protect this page so only authenticated users can add posts.
require_once __DIR__ . '/connection.php';

$pageTitle = 'Add Post';
$currentPage = 'add';
$errors = [];
$title = '';
$author = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Please provide a title.';
    }

    if ($author === '') {
        $errors['author'] = 'Please tell us who wrote the post.';
    }

    if ($content === '') {
        $errors['content'] = 'Content cannot be empty.';
    }

    if (empty($errors)) {
        $connection = get_db_connection();
        $stmt = $connection->prepare('INSERT INTO posts (title, author, content) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $title, $author, $content);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <h1>Share Your InspireHub Story</h1>
        <p>Let your ideas uplift communities, spotlight innovations, and ignite the next generation of builders.</p>
        <p class="tagline">Empowering Africa&rsquo;s Youth to Dream, Learn, and Build a Better Future.</p>
    </div>
</section>

<div class="form-wrapper">
    <?php if (!empty($errors)): ?>
        <div class="error-message" role="alert">
            <strong>We found a few issues:</strong>
            <ul>
                <?php foreach ($errors as $field => $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
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
            <button type="submit" class="btn btn-primary">Publish inspiration</button>
            <a class="btn btn-secondary" href="index.php">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
