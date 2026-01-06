<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $level_id = $_POST['level_id'];
    $team_x = $_POST['team_x'];
    $team_yz = $_POST['team_yz'];
    $required_balance = $_POST['required_balance'];

    $total_active_team = $team_x + $team_yz;

    $query = "UPDATE level_upgrade_requirements SET 
              level_id = ?, 
              team_x = ?, 
              team_yz = ?, 
              total_active_team = ?, 
              required_balance = ? 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiii", $level_id, $team_x, $team_yz, $total_active_team, $required_balance, $id);

    if ($stmt->execute()) {
        header("Location: level_require.php?success=Level Upgrade Requirement Updated Successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>