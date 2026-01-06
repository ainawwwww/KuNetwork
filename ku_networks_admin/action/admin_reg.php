<?php
include '../db.php';

if (isset($_POST['register'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $role = 1;


    $email_query = "SELECT * FROM `admin` WHERE `email` = '$email'";
    $email_sql = mysqli_query($conn, $email_query);
    $email_count = mysqli_num_rows($email_sql);

    if ($email_count > 0) {
        echo "<script>alert('Email already exists'); window.location.replace('../pages/examples/register.php');</script>";
    } else {
     
        $query = "INSERT INTO `admin`(`fname`, `lname`, `email`, `password`, `role`) 
                  VALUES ('$fname', '$lname', '$email', '$password', '$role')";

        $sql = mysqli_query($conn, $query);

        if ($sql) {
            echo "<script>alert('Registration successful'); window.location.replace('../pages/examples/login.php');</script>";
        } else {
            echo "<script>alert('Registration failed'); window.location.replace('../pages/examples/register.php');</script>";
        }
    }
}
?>




