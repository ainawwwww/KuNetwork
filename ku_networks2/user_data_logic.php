<?php
require 'config.php';
session_start();
require 'pointsmenagment.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php?error=" . urlencode("Please log in to view your account."));
    exit();
}

$loggedInUserIdentifier = (int) $_SESSION['user_id'];
$now = new DateTime("now", new DateTimeZone("UTC"));

// --- Weekly Bonus Logic (RESTORED) ---
$showWeeklyBonusPopup = false;

// 1. Get User Creation Date
$stmtUser = $conn->prepare("SELECT created_at FROM users WHERE id = ?");
$stmtUser->bind_param("i", $loggedInUserIdentifier);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userCreatedAt = null;
if ($rowUser = $resultUser->fetch_assoc()) {
    $userCreatedAt = new DateTime($rowUser['created_at'], new DateTimeZone("UTC"));
}
$stmtUser->close();

// 2. Get Last Lock
$stmtWeekly = $conn->prepare("SELECT capital_locked_until FROM weekly_bonus_claims WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmtWeekly->bind_param("i", $loggedInUserIdentifier);
$stmtWeekly->execute();
$resultWeekly = $stmtWeekly->get_result();
$lastLockedUntil = null;
if ($row = $resultWeekly->fetch_assoc()) {
    $lastLockedUntil = $row['capital_locked_until'] ? new DateTime($row['capital_locked_until'], new DateTimeZone("UTC")) : null;
}
$stmtWeekly->close();

// 3. Determine Eligibility
if ($userCreatedAt) {
    $intervalSinceAccountCreated = $now->getTimestamp() - $userCreatedAt->getTimestamp();
    $minutesSinceAccountCreated = $intervalSinceAccountCreated / 60;

    if ($lastLockedUntil === null || $now > $lastLockedUntil) {
        // No active lock, show popup if account older than 5 minutes (testing)
        if ($minutesSinceAccountCreated >= 5) {
            $showWeeklyBonusPopup = true;
        }
    } else {
        // Lock active, no popup
        $showWeeklyBonusPopup = false;
    }
}

// --- Standard Helper Functions ---
function getUserStage($user_id, $conn) {
    $sql = "SELECT s.* FROM user_stage_history ush JOIN stages s ON ush.stage_id = s.stage_id WHERE ush.user_id = ? ORDER BY ush.assigned_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
}

function column_exists(mysqli $conn, string $table, string $column): bool {
    $sql = "SHOW COLUMNS FROM `{$table}` LIKE ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $column);
        $stmt->execute();
        $res = $stmt->get_result();
        return ($res && $res->num_rows > 0);
    }
    return false;
}

// --- Defaults ---
$profileDisplayName = "N/A";
$profileUID = "N/A";
$profileLoginUsername = "N/A";
$userImageFilename = "default.png";
$userRank = "No Rank";
$userPoints = 0;
$userStatus = "active";
$lastLoginTime = "Never";
$lastLoginUtc = null;

// --- Fetch Basic User Data ---
$stmt = $conn->prepare("SELECT status, last_login_at, daily_points, weekly_points, monthly_points FROM user_login_times WHERE user_id = ?");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $userStatus = (strtolower(trim($row['status'])) === 'inactive') ? 'inactive' : 'active';
    $lastLoginUtc = $row['last_login_at'];
    $lastLoginTime = $lastLoginUtc ? gmdate('M d, Y h:i A', strtotime($lastLoginUtc)) . ' UTC' : "Never";
    $userPoints = (int)$row['daily_points'] + (int)$row['weekly_points'] + (int)$row['monthly_points'];
}
$displayStatusText  = ($userStatus === 'inactive') ? 'Inactive' : 'Active';
$displayStatusClass = ($userStatus === 'inactive') ? 'status-bad' : 'status-good';

// --- Profile Data ---
$stmt = $conn->prepare("SELECT id, name, user_id, image FROM users WHERE id = ?");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if ($user) {
    $profileDisplayName = htmlspecialchars($user['name']);
    $profileUID = htmlspecialchars($user['id']);
    $profileLoginUsername = htmlspecialchars($user['user_id']);
    if (!empty($user['image'])) $userImageFilename = htmlspecialchars($user['image']);
}

// --- Rank ---
$stmt = $conn->prepare("SELECT assigned_rank FROM rank_assignment_summary WHERE user_id = ?");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
if ($r = $stmt->get_result()->fetch_assoc()) $userRank = htmlspecialchars($r['assigned_rank']);

