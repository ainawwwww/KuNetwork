<?php

include 'config.php'; 

if(isset($_POST['email']) && isset($_POST['code'])){
    $email = $_POST['email'];
    $code = $_POST['code'];

    $stmt = $conn->prepare("SELECT email_verification_code FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($storedCode);
    $stmt->fetch();
    $stmt->close();

    if($storedCode == $code){

        $stmt = $conn->prepare("UPDATE users SET email_verified_at = NOW() WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        echo 'verified';
    } else {
        echo 'invalid';
    }
}
?>