<?php
require 'auth.php'; require_login();
if (($_SESSION['role'] ?? 'client') === 'admin') { header('Location: admin_dashboard.php'); exit; }
$sampleId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT sample_id, sample_code, sample_name, collection_date, source_location FROM samples WHERE sample_id = ? AND client_id = ? LIMIT 1');
$stmt->execute([$sampleId, $_SESSION['client_id'] ?? 0]);
$sample = $stmt->fetch();
if (!$sample) { http_response_code(404); exit('Sample not found or you do not have permission to view it.'); }
$tests = $pdo->prepare('SELECT test_name, result_value, test_date FROM test_results WHERE sample_id = ? ORDER BY test_date DESC, result_id DESC');
$tests->execute([$sampleId]); $results = $tests->fetchAll();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Sample Details</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header><h1>Laboratory Sample Tracking System</h1><nav><a href="index.php">My Dashboard</a><span class="nav-user">Hello, <?= h($_SESSION['full_name']) ?></span><a href="logout.php">Logout</a></nav></header>
<main><h2>Sample Details</h2><div class="detail-grid"><p><strong>Sample Code:</strong> <?= h($sample['sample_code']) ?></p><p><strong>Sample Type:</strong> <?= h($sample['sample_name']) ?></p><p><strong>Collection Date:</strong> <?= h($sample['collection_date']) ?></p><p><strong>Source Location:</strong> <?= h($sample['source_location']) ?></p></div>
<h3>Test Results</h3><?php if (!$results): ?><p class="message">No test results have been recorded yet.</p><?php else: ?><div class="table-wrap"><table><tr><th>Test Name</th><th>Result</th><th>Test Date</th></tr><?php foreach ($results as $result): ?><tr><td><?= h($result['test_name']) ?></td><td><?= h($result['result_value']) ?></td><td><?= h($result['test_date']) ?></td></tr><?php endforeach; ?></table></div><?php endif; ?>
<a class="button" href="index.php">Back to Dashboard</a></main><footer>Laboratory Sample Tracking System — Client Portal</footer></body></html>
