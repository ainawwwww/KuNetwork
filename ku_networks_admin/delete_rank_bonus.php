<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM rank_bonuses WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: rank_bonuses.php?success=Rank deleted successfully");
    } else {
        header("Location: rank_bonuses.php?error=Error deleting Rank");
    }
    exit();
} else {
    header("Location: rank_bonuses.php?error=Invalid request");
    exit();
}

?>