<?php
require 'user_data_logic.php';

// --- 1. Fetch User Name ---
$userName = "User";
$uQuery = "SELECT name FROM users WHERE id = ?";
$stmtU = $conn->prepare($uQuery);
$stmtU->bind_param("i", $loggedInUserIdentifier);
$stmtU->execute();
$resU = $stmtU->get_result()->fetch_assoc();
if($resU) $userName = $resU['name'];

// --- 2. Income Breakdown Logic ---
$incomeData = ['daily'=>0, 'comprehensive'=>0, 'reserve'=>0, 'event'=>0, 'bid'=>0, 'stake'=>0];
$sql = "SELECT 
    SUM(CASE WHEN level_id = 1 THEN amount * 0.01 ELSE 0 END) as daily,
    SUM(CASE WHEN level_id = 2 THEN amount * 0.02 ELSE 0 END) as comprehensive,
    SUM(CASE WHEN level_id = 3 THEN amount * 0.015 ELSE 0 END) as reserve,
    SUM(amount * 0.005) as event,
    SUM(amount * 0.003) as bid,
    SUM(amount * 0.008) as stake
    FROM payment WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if($res) $incomeData = $res;
$totalIncome = array_sum($incomeData);

// --- 3. Chart Data ---
$chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = gmdate('Y-m-d', strtotime("-$i days"));
    $sqlChart = "SELECT SUM(amount) * 0.05 as val FROM payment WHERE user_id = ? AND DATE(created_at) = ?";
    $stmtC = $conn->prepare($sqlChart);
    $stmtC->bind_param("is", $loggedInUserIdentifier, $date);
    $stmtC->execute();
    $resC = $stmtC->get_result()->fetch_assoc();
    $chartData[] = $resC['val'] ?? 0;
}
$maxVal = max($chartData) ?: 1;

// --- 4. Wallet History Logic ---
$filter = isset($_GET['filter']) ? $_GET['filter'] : '7days';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$date_sql = "";
if ($filter == '7days') {
    $date_sql = "AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter == '15days') {
    $date_sql = "AND created_at >= DATE_SUB(NOW(), INTERVAL 15 DAY)";
} elseif ($filter == '1month') {
    $date_sql = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
} elseif ($filter == 'custom' && !empty($start_date)) {
    $date_sql = "AND DATE(created_at) >= '$start_date' AND DATE(created_at) <= '$end_date'";
} else {
    $date_sql = "AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
}

// NOTE: Query logic matched to ensure descriptions appear correctly
$history_query = "
(
    SELECT 
        'Wallet' as category,
        type as type_label,
        amount,
        description, 
        created_at,
        'wallet' as source_table
    FROM wallet_history 
    WHERE user_id = '$loggedInUserIdentifier' $date_sql
)
UNION ALL
(
    SELECT 
        'Withdraw' as category,
        'withdraw' as type_label,
        amount,
        CONCAT('Withdraw request (', status, ')') as description,
        created_at,
        'withdraw' as source_table
    FROM withdrawals 
    WHERE user_id = '$loggedInUserIdentifier' $date_sql
)
UNION ALL
(
    SELECT 
        'Points' as category,
        'points_claim' as type_label,
        claim_amount as amount,
        CONCAT('Points claimed - used: ', COALESCE(points_used,0)) as description,
        created_at,
        'points' as source_table
    FROM points_claims 
    WHERE user_id = '$loggedInUserIdentifier' $date_sql
)
UNION ALL
(
    SELECT 
        'Bonus' as category,
        bonus_type as type_label,
        bonus_amount as amount,
        COALESCE(meta, '') as description,
        created_at,
        'bonus' as source_table
    FROM bonus_history 
    WHERE user_id = '$loggedInUserIdentifier' $date_sql
)
ORDER BY created_at DESC
";

