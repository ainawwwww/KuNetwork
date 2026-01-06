<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php?error=" . urlencode("Please log in to withdraw."));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $withdraw_amount = floatval($_POST['amount']);

    // 🔹 Fetch user's available wallet balance
    $stmt = $conn->prepare("SELECT available_balance, total_balance FROM user_wallets WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $wallet = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$wallet || $wallet['available_balance'] <= 0) {
        $error = "Your wallet is empty. You cannot submit a withdrawal request.";
    } else {
        $available_balance = $wallet['available_balance'];
        $total_balance = $wallet['total_balance'];

        // 🔹 Get user's enrolled package (which corresponds to membership.id)
        $pkg_stmt = $conn->prepare("SELECT package_id FROM enrolleduserspackages WHERE user_id = ?");
        $pkg_stmt->bind_param("i", $user_id);
        $pkg_stmt->execute();
        $pkg_result = $pkg_stmt->get_result();
        $pkg_stmt->close();

        if ($pkg_result->num_rows > 0) {
            // User has membership
            $pkg = $pkg_result->fetch_assoc();
            $package_id = $pkg['package_id'];

            // 🔹 Fetch withdraw fee from membership table
            $mem_stmt = $conn->prepare("SELECT withdraw_fee FROM membership WHERE id = ?");
            $mem_stmt->bind_param("i", $package_id);
            $mem_stmt->execute();
            $mem_result = $mem_stmt->get_result()->fetch_assoc();
            $mem_stmt->close();

            $fee_text = trim($mem_result['withdraw_fee']); // e.g., "3%" or "No Fee"
            $fee_percent = 0;

            if (strtolower($fee_text) !== 'no fee') {
                preg_match('/\d+(\.\d+)?/', $fee_text, $matches);
                $fee_percent = isset($matches[0]) ? floatval($matches[0]) : 0;
            }

            // 🔹 Validate withdraw request
            if ($withdraw_amount < 10) {
                
                $error = "Minimum withdrawal amount is $10.";
            } elseif ($withdraw_amount > 50) {
                $error = "You cannot withdraw more than $50 at a time.";
            } elseif ($withdraw_amount > $available_balance) {
                $error = "Insufficient balance for withdrawal.";
            } else {
                // 🔹 Calculate fee based on membership plan
                $fee_amount = ($withdraw_amount * $fee_percent) / 100;

                // 🔹 Deduct fee only from wallet (user gets full amount)
                $new_available_balance = $available_balance - $fee_amount;
                $new_total_balance = $total_balance - $fee_amount;

                // 🔹 Update wallet balances
                $update_stmt = $conn->prepare("
                    UPDATE user_wallets 
                    SET available_balance = ?, total_balance = ? 
                    WHERE user_id = ?
                ");
                $update_stmt->bind_param("ddi", $new_available_balance, $new_total_balance, $user_id);
                $update_stmt->execute();
                $update_stmt->close();

                // 🔹 Insert withdrawal request (user receives full amount)
                $insert_stmt = $conn->prepare("
                    INSERT INTO withdrawals (user_id, amount, fee_percent, fee_amount, status, created_at)
                    VALUES (?, ?, ?, ?, 'pending', NOW())
                ");
                $insert_stmt->bind_param("iddd", $user_id, $withdraw_amount, $fee_percent, $fee_amount);
                $insert_stmt->execute();
                $insert_stmt->close();

                $success = "Withdrawal request submitted successfully (Fee: {$fee_percent}%).";
            }
        } else {
            // User has no membership, check if 15 days have passed since account creation

            // Fetch user's account creation date
            $user_stmt = $conn->prepare("SELECT created_at FROM users WHERE id = ?");
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user_stmt->close();

            if ($user_result->num_rows > 0) {
                $user_data = $user_result->fetch_assoc();
                $created_at = $user_data['created_at'];
                $created_date = new DateTime($created_at);
                $current_date = new DateTime();

                $interval = $created_date->diff($current_date);
                $days_passed = $interval->days;

                if ($days_passed < 15) {
                    $error = "You cannot withdraw without membership until 15 days after account creation.";
                } else {
                    // 15 days passed, allow withdrawal with 8% fee
                    $fee_percent = 8;

                    // Validate withdrawal amount limits
                    if ($withdraw_amount < 10) {
                        $error = "Minimum withdrawal amount is $10.";
                    } elseif ($withdraw_amount > 50) {
                        $error = "You cannot withdraw more than $50 at a time.";
                    } elseif ($withdraw_amount > $available_balance) {
                        $error = "Insufficient balance for withdrawal.";
                    } else {
                        // Calculate fee and update wallet balances
                        $fee_amount = ($withdraw_amount * $fee_percent) / 100;

                        $new_available_balance = $available_balance - $fee_amount;
                        $new_total_balance = $total_balance - $fee_amount;

                        $update_stmt = $conn->prepare("
                            UPDATE user_wallets 
                            SET available_balance = ?, total_balance = ? 
                            WHERE user_id = ?
                        ");
                        $update_stmt->bind_param("ddi", $new_available_balance, $new_total_balance, $user_id);
                        $update_stmt->execute();
                        $update_stmt->close();

                        // Insert withdrawal request (user receives full amount)
                        $insert_stmt = $conn->prepare("
                            INSERT INTO withdrawals (user_id, amount, fee_percent, fee_amount, status, created_at)
                            VALUES (?, ?, ?, ?, 'pending', NOW())
                        ");
                        $insert_stmt->bind_param("iddd", $user_id, $withdraw_amount, $fee_percent, $fee_amount);
                        $insert_stmt->execute();
                        $insert_stmt->close();

                        $success = "Withdrawal request submitted successfully (Fee: {$fee_percent}%).";
                    }
                }
            } else {
                $error = "User data not found.";
            }
        }
    }

    // 🔹 Redirect with appropriate message
    if (isset($success)) {
        header("Location: withdrawinterface.php?success=" . urlencode($success));
    } else {
        echo "<script>
            alert('" . addslashes($error) . "');
            window.location.href = 'withdrawInterface.php';
        </script>";
    }
    exit();
}
?>