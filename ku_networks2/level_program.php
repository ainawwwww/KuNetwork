<?php
require 'user_data_logic.php';
// Note: $teamXIds, $teamYIds, etc are needed. We can re-fetch or assume common logic file. 
// For safety, let's recalculate the basic counts here quickly or include the logic.
// Simplest is to copy the Team ID fetcher here or make it a shared file. 
// For this response, I'll re-include the basic fetch logic to ensure it works standalone.


// --- Helper for IDs (Same as above) ---
function getTeamUserIdsSimple_LP(mysqli $conn, array $leaderIds): array {
    if (empty($leaderIds)) return [];
    $leaderIds = array_values(array_filter($leaderIds, function($v){ return is_numeric($v) && $v > 0; }));
    if (empty($leaderIds)) return [];
    $placeholders = implode(',', array_fill(0, count($leaderIds), '?'));
    $types = str_repeat('i', count($leaderIds));
    $sql = "SELECT DISTINCT referral_userid FROM referal_teams WHERE user_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $refs = []; $refs[] = & $types;
    for ($i = 0; $i < count($leaderIds); $i++) $refs[] = & $leaderIds[$i];
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $res = $stmt->get_result();
    $ids = [];
    while ($row = $res->fetch_assoc()) $ids[] = (int)$row['referral_userid'];
    $stmt->close();
    return array_unique($ids);
}

// 1. Get Current Level
$currentLevelId = 1;
$stmt = $conn->prepare("SELECT current_level_id FROM users WHERE id = ?");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
if ($res = $stmt->get_result()->fetch_assoc()) $currentLevelId = (int)$res['current_level_id'];
$stmt->close();

$targetLevel = $currentLevelId + 1;

// 2. Get Team Counts
$teamXIds = getTeamUserIdsSimple_LP($conn, [$loggedInUserIdentifier]);
$teamYIds = !empty($teamXIds) ? getTeamUserIdsSimple_LP($conn, $teamXIds) : [];
$teamZIds = !empty($teamYIds) ? getTeamUserIdsSimple_LP($conn, $teamYIds) : [];

$cur_team_x = count($teamXIds);
$cur_team_yz = count($teamYIds) + count($teamZIds);
$cur_total_team = $cur_team_x + $cur_team_yz;
$cur_balance = isset($walletBalance) ? (float)$walletBalance : 0.0;

// 3. Get Requirements
$req_row = null;
$stmt = $conn->prepare("SELECT team_x, team_yz, total_active_team, required_balance FROM level_upgrade_requirements WHERE level_id = ? LIMIT 1");
$stmt->bind_param("i", $targetLevel);
$stmt->execute();
$req_row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 4. Build Progress Bars
$linear_bars = [];
$calc_pct = function($cur, $req) {
    if ($req === null || $req <= 0) return 0;
    return (int) min(100, round(($cur / $req) * 100));
};

$req_team_x = $req_row ? (int)$req_row['team_x'] : 0;
$req_team_yz = $req_row ? (int)$req_row['team_yz'] : 0;
$req_total_active = $req_row ? (int)$req_row['total_active_team'] : 0;
$req_balance = $req_row ? (float)$req_row['required_balance'] : 0.0;

$linear_bars[] = ['label' => 'Team X', 'cur' => $cur_team_x, 'req' => $req_team_x, 'pct' => $calc_pct($cur_team_x, $req_team_x)];
$linear_bars[] = ['label' => 'Team Y+Z', 'cur' => $cur_team_yz, 'req' => $req_team_yz, 'pct' => $calc_pct($cur_team_yz, $req_team_yz)];
$linear_bars[] = ['label' => 'Total Team', 'cur' => $cur_total_team, 'req' => $req_total_active, 'pct' => $calc_pct($cur_total_team, $req_total_active)];
$linear_bars[] = ['label' => 'Balance', 'cur' => $cur_balance, 'req' => $req_balance, 'pct' => ($req_balance > 0 ? min(100, round(($cur_balance/$req_balance)*100)) : 0)];

$all_met = true;
foreach ($linear_bars as $lb) { if ($lb['pct'] < 100) $all_met = false; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Level Program Progress</title>
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
           
            
            <div class="card shadow-sm progress-linear-card">
                <div class="card-header bg-white">
                    <span class="fw-bold"><i class="fas fa-sliders card-title-icon text-success"></i> Level <?php echo $targetLevel; ?> Requirements</span>
                </div>
                <div class="card-body p-4">
                    <?php if(!$req_row): ?>
                        <div class="alert alert-info">You have reached the maximum level or no requirements found.</div>
                    <?php else: ?>
                        <p class="text-muted mb-4">Complete the following objectives to upgrade to Level <?php echo $targetLevel; ?>.</p>
                        
                        <?php foreach ($linear_bars as $index => $bar): 
                            $pct = $bar['pct'];
                            $fillColor = ($pct >= 100) ? 'background: linear-gradient(90deg,#16a34a,#059669);' : (($pct >= 50) ? 'background: linear-gradient(90deg,#1fa2ff,#0e86d1);' : 'background: linear-gradient(90deg,#f59e0b,#d97706);');
                        ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-dark"><?php echo $bar['label']; ?></span>
                                <span class="fw-bold <?php echo ($pct>=100)?'text-success':'text-muted'; ?>"><?php echo $pct; ?>%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-linear-fill" data-pct="<?php echo $pct; ?>" style="width:0%; <?php echo $fillColor; ?>"></div>
                            </div>
                            <div class="small text-muted mt-1">
                                <?php if($bar['label'] == 'Balance'): ?>
                                    Current: $<?php echo number_format($bar['cur'], 2); ?> / Target: $<?php echo number_format($bar['req'], 2); ?>
                                <?php else: ?>
                                    Current: <?php echo $bar['cur']; ?> / Target: <?php echo $bar['req']; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php if ($all_met): ?>
                            <div class="alert alert-success mt-3 fw-bold text-center">
                                <i class="fas fa-check-circle me-2"></i> Congratulations! You are eligible for the upgrade.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.progress-linear-fill').forEach(function(el) {
            var pct = el.getAttribute('data-pct');
            setTimeout(function(){ el.style.width = pct + '%'; }, 200);
        });
    });
    </script>
</body>
</html>