$history_result = mysqli_query($conn, $history_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Income Streams & History</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="custom_dashboard.css">
    <link rel="stylesheet" href="custom_account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .history-card { margin-top: 30px; box-shadow: 0 0 15px rgba(0,0,0,0.1); border: none; border-radius: 10px; overflow: hidden; }
        .filter-btn { margin-right: 5px; border-radius: 20px; padding: 5px 15px; font-size: 14px; font-weight: 500; }
        .filter-btn.active { background-color: #0d6efd; color: white; border-color: #0d6efd; }
        
        .table-custom thead th { 
            background-color: #0d6efd; 
            color: #fff; 
            font-weight: 600; 
            padding: 12px;
            border: none;
        }
        .table-custom tbody td {
            vertical-align: middle;
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
            color: #212529;
        }
        
        .amount-plus { color: #198754; font-weight: 700; }
        .amount-minus { color: #dc3545; font-weight: 700; }
        
        .type-text {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
            color: #495057;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="container py-5">
            <a href="account.php" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Back</a>

            <div class="card list-item-card mb-4">
                <div class="card-header">
                    <span><i class="fas fa-chart-pie card-title-icon"></i> Income Breakdown</span>
                </div>
                <div class="card-body">
                    <div class="row g-4 text-center">
                        <?php foreach($incomeData as $key => $val): ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted text-uppercase fw-bold"><?php echo $key; ?></small>
                                <div class="fs-5 fw-bold text-dark">$<?php echo number_format($val, 2); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4 pt-4 border-top">
                        <h5>7-Day Trend</h5>
                        <div class="d-flex align-items-end justify-content-between mt-3" style="height: 150px;">
                            <?php foreach($chartData as $val): 
                                $h = ($val / $maxVal) * 100;
                            ?>
                            <div class="w-100 mx-1 bg-primary rounded-top" style="height: <?php echo max(5, $h); ?>%; opacity: 0.7;" title="$<?php echo $val; ?>"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end fw-bold">
                    Total Income: $<?php echo number_format($totalIncome, 2); ?>
                </div>
            </div>

            <div class="card history-card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-list-alt text-primary me-2"></i> Transaction History</h5>
                    </div>
                    
                    <div class="mt-3">
                        <form method="GET" class="row g-2 align-items-center">
                            <div class="col-auto">
                                <a href="?filter=7days" class="btn btn-outline-secondary filter-btn <?= ($filter == '7days') ? 'active' : '' ?>">Last 7 Days</a>
                                <a href="?filter=15days" class="btn btn-outline-secondary filter-btn <?= ($filter == '15days') ? 'active' : '' ?>">15 Days</a>
                                <a href="?filter=1month" class="btn btn-outline-secondary filter-btn <?= ($filter == '1month') ? 'active' : '' ?>">1 Month</a>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <span class="text-muted mx-2">|</span>
                                <input type="date" name="start_date" class="form-control form-control-sm me-2" value="<?= $start_date ?>">
                                <span class="me-2">-</span>
                                <input type="date" name="end_date" class="form-control form-control-sm me-2" value="<?= $end_date ?>">
                                <button type="submit" name="filter" value="custom" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($history_result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($history_result)): ?>
                                        <?php 
                                            // --- DESCRIPTION & BONUS LOGIC (MATCHING wallet_history.php) ---
                                            $raw_desc = $row['description'];
                                            $final_desc = "N/A";
                                            $source = $row['source_table'];
                                            $amt = $row['amount']; // Capture amount for description string

                                            if ($source === 'bonus') {
                                                // Try to parse JSON safely
                                                $metaText = trim($raw_desc);
                                                $related = 'N/A';
                                                
                                                $json = json_decode($metaText, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                                                    // Try common keys found in admin logic
                                                    if (!empty($json['rank_claimed'])) {
                                                        $related = (string)$json['rank_claimed'];
                                                    } elseif (!empty($json['reason'])) {
                                                        $related = (string)$json['reason'];
                                                    } elseif (!empty($json['related'])) {
                                                        $related = (string)$json['related'];
                                                    } else {
                                                        // Fallback: pick first value
                                                        $first = reset($json);
                                                        $related = is_scalar($first) ? (string)$first : 'details available';
                                                    }
                                                } else {
                                                    // Plain text fallback
                                                    $related = ($metaText !== '') ? mb_substr($metaText, 0, 60) : 'N/A';
                                                }
                                                
                                                $displayType = ucfirst($row['type_label']);
                                                if($displayType === '') $displayType = 'Bonus';

                                                // UPDATED FORMATTING HERE:
                                                $final_desc = "Bonus ({$displayType}) - amount: " . number_format($amt, 2) . " | related: {$related}";

                                            } else {
                                                // Regular Description (Wallet, Withdraw, Points)
                                                $final_desc = trim($raw_desc);
                                                if($final_desc == "") { $final_desc = "-"; }
                                            }
                                        ?>
                                        <tr>
                                            <td class="ps-4 text-secondary" style="white-space: nowrap;">
                                                <?= date('d M, Y', strtotime($row['created_at'])) ?>
                                                <small class="d-block text-muted"><?= date('h:i A', strtotime($row['created_at'])) ?></small>
                                            </td>
                                            
                                            <td class="fw-bold text-dark">
                                                <?= htmlspecialchars($userName) ?>
                                            </td>

                                            <td>
                                                <span class="type-text">
                                                    <?= htmlspecialchars($row['type_label']) ?>
                                                </span>
                                            </td>
                                            
                                            <td class="<?= ($row['category'] == 'Withdraw') ? 'amount-minus' : 'amount-plus' ?>">
                                                <?= ($row['category'] == 'Withdraw') ? '-' : '+' ?>$<?= number_format($row['amount'], 2) ?>
                                            </td>
                                            
                                            <td class="text-dark">
                                                <?= htmlspecialchars($final_desc) ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            No transactions found for the selected period.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>
</body>
</html>