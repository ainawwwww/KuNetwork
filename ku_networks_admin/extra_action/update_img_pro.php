<?php
include '../db.php';
session_start();  

if(isset($_POST['up_image'])){
   
     $file = $_FILES['image']['name'];
    $tmp_file = $_FILES['image']['tmp_name'];

  

     move_uploaded_file($tmp_file,"../images/$file");

    $id=$_POST['lastid'];
    //  print_r( $id);
    //  exit;
$upquery="UPDATE `products` SET `pro_image`='$file' WHERE `id_p` = '$id'";
//echo $upquery;
    if(mysqli_query($conn,$upquery)){
     header("location:../all_product.php");
      exit;
  }
  else{
      echo "error";
  }
    

}

?>