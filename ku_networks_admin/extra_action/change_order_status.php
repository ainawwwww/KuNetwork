<?php
include '../db.php';

    $id=$_POST['id'];
    $selected_status=$_POST['selected_status'];

$upquery="UPDATE `orders` SET `status`='$selected_status',`delivery_date`=CURDATE() WHERE `id`= $id";
    if(mysqli_query($conn,$upquery)) {
      echo 1;
     
      exit;
  }
  else{
      echo "error";
  }
    



?>