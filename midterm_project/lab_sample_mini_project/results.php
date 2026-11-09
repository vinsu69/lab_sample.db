<?php
require 'auth.php';
require_admin();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sampleId = (int)($_POST['sample_id'] ?? 0);
    $testName = trim($_POST['test_name'] ?? '');
    $value = trim($_POST['result_value'] ?? '');
    $testDate = $_POST['test_date'] ?? '';

    if ($sampleId && $testName && $value !== '' && $testDate) {
        $stmt = $pdo->prepare(
            "INSERT INTO test_results (sample_id, test_name, result_value, test_date) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$sampleId, $testName, $value, $testDate]);
        $message = "Test result added successfully.";
    } else {
        $message = "Please complete all required fields.";
    }
}

$samples = $pdo->query("SELECT sample_id, sample_code FROM samples ORDER BY sample_code")->fetchAll();
$results = $pdo->query(
    "SELECT r.*, s.sample_code FROM test_results r JOIN samples s ON r.sample_id = s.sample_id ORDER BY r.result_id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header><h1>Laboratory Sample Tracking System</h1>
<nav><a href="admin_dashboard.php">Dashboard</a><a href="clients.php">Clients</a><a href="samples.php">Samples</a><a href="results.php">Test Results</a><span class="nav-user">Hi, <?= h($_SESSION['full_name'] ?? 'User') ?></span><a href="logout.php">Logout</a></nav></header>
<main>
    <h2>Test Result Management</h2>
    <?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if (!$samples): ?>
        <p class="message">Add a sample first before entering test results.</p>
    <?php else: ?>
    <form method="post">
        <label>Sample</label>
        <select name="sample_id" required>
            <option value="">Select sample</option>
            <?php foreach ($samples as $sample): ?>
                <option value="<?= (int)$sample['sample_id'] ?>"><?= htmlspecialchars($sample['sample_code']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Test Name</label>
        <input type="text" name="test_name" placeholder="e.g. pH Level" required>
        <label>Result Value</label>
        <input type="text" name="result_value" placeholder="e.g. 7.2" required>
        <label>Test Date</label>
        <input type="date" name="test_date" required>
        <button type="submit">Save Result</button>
    </form>
    <?php endif; ?>

    <h3>Recorded Test Results</h3>
    <table><tr><th>Sample Code</th><th>Test Name</th><th>Result</th><th>Test Date</th></tr>
    <?php foreach ($results as $result): ?>
        <tr>
            <td><?= htmlspecialchars($result['sample_code']) ?></td>
            <td><?= htmlspecialchars($result['test_name']) ?></td>
            <td><?= htmlspecialchars($result['result_value']) ?></td>
            <td><?= htmlspecialchars($result['test_date']) ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</main>
<footer>Laboratory Sample Tracking System — Mini Project</footer>
</body>
</html>
