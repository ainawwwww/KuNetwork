<?php
require 'user_data_logic.php';

// --- Helper for Team X ID ---
function getTeamXIds($conn, $uid) {
    $sql = "SELECT referral_userid FROM referal_teams WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    $ids = [];
    while($row = $res->fetch_assoc()) $ids[] = (int)$row['referral_userid'];
    return $ids;
}

// 1. Fetch Ranks
$ranks = [];
$res = $conn->query("SELECT * FROM rank_bonuses ORDER BY id ASC");

while($r = $res->fetch_assoc()) {
    $ranks[] = $r;
}

// 2. Identify Ranks
$currentRankName = trim((string)$userRank); 
$currentRankIndex = -1;
foreach ($ranks as $i => $rr) {
    if (strcasecmp($rr['rank_name'], $currentRankName) === 0) { $currentRankIndex = $i; break; }
}
$nextRank = isset($ranks[$currentRankIndex + 1]) ? $ranks[$currentRankIndex + 1] : null;

// 3. Calculations
$selfInvest = $walletBalance; // From user_data_logic
$teamXIds = getTeamXIds($conn, $loggedInUserIdentifier);
$teamX_initial_sum = 0.0;
if(!empty($teamXIds)) {
    // Sum of FIRST payments of direct team
    $inClause = implode(',', array_fill(0, count($teamXIds), '?'));
    $types = str_repeat('i', count($teamXIds));
    // Complex query: Sum of amounts where it is the user's first payment
    // Simplification based on original code:
    foreach($teamXIds as $tid) {
        $stmt = $conn->prepare("SELECT amount FROM payment WHERE user_id = ? ORDER BY created_at ASC LIMIT 1");
        $stmt->bind_param("i", $tid);
        $stmt->execute();
        $stmt->bind_result($amt);
        if($stmt->fetch()) $teamX_initial_sum += $amt;
        $stmt->close();
    }
}

// 4. Handle Claim Rank POST
$flash_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_rank']) && isset($_POST['rank_name'])) {
    $claimName = $_POST['rank_name'];
    $rankObj = null;
    foreach($ranks as $r) { if($r['rank_name'] == $claimName) $rankObj = $r; }
    
    if($rankObj) {
        $reqSelf = (float)$rankObj['self_invest'];
        $reqTeam = (float)$rankObj['team_business'];
        
        if($selfInvest >= $reqSelf && $teamX_initial_sum >= $reqTeam) {
            // Transaction
            $conn->begin_transaction();
            try {
                $bonus = (float)$rankObj['self_bonus'];
                // Update Wallet
                $stmt = $conn->prepare("UPDATE user_wallets SET total_balance = total_balance + ?, available_balance = available_balance + ? WHERE user_id = ?");
                $stmt->bind_param("ddi", $bonus, $bonus, $loggedInUserIdentifier);
                $stmt->execute();
                
                // History
                $stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, created_at) VALUES (?, ?, 'rank_self_bonus', UTC_TIMESTAMP())");
                $stmt->bind_param("id", $loggedInUserIdentifier, $bonus);
                $stmt->execute();
                
                // Update Rank Table
                $stmt = $conn->prepare("INSERT INTO rank_assignment_summary (user_id, user_name, assigned_rank, calculation_timestamp) VALUES (?, ?, ?, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE assigned_rank = VALUES(assigned_rank), calculation_timestamp = UTC_TIMESTAMP()");
                $stmt->bind_param("iss", $loggedInUserIdentifier, $profileDisplayName, $claimName);
                $stmt->execute();
                
                $conn->commit();
                $flash_msg = "Success! Rank claimed. Bonus: $$bonus";
                // Update local var for UI
                $userRank = $claimName; 
                $walletBalance += $bonus;
                // Refresh Page to update state
                header("Refresh:0");
            } catch (Exception $e) {
                $conn->rollback();
                $flash_msg = "Error: " . $e->getMessage();
            }
        } else {
            $flash_msg = "Requirements not met.";
        }
    }
}

// 5. Build Metrics
$invPct = 0; $teamPct = 0; $ovrPct = 0;
if($nextRank) {
    $reqSelf = (float)$nextRank['self_invest'];
    $reqTeam = (float)$nextRank['team_business'];
    $invPct = ($reqSelf > 0) ? min(100, round(($selfInvest/$reqSelf)*100)) : 100;
    $teamPct = ($reqTeam > 0) ? min(100, round(($teamX_initial_sum/$reqTeam)*100)) : 100;
    $ovrPct = round(($invPct + $teamPct)/2);
}

$metrics = [
    ['pct'=>$invPct, 'col'=>'#0cace7', 'lbl'=>'Self Investment', 'sub'=> ($nextRank ? "$$selfInvest / $$reqSelf" : "")],
    ['pct'=>$teamPct, 'col'=>'#f47656', 'lbl'=>'Team X Business', 'sub'=> ($nextRank ? "$$teamX_initial_sum / $$reqTeam" : "")],
    ['pct'=>$ovrPct, 'col'=>'#16a34a', 'lbl'=>'Overall Progress', 'sub'=> ($nextRank ? "To " . $nextRank['rank_name'] : "")]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rank Status</title>
   <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="custom_dashboard.css">
    <link rel="stylesheet" href="custom_account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <!-- CSS Files -->
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="container py-5">
            <a href="account.php" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            
            <?php if($flash_msg): ?>
                <div class="alert alert-info"><?php echo $flash_msg; ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <span class="fw-bold"><i class="fas fa-circle-notch card-title-icon text-warning"></i> Rank Progress</span>
                </div>
                <div class="card-body p-5 text-center">
                    
                    <?php if(!$nextRank): ?>
                        <div class="alert alert-success">You have achieved the highest rank!</div>
                    <?php else: ?>
                        <div class="row justify-content-center g-4">
                            <?php foreach($metrics as $m): ?>
                            <div class="col-md-4 d-flex flex-column align-items-center">
                                <div style="position:relative; width:120px; height:120px;">
                                    <svg class="circular-svg" viewBox="0 0 100 100" width="120" height="120">
                                        <circle class="ring-bg" cx="50" cy="50" r="40"></circle>
                                        <circle class="ring-fg" cx="50" cy="50" r="40" stroke="<?php echo $m['col']; ?>" data-pct="<?php echo $m['pct']; ?>" style="stroke-dashoffset: 251; stroke-dasharray: 251;"></circle>
                                    </svg>
                                    <div style="position:absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:1.2rem;">
                                        <?php echo $m['pct']; ?>%
                                    </div>
                                </div>
                                <div class="mt-3 fw-bold"><?php echo $m['lbl']; ?></div>
                                <div class="text-muted small"><?php echo $m['sub']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-5">
                            <?php if($invPct >= 100 && $teamPct >= 100): ?>
                                <form method="post">
                                    <input type="hidden" name="claim_rank" value="1">
                                    <input type="hidden" name="rank_name" value="<?php echo $nextRank['rank_name']; ?>">
                                    <button type="submit" class="btn btn-warning btn-lg fw-bold shadow">
                                        <i class="fas fa-trophy me-2"></i> Claim <?php echo $nextRank['rank_name']; ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Requirements not met</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.ring-fg').forEach(function(circle) {
            var pct = parseInt(circle.getAttribute('data-pct'));
            var r = 40; 
            var c = 2 * Math.PI * r; 
            var offset = c * (1 - (pct / 100));
            setTimeout(function(){ circle.style.strokeDashoffset = offset; }, 200);
        });
    });
    </script>
</body>
</html>