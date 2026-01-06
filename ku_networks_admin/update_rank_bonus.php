<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $rank_name = $_POST['rank_name'];
    $self_invest = $_POST['self_invest'];
    $team_business = $_POST['team_business'];
    $self_bonus = $_POST['self_bonus'];
    $event_bonus = $_POST['event_bonus'];

    $stmt = $conn->prepare("UPDATE rank_bonuses SET rank_name = ?, self_invest = ?, team_business = ?, self_bonus = ?, event_bonus = ? WHERE id = ?");
    $stmt->bind_param("sddddi", $rank_name, $self_invest, $team_business, $self_bonus, $event_bonus, $id);

    if ($stmt->execute()) {
        header("Location: rank_bonuses.php?success=Bonus updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>
