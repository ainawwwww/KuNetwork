<?php
include '../db.php';
session_start();

if(isset($_GET['id'])) {
    $id=$_GET['id'];
    // Check if the category has child rows in categories_level2
    $checkQuery = "SELECT COUNT(*) AS child_count FROM categories_level2 WHERE parent_catid = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['child_count'] > 0) {
        // Category has child rows
        echo "<script>
        alert('Cannot delete this category because it has child categories.');
        window.location='../categorylevelone.php';</script>";
    }
    else{
        $sql="DELETE FROM `categories_level1` WHERE `cat_id`=$id";
        //echo $sql;
        if(mysqli_query($conn,$sql)) {
            echo "<script>window.location='../categorylevelone.php';</script>";
        }
        else{
            echo "error";
        }
    }
    
}
?>