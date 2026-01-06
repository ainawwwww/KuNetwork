<?php
// filename: salary_logic.php
include 'config.php';
session_start();

// Browser caching rokne ke liye headers
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// TESTING: 5 Minutes (300 Seconds)
// PRODUCTION: 30 Days (2592000 Seconds)
$COOLDOWN_SECONDS = 300; 

// =================================================================
// 1. HELPER FUNCTION: TIME CHECK (DB Based Calculation)
// =================================================================
function getTimeStatus($conn, $user_id, $cooldown) {
    // Hum DB se pooch rahe hain ke aakhri claim kab hua tha aur abhi kitne seconds guzray hain
    // TIMESTAMPDIFF(SECOND, claimed_at, NOW()) ye batata hai ke kitne seconds pehle entry hui thi
    $sql = "SELECT TIMESTAMPDIFF(SECOND, claimed_at, NOW()) as seconds_ago 
            FROM monthly_bonus_claims 
            WHERE user_id = ? 
            ORDER BY claimed_at DESC LIMIT 1";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $seconds_passed = (int)$row['seconds_ago'];
        
        // Agar waqt kam guzra hai cooldown se
        if ($seconds_passed < $cooldown) {
            $remaining = $cooldown - $seconds_passed;
            return ['can_claim' => false, 'remaining' => $remaining];
        }
    }
    
    // Agar koi record nahi ya waqt guzar chuka hai
    return ['can_claim' => true, 'remaining' => 0];
}

// =================================================================
// 2. HELPER FUNCTION: COUNT ACTIVE TEAM MEMBERS
// =================================================================
function getActiveCounts($conn, $user_id) {
    // A. Active Team X
    $sqlX = "SELECT rt.referral_userid 
             FROM referal_teams rt 
             JOIN user_wallets w ON rt.referral_userid = w.user_id 
             WHERE rt.user_id = ? AND w.total_balance >= 50";
             
    $stmtX = $conn->prepare($sqlX);
    $stmtX->bind_param("i", $user_id);
    $stmtX->execute();
    $resX = $stmtX->get_result();
    
    $teamX_Ids = [];
    while ($row = $resX->fetch_assoc()) {
        $teamX_Ids[] = $row['referral_userid'];
    }
    $stmtX->close();
    $countX = count($teamX_Ids);
    
    // B. Active Team Y+Z
    $countYZ = 0;
    if ($countX > 0) {
        $x_ids_str = implode(',', array_map('intval', $teamX_Ids));
        
        $sqlY = "SELECT rt.referral_userid 
                 FROM referal_teams rt 
                 JOIN user_wallets w ON rt.referral_userid = w.user_id 
                 WHERE rt.user_id IN ($x_ids_str) AND w.total_balance >= 50";
        $resY = $conn->query($sqlY);
        $teamY_Ids = [];
        while ($row = $resY->fetch_assoc()) {
            $teamY_Ids[] = $row['referral_userid'];
            $countYZ++;
        }
        
        if (!empty($teamY_Ids)) {
            $y_ids_str = implode(',', array_map('intval', $teamY_Ids));
            $sqlZ = "SELECT COUNT(*) as total 
                     FROM referal_teams rt 
                     JOIN user_wallets w ON rt.referral_userid = w.user_id 
                     WHERE rt.user_id IN ($y_ids_str) AND w.total_balance >= 50";
            $resZ = $conn->query($sqlZ);
            $rowZ = $resZ->fetch_assoc();
            $countYZ += (int)$rowZ['total'];
        }
    }
    return ['x' => $countX, 'yz' => $countYZ];
}

// =================================================================
// 3. ACTION: CHECK ELIGIBILITY
// =================================================================
if ($action == 'check') {
    
    // A. Check Time First using new DB Logic
    $timeStatus = getTimeStatus($conn, $user_id, $COOLDOWN_SECONDS);
    
    if (!$timeStatus['can_claim']) {
        echo json_encode([
            'status' => 'wait', 
            'remaining_seconds' => $timeStatus['remaining'],
            'message' => 'Next claim available soon.'
        ]);
        exit();
    }

    // B. Check Requirements
    $counts = getActiveCounts($conn, $user_id);
    $myActiveX = $counts['x'];
    $myActiveYZ = $counts['yz'];

    $sqlRules = "SELECT * FROM monthly_salary_bonus ORDER BY bonus_amount DESC";
    $resultRules = $conn->query($sqlRules);

    $eligibleAmount = 0;
    while ($rule = $resultRules->fetch_assoc()) {
        if ($myActiveX >= $rule['team_x'] && $myActiveYZ >= $rule['team_yz']) {
            $eligibleAmount = (float)$rule['bonus_amount'];
            break; 
        }
    }

    if ($eligibleAmount > 0) {
        echo json_encode([
            'status' => 'eligible',
            'amount' => $eligibleAmount,
            'stats' => "Criteria Met: X($myActiveX), YZ($myActiveYZ)"
        ]);
    } else {
        echo json_encode([
            'status' => 'not_eligible',
            'stats' => "Active: X($myActiveX) | YZ($myActiveYZ). Criteria not met."
        ]);
    }
    exit();
}

// =================================================================
// 4. ACTION: CLAIM BONUS
// =================================================================
if ($action == 'claim') {
    
    // Security Time Check
    $timeStatus = getTimeStatus($conn, $user_id, $COOLDOWN_SECONDS);
    if (!$timeStatus['can_claim']) {
        echo json_encode(['status' => 'error', 'message' => 'Please wait for cooldown.']);
        exit();
    }

    // Security Eligibility Check
    $counts = getActiveCounts($conn, $user_id);
    $sqlRules = "SELECT * FROM monthly_salary_bonus ORDER BY bonus_amount DESC";
    $resRules = $conn->query($sqlRules);
    
    $claimAmount = 0;
    $claimLevel = 0;
    while ($rule = $resRules->fetch_assoc()) {
        if ($counts['x'] >= $rule['team_x'] && $counts['yz'] >= $rule['team_yz']) {
            $claimAmount = $rule['bonus_amount'];
            $claimLevel = $rule['level_id'];
            break;
        }
    }

    if ($claimAmount <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Requirements not met.']);
        exit();
    }

    // Process Transaction
    $conn->begin_transaction();
    try {
        // 1. Update Wallet
        $stmtW = $conn->prepare("UPDATE user_wallets SET available_balance = available_balance + ?, total_balance = total_balance + ? WHERE user_id = ?");
        $stmtW->bind_param("ddi", $claimAmount, $claimAmount, $user_id);
        $stmtW->execute();
        $stmtW->close();

        // 2. Insert Claim (Uses NOW() for consistent DB time)
        $stmtC = $conn->prepare("INSERT INTO monthly_bonus_claims (user_id, bonus_amount, level_achieved, claimed_at) VALUES (?, ?, ?, NOW())");
        $stmtC->bind_param("idi", $user_id, $claimAmount, $claimLevel);
        $stmtC->execute();
        $stmtC->close();
        
        // 3. Audit History
        $desc = "Monthly Salary Bonus (Level $claimLevel)";
        $stmtH = $conn->prepare("INSERT INTO wallet_history (user_id, amount, type, description, created_at) VALUES (?, ?, 'credit', ?, NOW())");
        $stmtH->bind_param("ids", $user_id, $claimAmount, $desc);
        $stmtH->execute();
        $stmtH->close();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => "Claimed $$claimAmount Successfully!"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
    }
    exit();
}
?>