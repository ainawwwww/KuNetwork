<?php

include 'db.php';
include 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $plan_name = $_POST['plan_name'];
    $member_detail = $_POST['member_detail'];
    $withdraw_fee = $_POST['withdraw_fee'];
    $customer_support = $_POST['customer_support'];
    $withdraw_processing_time = $_POST['withdraw_processing_time'];

    $stmt = $conn->prepare("UPDATE membership SET plan_name=?, member_detail=?, withdraw_fee=?, customer_support=?, withdraw_processing_time=? WHERE id=?");
    $stmt->bind_param("sssssi", $plan_name, $member_detail, $withdraw_fee, $customer_support, $withdraw_processing_time, $id);

    if ($stmt->execute()) {
        header("Location: membership.php?success=Plan updated successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>
