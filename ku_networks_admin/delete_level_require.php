<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM level_upgrade_requirements WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: level_require.php?success=Requirement deleted successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid request or ID not provided</div>";
}
?>