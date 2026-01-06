<?php
session_start();
include '../db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $query = "SELECT * FROM `admin` WHERE `email` = '$email'";
    $sql = mysqli_query($conn, $query);

    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);

        if ($password== $row['password']) {

            $_SESSION['A_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            header("location:../index.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.replace('../pages/examples/login.php');</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.replace('../pages/examples/login.php');</script>";
    }
}
?>
