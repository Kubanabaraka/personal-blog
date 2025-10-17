<?php
// login.php
// Handles user authentication for InspireHub Blog using session-based login.

require_once __DIR__ . '/connection.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// If the user is already logged in, there is no need to show the login form again.
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errorMessage = '';
$successMessage = '';
$username = '';
// Preserve redirect target across requests; default to homepage.
$redirectTo = $_GET['redirect'] ?? 'index.php';

if (isset($_GET['registered']) && (int) $_GET['registered'] === 1) {
    $successMessage = 'Account created successfully. You can now log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirectTo = $_POST['redirect'] ?? 'index.php';

    if ($username === '' || $password === '') {
        $errorMessage = 'Please provide both username and password.';
    } else {
        try {
            $connection = get_db_connection();
            $stmt = $connection->prepare('SELECT id, username, password, full_name FROM users WHERE username = ? LIMIT 1');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true); // Prevent session fixation.

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'] ?: $user['username'];

                // Restrict redirects to relative paths to avoid open redirect attacks.
          // Always redirect inside the project folder
$projectBase = '/personal_blogger/'; // your project folder name
$target = $projectBase . 'index.php';

if (!empty($redirectTo) && strpos($redirectTo, '://') === false) {
    $redirectTo = ltrim($redirectTo, '/');
    $target = $projectBase . $redirectTo;
}

header('Location: http://localhost' . $target);
exit;

            }

            $errorMessage = 'Invalid credentials. Please try again.';
        } catch (mysqli_sql_exception $exception) {
            // Fail safely without exposing database details.
            $errorMessage = 'Unable to process your request at the moment. Please try again later.';
        }
    }
}

$pageTitle = 'Login';
$currentPage = '';
include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <h1>Access InspireHub</h1>
        <p>Log in to publish new stories, refine your ideas, and keep the community inspired.</p>
    </div>
</section>

<div class="form-wrapper">
    <?php if ($errorMessage !== ''): ?>
        <div class="error-message" role="alert">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>
    <?php if ($successMessage !== ''): ?>
        <div class="success-message" role="status">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectTo); ?>">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Login</button>
            <a class="btn btn-secondary" href="index.php">Cancel</a>
        </div>
    </form>
    <p class="auth-note">Don’t have an account? <a href="register.php">Register here.</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
