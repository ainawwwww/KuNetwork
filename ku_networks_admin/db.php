<?php
//  $host='localhost';
//  $name='root'; 
//  $password='';
//  $db='ku_networks';
 
  $host = "localhost";
$name = "u537364093_kunetworks"; 
$password = "kunetworks_#Network123"; 
$db = "u537364093_kunetworks_db"; 
 $conn=mysqli_connect($host,$name,$password,$db);
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
?>
