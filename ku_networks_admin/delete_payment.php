<?php
include 'db.php'; 
include 'check_login.php'; 

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    $stmt = $conn->prepare("DELETE FROM payment WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: payment.php?success=Payment Deleted successfully");
    } else {
        header("Location: payment.php?error=Deletion failed");
    }
    exit();
} else {
    header("Location: payment.php?error=Invalid request");
    exit();
}
?>
