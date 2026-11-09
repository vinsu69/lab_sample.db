<?php
require_once 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'admin' or 'user'

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        $message = "Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        $error = "Username already exists or database error occurred.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Water Lab System</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; }
        h2 { color: #0066cc; margin-top: 0; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #28a745; color: white; width: 100%; padding: 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #218838; }
        .error { color: #dc3545; font-size: 13px; margin-bottom: 10px; text-align: center; }
        .success { color: #28a745; font-size: 13px; margin-bottom: 10px; text-align: center; }
        .link { text-align: center; margin-top: 15px; font-size: 13px; }
        .link a { color: #0066cc; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>Create Account</h2>
    <?php if (!empty($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if (!empty($message)): ?><div class="success"><?= $message ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Role Access:</label>
            <select name="role">
                <option value="user">User (Client View)</option>
                <option value="admin">Admin (Lab Manager)</option>
            </select>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    <div class="link">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
</body>
</html>