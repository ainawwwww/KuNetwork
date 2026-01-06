<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=" . urlencode("Please log in to deposit."));
    exit();
}

function insertBonusHistory($conn, $user_id, $bonus_type, $bonus_amount, $level_from = null, $level_to = null, $meta = null) {
    $stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, level_to, meta, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    if ($stmt) {
        // Agar meta null ya empty hai to NULL set kar dein
        if ($meta === null || trim($meta) === '') {
            $meta = null;
        }
        $stmt->bind_param("idsiss", $user_id, $bonus_amount, $bonus_type, $level_from, $level_to, $meta);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Bonus history insert prepare failed: " . $conn->error);
    }
}
/* ========= STAGE FUNCTIONS FOR DEPOSIT BONUS ========= */

function getUserStage($user_id, $conn) {
    $sql = "SELECT s.* FROM user_stage_history ush 
            JOIN stages s ON ush.stage_id = s.stage_id 
            WHERE ush.user_id = ? 
            ORDER BY ush.assigned_at DESC 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("getUserStage prepare failed: " . $conn->error);
        return null;
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stage = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $stmt->close();
    return $stage;
}

function calculateDepositBonus($user_id, $deposit_amount, $conn) {
    $stage = getUserStage($user_id, $conn);
    if (!$stage) return 0;

    // Min deposit condition
    if ($deposit_amount < (float)$stage['min_deposit_usdt']) {
        return 0;
    }

    $percentage = (float)$stage['deposit_bonus'];   // stages table ka column
    if ($percentage <= 0) return 0;

    return ($deposit_amount * $percentage) / 100;
}

/* ========= REFERRAL DEPOSIT BONUS FUNCTION (NEW) ========= */

function calculateReferralDepositBonus($referrer_user_id, $downline_deposit_amount, $conn) {
    $stage = getUserStage($referrer_user_id, $conn);
    if (!$stage) return 0;

    // Referrer ke stage ka referral_bonus column use karenge
    $percentage = (float)$stage['referral_bonus']; // e.g. Stage 1 = 12.00
    if ($percentage <= 0) return 0;

    return ($downline_deposit_amount * $percentage) / 100;
}

/* ========= WALLET UPDATE (with bonus support) ========= */

