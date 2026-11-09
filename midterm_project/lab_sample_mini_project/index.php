<?php
require 'auth.php'; require_login();
if (($_SESSION['role'] ?? 'client') === 'admin') {
    header('Location: admin_dashboard.php'); exit;
}
$clientId = $_SESSION['client_id'] ?? null;
$samples = [];
if ($clientId) {
    $stmt = $pdo->prepare("SELECT s.sample_id, s.sample_code, s.sample_name, s.collection_date, s.source_location,
        COUNT(r.result_id) AS result_count, MAX(r.test_date) AS latest_test_date
        FROM samples s LEFT JOIN test_results r ON r.sample_id = s.sample_id
        WHERE s.client_id = ? GROUP BY s.sample_id ORDER BY s.sample_id DESC");
    $stmt->execute([$clientId]);
    $samples = $stmt->fetchAll();
}
$totalSamples = count($samples);
$totalTests = 0;
foreach ($samples as $sample) $totalTests += (int)$sample['result_count'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Client Dashboard</title><link rel="stylesheet" href="assets/style.css"></head>
<body><header><h1>Laboratory Sample Tracking System</h1><nav><a href="index.php">My Dashboard</a><span class="nav-user">Hello, <?= h($_SESSION['full_name']) ?></span><a href="logout.php">Logout</a></nav></header>
<main><h2>My Sample Dashboard</h2><p>Welcome, <strong><?= h($_SESSION['full_name']) ?></strong>. You can track the samples registered under your account here.</p>
<div class="cards"><div class="card"><h3>My Samples</h3><p><?= $totalSamples ?></p></div><div class="card"><h3>Recorded Test Entries</h3><p><?= $totalTests ?></p></div></div>
<h3>Sample Tracking</h3>
<?php if (!$clientId): ?><p class="message">Your account is not linked to a client record yet. Please contact the laboratory administrator.</p>
<?php elseif (!$samples): ?><p class="message">No samples have been recorded for your account yet. Please contact the laboratory.</p>
<?php else: ?><div class="table-wrap"><table><tr><th>Sample Code</th><th>Sample Type</th><th>Collection Date</th><th>Source</th><th>Tracking Status</th><th>Latest Test Date</th><th>Results</th></tr>
<?php foreach ($samples as $sample): $status = ((int)$sample['result_count'] > 0) ? 'Testing / Results Recorded' : 'Sample Received / Awaiting Testing'; ?>
<tr><td><?= h($sample['sample_code']) ?></td><td><?= h($sample['sample_name']) ?></td><td><?= h($sample['collection_date']) ?></td><td><?= h($sample['source_location']) ?></td><td><span class="status"><?= h($status) ?></span></td><td><?= h($sample['latest_test_date'] ?: 'Not yet tested') ?></td><td><a href="my_sample.php?id=<?= (int)$sample['sample_id'] ?>">View details</a></td></tr>
<?php endforeach; ?></table></div><?php endif; ?>
<p class="small-note">The tracking status is based on whether test results have been entered. Contact the laboratory for official completion or release information.</p>
</main><footer>Laboratory Sample Tracking System — Client Portal</footer></body></html>