// --- Wallet Balance ---
$walletBalance = 0.00;
$walletCurrency = 'USD';
$wallet7DayChange = 0.00; 
$stmt = $conn->prepare("SELECT balance, total_balance, currency FROM user_wallets WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$stmt->bind_result($bal, $totBal, $curr);
if ($stmt->fetch()) {
    $walletBalance = ($totBal !== null) ? (float)$totBal : (($bal !== null) ? (float)$bal : 0.00);
    if (!empty($curr)) $walletCurrency = $curr;
}
$stmt->close();

// --- Recent Investment ---
$userRecentProductName = null;
$userRecentProductAmount = 0.00;
$productInvestPercent = 0;
$productUserCount = 0;
$totalInvestorsCount = 0;

$stmt = $conn->prepare("SELECT product_name, SUM(product_price) as total_spent, COUNT(*) as cnt FROM product_buy WHERE user_id = ? GROUP BY product_name ORDER BY MAX(purchase_date) DESC LIMIT 1");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
if ($row = $stmt->get_result()->fetch_assoc()) {
    $userRecentProductName = $row['product_name'];
    $userRecentProductAmount = (float)$row['total_spent'];
}
$stmt->close();

if ($userRecentProductName) {
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as c FROM product_buy WHERE product_name = ?");
    $stmt->bind_param("s", $userRecentProductName);
    $stmt->execute();
    $productUserCount = $stmt->get_result()->fetch_assoc()['c'];
    
    $res = $conn->query("SELECT COUNT(DISTINCT user_id) as t FROM product_buy");
    $totalInvestorsCount = $res->fetch_assoc()['t'];
    
    $productInvestPercent = ($totalInvestorsCount > 0) ? round(($productUserCount / $totalInvestorsCount) * 100) : 0;
}

// --- Commissions ---
$teamCommissionsMTD = 0.00;
$teamCommissionsLastMonth = 0.00;
$stmt = $conn->prepare("SELECT SUM(p.amount) * 0.10 FROM payment p INNER JOIN referal_teams rt ON p.user_id = rt.referral_userid WHERE rt.user_id = ? AND MONTH(p.created_at) = MONTH(UTC_DATE()) AND YEAR(p.created_at) = YEAR(UTC_DATE())");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$stmt->bind_result($mtd);
if ($stmt->fetch()) $teamCommissionsMTD = $mtd ?? 0.00;
$stmt->close();

$stmt = $conn->prepare("SELECT SUM(p.amount) * 0.10 FROM payment p INNER JOIN referal_teams rt ON p.user_id = rt.referral_userid WHERE rt.user_id = ? AND MONTH(p.created_at) = MONTH(DATE_SUB(UTC_DATE(), INTERVAL 1 MONTH)) AND YEAR(p.created_at) = YEAR(DATE_SUB(UTC_DATE(), INTERVAL 1 MONTH))");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$stmt->bind_result($lastM);
if ($stmt->fetch()) $teamCommissionsLastMonth = $lastM ?? 0.00;
$stmt->close();
$commissionChange = $teamCommissionsMTD - $teamCommissionsLastMonth;

// --- Misc ---
$userStage = getUserStage($loggedInUserIdentifier, $conn);

$userMembership = null;
$stmt = $conn->prepare("SELECT m.plan_name, m.member_detail, m.withdraw_fee, m.customer_support, m.withdraw_capital, m.withdraw_processing_time, e.purchase_date FROM enrolleduserspackages e JOIN membership m ON e.package_id = m.id WHERE e.user_id = ? AND e.status = 'active' ORDER BY e.purchase_date DESC LIMIT 1");
$stmt->bind_param("i", $loggedInUserIdentifier);
$stmt->execute();
$userMembership = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Image Path
if (!defined('DEFAULT_AVATAR_FILENAME')) define('DEFAULT_AVATAR_FILENAME', 'default.png');
if (!defined('PROFILE_IMAGE_UPLOAD_DIR_ADMIN')) define('PROFILE_IMAGE_UPLOAD_DIR_ADMIN', '/ku_networks_admin/images/uploads/profile_images/');
if (!defined('PROFILE_IMAGE_UPLOAD_DIR')) define('PROFILE_IMAGE_UPLOAD_DIR', 'images/uploads/profile_images/');
if (!defined('BASE_WEB_PATH_MAIN_PROJECT')) define('BASE_WEB_PATH_MAIN_PROJECT', '/ku_networks/');
if (!defined('ULTIMATE_DEFAULT_AVATAR_WEB_PATH')) define('ULTIMATE_DEFAULT_AVATAR_WEB_PATH', BASE_WEB_PATH_MAIN_PROJECT . 'images/wallet/profile.jpg');

function get_player_image_src_logic($image_filename) {
    $doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
    $admin_image_web_path = rtrim(PROFILE_IMAGE_UPLOAD_DIR_ADMIN, '/') . '/' . $image_filename;
    $admin_image_server_path = $doc_root . $admin_image_web_path;
    $user_image_relative_path = rtrim(PROFILE_IMAGE_UPLOAD_DIR, '/') . '/' . $image_filename;
    $user_image_web_path = rtrim(BASE_WEB_PATH_MAIN_PROJECT, '/') . '/' . ltrim($user_image_relative_path, '/');
    $user_image_server_path = $doc_root . $user_image_web_path;
    
    if (!empty($image_filename) && $image_filename !== DEFAULT_AVATAR_FILENAME) {
        if (file_exists($admin_image_server_path)) return $admin_image_web_path;
        if (file_exists($user_image_server_path)) return $user_image_web_path;
    }
    return ULTIMATE_DEFAULT_AVATAR_WEB_PATH; 
}
$profileImageSrc = get_player_image_src_logic($userImageFilename);

?>