<?php require 'auth.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Sample Tracking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <h1>Laboratory Sample Tracking System</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="clients.php">Clients</a>
        <a href="samples.php">Samples</a>
        <a href="results.php">Test Results</a><span class="nav-user">Hi, <?= h($_SESSION['full_name'] ?? 'User') ?></span><a href="logout.php">Logout</a>
    </nav>
</header>
<main>
    <h2>Dashboard</h2>
    <p>Welcome, <strong><?= h($_SESSION['full_name']) ?></strong>!</p>
    <?php $lastLogin = $pdo->prepare("SELECT login_time FROM login_logs WHERE user_id = ? ORDER BY log_id DESC LIMIT 1"); $lastLogin->execute([$_SESSION['user_id']]); $loginTime = $lastLogin->fetchColumn(); ?>
    <p class="message">Your login activity is saved in the database. Latest login: <?= h($loginTime ?: 'Just now') ?></p>
    <div class="cards">
        <div class="card">
            <h3>Clients</h3>
            <p><?= (int)$pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn() ?></p>
            <a class="button" href="clients.php">Manage Clients</a>
        </div>
        <div class="card">
            <h3>Samples</h3>
            <p><?= (int)$pdo->query("SELECT COUNT(*) FROM samples")->fetchColumn() ?></p>
            <a class="button" href="samples.php">Manage Samples</a>
        </div>
        <div class="card">
            <h3>Test Results</h3>
            <p><?= (int)$pdo->query("SELECT COUNT(*) FROM test_results")->fetchColumn() ?></p>
            <a class="button" href="results.php">Manage Results</a>
        </div>
    </div>
</main>
<footer>Laboratory Sample Tracking System — Mini Project</footer>
</body>
</html>
