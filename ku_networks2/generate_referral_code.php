<?php

include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "User not logged in.";
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);

$checkQuery = "SELECT * FROM user_referal_codes WHERE user_id = '$user_id' AND used_status = 0 AND expiration > NOW()";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    $existing = mysqli_fetch_assoc($checkResult);
    $full_link = "http://kunetworks.com/ku_networks2/registerinterface.php?ref=" . $existing['referral_code'];
    echo $full_link;
    exit();
}

// Generate new code
$referral_code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 12);
$expiration = date('Y-m-d H:i:s', strtotime('+24 hours'));

$query = "INSERT INTO user_referal_codes (user_id, referral_code, expiration) VALUES ('$user_id', '$referral_code', '$expiration')";

if (mysqli_query($conn, $query)) {
    $full_link = "http://kunetworks.com/ku_networks2/registerinterface.php?ref=" . $referral_code;
    echo $full_link;
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
