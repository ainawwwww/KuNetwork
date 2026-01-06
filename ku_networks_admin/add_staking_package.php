<?php
include 'db.php';
include 'check_login.php';

if (isset($_POST['submit'])) {
    $package_name = $_POST['package_name'];
    $min_amount = $_POST['min_amount'];
    $max_amount = $_POST['max_amount'];
    $duration_days = $_POST['duration_days'];
    $daily_profit = $_POST['daily_profit'];

    $stmt = $conn->prepare("INSERT INTO staking_packages (package_name, min_amount, max_amount, duration_days, daily_profit_percentage, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sddid", $package_name, $min_amount, $max_amount, $duration_days, $daily_profit);

    if ($stmt->execute()) {
        $msg = "Staking Package added successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error: " . $stmt->error;
        $msg_type = "danger";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Add Staking Package</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Staking Package</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Package Details</h3>
                            </div>
                            <form method="post">
                                <div class="card-body">
                                    <?php if(isset($msg)): ?>
                                        <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label>Package Name</label>
                                        <input type="text" class="form-control" name="package_name" placeholder="e.g. Silver Plan" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Min Amount ($)</label>
                                                <input type="number" step="0.01" class="form-control" name="min_amount" placeholder="100" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Max Amount ($)</label>
                                                <input type="number" step="0.01" class="form-control" name="max_amount" placeholder="5000" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration (Days)</label>
                                                <input type="number" class="form-control" name="duration_days" placeholder="30" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Daily Profit (%)</label>
                                                <input type="number" step="0.01" class="form-control" name="daily_profit" placeholder="0.5" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <?php include 'footer.php'; ?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>