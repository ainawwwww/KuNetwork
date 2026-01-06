<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $amount = floatval($_POST['amount']);

    $checkReferral = $conn->prepare("SELECT * FROM referal_teams WHERE parent_id = ? AND user_id = ?");
    $checkReferral->bind_param("ii", $sender_id, $receiver_id);
    $checkReferral->execute();
    $referralResult = $checkReferral->get_result();

    if ($referralResult->num_rows == 0) {
        die("Error: You can only transfer to users you referred.");
    }

 
    $checkBalance = $conn->prepare("SELECT available_balance FROM user_wallets WHERE user_id = ?");
    $checkBalance->bind_param("i", $sender_id);
    $checkBalance->execute();
    $balanceResult = $checkBalance->get_result()->fetch_assoc();
    
    if (!$balanceResult || $balanceResult['available_balance'] < $amount) {
        die("Error: Insufficient balance.");
    }


    $code = strtoupper(bin2hex(random_bytes(4))); 


    $stmt = $conn->prepare("INSERT INTO balance_transfers (sender_id, receiver_id, amount, code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iids", $sender_id, $receiver_id, $amount, $code);
    $stmt->execute();

    echo "Transfer request created. Share this code with the receiver: <strong>$code</strong>";
}
?>