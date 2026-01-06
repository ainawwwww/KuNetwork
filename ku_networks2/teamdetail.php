<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php");
    exit();
}

$loggedInUserId = $_SESSION['user_id'];

$enrollmentCheck = "SELECT * FROM enrolleduserspackages WHERE user_id = '$loggedInUserId' ";
$enrollmentResult = mysqli_query($conn, $enrollmentCheck);
if (mysqli_num_rows($enrollmentResult) == 0) {
    echo "<script>
            alert('Please buy the package');
            
            window.location.href = 'index.php';
          </script>";
    exit();
}

$enrollmentData = mysqli_fetch_assoc($enrollmentResult);
$packageId = $enrollmentData['package_id'];
  
$loggedInUserQuery = "SELECT * FROM users WHERE id = '$loggedInUserId'";
$loggedInUserResult = mysqli_query($conn, $loggedInUserQuery);
$loggedInUserData = mysqli_fetch_assoc($loggedInUserResult);

$referrerData = null;
$referrerQuery = "SELECT u.* FROM referal_teams rt 
                  JOIN users u ON rt.referral_userid = u.id
                  WHERE rt.user_id = '$loggedInUserId' LIMIT 1";
$referrerResult = mysqli_query($conn, $referrerQuery);
if ($referrerResult && mysqli_num_rows($referrerResult) > 0) {
    $referrerData = mysqli_fetch_assoc($referrerResult);
}

// Get counts for each team
$teamXCount = 0;
$teamYCount = 0;
$teamZCount = 0;

// Team X (Direct) count
$teamXQuery = "SELECT COUNT(*) as count FROM referal_teams WHERE user_id = '$loggedInUserId'";
$teamXResult = mysqli_query($conn, $teamXQuery);
if ($teamXResult) {
    $teamXData = mysqli_fetch_assoc($teamXResult);
    $teamXCount = $teamXData['count'];
}

// Team Y (Indirect-1) count
if ($packageId >= 2) {
    $teamYQuery = "SELECT COUNT(*) as count 
                   FROM referal_teams rt1
                   JOIN users u1 ON u1.id = rt1.referral_userid
                   JOIN referal_teams rt2 ON rt2.user_id = u1.id
                   WHERE rt1.user_id = '$loggedInUserId'";
    $teamYResult = mysqli_query($conn, $teamYQuery);
    if ($teamYResult) {
        $teamYData = mysqli_fetch_assoc($teamYResult);
        $teamYCount = $teamYData['count'];
    }
}

// Team Z (Indirect-2) count
if ($packageId >= 3) {
    $teamZQuery = "SELECT COUNT(*) as count 
                   FROM referal_teams rt1
                   JOIN users u1 ON u1.id = rt1.referral_userid
                   JOIN referal_teams rt2 ON rt2.user_id = u1.id
                   JOIN users u2 ON u2.id = rt2.referral_userid
                   JOIN referal_teams rt3 ON rt3.user_id = u2.id
                   WHERE rt1.user_id = '$loggedInUserId'";
    $teamZResult = mysqli_query($conn, $teamZQuery);
    if ($teamZResult) {
        $teamZData = mysqli_fetch_assoc($teamZResult);
        $teamZCount = $teamZData['count'];
    }
}

// Determine which team to show (default to Team X)
$activeTeam = isset($_GET['team']) ? $_GET['team'] : 'x';

