<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = mysqli_real_escape_string($conn, $_POST['username_or_email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE user_id = '$username_or_email' OR email = '$username_or_email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];         
            $_SESSION['username'] = $user['user_id']; 
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header("Location: account.php"); 
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = 'loginInterface.php';</script>";
        }
    } else {
        echo "<script>alert('User not found. Please try again.'); window.location.href = 'loginInterface.php';</script>";
    }
}
?>
