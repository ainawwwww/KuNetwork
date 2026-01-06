<?php
include '../db.php';


if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    
    $sql="
    DELETE FROM `product_images` WHERE `product_id`=".$id."; DELETE FROM `category_assign_to_product` WHERE `pro_id`=".$id."; DELETE FROM `best_selling` WHERE `product_id`=".$id."; DELETE FROM `product_clicks` WHERE `product_id`=".$id."; DELETE FROM `quantity_products` WHERE `products`=".$id."; DELETE FROM `top_products` WHERE `product_id`=".$id."; DELETE FROM `giftbag_collection_products` WHERE `product_id`=".$id."; 
DELETE FROM `packaging_products` WHERE `product_id`=".$id."; 
DELETE FROM `printing_products` WHERE `product_id`=".$id."; 
DELETE FROM `signage_products` WHERE `product_id`=".$id."; 
DELETE FROM `tshirt_collection_products` WHERE `product_id`=".$id."; 
    DELETE FROM `products` WHERE `id_p`=".$id; 
    //echo $sql;
    if(mysqli_multi_query($conn,$sql)) {
    header("location:../all_product.php");
    }
    else{
        echo "error";
    }
}
?>