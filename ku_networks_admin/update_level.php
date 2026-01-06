<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $min_amount = (float)$_POST['minimum_amount'];
    $max_amount = (float)$_POST['maximum_amount'];
    $min_profit = (float)$_POST['minimum_profit'];
    $max_profit = (float)$_POST['maximum_profit'];

    if (!empty($name)) {
        $query = "UPDATE levels SET 
                  name='$name', 
                  minimum_amount='$min_amount', 
                  maximum_amount='$max_amount', 
                  minimum_profit='$min_profit', 
                  maximum_profit='$max_profit'
                  WHERE id=$id";

        if (mysqli_query($conn, $query)) {
            header("Location: levels.php?success=Level updated successfully");
            exit();
        } else {
            echo "Error updating: " . mysqli_error($conn);
        }
    } else {
        echo "Name field cannot be empty.";
    }
} else {
    header("Location: levels.php");
    exit();
}
?>
