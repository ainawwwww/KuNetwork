<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['delete'])) {
    $id = $_POST['id']; 

    
    $stmt = $conn->prepare("DELETE FROM team_earning_commission WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
       
        header("Location: team_earning.php?success=Team deleted successfully");
    } else {
       
        header("Location: team_earning.php?error=Error deleting team");
    }

    $stmt->close();
    exit();
}
?>
