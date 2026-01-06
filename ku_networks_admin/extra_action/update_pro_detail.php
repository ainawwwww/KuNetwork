<?php
include '../db.php'; 

if(isset($_POST['update'])){
  $id=$_POST['lastid'];
  $pro_id=$_POST['pro_id'];
    $quantity=$_POST['quantity'];
    $size=$_POST['size'];
    $price=$_POST['price'];
    $urgent_price=$_POST['urgent_price'];
    $time=$_POST['time'];
    $urgent_time=$_POST['urgent_time'];

    
    $upquery="UPDATE `quantity_products` SET `quantity`='$quantity',`size`='$size',`price`='$price',`time`='$time',`urgent_price`='$urgent_price',`urgent_time`='$urgent_time' WHERE `id`='$id'";
    if(mysqli_query($conn,$upquery)) {
      echo "<script>
                             window.location='../product_detail.php?id=$pro_id';
                             </script>"; 
     
      exit;
  }
  else{
      echo "error";
  }
    

}

?>