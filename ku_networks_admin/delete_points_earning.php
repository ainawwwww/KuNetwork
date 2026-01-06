<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM points_earning WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: points_earning.php?success=Points Deleted successfully");
    } else {
        header("Location: points_earning.php?error=Deletion failed");
    }
    exit();
}else {
    header("Location: points_earning.php?error=Invalid request");
    exit();
}
?>