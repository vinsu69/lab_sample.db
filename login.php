<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check user and password (using simple verification for project clarity)
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // 'admin' or 'user'
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Water Lab System</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; }
        h2 { color: #0066cc; margin-top: 0; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #0066cc; color: white; width: 100%; padding: 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #004d99; }
        .error { color: #dc3545; font-size: 13px; margin-bottom: 10px; text-align: center; }
        .link { text-align: center; margin-top: 15px; font-size: 13px; }
        .link a { color: #0066cc; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>Lab System Login</h2>
    <?php if (!empty($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <div class="link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>