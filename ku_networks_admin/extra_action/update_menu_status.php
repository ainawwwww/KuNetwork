<?php
include '../db.php';
session_start();  

if(isset($_GET['id']))
{

    $query="SELECT * FROM `categories_level1` WHERE `cat_id` = ".$_GET['id'];
    if($result=mysqli_query($conn,$query)) {
        $row=mysqli_fetch_assoc($result);
      if($row['menu_status']==1)
      {
        $upquery="UPDATE `categories_level1` SET `menu_status`=0 WHERE `cat_id`=".$_GET['id'];

      }
      else{
        $upquery="UPDATE `categories_level1` SET `menu_status`=1 WHERE `cat_id`=".$_GET['id'];

      }
      $result=mysqli_query($conn,$upquery);
      echo "<script>window.location='../categorylevelone.php';</script>";
  }
}
  else{
      echo "error";
  }
    

?>