<?php


include 'db.php';
include 'check_login.php';

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $country_name = mysqli_real_escape_string($conn, $_POST['country_name']);
    $status = (int)$_POST['status'];

    if (!empty($country_name)) {
        $query = "UPDATE countries SET name='$country_name', status='$status' WHERE id=$id";
        if (mysqli_query($conn, $query)) {
            header("Location: countries.php?success=Country updated successfully");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Country name cannot be empty.";
    }
} else {
    header("Location: countries.php");
    exit();
}
?>
