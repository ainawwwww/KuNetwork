<?php
include '../db.php';
session_start();

if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $pro_id=$_GET['pro_id'];

    $sql="DELETE FROM `quantity_products` WHERE `id`='$id'";
    if(mysqli_query($conn,$sql)) {
    echo "<script>
                            window.location='../product_detail.php?id=$pro_id';
                            </script>"; 
    }
    else{
        echo "error";
    }
}
?>