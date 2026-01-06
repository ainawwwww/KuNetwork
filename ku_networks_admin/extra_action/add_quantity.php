<?php
include '../db.php';
session_start();

if(isset($_POST['add'])){

   
     $products=$_POST['pro_id'];
     $size=$_POST['size'];
     $quantity=$_POST['quantity'];
   $price=$_POST['price'];
   
   $urgent_price=$_POST['urgent_price'];
   $time=$_POST['time'];
   $urgent_time=$_POST['urgent_time'];
  
    $insert_query="INSERT INTO `quantity_products`(`quantity`, `products`, `size`, `price`, `time`, `urgent_price`, `urgent_time`) VALUES ('$quantity','$products','$size','$price','$time','$urgent_price','$urgent_time')";
    
   
    if(mysqli_query($conn,$insert_query)) {
        header("location:../add_pro_detail.php?id=$products");
       
        exit;
    }
    else{
        echo mysqli_error($conn);
    }
      
  

}

?>