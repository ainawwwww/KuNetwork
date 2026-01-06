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
$upquery="UPDATE `categories` SET `image`='$file' WHERE `cat_id` = '$id'";
    if(mysqli_query($conn,$upquery)) {
      $_SESSION['message0004'] = " image updated successfully";
      header("location:../all_cat.php");
     
      exit;
  }
  else{
      echo "error";
  }
    

}

?>