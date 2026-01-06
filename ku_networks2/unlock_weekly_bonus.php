<?php
// Include your DB connection here
require 'config.php';

$conn->begin_transaction();

try {
    // Select pending claims to unlock
    $sql = "SELECT id, user_id, deposit_amount, bonus_amount FROM weekly_bonus_claims WHERE status = 'pending' AND locked_until <= NOW() FOR UPDATE";
    
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $claimId = $row['id'];
        $userId = $row['user_id'];
        $depositAmount = (float)$row['deposit_amount'];
        $bonusAmount = (float)$row['bonus_amount'];

        // Update claim status to unlocked
        $stmtUpdateClaim = $conn->prepare("UPDATE weekly_bonus_claims SET status = 'unlocked' WHERE id = ?");
        $stmtUpdateClaim->bind_param("i", $claimId);
        $stmtUpdateClaim->execute();
        $stmtUpdateClaim->close();

        // Update user_wallets: move locked capital to available + add bonus
        $stmtUpdateWallet = $conn->prepare("
            UPDATE user_wallets
            SET capital_locked_balance = capital_locked_balance - ?,
                available_balance = available_balance + ? + ?,
                total_balance = total_balance + ?
            WHERE user_id = ?
        ");
        $stmtUpdateWallet->bind_param("dddi", $depositAmount, $depositAmount, $bonusAmount, $bonusAmount, $userId);
        $stmtUpdateWallet->execute();
        $stmtUpdateWallet->close();
    }

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error unlocking weekly bonuses: " . $e->getMessage());
}