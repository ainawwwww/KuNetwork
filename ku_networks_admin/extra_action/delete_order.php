<?php
include '../db.php';


if(isset($_POST['delete'])) {
    $id=$_POST['id'];
    $sql="DELETE FROM `custom_order` WHERE `id`='$id'";
    echo $sql;
    if(mysqli_query($conn,$sql)) {
    echo "<script>
                             window.location='../order_history.php';
                             </script>";
    }
    else{
        echo "error";
    }
}
?>