<?php
include '../db.php';

if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    
        $fetch_query_cat="DELETE FROM `admin` WHERE `id`=".$id;
    
    if(mysqli_query($conn,$fetch_query_cat)) {
    header("location:../show_subadmin.php");
    }
    else{
        echo "error";
    }
}
?>