<?php
include '../db.php';

if(isset($_POST['add_pro'])){

    $products=$_POST['pro'];


    $insert_query="INSERT INTO `best_selling`(`product_id`) VALUES ('$products')";
   
    if(mysqli_query($conn,$insert_query)) {
        echo "<script>window.location='../best_selling.php';</script>";
       
        exit;
    }
    else{
        echo "error";
    }
      
  

}

?>