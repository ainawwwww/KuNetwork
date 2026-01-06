<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

$response_data = [];

if (!isset($_SESSION['user_id'])) {
    echo json_encode($response_data);
    exit;
}
$loggedInUserDbId = $_SESSION['user_id']; 

$query_param = isset($_GET['query']) ? trim($_GET['query']) : '';
if (empty($query_param)) {
    echo json_encode($response_data);
    exit;
}


if (!isset($conn) || !$conn || mysqli_connect_errno()) {

    echo json_encode($response_data);
    exit;
}

$escaped_query = mysqli_real_escape_string($conn, $query_param);
if ($escaped_query === false) {

    echo json_encode($response_data);
    exit;
}


$loggedInUserDbId_safe = mysqli_real_escape_string($conn, $loggedInUserDbId);




$sql = "
    SELECT u.id, u.name, u.user_id AS string_user_id -- Aliasing u.user_id to avoid confusion if you use 'user_id' for the JSON key later
    FROM users u
    INNER JOIN referal_teams r ON u.id = r.referral_userid
    WHERE (u.email = '$escaped_query' OR u.user_id = '$escaped_query')
    AND r.user_id = '$loggedInUserDbId_safe'  -- CORRECTED: 'user_id' is the column for the referrer in referal_teams
";

$user_result = mysqli_query($conn, $sql);

if ($user_result === false) {

    echo json_encode($response_data);
    exit;
}

if ($row = mysqli_fetch_assoc($user_result)) {
    $response_data[] = [
        "id" => $row['id'],                
        "name" => $row['name'],
        "user_id" => $row['string_user_id'] 
    ];
}

echo json_encode($response_data);
exit;
?>