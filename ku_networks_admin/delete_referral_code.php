<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];


    $stmt = $conn->prepare("DELETE FROM user_referal_codes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: referral_codes.php?success=Referral code deleted successfully");
        exit();
    } else {
        $stmt->close();
        header("Location: referral_codes.php?error=Error deleting referral code");
        exit();
    }
    

} else {
    echo "Invalid request.";
}
?>

