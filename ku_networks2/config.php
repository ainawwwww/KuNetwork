<?php
// $servername = "localhost";
// $username = "root"; 
// $password = ""; 
// $dbname = "ku_networks"; 

$servername = "localhost";
$username = "u537364093_kunetworks"; 
$password = "kunetworks_#Network123"; 
$dbname = "u537364093_kunetworks_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Force MySQL session timezone to UTC for consistent writes/reads
if ($conn instanceof mysqli) {
  mysqli_query($conn, "SET time_zone = '+00:00'");
}


?>