<?php
include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM monthly_salary_bonus WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: monthly_salary_bonus.php?success=Bonus deleted successfully");
        exit();
    } else {
        $stmt->close();
        header("Location: monthly_salary_bonus.php?error=Error deleting bonus");
        exit();
    }
} else {
    header("Location: monthly_salary_bonus.php?error=Invalid request");
    exit();
}
?>