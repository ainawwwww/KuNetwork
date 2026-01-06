<?php
include '../db.php';
session_start();  

if(isset($_GET['id']))
{

    $query="SELECT * FROM `categories_level3` WHERE `id` = ".$_GET['id'];
    if($result=mysqli_query($conn,$query)) {
        $row=mysqli_fetch_assoc($result);
      if($row['product_filter_sidebar_status']==1)
      {
        $upquery="UPDATE `categories_level3` SET `product_filter_sidebar_status`=0 WHERE `id`=".$_GET['id'];

      }
      else{
        $upquery="UPDATE `categories_level3` SET `product_filter_sidebar_status`=1 WHERE `id`=".$_GET['id'];

      }
      $result=mysqli_query($conn,$upquery);
      echo "<script>window.location='../categorylevelthree.php';</script>";
  }
}
  else{
      echo "error";
  }
    

?>