// Build query based on selected team
$myReferralsQuery = "";
switch ($activeTeam) {
    case 'x':
        $myReferralsQuery = "
            SELECT u.id, u.name, u.email, u.phone, rt.referral_code, rt.created_at, 
                   'direct' AS level, 
                   (SELECT name FROM users WHERE id = rt.user_id) AS referred_by
            FROM referal_teams rt
            JOIN users u ON u.id = rt.referral_userid
            WHERE rt.user_id = '$loggedInUserId'
            ORDER BY rt.created_at DESC
        ";
        break;
    case 'y':
        if ($packageId >= 2) {
            $myReferralsQuery = "
                SELECT u2.id, u2.name, u2.email, u2.phone, rt2.referral_code, rt2.created_at, 
                       'indirect-1' AS level, 
                       (SELECT name FROM users WHERE id = rt2.user_id) AS referred_by
                FROM referal_teams rt1
                JOIN users u1 ON u1.id = rt1.referral_userid
                JOIN referal_teams rt2 ON rt2.user_id = u1.id
                JOIN users u2 ON u2.id = rt2.referral_userid
                WHERE rt1.user_id = '$loggedInUserId'
                ORDER BY rt2.created_at DESC
            ";
        }
        break;
    case 'z':
        if ($packageId >= 3) {
            $myReferralsQuery = "
                SELECT u3.id, u3.name, u3.email, u3.phone, rt3.referral_code, rt3.created_at, 
                       'indirect-2' AS level,
                       (SELECT name FROM users WHERE id = rt3.user_id) AS referred_by
                FROM referal_teams rt1
                JOIN users u1 ON u1.id = rt1.referral_userid
                JOIN referal_teams rt2 ON rt2.user_id = u1.id
                JOIN users u2 ON u2.id = rt2.referral_userid
                JOIN referal_teams rt3 ON rt3.user_id = u2.id
                JOIN users u3 ON u3.id = rt3.referral_userid
                WHERE rt1.user_id = '$loggedInUserId'
                ORDER BY rt3.created_at DESC
            ";
        }
        break;
}

$myReferralsResult = mysqli_query($conn, $myReferralsQuery);
if (!$myReferralsResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Count total rows for Show More logic
$totalRows = mysqli_num_rows($myReferralsResult);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Team Details</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

     <style>
    /* Base Styles */
    .profile-header { 
        background-color: #f8f9fa; 
        border-radius: 10px; 
        padding: 20px; 
        margin-bottom: 20px; 
    }
    .referral-card { 
        border-left: 4px solid #28a745; 
    }
    .table-responsive { 
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; 
        margin-bottom: 1rem;
    }
    .level-direct { background-color: #e8f5e9; }
    .level-indirect-1 { background-color: #e3f2fd; }
    .level-indirect-2 { background-color: #f3e5f5; }
    .nav-tabs .nav-link {
        font-weight: 600;
        padding: 12px 15px;
        white-space: nowrap;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #28a745;
        font-weight: 700;
        color: #28a745;
    }
    .team-count-badge {
        font-size: 0.7rem;
        margin-left: 5px;
        vertical-align: middle;
    }
    
    /* Table styles */
    table {
        width: 100%;
        min-width: 600px; 
    }
    table th, table td {
        padding: 0.75rem;
        white-space: normal; 
        word-break: break-word; 
        vertical-align: middle;
    }
    table thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }

    /* SHOW MORE LOGIC CSS */
    .hidden-row {
        display: none !important;
    }
    .show-more-container {
        text-align: center;
        padding: 10px;
        background: #f9f9f9;
        border-top: 1px solid #eee;
    }
    .btn-show-more {
        background: transparent;
        border: 1px solid #28a745;
        color: #28a745;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-show-more:hover {
        background: #28a745;
        color: white;
    }
    
    /* Typography */
    h2 { font-size: 1.8rem; }
    h4 { font-size: 1.4rem; }
    .display-4 { font-size: 2.5rem; }
    
    @media (max-width: 991.98px) {
        .nav-tabs { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 5px; }
        .nav-tabs::-webkit-scrollbar { display: none; }
        
      
    /* Desktop View Fixes (Jab screen badi ho) */
    @media (min-width: 992px) {
        
        /* 1. Header ko Sidebar ke right side par set karein */
        .dashboard-header {
            left: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width)) !important;
        }

        /* 2. Main Content ko Sidebar ke agay push karein */
        #content {
            margin-left: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width)) !important;
            padding-top: 20px; /* Thoda gap upar se */
        }

        /* 3. Agar Wrapper par pehle se margin tha to usay reset karein taake double gap na aaye */
        #wrapper {
            margin-left: 0 !important; 
        }
    }

    /* Mobile View Fixes (Jab screen choti ho) */
    @media (max-width: 991px) {
        #content {
            margin-left: 0 !important;
            width: 100% !important;
            padding-top: 100px; /* Mobile menu ke liye jagah */
        }
        .dashboard-header {
            left: 0 !important;
            width: 100% !important;
        }
    }

