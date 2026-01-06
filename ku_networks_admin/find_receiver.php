<?php
include 'db.php';
include 'check_login.php';

$query = $_GET['query'];
$query = mysqli_real_escape_string($conn, $query);

$matched = [];

$user_result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$query' OR user_id = '$query'");
if ($row = mysqli_fetch_assoc($user_result)) {
    $receiver_id = $row['id'];

    $referral_check = mysqli_query($conn, "SELECT * FROM referal_teams WHERE referral_userid = '$receiver_id'");
    if (mysqli_num_rows($referral_check)) {
        $matched[] = [
            "id" => $row['id'],
            "name" => $row['name'],
            "user_id" => $row['user_id']
        ];
    }
}

echo json_encode($matched);
