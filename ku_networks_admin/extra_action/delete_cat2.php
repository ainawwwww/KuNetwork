<?php
include '../db.php';
session_start();

if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $sql="DELETE FROM `categories_level2` WHERE `id`=$id";
    //echo $sql;
    if(mysqli_query($conn,$sql)) {
        echo "<script>window.location='../categoryleveltwo.php';</script>";
    }
    else{
        echo "error";
    }
}
?>