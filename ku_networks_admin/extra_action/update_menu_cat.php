<?php
include '../db.php';
session_start();  

if(isset($_POST['update'])){
 
     $menu_cat=$_POST['menu_cat'];
     
     $id=$_POST['lastid'];
   


$upquery="UPDATE `menu_category` SET `cat_id`='$menu_cat' WHERE `menu_cat_id` = $id";
    if(mysqli_query($conn,$upquery)) {
      $_SESSION['message006'] = "Category updated successfully";
      header("location:../show_menu_cat.php");
     
      exit;
  }
  else{
      echo "error";
  }
    

}

?>