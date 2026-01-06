<?php
session_start();
require 'config.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate Input
    if (!isset($_POST['package_id'])) {
        echo "<script>alert('Package ID missing.'); window.location.href='index.php';</script>";
        exit();
    }

    $package_id = intval($_POST['package_id']);

    // --- STEP A: Fetch Package Details ---
    $stmtPkg = $conn->prepare("SELECT * FROM staking_packages WHERE id = ? AND status='active'");
    $stmtPkg->bind_param("i", $package_id);
    $stmtPkg->execute();
    $package = $stmtPkg->get_result()->fetch_assoc();
    $stmtPkg->close();

    if (!$package) {
        echo "<script>alert('Invalid or Inactive Package'); window.location.href='index.php';</script>";
        exit();
    }

    // --- STEP B: DUPLICATE CHECK ---
    // User cannot buy the same package if it is currently 'running'
    $stmtCheck = $conn->prepare("SELECT id FROM staking_history WHERE user_id = ? AND package_id = ? AND status = 'running'");
    $stmtCheck->bind_param("ii", $user_id, $package_id);
    $stmtCheck->execute();
    $stmtCheck->store_result();
    
    if ($stmtCheck->num_rows > 0) {
        echo "<script>
            alert('You already have this package active! Please wait for it to expire.');
            window.location.href = 'account.php';
        </script>";
        $stmtCheck->close();
        exit();
    }
    $stmtCheck->close();

    // --- STEP C: Setup Amounts ---
    // Amount is fixed to the minimum amount of the package
    $amount = floatval($package['min_amount']); 
    
    // Wallet Balance Check
    $stmtWallet = $conn->prepare("SELECT available_balance FROM user_wallets WHERE user_id = ?");
    $stmtWallet->bind_param("i", $user_id);
    $stmtWallet->execute();
    $wallet = $stmtWallet->get_result()->fetch_assoc();
    $stmtWallet->close();

    $current_balance = $wallet['available_balance'] ?? 0;

    if ($current_balance < $amount) {
        echo "<script>
            alert('Insufficient Balance! You need $amount but have only $current_balance. Redirecting to Deposit...');
            window.location.href = 'deposit.php'; 
        </script>";
        exit();
    }

    // --- STEP D: PURCHASE TRANSACTION ---
    $conn->begin_transaction();

    try {
        // 1. Wallet se paisa kato
        $new_balance = $current_balance - $amount;
        $updateWallet = $conn->prepare("UPDATE user_wallets SET available_balance = ? WHERE user_id = ?");
        $updateWallet->bind_param("di", $new_balance, $user_id);
        $updateWallet->execute();

        // 2. Calculations
        $duration = intval($package['duration_days']);
        $daily_percent = floatval($package['daily_profit_percentage']);
        
        // Total Expected Profit (Maturity par)
        $total_expected_profit = $amount * ($daily_percent / 100) * $duration;
        
        // **Feature:** 1st Day Profit (Instant Reward)
        // Ye profit hum shuru mein hi 'current_profit_earned' mein daal denge
        $first_day_profit = $amount * ($daily_percent / 100);

        $end_date = date('Y-m-d H:i:s', strtotime("+$duration days"));

        // 3. Insert into Staking History (WITHOUT extra columns)
        // Hum 'current_profit_earned' mein $first_day_profit daal rahe hain.
        $insertStake = $conn->prepare("
            INSERT INTO staking_history 
            (user_id, package_id, staked_amount, total_expected_profit, current_profit_earned, start_date, end_date, status) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, 'running')
        ");
        
        // Bind Parameters: iiddds (User, Pkg, Amount, TotalExpected, FirstProfit, EndDate)
        $insertStake->bind_param("iiddds", $user_id, $package_id, $amount, $total_expected_profit, $first_day_profit, $end_date);
        
        if (!$insertStake->execute()) {
            throw new Exception("Insert Failed: " . $insertStake->error);
        }

        // Transaction Complete
        $conn->commit();

        echo "<script>
            alert('Staking Successful! 1st Day Profit ($" . $first_day_profit . ") credited instantly.');
            window.location.href = 'account.php';
        </script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>