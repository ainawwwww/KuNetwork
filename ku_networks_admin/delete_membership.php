<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM membership WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: membership.php?success=Plan deleted successfully");
        exit();
    } else {
        $stmt->close();
        header("Location: membership.php?error=Error deleting Plan");
        exit();
    }
} else {
    header("Location: membership.php?error=Invalid request");
    exit();
}
?>