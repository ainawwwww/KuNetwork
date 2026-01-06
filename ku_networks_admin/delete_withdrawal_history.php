<?php
include 'db.php';
include 'check_login.php';

if (isset($_GET['id'])) {
    $withdrawal_id = intval($_GET['id']);


    $stmt = $conn->prepare("DELETE FROM withdrawals WHERE id = ?");
    $stmt->bind_param("i", $withdrawal_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: withdraw_history.php?success=Withdrawal record deleted successfully.");
        exit;
    } else {
        $stmt->close();
        header("Location: withdraw_history.php?error=Failed to delete the withdrawal record.");
        exit;
    }
} else {

    header("Location: withdraw_history.php?error=Invalid request.");
    exit;
}
?>