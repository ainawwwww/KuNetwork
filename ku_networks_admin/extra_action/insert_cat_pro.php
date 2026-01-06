<?php
include '../db.php';
session_start();

if(isset($_POST['products'])){

    $products=$_POST['products'];

    $category=$_POST['category'];



    $insert_query="INSERT INTO `cat_pro`(`category_id`,`product_id`) VALUES ('$category','$products')";
   
    if(mysqli_query($conn,$insert_query)) {
        $_SESSION['message00122'] = "Product Insert successfully";
        header("location:../add_sidebar_pro.php");
       
        exit;
    }
    else{
        echo "error";
    }
      
  

}

?>