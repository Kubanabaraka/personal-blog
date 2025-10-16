<?php
// index.php
// Displays all blog posts in a card layout ordered by most recent first.

require_once __DIR__ . '/connection.php';

$pageTitle = 'Home';
$currentPage = 'home';

$connection = get_db_connection();

// Retrieve all posts ordered by creation date (newest first).
$stmt = $connection->prepare('SELECT id, title, author, content, created_at FROM posts ORDER BY created_at DESC');
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <h1>InspireHub Stories</h1>
        <p>Discover bold ideas, heartfelt narratives, and practical insights from changemakers across the continent.</p>
        <p class="tagline">Empowering Africa&rsquo;s Youth to Dream, Learn, and Build a Better Future.</p>
    </div>
</section>

<?php if (count($posts) === 0): ?>
    <div class="empty-state" role="status">
        <h2>No posts yet</h2>
    <p>Your voice can spark a movement. Share your vision and lead the conversation forward.</p>
    <a class="btn btn-primary" href="add.php">Share your first story</a>
    </div>
<?php else: ?>
    <section class="posts-grid" aria-live="polite">
        <?php foreach ($posts as $post): ?>
            <?php
                $preview = mb_substr($post['content'], 0, 100);
                if (mb_strlen($post['content']) > 100) {
                    $preview .= '…';
                }
            ?>
            <article class="post-card" tabindex="0">
                <h2>
                    <a href="view.php?id=<?php echo urlencode($post['id']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <p class="post-meta"><strong>By <?php echo htmlspecialchars($post['author']); ?></strong> &middot; <?php echo date('M j, Y g:i a', strtotime($post['created_at'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars($preview)); ?></p>
                <div class="post-actions">
                    <a class="btn btn-secondary" href="view.php?id=<?php echo urlencode($post['id']); ?>">Read more</a>
                    <a class="btn btn-secondary" href="edit.php?id=<?php echo urlencode($post['id']); ?>">Edit</a>
                    <form action="delete.php" method="post" data-delete-form data-post-title="<?php echo htmlspecialchars($post['title']); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
