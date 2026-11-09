<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Handle Admin Actions (Adding sample result or verifying)
if ($role === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_test') {
        $sample_code = trim($_POST['sample_code']);
        $location = trim($_POST['source_location']);
        $param_name = trim($_POST['parameter_name']);
        $unit = trim($_POST['standard_unit']);
        $result = $_POST['result_value'];

        // Insert or ignore sample & parameter reference data for convenience
        $pdo->prepare("INSERT OR IGNORE INTO Samples (sample_code, client_id, source_location, collection_date) VALUES (?, 1, ?, datetime('now'))")->execute([$sample_code, $location]);
        $pdo->prepare("INSERT OR IGNORE INTO TestParameters (parameter_name, standard_unit) VALUES (?, ?)")->execute([$param_name, $unit]);
        
        // Get parameter ID
        $p_id = $pdo->prepare("SELECT parameter_id FROM TestParameters WHERE parameter_name = ?");
        $p_id->execute([$param_name]);
        $param_id = $p_id->fetchColumn();

        // Save Sample Test Entry
        $stmt = $pdo->prepare("INSERT INTO SampleTests (sample_code, parameter_id, result_value, verification_status) VALUES (?, ?, ?, 'Unverified') 
                               ON CONFLICT(sample_code, parameter_id) DO UPDATE SET result_value = excluded.result_value");
        $stmt->execute([$sample_code, $param_id, $result]);
    } 
    elseif (isset($_POST['action']) && $_POST['action'] === 'verify') {
        $s_code = $_POST['sample_code'];
        $p_id = $_POST['parameter_id'];
        
        $stmt = $pdo->prepare("UPDATE SampleTests SET verification_status = 'Verified', verified_by = ? WHERE sample_code = ? AND parameter_id = ?");
        $stmt->execute([$username, $s_code, $p_id]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Water Lab System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #0066cc; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #0066cc; color: white; }
        .btn { background: #0066cc; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;}
        .btn:hover { background: #004d99; }
        .btn-verify { background: #28a745; padding: 5px 10px; font-size: 12px; }
        .btn-verify:hover { background: #218838; }
        .logout { float: right; background: #dc3545; }
        .logout:hover { background: #c82333; }
        .box { background: #f9f9f9; padding: 15px; border: 1px solid #e1e1e1; border-radius: 5px; margin-bottom: 20px; }
        .form-row { display: flex; gap: 10px; margin-bottom: 10px; }
        .form-row input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <a href="login.php?logout=true" class="btn logout">Logout</a>
    <h1>Environmental Water Lab Dashboard</h1>
    <p>Welcome, <strong><?= htmlspecialchars($username) ?></strong> | Role: <strong><?= ucfirst($role) ?></strong></p>
    <hr>

    <?php if ($role === 'admin'): ?>
        <!-- ADMIN VIEW: Full access to manage and verify samples -->
        <div class="box">
            <h3>Add Sample & Test Result</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_test">
                <div class="form-row">
                    <input type="text" name="sample_code" placeholder="Sample Code (e.g. SMP-001)" required>
                    <input type="text" name="source_location" placeholder="Source Location (e.g. River Intake)" required>
                </div>
                <div class="form-row">
                    <input type="text" name="parameter_name" placeholder="Parameter Name (e.g. pH)" required>
                    <input type="text" name="standard_unit" placeholder="Standard Unit (e.g. pH Units)" required>
                    <input type="number" step="0.001" name="result_value" placeholder="Result Value (e.g. 7.4)" required>
                </div>
                <button type="submit" class="btn">Save & Submit Test</button>
            </form>
        </div>

        <h2>All Sample Test Tracking Table (Admin View)</h2>
        <table>
            <tr>
                <th>Sample Code</th>
                <th>Location</th>
                <th>Parameter</th>
                <th>Result</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT st.sample_code, s.source_location, tp.parameter_name, st.result_value, tp.standard_unit, st.verification_status, st.parameter_id 
                                 FROM SampleTests st 
                                 JOIN Samples s ON st.sample_code = s.sample_code 
                                 JOIN TestParameters tp ON st.parameter_id = tp.parameter_id");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['sample_code']) ?></td>
                <td><?= htmlspecialchars($row['source_location']) ?></td>
                <td><?= htmlspecialchars($row['parameter_name']) ?></td>
                <td><?= htmlspecialchars($row['result_value'] . ' ' . $row['standard_unit']) ?></td>
                <td><?= htmlspecialchars($row['verification_status']) ?></td>
                <td>
                    <?php if ($row['verification_status'] === 'Unverified'): ?>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="action" value="verify">
                            <input type="hidden" name="sample_code" value="<?= $row['sample_code'] ?>">
                            <input type="hidden" name="parameter_id" value="<?= $row['parameter_id'] ?>">
                            <button type="submit" class="btn btn-verify">Verify</button>
                        </form>
                    <?php else: ?>
                        <span>Verified ✓</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

    <?php else: ?>
        <!-- USER / CLIENT VIEW: BR5 Enforcement (Only Verified Results shown) -->
        <h2>Final Verified Water Quality Reports</h2>
        <p><em>Note: Per lab policy (BR5), unverified data is restricted and hidden from regular user views.</em></p>
        <table>
            <tr>
                <th>Sample Code</th>
                <th>Source Location</th>
                <th>Parameter</th>
                <th>Result Value</th>
                <th>Status</th>
                <th>Verified By</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM vw_FinalWaterReports");
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($reports) > 0):
                foreach ($reports as $rep):
            ?>
            <tr>
                <td><?= htmlspecialchars($rep['sample_code']) ?></td>
                <td><?= htmlspecialchars($rep['source_location']) ?></td>
                <td><?= htmlspecialchars($rep['parameter_name']) ?></td>
                <td><?= htmlspecialchars($rep['result_value'] . ' ' . $rep['standard_unit']) ?></td>
                <td><?= htmlspecialchars($rep['verification_status']) ?></td>
                <td><?= htmlspecialchars($rep['verified_by']) ?></td>
            </tr>
            <?php 
                endforeach;
            else:
            ?>
            <tr><td colspan="6">No verified reports available at this time.</td></tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>