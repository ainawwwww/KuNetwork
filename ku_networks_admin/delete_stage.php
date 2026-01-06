<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stage_id'])) {

    // Get stage_id correctly
    
    $stage_id = (int)$_POST['stage_id'];

    // Correct delete query
    $stmt = $conn->prepare("DELETE FROM stages WHERE stage_id = ?");
    $stmt->bind_param("i", $stage_id);

    if ($stmt->execute()) {
        header("Location: stages.php?success=Stage deleted successfully");
    } else {
        header("Location: stages.php?error=Deletion failed");
    }

    exit();
} else {
    header("Location: stages.php?error=Invalid request");
    exit();
}
?>
