<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php");
    exit();
}

$loggedInUserId = $_SESSION['user_id'];


// 1. Get logged-in user's info
$loggedInUserQuery = "SELECT * FROM users WHERE id = '$loggedInUserId'";
$loggedInUserResult = mysqli_query($conn, $loggedInUserQuery);
$loggedInUserData = mysqli_fetch_assoc($loggedInUserResult);

// 2. Get referrer (who referred the logged-in user)
// Assuming referal_teams has user_id (referred) and referral_userid (who referred)
 $referrerQuery = "SELECT u.* FROM referal_teams rt 
                  JOIN users u ON rt.referral_userid = u.id
                  WHERE rt.user_id = '$loggedInUserId' LIMIT 1";
$referrerResult = mysqli_query($conn, $referrerQuery);
     $referrerData = mysqli_fetch_assoc($referrerResult);
 
// 3. Get all users that were referred by this user
$myReferralsQuery = "
    SELECT u.id, u.name, u.email, u.phone, rt.referral_code, rt.created_at
    FROM referal_teams rt
    JOIN users u ON u.id = rt.referral_userid
    WHERE rt.user_id = '$loggedInUserId'
    ORDER BY rt.created_at DESC
";
$myReferralsResult = mysqli_query($conn, $myReferralsQuery);
$referralCount = mysqli_num_rows($myReferralsResult);
$dataref = mysqli_fetch_assoc($myReferralsResult);
print_r($referrerData);
?>
