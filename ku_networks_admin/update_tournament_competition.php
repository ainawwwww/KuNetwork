<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tournament_range = $_POST['tournament_range'];
    $entry_fee = $_POST['entry_fee'];
    $team_business_min = $_POST['team_business_min'];
    $first_prize = $_POST['first_prize'];
    $second_prize = $_POST['second_prize'];
    $third_prize = $_POST['third_prize'];

    $stmt = $conn->prepare("UPDATE monthly_tournament_competition SET tournament_range = ?, entry_fee = ?, team_business_min = ?, first_prize = ?, second_prize = ?, third_prize = ? WHERE id = ?");

    $stmt->bind_param("sddddii", $tournament_range, $entry_fee, $team_business_min, $first_prize, $second_prize, $third_prize, $id);

    if ($stmt->execute()) {
        header("Location: monthly_tournament_competition.php?success=Tournament updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>
