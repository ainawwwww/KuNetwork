<?php

include 'db.php';
include 'check_login.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $team_name = $_POST['team_name'];
    $level_2 = $_POST['level_2'];
    $level_3 = $_POST['level_3'];
    $level_4 = $_POST['level_4'];
    $level_5 = $_POST['level_5'];
    $level_6 = $_POST['level_6'];

    $stmt = $conn->prepare("UPDATE team_earning_commission SET team_name=?, level_2=?, level_3=?, level_4=?, level_5=?, level_6=? WHERE id=?");
    $stmt->bind_param("ssssssi", $team_name, $level_2, $level_3, $level_4, $level_5, $level_6, $id);

    if ($stmt->execute()) {
        header("Location: team_earning.php?success=Team updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "Invalid Request!";
}
?>