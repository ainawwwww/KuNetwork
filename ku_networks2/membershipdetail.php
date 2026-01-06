<?php
require 'checkingAuth.php';
require 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];
$showModal = false;

if (isset($_GET['id'])) {
    $plan_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $user = "SELECT * FROM users WHERE id = $user_id";
    $query = "SELECT * FROM membership WHERE id = $plan_id LIMIT 1";

    $result = mysqli_query($conn, $query);
    $userresult = mysqli_query($conn, $user);

    if ($result && mysqli_num_rows($result) > 0) {
        $membership = mysqli_fetch_assoc($result);
        $userData = mysqli_fetch_assoc($userresult);
    } else {
        die("Invalid membership ID.");
    }
} else {
    die("No membership ID provided.");
}

if (isset($_POST['buy'])) {
    $plan_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // 🚫 Block membership purchase if user has locked capital
    $checkLocked = $conn->prepare("SELECT capital_locked_balance FROM user_wallets WHERE user_id = ?");
    $checkLocked->bind_param("i", $user_id);
    $checkLocked->execute();
    $lockedResult = $checkLocked->get_result();

    if ($lockedResult && $lockedResult->num_rows > 0) {
        $lockedData = $lockedResult->fetch_assoc();
        if (floatval($lockedData['capital_locked_balance']) > 0) {
            header("Location: wallet.php?error=" . urlencode("You cannot purchase membership while your capital is locked."));
            exit();
        }
    }

    $checkWallet = "SELECT * FROM `user_wallets` WHERE user_id = $user_id";
    $referal_teams = "SELECT * FROM `referal_teams` WHERE user_id = $user_id";

    $walet = mysqli_query($conn, $checkWallet);
    $teams = mysqli_query($conn, $referal_teams);

    if ($walet && $teams) {
        if (mysqli_num_rows($walet) > 0) {
            $waletdata = mysqli_fetch_assoc($walet);
            $balance = $waletdata["total_balance"];
            $query = "SELECT * FROM membership WHERE id = $plan_id LIMIT 1";
            $result = mysqli_query($conn, $query);
            $membership = mysqli_fetch_assoc($result);
            $spreading = $membership['plan_name'];

            preg_match_all('/\d+|\D+/', $spreading, $matches);
            $amount = $matches[0][1];

            if ($balance > $amount) {

                $finalwalletbalance = $balance - $amount;
                $buy = "UPDATE user_wallets SET total_balance='$finalwalletbalance', balance='$finalwalletbalance' WHERE user_id = $user_id";

                // Check if user already enrolled
                $checkEnroll = "SELECT * FROM enrolleduserspackages WHERE user_id = $user_id";
                $enrollResult = mysqli_query($conn, $checkEnroll);

                if (mysqli_num_rows($enrollResult) > 0) {
                    $existingData = mysqli_fetch_assoc($enrollResult);

                    // Backup to history
                    $backupQuery = "INSERT INTO enrolleduserspackages_history 
                                    (id, package_id, user_id, username, purchase_date) 
                                    VALUES 
                                    ('{$existingData['id']}', '{$existingData['package_id']}', '{$existingData['user_id']}', 
                                    '{$existingData['username']}', '{$existingData['purchase_date']}')";
                    mysqli_query($conn, $backupQuery);

                    // Update the current row with new package ID & date
                    $updateEnroll = "UPDATE enrolleduserspackages 
                                     SET package_id = '$plan_id', purchase_date = NOW() 
                                     WHERE user_id = $user_id";
                    mysqli_query($conn, $updateEnroll);
                } else {
                    // Insert new if not exists
                    $insertEnroll = "INSERT INTO enrolleduserspackages 
                                     (package_id, user_id, username, purchase_date) 
                                     VALUES 
                                     ('$plan_id', '$user_id', '$username', NOW())";
                    mysqli_query($conn, $insertEnroll);
                }

                $showModal = true;
            } else {
                header("Location: wallet.php?error=" . urlencode("Please fill the wallet"));
                exit();
            }
        } else {
            header("Location: wallet.php?error=" . urlencode("Please fill the wallet"));
            exit();
        }

        // ✅ Unlock user's locked balance after successful membership purchase
        $wallet_query = $conn->prepare("SELECT capital_locked_balance, available_balance FROM user_wallets WHERE user_id = ?");
        $wallet_query->bind_param("i", $user_id);
        $wallet_query->execute();
        $wallet_result = $wallet_query->get_result();

        if ($wallet_result->num_rows > 0) {
            $wallet = $wallet_result->fetch_assoc();
            $locked_balance = floatval($wallet['capital_locked_balance']);
            $available_balance = floatval($wallet['available_balance']);

            if ($locked_balance > 0) {
                $new_available = $available_balance + $locked_balance;

                $update_wallet = $conn->prepare("
                    UPDATE user_wallets 
                    SET available_balance = ?, capital_locked_balance = 0, last_transaction = NOW()
                    WHERE user_id = ?
                ");
                $update_wallet->bind_param("di", $new_available, $user_id);
                $update_wallet->execute();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Detail</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/colors/scheme-01.css" rel="stylesheet" />
    <link href="css/coloring.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .membership-card { max-width: 700px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .membership-header { border-bottom: 2px solid #007bff; padding-bottom: 12px; margin-bottom: 20px; }
        .detail-label { font-weight: 600; color: #495057; }
        .detail-value { font-weight: 500; color: #212529; }
        .price-tag { color: #28a745; font-weight: 700; font-size: 1.3rem; }
        .username { font-style: italic; color: #6c757d; }
    </style>
</head>

<body>
    <?php include 'Assets/header.php'; ?>
    <br><br>
    <div class="no-bottom no-top" id="content">
        <div class="membership-card">
            <div class="membership-header">
                <h2 class="mb-0"><?= htmlspecialchars($membership['plan_name']) ?></h2>
                <small class="username">User: <?= isset($userData['name']) ? htmlspecialchars($userData['name']) : 'Unknown User' ?></small>
            </div>

            <div class="mb-3">
                <span class="detail-label">Details:</span>
                <p class="detail-value"><?= nl2br(htmlspecialchars($membership['member_detail'])) ?></p>
            </div>

            <div class="mb-3">
                <span class="detail-label">Price:</span>
                <span class="price-tag">₨<?= htmlspecialchars($membership['withdraw_fee']) ?>/month</span>
            </div>

            <div class="mb-3">
                <span class="detail-label">Customer Support:</span>
                <span class="detail-value"><?= htmlspecialchars($membership['customer_support']) ?></span>
            </div>

            <div class="mb-3">
                <span class="detail-label">Capital:</span>
                <span class="detail-value"><?= htmlspecialchars($membership['withdraw_capital']) ?></span>
            </div>

            <div class="mb-3">
                <span class="detail-label">Processing Time:</span>
                <span class="detail-value"><?= htmlspecialchars($membership['withdraw_processing_time']) ?></span>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="plan_id" value="<?= htmlspecialchars($membership['id']) ?>">
                <button type="submit" name="buy" class="btn btn-success mt-4">
                    <i class="fa fa-shopping-cart"></i> Buy Now
                </button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="tokenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="position-relative text-center" style="width: 350px;">
                    <img src="images/wallet/Token.png" alt="Token" class="img-fluid w-100 mb-5" style="border-radius: 100px;" />
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal"></button>
                    <div class="position-absolute top-50 start-50 translate-middle w-100 px-5 pt-5 text-start">
                        <h5><span>Congratulations!</span></h5>
                        <p class="fw-bold text-dark">Congratulations! You have successfully purchased the membership.</p>
                        <div class="text-center mt-5 pt-4">
                            <p class="text-dark btn-claim-text mb-1">Tap the button to view the number of tokens</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($showModal): ?>
        $(document).ready(function () {
            var tokenModal = new bootstrap.Modal(document.getElementById('tokenModal'));
            tokenModal.show();
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 3000);
        });
        <?php endif; ?>
    </script>
    <?php include 'Assets/footer.php'; ?>
</body>
</html>
