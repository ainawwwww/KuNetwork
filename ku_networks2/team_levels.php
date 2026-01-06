<?php
require 'user_data_logic.php';

// --- 1. Helper Functions for Team ---
function getTeamUserIdsSimple(mysqli $conn, array $leaderIds): array {
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

// Note: Limit increased to 500 to fetch more members for "Show More" functionality
function fetchUserBasics(mysqli $conn, array $userIds, int $limit = 500): array {
    if (empty($userIds)) return [];
    $userIds = array_slice(array_values(array_filter($userIds, function($v){ return is_numeric($v) && $v > 0; })), 0, $limit);
    if (empty($userIds)) return [];
    
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));
    
    $types = str_repeat('i', count($userIds));
    $sql = "SELECT id, name, user_id FROM users WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    
    $refs = []; $refs[] = & $types;
    for ($i = 0; $i < count($userIds); $i++) $refs[] = & $userIds[$i];
    call_user_func_array([$stmt, 'bind_param'], $refs);
    
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $stmt->close();
    return $rows;
}

// --- 2. Logic: Fetch Teams ---
$teamXIds = getTeamUserIdsSimple($conn, [$loggedInUserIdentifier]);
$teamYIds = !empty($teamXIds) ? getTeamUserIdsSimple($conn, $teamXIds) : [];
$teamZIds = !empty($teamYIds) ? getTeamUserIdsSimple($conn, $teamYIds) : [];

$teamXCountTotal = count($teamXIds);
$teamYCountTotal = count($teamYIds);
$teamZCountTotal = count($teamZIds);
$totalTeamCountTotal = $teamXCountTotal + $teamYCountTotal + $teamZCountTotal;

// --- 3. Logic: Determine Visibility based on Package ---
$showTeamX = true; $showTeamY = false; $showTeamZ = false;
$package_id = null;
$stmtPkg = $conn->prepare("SELECT package_id FROM enrolleduserspackages WHERE user_id = ? ORDER BY purchase_date DESC LIMIT 1");
$stmtPkg->bind_param("i", $loggedInUserIdentifier);
$stmtPkg->execute();
$stmtPkg->bind_result($pkg);
if ($stmtPkg->fetch()) $package_id = (int)$pkg;
$stmtPkg->close();

if (!is_null($package_id)) {
    if ($package_id === 1) { $showTeamX = true; } 
    elseif ($package_id === 2) { $showTeamX = true; $showTeamY = true; } 
    elseif ($package_id === 3) { $showTeamX = true; $showTeamY = true; $showTeamZ = true; }
}

// Fetch data (limit set higher to allow "Show More")
$teamXPreview = $showTeamX ? fetchUserBasics($conn, $teamXIds, 500) : [];
$teamYPreview = $showTeamY ? fetchUserBasics($conn, $teamYIds, 500) : [];
$teamZPreview = $showTeamZ ? fetchUserBasics($conn, $teamZIds, 500) : [];

// --- 4. Logic: Fetch Current Level Info ---
$currentLevelId = null; $currentLevelTeamType = null; $currentLevelMinAmount = null;
$stmtLvl = $conn->prepare("SELECT current_level_id FROM users WHERE id = ?");
$stmtLvl->bind_param("i", $loggedInUserIdentifier);
$stmtLvl->execute();
$stmtLvl->bind_result($cid);
if ($stmtLvl->fetch()) $currentLevelId = $cid;
$stmtLvl->close();
if (empty($currentLevelId)) $currentLevelId = 1; // Fallback

// Level Details
$stmtLI = $conn->prepare("SELECT team_type, minimum_amount FROM levels WHERE id = ?");
$stmtLI->bind_param("i", $currentLevelId);
$stmtLI->execute();
$stmtLI->bind_result($tt, $minAmt);
if ($stmtLI->fetch()) { $currentLevelTeamType = $tt; $currentLevelMinAmount = $minAmt; }
$stmtLI->close();

// --- 5. Direct Investment Total ---
$totalDirectInvestment = 0.00;
$stmtInv = $conn->prepare("SELECT COALESCE(SUM(amount),0) FROM payment WHERE user_id = ?");
$stmtInv->bind_param("i", $loggedInUserIdentifier);
$stmtInv->execute();
$stmtInv->bind_result($sumAmt);
if ($stmtInv->fetch()) $totalDirectInvestment = (float)$sumAmt;
$stmtInv->close();

