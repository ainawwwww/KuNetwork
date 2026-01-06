<?php
include '../db.php';

if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    $sql="DELETE FROM `best_selling` WHERE `b_id`='$id'";
    if(mysqli_query($conn,$sql)) {
    header("location:../show_best_pro.php");
    }
    else{
        echo "error";
    }
}
?>