<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $message = 'Enter your name, a valid email, and a password with at least 8 characters.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $message = 'That email address is already registered.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><main class="auth-card">
<h1>Create Account</h1><p>Register to use the Laboratory Sample Tracking System.</p>
<?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post">
<label>Full Name</label><input type="text" name="full_name" maxlength="100" required>
<label>Email Address</label><input type="email" name="email" maxlength="150" required>
<label>Password (at least 8 characters)</label><input type="password" name="password" minlength="8" required>
<label>Confirm Password</label><input type="password" name="confirm_password" minlength="8" required>
<button type="submit">Register</button>
</form><p>Already have an account? <a href="login.php">Log in</a></p>
</main></body></html>
