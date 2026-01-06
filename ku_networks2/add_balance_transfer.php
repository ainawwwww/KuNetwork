<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=" . urlencode("Please log in to transfer balance."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);
    $receiver_id = $_POST['receiver_id'] ?? null;

    if (!$receiver_id) {
        header("Location: balance_transfer.php?error=" . urlencode("Transfer failed: Receiver not selected."));
        exit;
    }

    $code = strtoupper(bin2hex(random_bytes(5)));
    


    $check_referral = mysqli_query($conn, "SELECT id FROM referal_teams WHERE user_id = '$sender_id' AND referral_userid = '$receiver_id'");
    if (mysqli_num_rows($check_referral) == 0) {
        header("Location: balance_transfer.php?error=" . urlencode("Transfer failed: You can only transfer to someone you referred."));
        exit;
    }


    mysqli_query($conn, "INSERT INTO balance_transfers (sender_id, receiver_id, amount, code)
                         VALUES ('$sender_id', '$receiver_id', '$amount', '$code')");

    header("Location: balance_transfer.php?success=" . urlencode("Transfer request created. Share this code with the recipient: $code"));
}
?>