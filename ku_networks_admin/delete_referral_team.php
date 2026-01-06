<?php
include 'db.php';
include 'check_login.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM referal_teams WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: referral_teams.php?success=Referral team deleted successfully");
        exit();
    } else {
        $stmt->close();
        header("Location: referral_teams.php?error=Error deleting referral team");
        exit();
    }
    

} else {
    echo "Invalid request.";
}
?>