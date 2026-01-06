<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off direct error display for AJAX, log them instead
ini_set('log_errors', 1);
// ini_set('error_log', '/path/to/your/php-error.log'); // Set a log file path


include 'config.php'; // Your database connection

header('Content-Type: application/json');

$response = ['available' => false, 'message' => 'Invalid request.'];

if (isset($_GET['field']) && isset($_GET['value'])) {
    $field = trim($_GET['field']);
    $value = trim($_GET['value']);

    if (empty($value)) {
        $response = ['available' => false, 'message' => ucfirst($field) . ' cannot be empty.'];
        echo json_encode($response);
        exit();
    }

    $columnNameInDb = '';
    $defaultTakenMessage = '';

    switch ($field) {
        case 'username':
            $columnNameInDb = 'user_id'; // As per your users table schema for username
            $defaultTakenMessage = 'Username is already taken.';
            if (strlen($value) < 3) {
                $response = ['available' => false, 'message' => 'Username must be at least 3 characters.'];
                echo json_encode($response);
                exit();
            }
            break;
        case 'email':
            $columnNameInDb = 'email';
            $defaultTakenMessage = 'This email address is already registered.';
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $response = ['available' => false, 'message' => 'Invalid email format.'];
                echo json_encode($response);
                exit();
            }
            break;
        default:
            $response = ['available' => false, 'message' => 'Invalid field for availability check.'];
            echo json_encode($response);
            exit();
    }

    try {
        $stmt = $conn->prepare("SELECT $columnNameInDb FROM users WHERE $columnNameInDb = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $response = ['available' => true, 'message' => ucfirst($field) . ' is available.'];
        } else {
            $response = ['available' => false, 'message' => $defaultTakenMessage];
        }
        $stmt->close();

    } catch (Exception $e) {
        error_log("Error in check_availability.php: " . $e->getMessage());
        $response = ['available' => false, 'message' => 'Server error checking availability. Please try again.'];
    }

} else {
    $response = ['available' => false, 'message' => 'Required parameters missing.'];
}

$conn->close();
echo json_encode($response);
exit();
?>