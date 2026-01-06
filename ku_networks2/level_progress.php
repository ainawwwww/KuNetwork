<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: loginInterface.php"); exit(); }
$uid = (int) $_SESSION['user_id'];

// Fetch Data
$stmt = $conn->prepare("SELECT current_level_id FROM users WHERE id = ?");
$stmt->bind_param("i", $uid); $stmt->execute();
$currLevel = $stmt->get_result()->fetch_assoc()['current_level_id'] ?? 1;
$targetLevel = $currLevel + 1;

// Requirements (Mock or DB)
$stmt = $conn->prepare("SELECT * FROM level_upgrade_requirements WHERE level_id = ?");
$stmt->bind_param("i", $targetLevel); $stmt->execute();
$req = $stmt->get_result()->fetch_assoc();

// User Stats
$stmt = $conn->prepare("SELECT total_balance FROM user_wallets WHERE user_id=?");
$stmt->bind_param("i", $uid); $stmt->execute();
$bal = $stmt->get_result()->fetch_assoc()['total_balance'] ?? 0;

// Function to get team count
function getTeamCount($conn, $uid) {
    $q = $conn->query("SELECT COUNT(*) as c FROM referal_teams WHERE user_id=$uid");
    return $q->fetch_assoc()['c'] ?? 0;
}
$teamCount = getTeamCount($conn, $uid);

// Calculate Percents
$reqBal = $req['required_balance'] ?? 1000;
$reqTeam = $req['total_active_team'] ?? 10;

$balPct = min(100, round(($bal / $reqBal) * 100));
$teamPct = min(100, round(($teamCount / $reqTeam) * 100));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Level Progress</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .prog-card { background: #fff; padding: 30px; border-radius: 15px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .progress { height: 20px; border-radius: 10px; background: #eee; margin-bottom: 5px; }
        .bar-label { display: flex; justify-content: space-between; font-weight: 600; margin-bottom: 5px; }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include 'Assets/header.php'; ?>
    <div class="no-bottom no-top" id="content">
        <section id="subheader" class="text-light" data-bgimage="url(images/background/bg.png) top">
            <div class="center-y relative text-center"><h1>Level <?php echo $targetLevel; ?> Progress</h1></div>
        </section>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="prog-card">
                        <h4 class="mb-4"><i class="fas fa-sliders-h me-2 text-primary"></i> Upgrade Requirements</h4>
                        
                        <div class="mb-4">
                            <div class="bar-label">
                                <span>Required Balance</span>
                                <span>$<?php echo number_format($bal); ?> / $<?php echo number_format($reqBal); ?></span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: <?php echo $balPct; ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="bar-label">
                                <span>Active Team Members</span>
                                <span><?php echo $teamCount; ?> / <?php echo $reqTeam; ?></span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: <?php echo $teamPct; ?>%"></div>
                            </div>
                        </div>

                        <div class="alert alert-light border mt-4">
                            <i class="fas fa-info-circle me-2"></i> Complete all requirements to automatically upgrade to Level <?php echo $targetLevel; ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'Assets/footer.php'; ?>
</div>
</body>
</html>