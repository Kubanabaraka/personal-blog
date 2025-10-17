<?php
// register.php
// Allows InspireHub Blog visitors to create an account for posting and managing content.

require_once __DIR__ . '/connection.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// If the visitor is already authenticated, there is no need to register a new account.
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Create Account';
$currentPage = '';

$errors = [];
$fullName = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $username === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'All fields are required. Please complete the form.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match. Enter the same password in both fields.';
    }

    if (empty($errors)) {
        try {
            $connection = get_db_connection();

            // Ensure the requested username is not already in use before attempting to insert.
            $userCheck = $connection->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
            $userCheck->bind_param('s', $username);
            $userCheck->execute();
            $existingUser = $userCheck->get_result()->fetch_assoc();

            if ($existingUser) {
                $errors[] = 'Username already taken. Pick another username to continue.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = $connection->prepare('INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)');
                $insert->bind_param('sss', $username, $hashedPassword, $fullName);
                $insert->execute();

                header('Location: login.php?registered=1');
                exit;
            }
        } catch (mysqli_sql_exception $exception) {
            // Provide a generic error to avoid leaking system details.
            $errors[] = 'We ran into an issue creating your account. Please try again in a moment.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div class="section-intro">
        <h1>Join InspireHub</h1>
        <p>Create an account to share your ideas, collaborate with peers, and grow our storytelling community.</p>
    </div>
</section>

<div class="form-wrapper">
    <?php if (!empty($errors)): ?>
        <div class="error-message" role="alert">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <div>
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>" autocomplete="name" required>
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="new-password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
            <a class="btn btn-secondary" href="login.php">Cancel</a>
        </div>
    </form>

    <p class="auth-note">Already have an account? <a href="login.php">Login here.</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
