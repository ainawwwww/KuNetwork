<?php
include '../db.php';
session_start();
if(isset($_GET['btn_add'])){
 $category=$_GET['category'];
 
 print_r($_GET);

    header("location:../show_sidebar_pro.php?cat_id=$category");
}

?>