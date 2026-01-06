<?php
include '../db.php';



if(isset($_POST['register'])){
    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);
  


    $email_query="SELECT * FROM `admin` WHERE  `email` = '$email'";
     $email_sql=mysqli_query($conn,$email_query);
     $email_count=mysqli_num_rows($email_sql);

if($email_count>0){

    echo "<script>alert('email already exist');
    window.location.replace('../pages/examples/register.php');</script>";
}else
{
   $query="INSERT INTO `admin`(`fname`, `lname`, `email`, `password`, `role`) VALUES ('$fname','$lname','$email','$password',2)";
    // print_r($query);
    
    $sql=mysqli_query($conn,$query);

    if($sql == true){
        echo "<script>window.location.replace('../pages/examples/login.php')</script>";
    }else{
        echo "<script>window.location.replace('../pages/examples/register.php')</script>";
    }
}
}  










?>