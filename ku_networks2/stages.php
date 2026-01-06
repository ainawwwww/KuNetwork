<?php
session_start();
include 'config.php'; // Apni DB connection file include karein

// Fetch stages from DB
$query = "SELECT * FROM stages ORDER BY stage_id ASC";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Stages Information</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Stages Details</h2>
    <table>
        <tr>
            <th>Stage</th>
            <th>User Limit</th>
            <th>Referral Bonus (%)</th>
            <th>Deposit Bonus (%)</th>
            <th>Min Deposit (USDT)</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['stage_name']); ?></td>
            <td><?php echo $row['user_limit'] == 0 ? 'Unlimited' : htmlspecialchars($row['user_limit']); ?></td>
            <td><?php echo htmlspecialchars($row['referral_bonus']); ?>%</td>
            <td><?php echo htmlspecialchars($row['deposit_bonus']); ?>%</td>
            <td><?php echo htmlspecialchars($row['min_deposit_usdt']); ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>