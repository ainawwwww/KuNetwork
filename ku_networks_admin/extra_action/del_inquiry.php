<?php
include '../db.php';
session_start();

if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    
        $fetch_query_cat="SELECT * FROM `inquiries` WHERE `id`=".$id;
    $sql_cat=mysqli_query($conn,$fetch_query_cat);
    $row_cat=mysqli_fetch_assoc($sql_cat);
    
    $targetDir = "../../inquiry_files/";
                $fileName=$row_cat['file'];
                $targetFilePath = $targetDir.$fileName;
                echo $targetFilePath;
                unlink($targetFilePath);
    $sql="DELETE FROM `inquiries` WHERE `id`=".$id;
    if(mysqli_query($conn,$sql)) {
        $_SESSION['message009'] = "Inquiry Deleted successfully";
    header("location:../inquiries.php");
    }
    else{
        echo "error";
    }
}
?>