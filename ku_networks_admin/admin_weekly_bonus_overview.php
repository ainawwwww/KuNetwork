<?php

include 'db.php';
include 'check_login.php';
$batch_id_filter = isset($_GET['batch_id']) ? $_GET['batch_id'] : null;
?>
<!DOCTYPE html>
<html>
<head><title>Weekly Bonus Offers Overview</title>
<style> table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; } </style>
</head>
<body>
    <h2>Weekly Bonus Offers Overview</h2>

    <form method="GET" action="">
        Filter by Batch ID: <input type="text" name="batch_id" value="<?php echo htmlspecialchars($batch_id_filter ?? ''); ?>">
        <input type="submit" value="Filter">
        <a href="admin_weekly_bonus_overview.php">Clear Filter</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Offer ID</th>
                <th>User ID</th>
                <th>Batch ID</th>
                <th>Deposit Req.</th>
                <th>Profit %</th>
                <th>Lock Hours</th>
                <th>Offer Created</th>
                <th>Offer Expires</th>
                <th>Popup Shown At</th>
                <th>Deposit Payment ID</th>
                <th>Profit Bonus Hist. ID</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT wbo.*, u.username FROM weekly_bonus_offers wbo JOIN users u ON wbo.user_id = u.id";
            $params = [];
            $types = "";
            if ($batch_id_filter) {
                $sql .= " WHERE wbo.admin_trigger_batch_id = ?";
                $params[] = $batch_id_filter;
                $types .= "s";
            }
            $sql .= " ORDER BY wbo.offer_created_at DESC, wbo.id DESC LIMIT 100"; // Add pagination for more records

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                if ($batch_id_filter) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . " (".htmlspecialchars($row['username']).")</td>";
                    echo "<td>" . htmlspecialchars($row['admin_trigger_batch_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['deposit_required']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['profit_percentage']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lock_duration_hours']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['offer_created_at']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['offer_expires_at']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['popup_displayed_at'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['deposit_payment_id'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['profit_bonus_history_id'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                $stmt->close();
            } else {
                echo "<tr><td colspan='12'>Error preparing statement: " . $conn->error . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>