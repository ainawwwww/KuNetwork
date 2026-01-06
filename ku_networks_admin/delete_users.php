<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Check if user exists before trying to delete
    $checkUserQuery = "SELECT COUNT(*) AS count FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $checkUserQuery);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
  
        mysqli_begin_transaction($conn);

        try {
            $deletePaymentQuery = "DELETE FROM payment WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $deletePaymentQuery);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

          
            $deleteUserQuery = "DELETE FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $deleteUserQuery);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);

            header("Location: users.php?success=User deleted successfully");
        } catch (Exception $e) {
      
            mysqli_rollback($conn);
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>User does not exist.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>User ID is missing.</div>";
}
?>