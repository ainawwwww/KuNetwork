<?php
include 'config.php';
session_start();

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=" . urlencode("Please log in to accept the transfer."));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $receiver_id = $_SESSION['user_id'];

    try {
        // 1️⃣ Fetch the transfer record
        $stmt = $conn->prepare("SELECT * FROM balance_transfers WHERE code = ? AND receiver_accept_status = 'pending'");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $transfer = $stmt->get_result()->fetch_assoc();

        if (!$transfer) {
            header("Location: balance_transfer.php?error=" . urlencode("Invalid or already accepted code."));
            exit();
        }

        $sender_id = $transfer['sender_id'];
        $amount = $transfer['amount'];

        if ($receiver_id != $transfer['receiver_id']) {
            header("Location: balance_transfer.php?error=" . urlencode("Transfer failed: You are not the intended recipient."));
            exit();
        }

        // 2️⃣ Check referral
        $check_referral = $conn->prepare("SELECT id FROM referal_teams WHERE user_id = ? AND referral_userid = ?");
        $check_referral->bind_param("ii", $sender_id, $receiver_id);
        $check_referral->execute();
        if ($check_referral->get_result()->num_rows === 0) {
            header("Location: balance_transfer.php?error=" . urlencode("Transfer failed: You can only receive from someone you referred."));
            exit();
        }

        // 3️⃣ Start transaction
        $conn->begin_transaction();

        // 4️⃣ Deduct from sender
        $deduct_sender = $conn->prepare("
            UPDATE user_wallets 
            SET total_balance = total_balance - ?, available_balance = available_balance - ? 
            WHERE user_id = ? AND total_balance >= ? AND available_balance >= ?
        ");
        $deduct_sender->bind_param("ddidd", $amount, $amount, $sender_id, $amount, $amount);
        $deduct_sender->execute();

        if ($deduct_sender->affected_rows === 0) {
            throw new Exception("Sender has insufficient balance.");
        }

        // 5️⃣ Add to receiver
        $add_receiver = $conn->prepare("
            UPDATE user_wallets 
            SET total_balance = total_balance + ?, available_balance = available_balance + ? 
            WHERE user_id = ?
        ");
        $add_receiver->bind_param("ddi", $amount, $amount, $receiver_id);
        $add_receiver->execute();

        // 6️⃣ Update transfer status
        $update_transfer = $conn->prepare("
            UPDATE balance_transfers 
            SET receiver_accept_status = 'received', admin_approval_status = 'approved' 
            WHERE id = ?
        ");
        $update_transfer->bind_param("i", $transfer['id']);
        $update_transfer->execute();

        $conn->commit();

        // ✅ Redirect back with success message
        header("Location: balance_transfer.php?success=" . urlencode("Transfer successful! Amount added to your account."));
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: balance_transfer.php?error=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
}
?>
