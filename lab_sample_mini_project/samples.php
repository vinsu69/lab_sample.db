<?php
require 'auth.php';
require_login();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['sample_code'] ?? '');
    $clientId = (int)($_POST['client_id'] ?? 0);
    $sampleName = trim($_POST['sample_name'] ?? '');
    $collectionDate = $_POST['collection_date'] ?? '';
    $location = trim($_POST['source_location'] ?? '');

    if ($code && $clientId && $sampleName && $collectionDate) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO samples (sample_code, client_id, sample_name, collection_date, source_location)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$code, $clientId, $sampleName, $collectionDate, $location]);
            $message = "Sample added successfully.";
        } catch (PDOException $e) {
            $message = "Could not add sample. Check that the sample code is unique.";
        }
    } else {
        $message = "Please complete all required fields.";
    }
}

$clients = $pdo->query("SELECT client_id, client_name FROM clients ORDER BY client_name")->fetchAll();
$samples = $pdo->query(
    "SELECT s.*, c.client_name FROM samples s JOIN clients c ON s.client_id = c.client_id ORDER BY s.sample_id DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samples</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header><h1>Laboratory Sample Tracking System</h1>
<nav><a href="index.php">Home</a><a href="clients.php">Clients</a><a href="samples.php">Samples</a><a href="results.php">Test Results</a><span class="nav-user">Hi, <?= h($_SESSION['full_name'] ?? 'User') ?></span><a href="logout.php">Logout</a></nav></header>
<main>
    <h2>Sample Management</h2>
    <?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if (!$clients): ?>
        <p class="message">Add a client first before recording a sample.</p>
    <?php else: ?>
    <form method="post">
        <label>Sample Code</label>
        <input type="text" name="sample_code" required>
        <label>Client</label>
        <select name="client_id" required>
            <option value="">Select client</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= (int)$client['client_id'] ?>"><?= htmlspecialchars($client['client_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Sample Name / Type</label>
        <input type="text" name="sample_name" placeholder="e.g. Water Sample" required>
        <label>Collection Date</label>
        <input type="date" name="collection_date" required>
        <label>Source Location</label>
        <input type="text" name="source_location">
        <button type="submit">Add Sample</button>
    </form>
    <?php endif; ?>

    <h3>Sample List</h3>
    <table><tr><th>Sample Code</th><th>Client</th><th>Sample</th><th>Collection Date</th><th>Source</th></tr>
    <?php foreach ($samples as $sample): ?>
        <tr>
            <td><?= htmlspecialchars($sample['sample_code']) ?></td>
            <td><?= htmlspecialchars($sample['client_name']) ?></td>
            <td><?= htmlspecialchars($sample['sample_name']) ?></td>
            <td><?= htmlspecialchars($sample['collection_date']) ?></td>
            <td><?= htmlspecialchars($sample['source_location'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</main>
<footer>Laboratory Sample Tracking System — Mini Project</footer>
</body>
</html>
