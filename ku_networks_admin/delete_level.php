<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);

    $query = "DELETE FROM levels WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: levels.php?success=Level deleted successfully");
        exit();
    } else {
        echo "Error deleting: " . mysqli_error($conn);
    }
} else {
    header("Location: levels.php");
    exit();
}
?>
