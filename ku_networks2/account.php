<?php
require 'user_data_logic.php';

// --- STAKING SYSTEM LOGIC START ---
$user_id_stake = $_SESSION['user_id'];
$total_locked_capital = 0.00;
$total_locked_profit = 0.00;
$activeStake = null;
$days_remaining = 0;

if (isset($user_id_stake)) {
    // 1. Fetch Total Locked Capital & Profit (Running Stakes)
    if ($stmtStakeStats = $conn->prepare("SELECT SUM(staked_amount) as total_locked_capital, SUM(current_profit_earned) as total_locked_profit FROM staking_history WHERE user_id = ? AND status = 'running'")) {
        $stmtStakeStats->bind_param("i", $user_id_stake);
        $stmtStakeStats->execute();
        $resStakeStats = $stmtStakeStats->get_result()->fetch_assoc();
        $total_locked_capital = $resStakeStats['total_locked_capital'] ?? 0.00;
        $total_locked_profit = $resStakeStats['total_locked_profit'] ?? 0.00;
        $stmtStakeStats->close();
    }

    // 2. Fetch Active Staking Package Details (Latest Running Stake)
    if ($stmtActiveStake = $conn->prepare("SELECT sp.package_name, sp.daily_profit_percentage, sh.staked_amount, sh.start_date, sh.end_date, sh.total_expected_profit FROM staking_history sh JOIN staking_packages sp ON sh.package_id = sp.id WHERE sh.user_id = ? AND sh.status = 'running' ORDER BY sh.id DESC LIMIT 1")) {
        $stmtActiveStake->bind_param("i", $user_id_stake);
        $stmtActiveStake->execute();
        $activeStake = $stmtActiveStake->get_result()->fetch_assoc();
        $stmtActiveStake->close();
        
        // Calculate Days Remaining
        if ($activeStake) {
            $endDate = strtotime($activeStake['end_date']);
            $now = time();
            $diff = $endDate - $now;
            $days_remaining = floor($diff / (60 * 60 * 24));
            if ($days_remaining < 0) { $days_remaining = 0; }
        }
    }
}
// --- STAKING SYSTEM LOGIC END ---

