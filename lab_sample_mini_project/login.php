<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$message = isset($_GET['registered']) ? 'Registration successful. You can now log in.' : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];

        // Save each successful login to the database.
        $log = $pdo->prepare('INSERT INTO login_logs (user_id, ip_address, user_agent) VALUES (?, ?, ?)');
        $log->execute([
            $user['user_id'],
            $_SERVER['REMOTE_ADDR'] ?? null,
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
        ]);

        header('Location: index.php');
        exit;
    } else {
        $message = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><main class="auth-card">
<h1>Log In</h1><p>Welcome back to the Laboratory Sample Tracking System.</p>
<?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post">
<label>Email Address</label><input type="email" name="email" required>
<label>Password</label><input type="password" name="password" required>
<button type="submit">Log In</button>
</form><p>Don't have an account? <a href="register.php">Register here</a></p>
</main></body></html>
