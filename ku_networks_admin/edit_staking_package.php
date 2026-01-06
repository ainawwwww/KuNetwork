<?php
include 'db.php';
include 'check_login.php';

// 1. Get ID from URL and Fetch Existing Data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM staking_packages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
    $stmt->close();

    // Agar package nahi mila to wapas bhej do
    if (!$package) {
        header("Location: manage_staking_packages.php");
        exit();
    }
} else {
    header("Location: manage_staking_packages.php");
    exit();
}

// 2. Handle Update Form Submission
if (isset($_POST['update'])) {
    $package_name = $_POST['package_name'];
    $min_amount = $_POST['min_amount'];
    $max_amount = $_POST['max_amount'];
    $duration_days = $_POST['duration_days'];
    $daily_profit = $_POST['daily_profit'];
    $status = $_POST['status'];

    $updateStmt = $conn->prepare("UPDATE staking_packages SET package_name=?, min_amount=?, max_amount=?, duration_days=?, daily_profit_percentage=?, status=? WHERE id=?");
    $updateStmt->bind_param("sddidsi", $package_name, $min_amount, $max_amount, $duration_days, $daily_profit, $status, $id);

    if ($updateStmt->execute()) {
        $msg = "Package updated successfully!";
        $msg_type = "success";
        
        // Refresh data to show new values immediately
        $package['package_name'] = $package_name;
        $package['min_amount'] = $min_amount;
        $package['max_amount'] = $max_amount;
        $package['duration_days'] = $duration_days;
        $package['daily_profit_percentage'] = $daily_profit;
        $package['status'] = $status;
    } else {
        $msg = "Error updating record: " . $updateStmt->error;
        $msg_type = "danger";
    }
    $updateStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Edit Staking Package</title>

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
                        <h1>Edit Staking Package</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="manage_staking_packages.php">Packages</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Update Package Details</h3>
                            </div>
                            <form method="post">
                                <div class="card-body">
                                    <?php if(isset($msg)): ?>
                                        <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label>Package Name</label>
                                        <input type="text" class="form-control" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Min Amount ($)</label>
                                                <input type="number" step="0.01" class="form-control" name="min_amount" value="<?php echo $package['min_amount']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Max Amount ($)</label>
                                                <input type="number" step="0.01" class="form-control" name="max_amount" value="<?php echo $package['max_amount']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Duration (Days)</label>
                                                <input type="number" class="form-control" name="duration_days" value="<?php echo $package['duration_days']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Daily Profit (%)</label>
                                                <input type="number" step="0.01" class="form-control" name="daily_profit" value="<?php echo $package['daily_profit_percentage']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" name="status">
                                                    <option value="active" <?php if($package['status'] == 'active') echo 'selected'; ?>>Active</option>
                                                    <option value="inactive" <?php if($package['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" name="update" class="btn btn-warning">Update Package</button>
                                    <a href="manage_staking_packages.php" class="btn btn-default float-right">Cancel</a>
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
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>