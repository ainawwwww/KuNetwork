<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    $stmt = $conn->prepare("SELECT * FROM balance_transfers WHERE code = ? AND receiver_accept_status = 'pending'");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or already accepted code.");
    }

    $transfer = $result->fetch_assoc();
    $sender_id = $transfer['sender_id'];
    $receiver_id = $transfer['receiver_id'];
    $amount = $transfer['amount'];

    $check_referral = mysqli_query($conn, "SELECT id FROM referal_teams WHERE user_id = '$sender_id' AND referral_userid = '$receiver_id'");
    if (mysqli_num_rows($check_referral) == 0) {
        echo "Transfer failed: You can only transfer to someone you referred.";
        exit;
    }

    $conn->query("UPDATE balance_transfers SET receiver_accept_status = 'received' WHERE id = " . $transfer['id']);

    echo "Transfer accepted. Waiting for admin approval.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accept Balance Transfer</title>
</head>
<body>
    <h2>Accept Balance Transfer</h2>

    <form method="POST" action="accept_transfer.php">
        <label for="code">Enter Transfer Code:</label>
        <input type="text" name="code" id="code" required>
        <br><br>
        <button type="submit">Accept Transfer</button>
    </form>
</body>
</html>