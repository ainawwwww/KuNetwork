<?php

include 'db.php';
include 'check_login.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $points = $_POST['points'];
    $bonus = $_POST['bonus'];
    $claimable = $_POST['claimable'];

    $stmt = $conn->prepare("UPDATE points_earning SET points = ?, bonus = ?, claimable = ? WHERE id = ?");
    $stmt->bind_param("idsi", $points, $bonus, $claimable, $id);

    if ($stmt->execute()) {
        header("Location: points_earning.php?success=Points updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

?>