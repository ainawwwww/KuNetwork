<?php
include '../db.php';
session_start();
if(isset($_GET['btn_add'])){
 $category=$_GET['category'];
 $products=$_GET['products'];
 

    header("location:../product_detail.php?cat_id=$category&pro_id=$products");
}

?>