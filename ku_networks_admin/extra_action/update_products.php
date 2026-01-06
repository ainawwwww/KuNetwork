<?php
include '../db.php';
session_start();  

if(isset($_POST['update'])){
    // Escape special characters to prevent SQL errors and SQL injection
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $discount_price = mysqli_real_escape_string($conn, $_POST['discount_price']);
    $p_badge = mysqli_real_escape_string($conn, $_POST['p_badge']);
    $p_stock = mysqli_real_escape_string($conn, $_POST['p_stock']);
    $p_description = mysqli_real_escape_string($conn, $_POST['Pro_description']);
    $Pro_detailed_description = mysqli_real_escape_string($conn, $_POST['Pro_detailed_description']);
    $id = mysqli_real_escape_string($conn, $_POST['lastid']);

    // Update query
    $upquery = "UPDATE `products` SET 
                `product_name`='$p_name', 
                `product_price`='$p_price', 
                `discount_price`='$discount_price',
                `stock`='$p_stock', 
                `Badge`='$p_badge', 
                `product_discription`='$p_description', 
                `product_detail_description`='$Pro_detailed_description' 
                WHERE `id_p` = '$id'";

    // Execute the query
    if(mysqli_query($conn, $upquery)) {
        header("location: ../all_product.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>