</style>
</head>
<body>
    <?php include 'Assets/header.php'; ?>
     
 <br>
        <div class="no-bottom no-top mt-5" id="content">

    <div class="container py-3 py-md-5">
        <div class="profile-header mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h2 class="mb-3 mb-md-0">Welcome, <?= htmlspecialchars($loggedInUserData['name']) ?></h2>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary">Your Code: <?= htmlspecialchars($loggedInUserData['referral_code'] ?? 'N/A') ?></span>
                    <span class="badge bg-info">Package: <?= $packageId == 1 ? 'Basic' : ($packageId == 2 ? 'Intermediate' : 'Premium') ?></span>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4"><p><strong>Email:</strong> <?= htmlspecialchars($loggedInUserData['email']) ?></p></div>
                <div class="col-md-4"><p><strong>Phone:</strong> <?= htmlspecialchars($loggedInUserData['phone']) ?></p></div>
                <div class="col-md-4"><p><strong>Member Since:</strong> <?= date('d M Y', strtotime($loggedInUserData['created_at'])) ?></p></div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white"><h4 class="mb-0">Your Referrer</h4></div>
                    <div class="card-body">
                        <?php if ($referrerData): ?>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5><?= htmlspecialchars($referrerData['name']) ?></h5>
                                    <p class="mb-1"><small>Code: <?= htmlspecialchars($referrerData['referral_code']) ?></small></p>
                                    <p class="mb-1"><?= htmlspecialchars($referrerData['email']) ?></p>
                                    <p class="mb-0"><?= htmlspecialchars($referrerData['phone']) ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <p class="text-muted">You were not referred by anyone</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white"><h4 class="mb-0">Referral Stats</h4></div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4 col-md-4">
                                <h2 class="text-primary"><?= $teamXCount ?></h2>
                                <p><strong>Team X</strong><br><small>(Direct Referrals)</small></p>
                            </div>
                            <?php if ($packageId >= 2): ?>
                            <div class="col-4 col-md-4">
                                <h2 class="text-info"><?= $teamYCount ?></h2>
                                <p><strong>Team Y</strong><br><small>(Level 1 Indirect)</small></p>
                            </div>
                            <?php endif; ?>
                            <?php if ($packageId >= 3): ?>
                            <div class="col-4 col-md-4">
                                <h2 class="text-warning"><?= $teamZCount ?></h2>
                                <p><strong>Team Z</strong><br><small>(Level 2 Indirect)</small></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center mt-3">
                            <h1 class="display-4"><?= $teamXCount + $teamYCount + $teamZCount ?></h1>
                            <p>Total People in Your Network</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card referral-card">
            <div class="card-header bg-success text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h4 class="mb-2 mb-md-0">People in Your Network</h4>
                <span class="badge bg-light text-dark">
                    <?php 
                        if ($activeTeam == 'x') echo $teamXCount . ' member'.($teamXCount != 1 ? 's' : '').' in Team X';
                        elseif ($activeTeam == 'y') echo $teamYCount . ' member'.($teamYCount != 1 ? 's' : '').' in Team Y';
                        elseif ($activeTeam == 'z') echo $teamZCount . ' member'.($teamZCount != 1 ? 's' : '').' in Team Z';
                    ?>
                </span>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="teamTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $activeTeam == 'x' ? 'active' : '' ?>" 
                                id="teamX-tab" data-bs-toggle="tab" 
                                onclick="window.location.href='?team=x'" 
                                type="button" role="tab">
                            Team X <span class="badge bg-primary team-count-badge"><?= $teamXCount ?></span>
                        </button>
                    </li>
                    <?php if ($packageId >= 2): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $activeTeam == 'y' ? 'active' : '' ?>" 
                                id="teamY-tab" data-bs-toggle="tab" 
                                onclick="window.location.href='?team=y'" 
                                type="button" role="tab">
                            Team Y <span class="badge bg-info team-count-badge"><?= $teamYCount ?></span>
                        </button>
                    </li>
                    <?php endif; ?>
                    <?php if ($packageId >= 3): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $activeTeam == 'z' ? 'active' : '' ?>" 
                                id="teamZ-tab" data-bs-toggle="tab" 
                                onclick="window.location.href='?team=z'" 
                                type="button" role="tab">
                            Team Z <span class="badge bg-warning team-count-badge"><?= $teamZCount ?></span>
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>

                <?php if (mysqli_num_rows($myReferralsResult) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="referralTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="d-none d-sm-table-cell">Phone</th>
                                    <th class="d-none d-md-table-cell">Referred By</th>  
                                    <th>Joined On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                while ($ref = mysqli_fetch_assoc($myReferralsResult)): 
                                    $levelClass = '';
                                    if (isset($ref['level'])) {
                                        if ($ref['level'] == 'indirect-1') {
                                            $levelClass = 'level-indirect-1';
                                        } elseif ($ref['level'] == 'indirect-2') {
                                            $levelClass = 'level-indirect-2';
                                        } else {
                                            $levelClass = 'level-direct';
                                        }
                                    }
                                    // Logic to hide rows > 10
                                    $isHidden = ($count > 10) ? 'hidden-row' : '';
                                ?>
                                    <tr class="<?= $levelClass ?> <?= $isHidden ?>">
                                        <td><?= $count++ ?></td>
                                        <td><strong><?= htmlspecialchars($ref['name']) ?></strong></td>
                                        <td><?= htmlspecialchars($ref['email']) ?></td>
                                        <td class="d-none d-sm-table-cell"><?= htmlspecialchars($ref['phone']) ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($ref['referred_by']) ?></td>
                                        <td><?= date('d M Y', strtotime($ref['created_at'])) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($totalRows > 10): ?>
                        <div class="show-more-container">
                            <button class="btn btn-show-more" onclick="toggleRows(this, <?= $totalRows ?>)">
                                Show More (+<?= $totalRows - 10 ?>)
                            </button>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" width="100" alt="No referrals" class="img-fluid" />
                        <h5 class="mt-3">No members in this team yet</h5>
                        <p class="text-muted">Share your referral code to build your network</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
    <?php include 'Assets/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="js/jquery.plugin.js"></script>
<script src="js/jquery.countTo.js"></script>
<script src="js/owl.carousel.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/easing.js"></script>
<script src="js/validation.js"></script>
<script src="js/jquery.lazy.min.js"></script>
<script src="js/jquery.lazy.plugins.min.js"></script>
<script src="js/designesia.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/enquire.js/2.1.6/enquire.min.js"></script>

<script>
function toggleRows(btn, total) {
    const hiddenRows = document.querySelectorAll('.hidden-row');
    const isExpanded = btn.innerText.includes('Show Less');

    if (!isExpanded) {
        // Show all rows
        hiddenRows.forEach(row => {
            row.classList.remove('hidden-row');
            row.classList.add('visible-row'); // temporary marker
        });
        btn.innerText = 'Show Less';
    } else {
        // Hide rows again (those that were hidden initially)
        const rowsToHide = document.querySelectorAll('.visible-row');
        rowsToHide.forEach(row => {
            row.classList.add('hidden-row');
            row.classList.remove('visible-row');
        });
        const diff = total - 10;
        btn.innerText = 'Show More (+' + diff + ')';
    }
}
</script>

</body>
</html>