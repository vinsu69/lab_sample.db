<?php require 'auth.php'; require_admin();
$clientCount = (int)$pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn();
$sampleCount = (int)$pdo->query('SELECT COUNT(*) FROM samples')->fetchColumn();
$resultCount = (int)$pdo->query('SELECT COUNT(*) FROM test_results')->fetchColumn();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Dashboard</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header><h1>Laboratory Sample Tracking System</h1><nav><a href="admin_dashboard.php">Admin Dashboard</a><a href="clients.php">Clients</a><a href="samples.php">Samples</a><a href="results.php">Test Results</a><span class="nav-user">Admin: <?= h($_SESSION['full_name']) ?></span><a href="logout.php">Logout</a></nav></header>
<main><h2>Administrator Dashboard</h2><div class="cards"><div class="card"><h3>Clients</h3><p><?= $clientCount ?></p><a class="button" href="clients.php">Manage Clients</a></div><div class="card"><h3>Samples</h3><p><?= $sampleCount ?></p><a class="button" href="samples.php">Manage Samples</a></div><div class="card"><h3>Test Results</h3><p><?= $resultCount ?></p><a class="button" href="results.php">Manage Results</a></div></div></main><footer>Laboratory Sample Tracking System — Admin Portal</footer></body></html>
