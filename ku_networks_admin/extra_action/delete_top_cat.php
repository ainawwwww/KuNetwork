<?php
include '../db.php';
session_start();

if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    $sql="DELETE FROM `top_category` WHERE `id`='$id'";
    if(mysqli_query($conn,$sql)) {
        $_SESSION['message0001'] = "Menu Category Deleted successfully";
    header("location:../show_top_cat.php");
    }
    else{
        echo "error";
    }
}
?>