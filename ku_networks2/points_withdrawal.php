<?php
require 'user_data_logic.php';

// --- Handle Claim Logic Here ---
$flash_success = null;
$flash_error = null;

$points_bonus_map = [100 => 10, 500 => 60, 1000 => 130, 1500 => 200, 5000 => 700];
// Calculate eligibility
$eligible_amount = 0;
$eligible_threshold = null;
foreach ($points_bonus_map as $th => $bonus) {
    if ($userPoints >= $th) {
        $eligible_amount = $bonus;
        $eligible_threshold = $th;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_points_withdraw'])) {
    
    // ... (Paste the POST claim logic from the original file here) ...
    // Note: Use $conn from user_data_logic.php
    // Ensure to update $flash_success or $flash_error messages
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Points Withdrawal</title>
  <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="custom_dashboard.css">
    <link rel="stylesheet" href="custom_account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <!-- CSS Files -->
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="container py-5">
           
            
            <div class="card list-item-card">
                <div class="card-header">
                    <span><i class="fas fa-hand-holding-dollar card-title-icon text-warning"></i> Points Withdrawal</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-center border-end">
                            <h5 class="text-muted">Your Points</h5>
                            <h2 class="text-primary fw-bold"><?php echo number_format($userPoints); ?></h2>
                            
                            <ul class="list-group list-group-flush mt-4 text-start">
                                <?php foreach ($points_bonus_map as $th => $bonus): ?>
                                    <li class="list-group-item d-flex justify-content-between <?php echo ($userPoints >= $th) ? 'bg-success-subtle' : ''; ?>">
                                        <span><?php echo $th; ?> pts</span>
                                        <span class="fw-bold"><?php echo $bonus; ?> USDT</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center p-4">
                            <?php if ($eligible_amount > 0): ?>
                                <div class="alert alert-success w-100 text-center">
                                    Eligible for: <strong>$<?php echo $eligible_amount; ?></strong>
                                </div>
                                <form method="post" class="w-100">
                                    <input type="hidden" name="claim_points_withdraw" value="1">
                                    <button type="submit" class="btn btn-primary w-100 btn-lg">Claim Now</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-secondary w-100 text-center">
                                    Keep earning points to reach the next threshold!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>
    <?php if($flash_success): ?><script>alert('<?php echo $flash_success; ?>');</script><?php endif; ?>
    <?php if($flash_error): ?><script>alert('<?php echo $flash_error; ?>');</script><?php endif; ?>
</body>
</html>