// Weekly Bonus Logic
$showWeeklyBonusPopup = false;
if (isset($lastLoginUtc) && $lastLoginUtc) {
    // $showWeeklyBonusPopup = true; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>KU Network - Dashboard</title>
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
    
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>

        <div class="container-fluid py-5 px-5">
            
            <div class="row g-4 mt-4">
                
                <div class="col-lg-6 col-md-12">
                    <div class="card h-100" id="profileCard">
                        <div class="card-body text-center pt-4">
                            <div id="profileAvatarContainer">
                                <div id="profileAvatar">
                                    <img src="<?php echo htmlspecialchars($profileImageSrc); ?>" alt="User Avatar">
                                </div>
                            </div>
                            <h4 id="profileName"><?php echo $profileDisplayName; ?></h4>
                            <p id="profileUid">UID: <?php echo $profileUID; ?></p>
                            <p id="profileDesignatedUsername">Username: <?php echo $profileLoginUsername; ?></p>

                            <div class="profile-info-item">
                                <span class="label"><i class="fas fa-medal icon"></i> Rank</span>
                                <span class="value"><?php echo $userRank; ?></span>
                            </div>
                            <div class="profile-info-item">
                                <span class="label"><i class="fas fa-star icon" style="color:var(--accent-orange);"></i> Points</span>
                                <span class="value" style="color:var(--accent-orange);"><?php echo number_format($userPoints); ?></span>
                            </div>
                            <div class="profile-info-item">
                                <span class="label"><i class="fas fa-user-check icon"></i> Status</span>
                                <span class="<?php echo $displayStatusClass; ?> rounded-pill fw-medium px-2 py-1">
                                    <?php echo htmlspecialchars($displayStatusText, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <div class="profile-info-item">
                                <span class="label"><i class="fas fa-clock icon"></i> Last Login</span>
                                <span class="value" id="lastLoginValue" data-utc="<?php echo htmlspecialchars($lastLoginUtc ?? ''); ?>">
                                    <?php echo $lastLoginTime; ?>
                                </span>
                            </div>

                            <div class="profile-info-item">
                                <span class="label"><i class="fas fa-layer-group icon"></i> Current Stage</span>
                                <span class="value">
                                    <?php 
                                        if (isset($userStage) && !empty($userStage['stage_name'])) {
                                            echo htmlspecialchars($userStage['stage_name']);
                                        } else {
                                            echo "N/A";
                                        }
                                    ?>
                                </span>
                            </div>
                            <?php if ($showWeeklyBonusPopup): ?>
                            <div class="mt-3 text-center">
                                <button id="btnShowWeeklyBonus" class="btn btn-primary">Claim Weekly Bonus</button>
                            </div>
                            <?php endif; ?>

                            <button class="btn btn-view-profile w-100 mt-4">Manage Profile</button>
                            
                            <?php if (isset($userRank) && $userRank !== "No Rank" && $userRank !== ""): ?>
                                <a href="view_certificate.php" class="btn btn-certificate w-100 mt-2">
                                    <i class="fas fa-award me-2"></i>Get Your Certificate
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="card h-100 list-item-card" id="membershipCard">
                        <div class="card-header">
                            <span><i class="fas fa-id-card card-title-icon"></i> Membership & Staking</span>
                            <div class="card-header-actions"><a href="index.php#section-packages" class="btn-link">View Plans</a></div>
                        </div>
                        <div class="card-body py-3">
                            <?php if ($userMembership): ?>
                                <h5 class="mb-2"><?php echo htmlspecialchars($userMembership['plan_name']); ?></h5>
                                <ul class="list-group list-group-flush mb-2">
                                    <li class="list-group-item"><strong>See Members Detail:</strong> <?php echo htmlspecialchars($userMembership['member_detail']); ?></li>
                                    <li class="list-group-item"><strong>Withdraw Fee:</strong> <?php echo htmlspecialchars($userMembership['withdraw_fee']); ?></li>
                                    <li class="list-group-item"><strong>Customer Support:</strong> <?php echo htmlspecialchars($userMembership['customer_support']); ?></li>
                                    <li class="list-group-item"><strong>Withdraw Capital Amount:</strong> <?php echo htmlspecialchars($userMembership['withdraw_capital']); ?></li>
                                    <li class="list-group-item"><strong>Withdraw Processing:</strong> <?php echo htmlspecialchars($userMembership['withdraw_processing_time']); ?></li>
                                </ul>
                                <div class="small text-muted mt-3">Purchased: <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($userMembership['purchase_date']))); ?></div>
                            <?php else: ?>
                                <div class="small text-muted">You do not have an active membership. <a href="index.php#section-packages">Choose a plan</a></div>
                            <?php endif; ?>
                            
                            <?php if ($activeStake): ?>
                                <hr class="my-3" style="border-top: 2px dashed #eee;">
                                <h5 class="mb-2" style="color: var(--accent-orange);">
                                    <i class="fas fa-layer-group me-2"></i>Active Staking Plan
                                </h5>
                                <ul class="list-group list-group-flush mb-2">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Package:</strong> 
                                        <span><?php echo htmlspecialchars($activeStake['package_name']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Daily Profit:</strong> 
                                        <span><?php echo $activeStake['daily_profit_percentage']; ?>%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Maturity Date:</strong> 
                                        <span><?php echo date('d M, Y', strtotime($activeStake['end_date'])); ?></span>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                
                <div class="col-lg-4 col-md-6">
                    <div class="card metric-card h-100" id="walletBalanceCard">
                        <div class="card-body p-4">
                            <div class="icon-bg"><i class="fas fa-wallet"></i></div>
                            <div class="metric-title">Wallet Balance</div>
                            <div class="metric-value">$ <?php echo number_format((float)$walletBalance, 2); ?></div>
                            <div class="metric-subtext <?php echo $wallet7DayChange >= 0 ? 'text-success' : 'text-danger'; ?>">
                                <i class="fas fa-arrow-<?php echo $wallet7DayChange >= 0 ? 'up' : 'down'; ?> fa-xs"></i> 
                                $ <?php echo number_format(abs((float)$wallet7DayChange), 2); ?> last 7d
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card metric-card h-100" id="teamCommissionsCard">
                        <div class="card-body p-4">
                            <div class="icon-bg"><i class="fas fa-users-rays"></i></div>
                            <div class="metric-title">Team Commissions (MTD)</div>
                            <div class="metric-value">$ <?php echo number_format((float)$teamCommissionsMTD, 2); ?></div>
                            <div class="metric-subtext <?php echo $commissionChange >= 0 ? 'text-success' : 'text-danger'; ?>">
                                <i class="fas fa-arrow-<?php echo $commissionChange >= 0 ? 'up' : 'down'; ?> fa-xs"></i> 
                                $ <?php echo number_format(abs((float)$commissionChange), 2); ?> vs last month
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="card metric-card h-100" id="recentInvestmentCard">
                        <div class="card-body p-4">
                            <div class="icon-bg"><i class="fas fa-chart-line"></i></div>
                            <div class="metric-title">Recent Investment</div>
                            <?php if (!empty($userRecentProductName)): ?>
                                <div class="metric-value">$ <?php echo number_format((float)$userRecentProductAmount, 2); ?></div>
                                <div class="metric-subtext text-muted">
                                    <small>Product: <strong><?php echo htmlspecialchars($userRecentProductName, ENT_QUOTES, 'UTF-8'); ?></strong></small>
                                </div>
                                <div class="mt-3">
                                    <div class="small text-muted mt-2">
                                        <?php echo isset($productInvestPercent) ? (int)$productInvestPercent : 0; ?>% of users invested in this item
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="metric-value">$ 0.00</div>
                                <div class="metric-subtext text-muted"><small>No investments found.</small></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                     <div class="card metric-card shadow-sm" style="border-left: 5px solid var(--accent-orange);">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-3 col-md-12 mb-3 mb-lg-0 border-end-lg">
                                    <div class="d-flex align-items-center">
                                        <div style="background: rgba(255, 165, 0, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                            <i class="fas fa-lock text-warning fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size: 1.1rem;">Staking Holdings</h6>
                                            <small class="text-muted">Live Tracking</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-md-4 col-sm-12 mb-3 mb-md-0 text-center border-end-md">
                                    <span class="text-muted d-block small mb-1">Locked Capital</span>
                                    <h5 class="fw-bold mb-0 text-dark">$ <?php echo number_format($total_locked_capital, 2); ?></h5>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-12 mb-3 mb-md-0 text-center border-end-md">
                                    <span class="text-muted d-block small mb-1">Total Profit Earned</span>
                                    <h5 class="fw-bold mb-0 text-success">+ $ <?php echo number_format($total_locked_profit, 2); ?></h5>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-12 text-center">
                                    <span class="text-muted d-block small mb-1">Time Remaining</span>
                                    <span class="badge bg-primary rounded-pill px-3 py-2 mt-1">
                                        <i class="fas fa-clock me-1"></i> <?php echo $days_remaining; ?> Days
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card" id="notificationsCard">
                        <div class="card-header">
                            <span><i class="fas fa-bell card-title-icon" style="color:var(--accent-orange)"></i> Notifications</span>
                            <div class="card-header-actions">
                                <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">New</span>
                            </div>
                        </div>
                        <div class="card-body py-2 px-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-start">
                                    <span class="notification-icon" style="background-color: var(--primary-blue);">
                                        <i class="fas fa-shield-alt"></i>
                                    </span>
                                    <div>
                                        <div class="notification-title">Welcome to KU Network</div>
                                        <div class="notification-text">Your account has been successfully created.</div>
                                        <div class="notification-time">Just now</div>
                                    </div>
                                </li>
                                <?php if ($teamCommissionsMTD > 0): ?>
                                <li class="list-group-item d-flex align-items-start">
                                    <span class="notification-icon" style="background-color: #198754;">
                                        <i class="fas fa-comments-dollar"></i>
                                    </span>
                                    <div>
                                        <div class="notification-title">Team Commission</div>
                                        <div class="notification-text">$ <?php echo number_format((float)$teamCommissionsMTD, 2); ?> earned this month.</div>
                                        <div class="notification-time">This month</div>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="card mt-4 list-item-card" id="quickSettingsCard">
                        <div class="card-header">
                            <span><i class="fas fa-sliders card-title-icon"></i> Quick Settings</span>
                        </div>
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="fas fa-key item-icon"></i><span class="item-label">Change Password</span> <i class="fas fa-chevron-right fa-xs ms-auto text-muted"></i></li>
                                <li class="list-group-item"><i class="fas fa-bell item-icon"></i><span class="item-label">Notification Prefs</span> <i class="fas fa-chevron-right fa-xs ms-auto text-muted"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        <?php include 'Assets/modals.php'; ?>
        <?php include 'Assets/footer.php'; ?>
    </div>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>