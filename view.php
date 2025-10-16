<?php
// view.php
// Displays the full content for a single post.

require_once __DIR__ . '/connection.php';

$postId = $_GET['id'] ?? null;
$post = null;

if ($postId !== null && ctype_digit($postId)) {
    $connection = get_db_connection();
    $stmt = $connection->prepare('SELECT id, title, author, content, created_at FROM posts WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
}

if ($post === null) {
    $pageTitle = 'Post not found';
} else {
    $pageTitle = $post['title'];
}

$currentPage = '';

include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <a href="index.php" class="btn btn-secondary">&larr; Back to InspireHub stories</a>
        <p class="tagline">Empowering Africa&rsquo;s Youth to Dream, Learn, and Build a Better Future.</p>
    </div>
</section>

<?php if ($post === null): ?>
    <div class="empty-state" role="alert">
        <h2>Post not found</h2>
        <p>The post you are looking for might have been removed or never existed.</p>
        <a class="btn btn-primary" href="index.php">Return home</a>
    </div>
<?php else: ?>
    <article class="post-card">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="post-meta"><strong>By <?php echo htmlspecialchars($post['author']); ?></strong> &middot; <?php echo date('M j, Y g:i a', strtotime($post['created_at'])); ?></p>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <div class="post-actions">
            <a class="btn btn-secondary" href="edit.php?id=<?php echo urlencode($post['id']); ?>">Edit story</a>
            <form action="delete.php" method="post" data-delete-form data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
                <button type="submit" class="btn btn-primary">Delete story</button>
            </form>
        </div>
    </article>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
