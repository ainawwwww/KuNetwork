<?php
session_start();
require 'config.php';

$userId = (int)$_SESSION['user_id'];

// Check if user already has a pending claim with capital_locked_until in future
$stmt = $conn->prepare("SELECT capital_locked_until FROM weekly_bonus_claims WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$resultCheck = $stmt->get_result();

$now = new DateTime("now", new DateTimeZone("UTC"));
$canClaim = true;

if ($row = $resultCheck->fetch_assoc()) {
    $lockedUntil = $row['capital_locked_until'] ? new DateTime($row['capital_locked_until'], new DateTimeZone("UTC")) : null;
    if ($lockedUntil && $lockedUntil > $now) {
        $canClaim = false; // Still locked, cannot claim
    }
}
$stmt->close();

if ($canClaim) {
    // Insert new claim with capital_locked_until = now + 5 minutes (testing)
    $depositAmount = 100.00; // or dynamically set
    $bonusAmount = 10.00;    // or dynamically set
    $lockedUntilStr = (clone $now)->modify('+5 minutes')->format('Y-m-d H:i:s');
    

  $stmtInsert = $conn->prepare("INSERT INTO weekly_bonus_claims (user_id, capital_locked_amount, bonus_amount, capital_locked_until, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
$stmtInsert->bind_param("idds", $userId, $depositAmount, $bonusAmount, $lockedUntilStr);
  $stmtInsert->bind_param("idds", $userId, $depositAmount, $bonusAmount, $lockedUntilStr);
    $stmtInsert->execute();
    $stmtInsert->close();

    // Redirect to deposit page with success message
  header("Location: deposit.php?success=Bonus claimed! Please deposit at least $100 to unlock your bonus.&claim_bonus=1");
exit;
} else {
    // Redirect back with error message
    header("Location: account.php?error=You can claim your next bonus after 5 minutes.");
    exit;
}