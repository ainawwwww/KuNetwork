<?php
include '../db.php';
session_start();  

if(isset($_POST['update'])){
    $p_name=$_POST['b_name'];

    $p_price=$_POST['b_price'];

    // $file = $_FILES['image']['name'];
    // $tmp_file = $_FILES['image']['tmp_name'];

    $p_description=$_POST['b_description'];

    // move_uploaded_file($tmp_file,"../images/$file");

    $id=$_POST['lastid'];
    //  print_r( $id);
    //  exit;
$upquery="UPDATE `best_selling` SET `b_name`=' $p_name',`b_price`=' $p_price',`b_description`=' $p_description' WHERE `b_id` = '$id'";
    if(mysqli_query($conn,$upquery)) {
      $_SESSION['message006'] = "Product updated successfully";
      header("location:../show_best_pro.php");
     
      exit;
  }
  else{
      echo "error";
  }
    

}

?>