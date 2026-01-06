<?php

include 'db.php';
include 'check_login.php';

if (isset($_POST['delete'])) {
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("DELETE FROM monthly_tournament_competition WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: monthly_tournament_competition.php?success=Tournament deleted successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
} else {
    header("Location: monthly_tournament_competition.php?error=Invalid request");
    exit();
}
?>