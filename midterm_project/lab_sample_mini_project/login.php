
<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in.
if (!empty($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? 'client') === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$message = isset($_GET['registered'])
    ? 'Registration successful. You can now log in.'
    : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        'SELECT user_id, full_name, email, password, role
         FROM users
         WHERE email = ?
         LIMIT 1'
    );

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $user['user_id'];
        $_SESSION['client_id'] = (int) $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: index.php');
        }

        exit;

    } else {
        $message = 'Incorrect email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client / Admin Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="auth-page">

<main class="auth-card">

    <h1>Log In</h1>

    <p>
   
    </p>

    <?php if ($message): ?>
        <p class="message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="post" action="login.php">

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            autocomplete="username"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">Log In</button>

    </form>

    <p>
        New client?
        <a href="register.php">Create an account</a>
    </p>

</main>

</body>
</html>
