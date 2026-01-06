<?php
// update_transferfee.php
include 'db.php';
include 'check_login.php';

// Optional: enable mysqli exceptions for clearer error handling in dev
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: transfer_fee_management.php");
    exit();
}

// Collect and validate inputs
$sno = isset($_POST['sno']) ? intval($_POST['sno']) : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$fee_input = isset($_POST['fee']) ? $_POST['fee'] : '';

if ($sno <= 0) {
    header("Location: transfer_fee_management.php?error=" . urlencode("Invalid fee ID."));
    exit();
}

if ($name === '' || $fee_input === '') {
    header("Location: edit_transferfee.php?id={$sno}&error=" . urlencode("Please provide both name and fee."));
    exit();
}

// Validate numeric input
if (!is_numeric($fee_input)) {
    header("Location: edit_transferfee.php?id={$sno}&error=" . urlencode("Fee must be a valid number."));
    exit();
}

$fee_percent_input = floatval($fee_input);   // admin-entered percent, e.g. 5 means 5%

if ($fee_percent_input < 0) {
    header("Location: edit_transferfee.php?id={$sno}&error=" . urlencode("Fee must be non-negative."));
    exit();
}

if ($fee_percent_input > 100) {
    // optional: disallow >100%
    header("Location: edit_transferfee.php?id={$sno}&error=" . urlencode("Fee cannot exceed 100%."));
    exit();
}

// convert percent to decimal for storage
$fee = $fee_percent_input / 100.0;

// clamp just in case
if ($fee < 0) $fee = 0;
if ($fee > 1) $fee = 1;

try {
    // Ensure record exists
    $stmt = $conn->prepare("SELECT sno FROM transferfee WHERE sno = ?");
    $stmt->bind_param("i", $sno);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->fetch_assoc();
    $stmt->close();

    if (!$exists) {
        header("Location: transfer_fee_management.php?error=" . urlencode("Fee record not found."));
        exit();
    }

    // Update the record
    $stmt = $conn->prepare("UPDATE transferfee SET name = ?, fee = ? WHERE sno = ?");
    $stmt->bind_param("sdi", $name, $fee, $sno);
    $stmt->execute();
    $stmt->close();

    header("Location: transfer_fee_management.php?success=" . urlencode("Fee updated successfully."));
    exit();

} catch (Exception $e) {
    // For dev you may want to log $e->getMessage()
    header("Location: edit_transferfee.php?id={$sno}&error=" . urlencode("DB Error: " . $e->getMessage()));
    exit();
}