<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Autoload phpmailer
include 'db_connect.php';

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $code = rand(100000, 999999);

    $stmt = $conn->prepare("UPDATE users SET email_verification_code = ? WHERE email = ?");
    $stmt->bind_param("ss", $code, $email);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kashafaptech747@gmail.com'; 
        $mail->Password   = 'pnyc uyrx zisc jwac';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('yourgmail@gmail.com', 'Ku Networks');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body    = 'Your verification code is: <b>' . $code . '</b>';

        $mail->send();
        echo 'sent';
    } catch (Exception $e) {
        echo 'fail';
    }
}
?>