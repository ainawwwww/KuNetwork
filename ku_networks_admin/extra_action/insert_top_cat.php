<?php
include '../db.php';
session_start();
if(isset($_POST['add_top_cat'])){

$cat=$_POST['category'];


$query="INSERT INTO `top_category`( `cat_id`) VALUES ('$cat')";
if($sql=mysqli_query($conn,$query)){
        header("location:../add_top_cat.php");
       
        exit;
    }
    else{
        echo "error";
    }
      

}
?>