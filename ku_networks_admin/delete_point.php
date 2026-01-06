<?php
include 'db.php';
include 'check_login.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM points_settings WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: points_settings.php?success=Point deleted successfully");
exit;
?>