// --- 6. Upgrade History ---
$upgradeHistory = [];
$stmtUp = $conn->prepare("SELECT bonus_amount, level_from, level_to, created_at FROM bonus_history WHERE user_id = ? AND bonus_type = 'upgrade' ORDER BY created_at DESC LIMIT 10");
$stmtUp->bind_param("i", $loggedInUserIdentifier);
$stmtUp->execute();
$resUp = $stmtUp->get_result();
while ($row = $resUp->fetch_assoc()) $upgradeHistory[] = $row;
$stmtUp->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Team & Levels</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="custom_dashboard.css">
    <link rel="stylesheet" href="custom_account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    <style>
        /* Show More Logic CSS - !important added to override Bootstrap d-flex */
        .hidden-member {
            display: none !important;
        }
        .show-more-btn {
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            display: block;
            text-align: center;
            padding: 8px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            color: var(--primary-blue, #0d6efd);
            transition: background 0.2s;
        }
        .show-more-btn:hover {
            background: #e9ecef;
        }
        .list-group {
            max-height: 500px; /* Scrollbar if too long after expanding */
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="container py-5">
            <a href="account.php" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            
            <div class="card shadow-sm">
              <div class="card-header bg-white">
                <span class="fw-bold"><i class="fas fa-sitemap card-title-icon text-primary"></i> Levels & Referral Team</span>
              </div>
              <div class="card-body p-4">
                <div class="row g-3">
                  <div class="col-md-6 border-end">
                    <div class="mb-3">
                      <div class="text-muted small fw-semibold text-uppercase">Current Level</div>
                      <div class="fs-4 fw-bold text-primary">
                        <?php echo 'Level ' . htmlspecialchars((string)$currentLevelId); ?>
                      </div>
                      <?php if ($currentLevelTeamType): ?>
                      <div class="text-muted small">Type: <?php echo htmlspecialchars($currentLevelTeamType); ?></div>
                      <?php endif; ?>
                      <div class="text-muted small">Total Direct Investment: $<?php echo number_format($totalDirectInvestment, 2); ?></div>
                    </div>
                  </div>
                  <div class="col-md-6 ps-md-4">
                    <div class="mb-2">
                      <div class="text-muted small fw-semibold text-uppercase">Team Overview</div>
                      <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="badge bg-primary px-3 py-2">Team X: <?php echo $teamXCountTotal; ?></span>
                        <span class="badge bg-info text-dark px-3 py-2">Team Y: <?php echo $teamYCountTotal; ?></span>
                        <span class="badge bg-warning text-dark px-3 py-2">Team Z: <?php echo $teamZCountTotal; ?></span>
                      </div>
                      <div class="small text-muted mt-2">Total Team Size: <strong><?php echo $totalTeamCountTotal; ?></strong></div>
                    </div>
                  </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                  
                  <div class="col-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                         <div class="text-primary small fw-bold">TEAM X (Direct)</div>
                         <?php if(!$showTeamX): ?><i class="fas fa-lock text-muted"></i><?php endif; ?>
                    </div>
                    <?php if ($showTeamX && !empty($teamXPreview)): ?>
                      <div class="border rounded position-relative">
                          <ul class="list-group list-group-flush" id="list-team-x">
                            <?php 
                            $count = 0;
                            foreach ($teamXPreview as $m): 
                                $count++;
                                $hiddenClass = ($count > 10) ? 'hidden-member' : '';
                            ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center small team-item <?php echo $hiddenClass; ?>">
                                <span class="fw-medium"><?php echo htmlspecialchars($m['name']); ?></span>
                                <span class="text-muted" style="font-size:0.75rem">@<?php echo htmlspecialchars($m['user_id']); ?></span>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                          <?php if(count($teamXPreview) > 10): ?>
                            <div class="show-more-btn" onclick="toggleTeam('list-team-x', this, <?php echo count($teamXPreview); ?>)">
                                Show More (+<?php echo count($teamXPreview)-10; ?>)
                            </div>
                          <?php endif; ?>
                      </div>
                    <?php else: ?>
                      <div class="alert alert-light text-center small text-muted">No members visible.</div>
                    <?php endif; ?>
                  </div>

                  <div class="col-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                         <div class="text-info small fw-bold">TEAM Y (L2)</div>
                         <?php if(!$showTeamY): ?><i class="fas fa-lock text-muted" title="Upgrade package to view"></i><?php endif; ?>
                    </div>
                    <?php if ($showTeamY && !empty($teamYPreview)): ?>
                      <div class="border rounded position-relative">
                          <ul class="list-group list-group-flush" id="list-team-y">
                            <?php 
                            $count = 0;
                            foreach ($teamYPreview as $m): 
                                $count++;
                                $hiddenClass = ($count > 10) ? 'hidden-member' : '';
                            ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center small team-item <?php echo $hiddenClass; ?>">
                                <span class="fw-medium"><?php echo htmlspecialchars($m['name']); ?></span>
                                <span class="text-muted" style="font-size:0.75rem">@<?php echo htmlspecialchars($m['user_id']); ?></span>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                          <?php if(count($teamYPreview) > 10): ?>
                            <div class="show-more-btn" onclick="toggleTeam('list-team-y', this, <?php echo count($teamYPreview); ?>)">
                                Show More (+<?php echo count($teamYPreview)-10; ?>)
                            </div>
                          <?php endif; ?>
                      </div>
                    <?php elseif (!$showTeamY): ?>
                       <div class="alert alert-warning small"><i class="fas fa-lock me-1"></i> Upgrade to Leader/Prime to view.</div>
                    <?php else: ?>
                       <div class="alert alert-light text-center small text-muted">No members found.</div>
                    <?php endif; ?>
                  </div>

                  <div class="col-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                         <div class="text-warning small fw-bold">TEAM Z (L3)</div>
                         <?php if(!$showTeamZ): ?><i class="fas fa-lock text-muted" title="Upgrade package to view"></i><?php endif; ?>
                    </div>
                    <?php if ($showTeamZ && !empty($teamZPreview)): ?>
                      <div class="border rounded position-relative">
                          <ul class="list-group list-group-flush" id="list-team-z">
                            <?php 
                            $count = 0;
                            foreach ($teamZPreview as $m): 
                                $count++;
                                $hiddenClass = ($count > 10) ? 'hidden-member' : '';
                            ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center small team-item <?php echo $hiddenClass; ?>">
                                <span class="fw-medium"><?php echo htmlspecialchars($m['name']); ?></span>
                                <span class="text-muted" style="font-size:0.75rem">@<?php echo htmlspecialchars($m['user_id']); ?></span>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                          <?php if(count($teamZPreview) > 10): ?>
                            <div class="show-more-btn" onclick="toggleTeam('list-team-z', this, <?php echo count($teamZPreview); ?>)">
                                Show More (+<?php echo count($teamZPreview)-10; ?>)
                            </div>
                          <?php endif; ?>
                      </div>
                    <?php elseif (!$showTeamZ): ?>
                       <div class="alert alert-warning small"><i class="fas fa-lock me-1"></i> Upgrade to Prime to view.</div>
                    <?php else: ?>
                       <div class="alert alert-light text-center small text-muted">No members found.</div>
                    <?php endif; ?>
                  </div>
                </div>

                <hr class="my-4">

                <div>
                  <div class="text-muted small fw-semibold mb-3">Recent Level Upgrades</div>
                  <?php if (!empty($upgradeHistory)): ?>
                    <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>Level</th><th>Bonus</th><th>Date</th></tr></thead>
                        <tbody>
                        <?php foreach ($upgradeHistory as $u): ?>
                        <tr>
                          <td>L<?php echo (int)$u['level_from']; ?> <i class="fas fa-arrow-right text-muted mx-1" style="font-size:0.7rem"></i> L<?php echo (int)$u['level_to']; ?></td>
                          <td class="text-success fw-bold">+$<?php echo number_format((float)$u['bonus_amount'], 2); ?></td>
                          <td class="text-muted small"><?php echo htmlspecialchars(date('M d, Y', strtotime($u['created_at']))); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                  <?php else: ?>
                    <div class="small text-muted fst-italic">No upgrade records found yet.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>

    <script>
        function toggleTeam(listId, btn, totalCount) {
            var list = document.getElementById(listId);
            // We select items that have the class 'team-item' inside the list
            var allItems = list.querySelectorAll('.team-item');
            
            // Check current state by looking at the button text
            var isExpanded = btn.innerText.includes('Show Less');

            if (!isExpanded) {
                // EXPAND: Remove hidden-member class from all items
                allItems.forEach(function(item) {
                    item.classList.remove('hidden-member');
                });
                btn.innerText = 'Show Less';
            } else {
                // COLLAPSE: Add hidden-member class to items > 10
                allItems.forEach(function(item, index) {
                    if (index >= 10) {
                        item.classList.add('hidden-member');
                    }
                });
                var diff = totalCount - 10;
                btn.innerText = 'Show More (+' + diff + ')';
            }
        }
    </script>
</body>
</html>