function updateWallet($conn, $user_id, $amount, $is_first_payment, $bonus_amount = 0.0)
{
    // Total deposit credited = actual deposit + bonus
    $total_credit = $amount + $bonus_amount;

    $wallet = $conn->query("SELECT * FROM user_wallets WHERE user_id = $user_id")->fetch_assoc();

    if ($wallet) {
        // Existing wallet
        $total_balance = $wallet['total_balance'] + $total_credit;
        $capital_locked_balance = $wallet['capital_locked_balance'];
        $capital_locked_until = $wallet['capital_locked_until'];

        // Capital lock only deposit amount pe (bonus pe nahi), agar aap aisa chahte hain.
        if ($is_first_payment) {
            $capital_locked_balance += $amount;
            $capital_locked_until = date('Y-m-d H:i:s', strtotime('+15 days'));
        }

        $available_balance = $total_balance - $capital_locked_balance;

        $stmt = $conn->prepare("
            UPDATE user_wallets
            SET balance = balance + ?,
                total_balance = ?,
                capital_locked_balance = ?,
                capital_locked_until = ?,
                available_balance = ?,
                last_transaction = NOW()
            WHERE user_id = ?
        ");
        if (!$stmt) {
            header("Location: deposit.php?error=" . urlencode("Error preparing wallet update."));
            exit();
        }
        $stmt->bind_param(
            "dddsdi",
            $total_credit,      // balance increase = deposit + bonus
            $total_balance,
            $capital_locked_balance,
            $capital_locked_until,
            $available_balance,
            $user_id
        );
    } else {
        // New wallet record
        $capital_locked_balance = $is_first_payment ? $amount : 0;
        $capital_locked_until = $is_first_payment ? date('Y-m-d H:i:s', strtotime('+15 days')) : null;

        $total_balance = $total_credit;
        $available_balance = $total_balance - $capital_locked_balance;

        $stmt = $conn->prepare("
            INSERT INTO user_wallets (user_id, balance, total_balance, capital_locked_balance, capital_locked_until, available_balance, currency, last_transaction)
            VALUES (?, ?, ?, ?, ?, ?, 'USD', NOW())
        ");
        if (!$stmt) {
            header("Location: deposit.php?error=" . urlencode("Error preparing wallet insert."));
            exit();
        }

        // capital_locked_until NULL handle:
        if ($capital_locked_until === null) {
            // MySQL ke liye NULL ko PHP se string ke bajaye null allow karne ka simple tareeqa:
            $null = null;
            $stmt->bind_param(
                "idddsd",
                $user_id,
                $total_credit,
                $total_balance,
                $capital_locked_balance,
                $null,              // capital_locked_until
                $available_balance
            );
        } else {
            $stmt->bind_param(
                "idddsd",
                $user_id,
                $total_credit,
                $total_balance,
                $capital_locked_balance,
                $capital_locked_until,
                $available_balance
            );
        }
    }

    if (!$stmt->execute()) {
        error_log("Wallet update failed: " . $stmt->error);
        header("Location: deposit.php?error=" . urlencode("Error updating wallet."));
        exit();
    }
    $stmt->close();
}

/* ========= MAIN PAYMENT HANDLER ========= */

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $amount  = floatval($_POST['amount']);

    if (!is_numeric($amount) || $amount <= 0) {
        header("Location: deposit.php?error=" . urlencode("Invalid amount entered."));
        exit();
    }

    // Check if this is the user's first payment
    $payment_count_row = $conn->query("SELECT COUNT(*) AS payment_count FROM payment WHERE user_id = $user_id");
    $payment_count     = $payment_count_row ? $payment_count_row->fetch_assoc()['payment_count'] : 0;
    $is_first_payment  = ($payment_count == 0);

    // Level logic (existing)
    if ($is_first_payment) {
        // Always assign Level 1 for first payment
        $level_id = 1;
        $level_row = $conn->query("SELECT * FROM levels WHERE id = 1")->fetch_assoc();
        $level_name = $level_row['name'];
    } else {
        $level_query = $conn->prepare("SELECT * FROM levels WHERE ? BETWEEN minimum_amount AND maximum_amount");
        if (!$level_query) {
            header("Location: deposit.php?error=" . urlencode("Error finding level."));
            exit();
        }
        $level_query->bind_param("d", $amount);
        $level_query->execute();
        $level_result = $level_query->get_result();

        if ($level_result && $level_result->num_rows > 0) {
            $level_row  = $level_result->fetch_assoc();
            $level_id   = $level_row['id'];
            $level_name = $level_row['name'];
        } else {
            header("Location: deposit.php?error=" . urlencode("No level matched for this amount."));
            exit();
        }
        $level_query->close();
    }

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payment (user_id, amount, level_id, created_at) VALUES (?, ?, ?, NOW())");
    if (!$stmt) {
        header("Location: deposit.php?error=" . urlencode("Error preparing payment insert."));
        exit();
    }
    $stmt->bind_param("idi", $user_id, $amount, $level_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Re-check payment count to see if this was first (after insert)
        $payment_count_row = $conn->query("SELECT COUNT(*) AS payment_count FROM payment WHERE user_id = $user_id");
        $payment_count     = $payment_count_row ? $payment_count_row->fetch_assoc()['payment_count'] : 1;
        $is_first_payment  = ($payment_count == 1);

        /* ========= CALCULATE STAGE-BASED DEPOSIT BONUS ========= */
        $bonus = calculateDepositBonus($user_id, $amount, $conn);   // yahan se bonus nikal raha hai

        // Update wallet with amount + bonus
        updateWallet($conn, $user_id, $amount, $is_first_payment, $bonus);



        // Deposit bonus calculate karne ke baad aur wallet update ke baad
if ($bonus > 0) {
    $meta_data = json_encode([
        'description' => 'Deposit bonus',
        'amount' => $bonus,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    insertBonusHistory($conn, $user_id, 'bonus', $bonus, null, null, $meta_data);
}

$claim_bonus = isset($_POST['claim_bonus']) && $_POST['claim_bonus'] == 1;

if ($claim_bonus) {
    // *** Bonus calculation and immediate addition to wallet ***
    $bonusPercent = 10; // 10%
    $bonusAmount = $amount * ($bonusPercent / 100);

    $wallet = $conn->query("SELECT * FROM user_wallets WHERE user_id = $user_id")->fetch_assoc();

    if ($wallet) {
        $new_total_balance = $wallet['total_balance'] + $bonusAmount;
        $new_available_balance = $wallet['available_balance'] + $bonusAmount;

        $stmtBonus = $conn->prepare("UPDATE user_wallets SET total_balance = ?, available_balance = ? WHERE user_id = ?");
        $stmtBonus->bind_param("ddi", $new_total_balance, $new_available_balance, $user_id);
        $stmtBonus->execute();
             if (!$stmtBonus) {
    error_log("Prepare failed: " . $conn->error);
} else {
    if (!$stmtBonus->execute()) {
        error_log("Execute failed: " . $stmtBonus->error);
    }
    $stmtBonus->close();
}
    } else {
        $stmtBonus = $conn->prepare("INSERT INTO user_wallets (user_id, total_balance, available_balance, currency, last_transaction) VALUES (?, ?, ?, 'USD', NOW())");
        $stmtBonus->bind_param("idd", $user_id, $bonusAmount, $bonusAmount);
        $stmtBonus->execute();
        if (!$stmtBonus) {
    error_log("Prepare failed: " . $conn->error);
} else {
    if (!$stmtBonus->execute()) {
        error_log("Execute failed: " . $stmtBonus->error);
    }
    $stmtBonus->close();
}
    }



    // Weekly bonus claim record update
    $lockDurationMinutes = 5; // For testing, adjust as needed
    $now = new DateTime("now", new DateTimeZone("UTC"));
    $capitalLockedUntil = $now->modify("+$lockDurationMinutes minutes")->format('Y-m-d H:i:s');

    $stmtCheck = $conn->prepare("SELECT 1 FROM weekly_bonus_claims WHERE user_id = ? LIMIT 1");
    $stmtCheck->bind_param("i", $user_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $stmtUpdate = $conn->prepare("UPDATE weekly_bonus_claims SET capital_locked_amount = ?, capital_locked_until = ?, bonus_amount = ?, bonus_percent = ?, bonus_claimed = 0 WHERE user_id = ?");
        $stmtUpdate->bind_param("dsdii", $amount, $capitalLockedUntil, $bonusAmount, $bonusPercent, $user_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        $stmtInsert = $conn->prepare("INSERT INTO weekly_bonus_claims (user_id, capital_locked_amount, capital_locked_until, bonus_amount, bonus_claimed) VALUES (?, ?, ?, ?, 0)");
        $stmtInsert->bind_param("idsd", $user_id, $amount, $capitalLockedUntil, $bonusAmount);
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    // Insert weekly bonus history
$meta_data = json_encode([
    'description' => 'Weekly bonus claimed',
    'capital_locked_amount' => $amount,
    'bonus_percent' => $bonusPercent,
    'bonus_amount' => $bonusAmount,
    'timestamp' => date('Y-m-d H:i:s')
]);

insertBonusHistory($conn, $user_id, 'weekly_bonus', $bonusAmount, null, null, $meta_data);

    $stmtCheck->close();
}
        /* ========= REFERRAL DEPOSIT BONUS (NEW LOGIC) ========= */
        
        // Check if current user has a referrer
        $referrer_user_id = null;
        $ref_query = $conn->prepare("SELECT user_id FROM referal_teams WHERE referral_userid = ?");
        if ($ref_query) {
            $ref_query->bind_param("i", $user_id);
            $ref_query->execute();
            $ref_result = $ref_query->get_result();
            if ($ref_result->num_rows > 0) {
                $ref_row = $ref_result->fetch_assoc();
                $referrer_user_id = (int)$ref_row['user_id'];
            }
            $ref_query->close();
        }

        // Agar referrer hai, to usko bonus do
        if (!empty($referrer_user_id)) {
            $referral_bonus = calculateReferralDepositBonus($referrer_user_id, $amount, $conn);

            if ($referral_bonus > 0) {
                // Referrer ke wallet mein bonus add karo
                $ref_wallet_query = $conn->query("SELECT * FROM user_wallets WHERE user_id = $referrer_user_id");
                $ref_wallet = $ref_wallet_query ? $ref_wallet_query->fetch_assoc() : null;

                if ($ref_wallet) {
                    // Existing wallet - update karo
                    $new_balance = $ref_wallet['balance'] + $referral_bonus;
                    $new_total_balance = $ref_wallet['total_balance'] + $referral_bonus;
                    $new_available_balance = $ref_wallet['available_balance'] + $referral_bonus;

                    $ref_stmt = $conn->prepare("
                        UPDATE user_wallets
                        SET balance = ?, 
                            total_balance = ?, 
                            available_balance = ?, 
                            last_transaction = NOW()
                        WHERE user_id = ?
                    ");
                    if ($ref_stmt) {
                        $ref_stmt->bind_param("dddi", $new_balance, $new_total_balance, $new_available_balance, $referrer_user_id);
                        $ref_stmt->execute();
                        $ref_stmt->close();
                    } else {
                        error_log("Referral bonus wallet update prepare failed: " . $conn->error);
                    }

                } else {
                    // Naya wallet record banao referrer ke liye
                    $ref_stmt = $conn->prepare("
                        INSERT INTO user_wallets (user_id, balance, total_balance, capital_locked_balance, capital_locked_until, available_balance, currency, last_transaction)
                        VALUES (?, ?, ?, 0, NULL, ?, 'USD', NOW())
                    ");
                    if ($ref_stmt) {
                        $ref_stmt->bind_param("iddd", $referrer_user_id, $referral_bonus, $referral_bonus, $referral_bonus);
                        $ref_stmt->execute();
                        $ref_stmt->close();
                    } else {
                        error_log("Referral bonus wallet insert prepare failed: " . $conn->error);
                    }
                }

     if ($referral_bonus > 0) {
    $meta_data = json_encode([
        'description' => 'Referral deposit bonus from user ID ' . $user_id,
        'amount' => $referral_bonus,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    insertBonusHistory($conn, $referrer_user_id, 'bonus', $referral_bonus, null, null, $meta_data);
}
            }
        }

        /* ========= LEVEL UPGRADE LOGIC (existing) ========= */
        $check_upgrade = $conn->prepare("SELECT id FROM users_level_upgrade WHERE user_id = ?");
        $check_upgrade->bind_param("i", $user_id);
        $check_upgrade->execute();
        $check_result = $check_upgrade->get_result();

        if ($check_result->num_rows == 0) {
            $insert_upgrade = $conn->prepare("INSERT INTO users_level_upgrade (user_id, capital_level) VALUES (?, ?)");
            $insert_upgrade->bind_param("ii", $user_id, $level_id);
            $insert_upgrade->execute();
            $insert_upgrade->close();
        } else {
            $update_upgrade = $conn->prepare("UPDATE users_level_upgrade SET capital_level = ? WHERE user_id = ?");
            $update_upgrade->bind_param("ii", $level_id, $user_id);
            $update_upgrade->execute();
            $update_upgrade->close();
        }
        $check_upgrade->close();

       

        /* ========= REDIRECT WITH SUCCESS MESSAGE ========= */

/* ========= REDIRECT WITH SUCCESS MESSAGE ========= */
$success_msg = "Payment added successfully!";

// Check if this was a bonus claim (Weekly Bonus Deposit)
if ($claim_bonus) {
    // Redirect to Account Page with Product Modal Flag AND Custom Title Flag
    header("Location: account.php?success=" . urlencode("Weekly Bonus Deposit Successful!") . "&showProductModal=1&offerType=limited");
    exit();
}

// Existing logic for normal First Payment (Redirect to Deposit Page)
if ($is_first_payment) {
    header("Location: deposit.php?success=" . urlencode($success_msg) . "&showProductModal=1");
    exit();
}

// Normal Deposit
header("Location: deposit.php?success=" . urlencode($success_msg));
exit();

    } else {
        error_log("Failed to add payment: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        header("Location: deposit.php?error=" . urlencode("Failed to add payment."));
        exit();
    }

} else {
    // Direct access
    header("Location: deposit.php");
    exit();
}