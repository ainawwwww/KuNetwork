<?php
include 'config.php';
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/my_custom_php_errors.log');
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please log in to claim daily bonus."));
    exit();
}

function creditToWallet($conn, $user_id, $amount, $description = '')

{
    error_log("[CLAIM_BONUS_DEBUG] creditToWallet called for user_id: $user_id, amount: $amount, description: $description");
    $wallet_stmt = $conn->prepare("SELECT balance FROM user_wallets WHERE user_id = ?");
    $wallet_stmt->bind_param("i", $user_id);
    $wallet_stmt->execute();
    $wallet_result = $wallet_stmt->get_result();
    $wallet = $wallet_result->fetch_assoc();

    if ($wallet) {
        $new_balance = $wallet['balance'] + $amount;
        $stmt = $conn->prepare("
            UPDATE user_wallets
            SET balance = ?, total_balance = total_balance + ?, available_balance = available_balance + ?, last_transaction = NOW()
            WHERE user_id = ?
        ");
        $stmt->bind_param("dddi", $new_balance, $amount, $amount, $user_id);
        if (!$stmt->execute()) {
            error_log("[CLAIM_BONUS_DEBUG] Failed to update wallet for user $user_id: " . $stmt->error);
        } else {
            error_log("[CLAIM_BONUS_DEBUG] Wallet updated for user $user_id. New balance: $new_balance");
        }
    } else {
        $stmt = $conn->prepare("
            INSERT INTO user_wallets (user_id, balance, total_balance, available_balance, currency, last_transaction)
            VALUES (?, ?, ?, ?, 'USD', NOW())
        ");
        $stmt->bind_param("iddd", $user_id, $amount, $amount, $amount);
        if (!$stmt->execute()) {
            error_log("[CLAIM_BONUS_DEBUG] Failed to create wallet for user $user_id: " . $stmt->error);
        } else {
            error_log("[CLAIM_BONUS_DEBUG] Wallet created for user $user_id. Balance: $amount");
        }
    }
}
function payUplinesDailyCommissionOnChildBalance($conn, $child_user_id, $max_levels = 3) // Limiting to 3 levels for fixed percentages
{
    error_log("[DAILY_UPLINE_COMM_FIXED] START child={$child_user_id}");

    // Define fixed percentages for each level of distance from the child
    // distance_level 1 = direct parent
    // distance_level 2 = grandparent
    // distance_level 3 = great-grandparent
    $fixed_percentages = [
        1 => 3.00, // 3% for direct parent
        2 => 1.00, // 1% for grandparent
        3 => 0.50, // 0.5% for great-grandparent
    ];

    $current_child = (int)$child_user_id;
    $levels_paid = 0;

    // Walk up parents
    while ($levels_paid < $max_levels) {
        $distance_level = $levels_paid + 1;

        // Check if we have a fixed percentage defined for this distance level
        if (!isset($fixed_percentages[$distance_level])) {
            error_log("[DAILY_UPLINE_COMM_FIXED] No fixed percentage defined for distance level {$distance_level}. Stopping upline walk.");
            break; // Stop if no percentage is defined for this level
        }

        $percent = $fixed_percentages[$distance_level];
        $percent_str = number_format($percent, 2, '.', '');

        // Find direct parent of current_child
        $sqlParent = "SELECT user_id AS parent_id FROM referal_teams WHERE referral_userid = ? LIMIT 1";
        $stmt = $conn->prepare($sqlParent);
        if (!$stmt) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Prepare failed (parent lookup): " . $conn->error);
            break;
        }
        $stmt->bind_param("i", $current_child);
        if (!$stmt->execute()) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Execute failed (parent lookup): " . $stmt->error);
            $stmt->close();
            break;
        }
        $res = $stmt->get_result();
        $parentRow = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if (!$parentRow || empty($parentRow['parent_id'])) {
            error_log("[DAILY_UPLINE_COMM_FIXED] No parent found for user {$current_child} -- stopping upline walk.");
            break;
        }

        $parent_id = (int)$parentRow['parent_id'];
        error_log("[DAILY_UPLINE_COMM_FIXED] Found parent_id={$parent_id} for child={$current_child} at distance L{$distance_level}. Commission percent: {$percent_str}%");

        // Fetch child's current balance (child is the original claimant)
        $sqlBal = "SELECT balance FROM user_wallets WHERE user_id = ? LIMIT 1";
        $wb = $conn->prepare($sqlBal);
        if (!$wb) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Prepare failed (wallet lookup): " . $conn->error);
            break;
        }
        $wb->bind_param("i", $child_user_id);
        if (!$wb->execute()) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Execute failed (wallet lookup): " . $wb->error);
            $wb->close();
            break;
        }
        $wbRes = $wb->get_result();
        $walletRow = $wbRes ? $wbRes->fetch_assoc() : null;
        $wb->close();

        if (!$walletRow) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Child {$child_user_id} has no wallet row. Skip ancestor {$parent_id}.");
            $current_child = $parent_id;
            $levels_paid++;
            continue;
        }

        $child_balance = (float)$walletRow['balance'];
        if ($child_balance <= 0) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Child {$child_user_id} balance <= 0; nothing to pay. Stop.");
            break;
        }

        // Compute commission (based on child's balance)
        $commission_amount = round(($child_balance * $percent) / 100.0, 2);
        if ($commission_amount <= 0) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Commission computed <= 0 for ancestor {$parent_id}. Continue.");
            $current_child = $parent_id;
            $levels_paid++;
            continue;
        }

        error_log("[DAILY_UPLINE_COMM_FIXED] Commission to ancestor {$parent_id} = {$commission_amount}");

        // Prevent duplicate for the same day (idempotency)
        $today = date('Y-m-d');
        $dupSQL = "SELECT id FROM team_commission_history WHERE from_user_id = ? AND to_user_id = ? AND DATE(created_at) = ?";
        $dup = $conn->prepare($dupSQL);
        if ($dup) {
            $dup->bind_param("iis", $child_user_id, $parent_id, $today);
            if ($dup->execute()) {
                $dupRes = $dup->get_result();
                if ($dupRes && $dupRes->num_rows > 0) {
                    error_log("[DAILY_UPLINE_COMM_FIXED] Duplicate for child {$child_user_id} -> ancestor {$parent_id} on {$today}. Skipping payout.");
                    $dup->close();
                    $current_child = $parent_id;
                    $levels_paid++;
                    continue;
                }
            } else {
                error_log("[DAILY_UPLINE_COMM_FIXED] Dup-check execute failed: " . $dup->error);
            }
            $dup->close();
        } else {
            error_log("[DAILY_UPLINE_COMM_FIXED] Dup-check prepare failed: " . $conn->error);
        }

        // Insert history record
        $histSQL = "INSERT INTO team_commission_history (from_user_id, to_user_id, level, percentage, amount, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $hist = $conn->prepare($histSQL);
        if (!$hist) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Prepare failed (history insert): " . $conn->error);
            $current_child = $parent_id;
            $levels_paid++;
            continue;
        }
        $hist->bind_param("iiidd", $child_user_id, $parent_id, $distance_level, $percent, $commission_amount);
        if (!$hist->execute()) {
            error_log("[DAILY_UPLINE_COMM_FIXED] Failed to insert commission history for ancestor {$parent_id}: " . $hist->error);
            $hist->close();
            $current_child = $parent_id;
            $levels_paid++;
            continue;
        }
        $hist_id = $hist->insert_id;
        $hist->close();
        error_log("[DAILY_UPLINE_COMM_FIXED] Inserted team_commission_history id={$hist_id} for ancestor {$parent_id}");

        // Credit ancestor's wallet
        creditToWallet($conn, $parent_id, $commission_amount, "Daily Referral Commission from child {$child_user_id} (distance L{$distance_level}) {$percent_str}%");
        error_log("[DAILY_UPLINE_COMM_FIXED] Credited {$commission_amount} to ancestor {$parent_id} (distance L{$distance_level})");

        // Move up the chain
        $current_child = $parent_id;
        $levels_paid++;
    }

    error_log("[DAILY_UPLINE_COMM_FIXED] FINISH for child={$child_user_id}. Levels processed={$levels_paid}");
}
function giveTeamCommission($conn, $from_user_id, $bonus_amount)
{
    error_log("[CLAIM_BONUS_DEBUG] giveTeamCommission called for from_user_id: $from_user_id, bonus_amount: $bonus_amount");
    $current_user_id_for_commission = $from_user_id;

    for ($ref_level = 1; $ref_level <= 3; $ref_level++) {
        $ref_stmt = $conn->prepare("SELECT user_id FROM referal_teams WHERE referral_userid = ?");
        $ref_stmt->bind_param("i", $current_user_id_for_commission);
        $ref_stmt->execute();
        $ref_result = $ref_stmt->get_result();
        if ($ref_result->num_rows == 0) {
            error_log("[CLAIM_BONUS_DEBUG] No upline found for user $current_user_id_for_commission at ref_level $ref_level.");
            break;
        }
        $upline_id = $ref_result->fetch_assoc()['user_id'];
        if (!$upline_id) {
            error_log("[CLAIM_BONUS_DEBUG] Upline ID is null for user $current_user_id_for_commission at ref_level $ref_level.");
            break;
        }

        $payment_stmt = $conn->prepare("SELECT level_id FROM payment WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $payment_stmt->bind_param("i", $upline_id);
        $payment_stmt->execute();
        $payment_result = $payment_stmt->get_result();
        if ($payment_result->num_rows == 0) {
            error_log("[CLAIM_BONUS_DEBUG] No payment record found for upline $upline_id.");
            break;
        }
        $level_id = $payment_result->fetch_assoc()['level_id'];
        if (!$level_id) {
            error_log("[CLAIM_BONUS_DEBUG] Level ID is null for upline $upline_id.");
            break;
        }

        $level_stmt = $conn->prepare("SELECT team_type FROM levels WHERE id = ?");
        $level_stmt->bind_param("i", $level_id);
        $level_stmt->execute();
        $level_result = $level_stmt->get_result();
        if ($level_result->num_rows == 0) {
            error_log("[CLAIM_BONUS_DEBUG] No level config found for level_id $level_id (upline $upline_id).");
            break;
        }
        $team_type = $level_result->fetch_assoc()['team_type'];
        if (!$team_type) {
            error_log("[CLAIM_BONUS_DEBUG] Team type is null for level_id $level_id (upline $upline_id).");
            break;
        }

        $team_commission_stmt = $conn->prepare("SELECT * FROM team_earning_commission WHERE team_name = ?");
        $team_commission_stmt->bind_param("s", $team_type);
        $team_commission_stmt->execute();
        $team_commission_result = $team_commission_stmt->get_result();
        $team_commission_config = $team_commission_result->fetch_assoc();
        if (!$team_commission_config) {
            error_log("[CLAIM_BONUS_DEBUG] No team commission config found for team_type '$team_type'.");
            break;
        }

        $level_col = 'level_' . ($ref_level + 1);
        if (!isset($team_commission_config[$level_col])) {
            error_log("[CLAIM_BONUS_DEBUG] Commission percentage column '$level_col' not found in team_earning_commission for team_type '$team_type'.");
            break;
        }

        $percent = floatval(str_replace('%', '', $team_commission_config[$level_col]));
        $commission_amount = ($bonus_amount * $percent) / 100;

        if ($commission_amount > 0) {
            $insert_commission_stmt = $conn->prepare("INSERT INTO team_commission_history (from_user_id, to_user_id, level, percentage, amount) VALUES (?, ?, ?, ?, ?)");
            $insert_commission_stmt->bind_param("iiidd", $from_user_id, $upline_id, $ref_level, $percent, $commission_amount);
            if ($insert_commission_stmt->execute()) {
                error_log("[CLAIM_BONUS_DEBUG] Team commission $commission_amount to upline $upline_id (L$ref_level).");
                creditToWallet($conn, $upline_id, $commission_amount, "Team Commission L$ref_level ($team_type)");
            } else {
                error_log("[CLAIM_BONUS_DEBUG] Failed to insert team commission history for upline $upline_id: " . $insert_commission_stmt->error);
            }
        }
        $current_user_id_for_commission = $upline_id;
    }
}

function getTeamUserIds($conn, $leaderUserIds) {
    if (empty($leaderUserIds)) {
        return [];
    }

    $sanitizedLeaderUserIds = [];
    if (!is_array($leaderUserIds)) {
        $leaderUserIds = [$leaderUserIds];
    }
    foreach ($leaderUserIds as $id) {
        if (is_numeric($id) && $id > 0) {
            $sanitizedLeaderUserIds[] = (int)$id;
        }
    }

    if (empty($sanitizedLeaderUserIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($sanitizedLeaderUserIds), '?'));
    $types = str_repeat('i', count($sanitizedLeaderUserIds));

    $sql = "SELECT DISTINCT referral_userid FROM referal_teams WHERE user_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Failed to prepare statement for getTeamUserIds: " . $conn->error . " SQL: " . $sql);
        return [];
    }

    $stmt->bind_param($types, ...$sanitizedLeaderUserIds);
    $stmt->execute();
    $result = $stmt->get_result();
    $memberIds = [];
    while ($row = $result->fetch_assoc()) {
        $memberIds[] = (int)$row['referral_userid'];
    }
    $stmt->close();
    return array_unique($memberIds);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim'])) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        error_log("[CLAIM_BONUS_ERROR] User ID not found in session.");
        header("Location: claimbonus.php?error=" . urlencode("Session expired or user not logged in."));
        exit();
    }

    $user_id = (int)$_SESSION['user_id'];
    $current_date_string = date('Y-m-d');
   

    error_log("[CLAIM_BONUS_DEBUG] User $user_id attempting to claim bonus for date $current_date_string.");

    // Check if daily bonus already claimed
    $claimed_stmt = $conn->prepare("SELECT id FROM bonus_history WHERE user_id = ? AND DATE(created_at) = ? AND bonus_type = 'daily'");
    $claimed_stmt->bind_param("is", $user_id, $current_date_string);
    $claimed_stmt->execute();
    $claimed_result = $claimed_stmt->get_result();
    if ($claimed_result->num_rows > 0) {
        error_log("[CLAIM_BONUS_DEBUG] User $user_id already claimed daily bonus today.");
        header("Location: claimbonus.php?error=" . urlencode("You have already claimed your daily bonus today."));
        exit();
    }
    $claimed_stmt->close();

    // Determine current level for daily bonus calculation
    $level_for_daily_bonus = null;

    // 1. Latest upgrade level
    $latest_upgrade_stmt = $conn->prepare("SELECT level_to FROM bonus_history WHERE user_id = ? AND bonus_type = 'upgrade' AND level_to IS NOT NULL ORDER BY created_at DESC, id DESC LIMIT 1");
    $latest_upgrade_stmt->bind_param("i", $user_id);
    $latest_upgrade_stmt->execute();
    $latest_upgrade_result = $latest_upgrade_stmt->get_result();
    if ($row = $latest_upgrade_result->fetch_assoc()) {
        $level_for_daily_bonus = (int)$row['level_to'];
    }
    $latest_upgrade_stmt->close();

    // 2. Latest daily bonus level
    if ($level_for_daily_bonus === null) {
        $latest_daily_stmt = $conn->prepare("SELECT level_from FROM bonus_history WHERE user_id = ? AND bonus_type = 'daily' ORDER BY created_at DESC, id DESC LIMIT 1");
        $latest_daily_stmt->bind_param("i", $user_id);
        $latest_daily_stmt->execute();
        $latest_daily_result = $latest_daily_stmt->get_result();
        if ($row = $latest_daily_result->fetch_assoc()) {
            $level_for_daily_bonus = (int)$row['level_from'];
        }
        $latest_daily_stmt->close();
    }

    // 3. Initial payment level
    if ($level_for_daily_bonus === null) {
        $initial_payment_stmt = $conn->prepare("SELECT level_id FROM payment WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $initial_payment_stmt->bind_param("i", $user_id);
        $initial_payment_stmt->execute();
        $initial_payment_result = $initial_payment_stmt->get_result();
        if ($row = $initial_payment_result->fetch_assoc()) {
            $level_for_daily_bonus = (int)$row['level_id'];
        }
        $initial_payment_stmt->close();
    }

    // Fallback to 1
    if ($level_for_daily_bonus === null) {
        $default_level_check_stmt = $conn->prepare("SELECT id FROM levels WHERE id = 1");
        $default_level_check_stmt->execute();
        if ($default_level_check_stmt->get_result()->num_rows > 0) {
            $level_for_daily_bonus = 1;
            error_log("[CLAIM_BONUS_DEBUG] User $user_id - No specific level found, defaulting to level 1 for daily bonus calculation.");
        } else {
            error_log("[CLAIM_BONUS_CRITICAL] User $user_id - Cannot determine level_for_daily_bonus and default level 1 not found in 'levels' table.");
            echo "<script>alert('Cannot determine your current level. Please contact support.'); window.location='claimbonus.php';</script>";
            exit();
        }
        $default_level_check_stmt->close();
    }

    error_log("[CLAIM_BONUS_DEBUG] User $user_id - Final level_for_daily_bonus (current level): $level_for_daily_bonus");

    // Fetch level configuration for daily bonus
    $level_config_stmt = $conn->prepare("SELECT minimum_amount, minimum_profit, maximum_profit FROM levels WHERE id = ?");
    $level_config_stmt->bind_param("i", $level_for_daily_bonus);
    $level_config_stmt->execute();
    $level_config_result = $level_config_stmt->get_result();
    $level_config = $level_config_result->fetch_assoc();
    $level_config_stmt->close();

    if (!$level_config) {
        error_log("[CLAIM_BONUS_DEBUG] User $user_id - Level configuration not found for level_id $level_for_daily_bonus (used for daily bonus calc).");
        echo "<script>alert('Level information for daily bonus not found. Please contact support.'); window.location='claimbonus.php';</script>";
        exit();
    }

    // Calculate daily bonus
    $bonus_base = (float)$level_config['minimum_amount'];
    $min_profit_percent = (float)$level_config['minimum_profit'];
    $max_profit_percent = (float)$level_config['maximum_profit'];
    if ($max_profit_percent < $min_profit_percent) $max_profit_percent = $min_profit_percent;
    $bonus_percent = ($min_profit_percent == $max_profit_percent) ? $min_profit_percent : (mt_rand($min_profit_percent * 100, $max_profit_percent * 100) / 100);
    $daily_bonus = round(($bonus_base * $bonus_percent) / 100, 2);
$success_message = "You claimed daily bonus of $" . number_format($daily_bonus, 2) . "!";
    error_log("[CLAIM_BONUS_DEBUG] User $user_id - Daily Bonus Calculation: Base=$bonus_base, Percent=$bonus_percent, Bonus Amount=$daily_bonus");

    // Insert daily bonus into history
    $insert_bonus_stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, created_at) VALUES (?, ?, 'daily', ?, NOW())");
    $insert_bonus_stmt->bind_param("idi", $user_id, $daily_bonus, $level_for_daily_bonus);

    if ($insert_bonus_stmt->execute()) {
        $daily_bonus_history_id = $insert_bonus_stmt->insert_id;
        $insert_bonus_stmt->close();

        // 1) Credit the user's daily bonus
        creditToWallet($conn, $user_id, $daily_bonus, 'Daily Bonus');
        error_log("[CLAIM_BONUS_DEBUG] User $user_id - Daily bonus of $daily_bonus recorded (ID: $daily_bonus_history_id) and credited.");

        // 2) Pay parent commission based on child's total wallet balance (includes today's credit)
        payUplinesDailyCommissionOnChildBalance($conn, $user_id);

        // --- LEVEL UPGRADE CHECK ---
        // Get total direct investment (from payment table)
        $inv_stmt = $conn->prepare("SELECT SUM(amount) AS total_direct_investment FROM payment WHERE user_id = ?");
        $inv_stmt->bind_param("i", $user_id);
        $inv_stmt->execute();
        $inv_result = $inv_stmt->get_result();
        $total_direct_investment = (float)($inv_result->fetch_assoc()['total_direct_investment'] ?? 0);
        $inv_stmt->close();

        $effective_total_investment = $total_direct_investment + $daily_bonus;

        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Current Level (for upgrade 'from'): $level_for_daily_bonus");
        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Total Direct Investment (Payment): $total_direct_investment");
        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Just Claimed Daily Bonus: $daily_bonus");
        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Effective Total Investment for Upgrade Check (Required Balance): $effective_total_investment");

        // Calculate team counts
        $teamXUserIds = getTeamUserIds($conn, $user_id);
        $teamYUserIds = getTeamUserIds($conn, $teamXUserIds);
        $teamZUserIds = getTeamUserIds($conn, $teamYUserIds);

        $actual_team_x_count = count($teamXUserIds);
        $actual_team_y_count = count($teamYUserIds);
        $actual_team_z_count = count($teamZUserIds);

        $actual_team_yz_count = $actual_team_y_count + $actual_team_z_count;
        $actual_total_xyz_count = $actual_team_x_count + $actual_team_y_count + $actual_team_z_count;

        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Actual Team X: $actual_team_x_count, Team Y: $actual_team_y_count, Team Z: $actual_team_z_count");
        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Actual Team YZ (Y+Z): $actual_team_yz_count");
        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Actual Total Active Team (X+Y+Z): $actual_total_xyz_count");

        // Find the highest eligible level based on level_upgrade_requirements
        $eligible_level_check_stmt = $conn->prepare("
            SELECT lur.level_id
            FROM level_upgrade_requirements lur
            WHERE lur.level_id > ?
              AND ? >= lur.team_x
              AND ? >= lur.team_yz
              AND ? >= lur.total_active_team
              AND ? >= lur.required_balance
            ORDER BY lur.level_id DESC
            LIMIT 1
        ");
        $eligible_level_check_stmt->bind_param("iiiid",
            $level_for_daily_bonus,
            $actual_team_x_count,
            $actual_team_yz_count,
            $actual_total_xyz_count,
            $effective_total_investment
        );
        $eligible_level_check_stmt->execute();
        $eligible_level_result = $eligible_level_check_stmt->get_result();
        $level_data = $eligible_level_result->fetch_assoc();
        $eligible_level_check_stmt->close();

        if ($level_data) {
            $eligible_level = (int)$level_data['level_id'];
            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Eligible for upgrade to Level $eligible_level based on requirements.");

            // Fetch upgrade bonus configuration for the new eligible level
            $bonus_conf_stmt = $conn->prepare("SELECT min_bonus, max_bonus FROM level_upgrade_bonus WHERE level_id = ?");
            $bonus_conf_stmt->bind_param("i", $eligible_level);
            $bonus_conf_stmt->execute();
            $bonus_conf_result = $bonus_conf_stmt->get_result();
            $bonus_info = $bonus_conf_result->fetch_assoc();
            $bonus_conf_stmt->close();

            if ($bonus_info) {
                $min_upgrade_bonus = (float)$bonus_info['min_bonus'];
                $max_upgrade_bonus = (float)$bonus_info['max_bonus'];
                if ($max_upgrade_bonus < $min_upgrade_bonus) $max_upgrade_bonus = $min_upgrade_bonus;

                $upgrade_bonus = ($min_upgrade_bonus == $max_upgrade_bonus) ? $min_upgrade_bonus : (mt_rand($min_upgrade_bonus * 100, $max_upgrade_bonus * 100) / 100);
                $upgrade_bonus = round($upgrade_bonus, 2);
                error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Upgrade bonus for L$eligible_level: $upgrade_bonus (Min: $min_upgrade_bonus, Max: $max_upgrade_bonus)");

                $conn->begin_transaction();
                try {
                    // Record upgrade bonus in history
                    $upgrade_stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, level_to, created_at) VALUES (?, ?, 'upgrade', ?, ?, NOW())");
                    $upgrade_stmt->bind_param("idii", $user_id, $upgrade_bonus, $level_for_daily_bonus, $eligible_level);
                    $upgrade_stmt->execute();
                    $upgrade_history_id = $upgrade_stmt->insert_id;
                    $upgrade_stmt->close();
                    error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Upgrade bonus history recorded (ID: $upgrade_history_id).");

                    // Credit upgrade bonus to wallet
                    creditToWallet($conn, $user_id, $upgrade_bonus, "Level Upgrade Bonus (L$level_for_daily_bonus → L$eligible_level)");

                    // Give team commission (if applicable)
                    giveTeamCommission($conn, $user_id, $upgrade_bonus);

                    // Update user's main level
                    $update_user_level_stmt = $conn->prepare("UPDATE users SET current_level_id = ? WHERE id = ?");
                    if ($update_user_level_stmt) {
                        $update_user_level_stmt->bind_param("ii", $eligible_level, $user_id);
                        if ($update_user_level_stmt->execute()) {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Main level updated to $eligible_level in users table.");
                        } else {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to update main level in users table: " . $update_user_level_stmt->error);
                        }
                        $update_user_level_stmt->close();
                    } else {
                        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to prepare statement for updating users.current_level_id: " . $conn->error);
                    }

                    // Update users_level_upgrade table
                    $update_upgrade_table_stmt = $conn->prepare("UPDATE users_level_upgrade SET bonus_level = ? WHERE user_id = ?");
                    if ($update_upgrade_table_stmt) {
                        $update_upgrade_table_stmt->bind_param("ii", $eligible_level, $user_id);
                        if ($update_upgrade_table_stmt->execute()) {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - users_level_upgrade.bonus_level updated to $eligible_level.");
                        } else {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to update users_level_upgrade.bonus_level: " . $update_upgrade_table_stmt->error);
                        }
                        $update_upgrade_table_stmt->close();
                    } else {
                        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to prepare statement for updating users_level_upgrade.bonus_level: " . $conn->error);
                    }

                    $conn->commit();
                    error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Level Upgrade Transaction COMMITTED. Upgraded from L$level_for_daily_bonus to L$eligible_level with bonus $upgrade_bonus.");
                    $success_message = "Daily bonus claimed! You've been upgraded to Level $eligible_level and received an upgrade bonus of $" . number_format($upgrade_bonus, 2) . "!";
                } catch (Exception $e) {
                    $conn->rollback();
                    error_log("[LEVEL_UPGRADE_ERROR] User $user_id - Level Upgrade Transaction FAILED and ROLLED BACK: " . $e->getMessage());
                }
            } else {
                error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - No upgrade bonus configuration found for eligible level $eligible_level.");

                $conn->begin_transaction();
                try {
                    $upgrade_bonus_if_no_config = 0.00;
                    $upgrade_stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, level_to, created_at) VALUES (?, ?, 'upgrade', ?, ?, NOW())");
                    $upgrade_stmt->bind_param("idii", $user_id, $upgrade_bonus_if_no_config, $level_for_daily_bonus, $eligible_level);
                    $upgrade_stmt->execute();
                    $upgrade_history_id = $upgrade_stmt->insert_id;
                    $upgrade_stmt->close();
                    error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Upgrade to L$eligible_level recorded (ID: $upgrade_history_id) with $0 bonus as not configured.");

                    $update_user_level_stmt = $conn->prepare("UPDATE users SET current_level_id = ? WHERE id = ?");
                    if ($update_user_level_stmt) {
                        $update_user_level_stmt->bind_param("ii", $eligible_level, $user_id);
                        if ($update_user_level_stmt->execute()) {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - Main level updated to $eligible_level in users table (no monetary bonus).");
                        } else {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to update main level in users table (no monetary bonus): " . $update_user_level_stmt->error);
                        }
                        $update_user_level_stmt->close();
                    } else {
                        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to prepare statement for updating users.current_level_id (no monetary bonus): " . $conn->error);
                    }

                    $update_upgrade_table_stmt = $conn->prepare("UPDATE users_level_upgrade SET bonus_level = ? WHERE user_id = ?");
                    if ($update_upgrade_table_stmt) {
                        $update_upgrade_table_stmt->bind_param("ii", $eligible_level, $user_id);
                        if ($update_upgrade_table_stmt->execute()) {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - users_level_upgrade.bonus_level updated to $eligible_level (no monetary bonus).");
                        } else {
                            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to update users_level_upgrade.bonus_level (no monetary bonus): " . $update_upgrade_table_stmt->error);
                        }
                        $update_upgrade_table_stmt->close();
                    } else {
                        error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - FAILED to prepare statement for updating users_level_upgrade.bonus_level (no monetary bonus): " . $conn->error);
                    }

                    $conn->commit();
                    $success_message = "Daily bonus claimed! You've been upgraded to Level $eligible_level!";
                } catch (Exception $e) {
                    $conn->rollback();
                    error_log("[LEVEL_UPGRADE_ERROR] User $user_id - Level Upgrade Transaction (no monetary bonus) FAILED and ROLLED BACK: " . $e->getMessage());
                }
            }
        } else {
            error_log("[LEVEL_UPGRADE_DEBUG] User $user_id - No upgrade applicable. Criteria not met for higher than L$level_for_daily_bonus.");
        }

        header("Location: claimbonus.php?success=" . urlencode($success_message));
        exit();

    } else {
        $error_msg = $insert_bonus_stmt->error ?? 'Unknown error during daily bonus insertion';
        error_log("[CLAIM_BONUS_ERROR] User $user_id - FAILED to insert daily bonus history: $error_msg");
        echo "<script>alert('Error processing your daily bonus. Please try again later or contact support.'); window.location='claimbonus.php';</script>";
        exit();
    }
}

?>