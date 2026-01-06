<?php
include '../db.php';
session_start();

if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    $sql="DELETE FROM `top_products` WHERE `id`='$id'";
    if(mysqli_query($conn,$sql)) {
        $_SESSION['message009'] = "Product Deleted successfully";
    header("location:../show_top_pro.php");
    }
    else{
        echo "error";
    }
}
?>