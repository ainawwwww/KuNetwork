<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Update weekly bonus claim status and last claimed time
$stmt = $conn->prepare("UPDATE weekly_bonus_claims SET bonus_claimed = 1, last_claimed_at = NOW() WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Optionally, add bonus amount to user's wallet here

// Redirect back with success message
header("Location: account.php?weekly_bonus_claimed=1");

exit();
?>