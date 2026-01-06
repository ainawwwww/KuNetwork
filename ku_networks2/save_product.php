<?php
include 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data);

if (!$data || !isset($data->product_name) || !isset($data->product_price)) {
    echo json_encode(['success' => false, 'error' => 'Invalid product data received.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_name = trim($data->product_name);

$product_price = floatval($data->product_price);

if (empty($product_name)) {
    echo json_encode(['success' => false, 'error' => 'Product name cannot be empty.']);
    exit();
}
if ($product_price < 0) { 
    echo json_encode(['success' => false, 'error' => 'Product price cannot be negative.']);
    exit();
}

if (!$conn) {

    echo json_encode(['success' => false, 'error' => 'Database connection error. Please try again later.']);
    exit();
}


$time_window_seconds = 30; 
$check_stmt = $conn->prepare("SELECT id FROM product_buy 
                              WHERE user_id = ? 
                                AND product_name = ? 
                                AND ABS(product_price - ?) < 0.001 
                                AND purchase_date >= NOW() - INTERVAL ? SECOND
                              LIMIT 1");

if ($check_stmt === false) {
    error_log("Prepare failed for duplicate check: " . $conn->error); 
    echo json_encode(['success' => false, 'error' => 'Database operation failed. Please try again. [Code: D1]']);
    exit();
}

$check_stmt->bind_param("isdi", $user_id, $product_name, $product_price, $time_window_seconds);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $check_stmt->close();

    echo json_encode(['success' => true, 'message' => 'Purchase already processed or request is a duplicate.', 'duplicate' => true]);

    exit();
}
$check_stmt->close();



$stmt = $conn->prepare("INSERT INTO product_buy (user_id, product_name, product_price, purchase_date) VALUES (?, ?, ?, NOW())");

if ($stmt === false) {
    error_log("Prepare failed for insert: " . $conn->error); 
    echo json_encode(['success' => false, 'error' => 'Database operation failed. Please try again. [Code: P1]']);
    exit();
}

$stmt->bind_param("isd", $user_id, $product_name, $product_price);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product purchased successfully!']);
} else {
    error_log("Execute failed for insert: " . $stmt->error); 
    echo json_encode(['success' => false, 'error' => 'Failed to save product. Please try again. [Code: E1]']);
}

$stmt->close();
$conn->close();
?>