<?php
// admin_actions/trigger_weekly_bonus.php
include '../config.php'; // Adjust path to your DB config
session_start();

// Ensure admin is logged in (implement your admin auth check)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Admin access required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trigger_weekly_offer'])) {
    $deposit_required = 200.00;
    $profit_percentage = 20.00;
    $lock_duration_hours = 6;
    $offer_duration_days = 7; // How long users have to make the deposit after offer creation

    $conn->begin_transaction();
    try {
        // 1. Get all users who have made at least one payment
        $stmt_users = $conn->prepare("SELECT DISTINCT user_id FROM payment"); // Assuming 'payment' table logs all deposits
        if (!$stmt_users) throw new Exception("Prepare failed (stmt_users): " . $conn->error);
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();
        $eligible_user_ids = [];
        while ($row = $result_users->fetch_assoc()) {
            $eligible_user_ids[] = $row['user_id'];
        }
        $stmt_users->close();

        if (empty($eligible_user_ids)) {
            throw new Exception("No eligible users found (users who have made at least one payment).");
        }

        $admin_trigger_batch_id = "WB_" . date("YmdHis");
        $offer_expires_at_dt = new DateTime();
        $offer_expires_at_dt->modify("+" . $offer_duration_days . " days");
        $offer_expires_at_sql = $offer_expires_at_dt->format('Y-m-d H:i:s');

        $stmt_insert_offer = $conn->prepare("
            INSERT INTO weekly_bonus_offers 
            (user_id, admin_trigger_batch_id, deposit_required, profit_percentage, lock_duration_hours, offer_expires_at, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending_popup')
        ");
        if (!$stmt_insert_offer) throw new Exception("Prepare failed (stmt_insert_offer): " . $conn->error);

        $inserted_count = 0;
        foreach ($eligible_user_ids as $user_id) {
            // Optional: Check if user already has an active 'pending_popup' or 'popup_shown' offer
            // For simplicity, we're allowing multiple offers, but you might want to limit this.
            $stmt_insert_offer->bind_param("isddis", 
                $user_id, 
                $admin_trigger_batch_id, 
                $deposit_required, 
                $profit_percentage, 
                $lock_duration_hours,
                $offer_expires_at_sql
            );
            if ($stmt_insert_offer->execute()) {
                $inserted_count++;
            } else {
                error_log("Failed to insert weekly offer for user $user_id: " . $stmt_insert_offer->error);
            }
        }
        $stmt_insert_offer->close();

        $conn->commit();
        $message = "Successfully created weekly bonus offers for $inserted_count eligible users. Batch ID: $admin_trigger_batch_id";
        error_log("[ADMIN_WEEKLY_BONUS] " . $message);

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error triggering weekly bonus: " . $e->getMessage();
        error_log("[ADMIN_WEEKLY_BONUS_ERROR] " . $message);
    }
    // Redirect back to admin page or show message
    header("Location: ../admin_dashboard.php?weekly_bonus_message=" . urlencode($message)); // Adjust redirect
    exit();
}
?>

<!-- Simple form for the admin panel -->
<!DOCTYPE html>
<html>
<head><title>Trigger Weekly Bonus</title></head>
<body>
    <h2>Trigger Weekly Bonus Offer</h2>
    <p>This will create an offer for all users who have made at least one payment.</p>
    <p>Offer: Deposit $200, get 20% profit, locked for 6 hours. Users have 7 days to act.</p>
    <form method="POST" action="trigger_weekly_bonus.php">
        <button type="submit" name="trigger_weekly_offer" onclick="return confirm('Are you sure you want to trigger this offer for all eligible users?');">Trigger Offer Now</button>
    </form>
    <?php if(isset($_GET['weekly_bonus_message'])) echo "<p>" . htmlspecialchars($_GET['weekly_bonus_message']) . "</p>"; ?>
</body>
</html>