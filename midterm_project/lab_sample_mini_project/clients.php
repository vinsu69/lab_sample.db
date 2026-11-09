<?php
require 'auth.php';
require_admin();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['client_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $contact = trim($_POST['contact_number'] ?? '');

    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO clients (client_name, email, contact_number) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email !== '' ? $email : null, $contact]);
        $message = "Client added successfully.";
    } else {
        $message = "Please enter the client name.";
    }
}

$clients = $pdo->query("SELECT * FROM clients ORDER BY client_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header><h1>Laboratory Sample Tracking System</h1>
<nav><a href="admin_dashboard.php">Dashboard</a><a href="clients.php">Clients</a><a href="samples.php">Samples</a><a href="results.php">Test Results</a><span class="nav-user">Hi,
     <?= h($_SESSION['full_name'] ?? 'User') ?></span><a href="logout.php">Logout</a></nav></header>
<main>
    <h2>Client Management</h2>
    <?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <form method="post">
        <label>Client Name</label>
        <input type="text" name="client_name" required>
        <label>Email Address (use this email for client account matching)</label>
        <input type="email" name="email">
        <label>Contact Number</label>
        <input type="text" name="contact_number">
        <button type="submit">Add Client</button>
    </form>

    <h3>Client List</h3>
    <table><tr><th>ID</th><th>Client Name</th><th>Email</th><th>Contact Number</th></tr>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?= (int)$client['client_id'] ?></td>
            <td><?= htmlspecialchars($client['client_name']) ?></td>
            <td><?= htmlspecialchars($client['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($client['contact_number'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</main>
<footer>Laboratory Sample Tracking System — Mini Project</footer>
</body>
</html>
