<?php
include '../db.php';

if(isset($_POST['add_pro'])){

    $products=$_POST['pro'];


    $insert_query="INSERT INTO `top_products`(`product_id`) VALUES ('$products')";
   
    if(mysqli_query($conn,$insert_query)) {
        echo "<script>window.location='../add_top_pro.php';</script>";
       
        exit;
    }
    else{
        echo "error";
    }
      
  

}

?>
