<?php

include 'db.php';
include 'check_login.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "DELETE FROM countries WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: countries.php?success=Country deleted successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: countries.php");
    exit();
}
?>