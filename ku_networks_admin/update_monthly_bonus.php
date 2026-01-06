<?php

include 'db.php';
include 'check_login.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $level_id = $_POST['level_id'];
    $team_x = $_POST['team_x'];
    $team_yz = $_POST['team_yz'];
    $bonus_amount = $_POST['bonus_amount'];
    $eligibility_criteria = $_POST['eligibility_criteria'];

    $query = "UPDATE monthly_salary_bonus SET 
                level_id = ?, 
                team_x = ?, 
                team_yz = ?, 
                bonus_amount = ?, 
                eligibility_criteria = ? 
              WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissi", $level_id, $team_x, $team_yz, $bonus_amount, $eligibility_criteria, $id);

    if ($stmt->execute()) {
        header("Location: monthly_salary_bonus.php?success=Bonus updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>