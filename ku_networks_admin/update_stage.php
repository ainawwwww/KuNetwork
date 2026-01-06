<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Correct POST names
    $stage_id = (int)$_POST['stage_id'];
    $stage_name = $_POST['stage_name'];
    $user_limit = (int)$_POST['user_limit'];
    $referral_bonus = (float)$_POST['referral_bonus'];
    $deposit_bonus = (float)$_POST['deposit_bonus'];
    $min_deposit_usdt = (float)$_POST['min_deposit_usdt'];

    // Correct SQL query with correct column names
    $stmt = $conn->prepare("
        UPDATE stages 
        SET stage_name = ?, user_limit = ?, referral_bonus = ?, deposit_bonus = ?, min_deposit_usdt = ?
        WHERE stage_id = ?
    ");

    $stmt->bind_param("siddii", $stage_name, $user_limit, $referral_bonus, $deposit_bonus, $min_deposit_usdt, $stage_id);

    if ($stmt->execute()) {
        header("Location: stages.php?success=Stage updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "Invalid Request!";
}
?>
