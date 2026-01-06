<?php
include 'config.php';

$response = [
    'countries' => [],
];

// Countries
$countries_result = mysqli_query($conn, "SELECT id, name FROM countries WHERE status = 1 ORDER BY name ASC");
while($row = mysqli_fetch_assoc($countries_result)) {
    $response['countries'][] = $row;
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>