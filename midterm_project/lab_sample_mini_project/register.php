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
            $pdo->beginTransaction();
            // Match an existing client record by email, or create a client record.
            $find = $pdo->prepare('SELECT client_id FROM clients WHERE email = ? LIMIT 1');
            $find->execute([$email]);
            $clientId = $find->fetchColumn();
            if (!$clientId) {
                $newClient = $pdo->prepare('INSERT INTO clients (client_name, email) VALUES (?, ?)');
                $newClient->execute([$name, $email]);
                $clientId = (int)$pdo->lastInsertId();
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $addUser = $pdo->prepare("INSERT INTO users (client_id, full_name, email, password, role) VALUES (?, ?, ?, ?, 'client')");
            $addUser->execute([$clientId, $name, $email, $hash]);
            $pdo->commit();
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $message = 'This email may already have an account. Please log in or use another email.';
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Client Registration</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><main class="auth-card"><h1>Client Registration</h1><p>Create an account to track your laboratory samples.</p>
<?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post"><label>Full Name</label><input type="text" name="full_name" maxlength="100" required>
<label>Email Address</label><input type="email" name="email" maxlength="150" required>
<label>Password (at least 8 characters)</label><input type="password" name="password" minlength="8" required>
<label>Confirm Password</label><input type="password" name="confirm_password" minlength="8" required><button type="submit">Register</button></form>
<p>Already registered? <a href="login.php">Log in here</a></p></main></body></html>
