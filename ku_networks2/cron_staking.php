<?php
// Connect Database
// Adjust path if config.php is in a different folder (e.g., '../config.php')
require 'config.php'; 

// Current Time
$now = date('Y-m-d H:i:s');
echo "Starting Staking Cron Job at: $now <br>";

// --- PART 1: PROCESS DAILY PROFITS (Calculation Based) ---
// Sirf 'running' stakes uthao
$sqlProfit = "SELECT sh.id, sh.staked_amount, sh.current_profit_earned, sh.start_date, sp.daily_profit_percentage 
              FROM staking_history sh
              JOIN staking_packages sp ON sh.package_id = sp.id
              WHERE sh.status = 'running' 
              AND sh.end_date > '$now'"; // Sirf wo jo abhi expire nahi huye

$resultProfit = $conn->query($sqlProfit);

if ($resultProfit && $resultProfit->num_rows > 0) {
    while ($row = $resultProfit->fetch_assoc()) {
        $stake_id = $row['id'];
        $amount = floatval($row['staked_amount']);
        $current_earned = floatval($row['current_profit_earned']);
        $daily_percent = floatval($row['daily_profit_percentage']);
        $start_date = strtotime($row['start_date']);
        
        // 1. Calculate: Kitne din guzar chuke hain start date se lekar ab tak?
        //    (Floor use kar rahe hain taake complete 24 hours count hon)
        $seconds_passed = time() - $start_date;
        $days_passed = floor($seconds_passed / (60 * 60 * 24));
        
        // Agar 0 days hain (matlab abhi 24 ghante nahi huye), to skip karo
        // Lekin kyunki humne 1st Day Profit already de diya hai, humein check karna hai 
        // ke kya 'Expected Profit' current profit se zyada hai?
        
        // Note: First day profit process_stake.php mein de diya tha (Day 0 ka reward maano usay).
        // Ab hum calculate karenge: (Amount * % * (DaysPassed + 1))
        // +1 isliye kyunki pehla profit instant tha.
        
        // Calculation: Total profit jo AB TAK user ke paas hona chahiye
        // Day 0 (Instant): 1 Day Profit
        // Day 1 (After 24h): 2 Days Profit total
        
        $one_day_profit = $amount * ($daily_percent / 100);
        
        // Total should be: (Instant Profit) + (Days Passed * Daily Profit)
        $should_have_earned = $one_day_profit + ($days_passed * $one_day_profit);
        
        // 2. Check: Kya user ke paas already itna profit hai?
        if ($should_have_earned > $current_earned) {
            // Farq nikalo (Difference)
            $profit_to_add = $should_have_earned - $current_earned;
            
            // Safety check: Profit bohot chota ho (floating point error) to ignore karo
            if ($profit_to_add > 0.01) {
                $updateQ = "UPDATE staking_history 
                            SET current_profit_earned = current_profit_earned + $profit_to_add 
                            WHERE id = $stake_id";
                
                if ($conn->query($updateQ)) {
                    echo "Updated Stake ID: $stake_id | Added: $$profit_to_add | Days Passed: $days_passed <br>";
                } else {
                    echo "Error Updating ID $stake_id: " . $conn->error . "<br>";
                }
            }
        }
    }
} else {
    echo "No running stakes found for profit calculation.<br>";
}

// --- PART 2: PROCESS MATURITY (Release Funds) ---
// Find active stakes where end_date has PASSED
$sqlMaturity = "SELECT id, user_id, staked_amount, current_profit_earned 
                FROM staking_history 
                WHERE status = 'running' 
                AND end_date <= '$now'";

$resultMaturity = $conn->query($sqlMaturity);

if ($resultMaturity && $resultMaturity->num_rows > 0) {
    while ($row = $resultMaturity->fetch_assoc()) {
        $stake_id = $row['id'];
        $user_id = $row['user_id'];
        $capital = floatval($row['staked_amount']);
        $profit = floatval($row['current_profit_earned']);
        
        $total_payout = $capital + $profit;

        // Transaction Start
        $conn->begin_transaction();
        try {
            // 1. Add Capital + Profit to Main User Wallet
            $updateWallet = $conn->prepare("UPDATE user_wallets SET available_balance = available_balance + ? WHERE user_id = ?");
            $updateWallet->bind_param("di", $total_payout, $user_id);
            $updateWallet->execute();

            // 2. Mark Stake as Completed
            $conn->query("UPDATE staking_history SET status = 'completed' WHERE id = $stake_id");

            $conn->commit();
            echo "<strong>Maturity Processed for Stake ID: $stake_id. Payout: $$total_payout</strong> <br>";

        } catch (Exception $e) {
            $conn->rollback();
            echo "Error processing maturity for ID $stake_id: " . $e->getMessage() . "<br>";
        }
    }
} else {
    echo "No stakes matured yet.<br>";
}

echo "Cron Job Finished.";
?>