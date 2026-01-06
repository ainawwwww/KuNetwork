<?php

include 'db.php';
include 'check_login.php';

if (isset($_POST['save'])) {
    $type = $_POST['point_type'];
    $value = $_POST['point_value'];

    $stmt = $conn->prepare("INSERT INTO points_settings (point_type, point_value) VALUES (?, ?)");
    $stmt->bind_param("si", $type, $value);
    $stmt->execute();
    $stmt->close();

    header("Location: points_settings.php?success=Point added successfully");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Point</title>
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
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
                    <div class="col-sm-6"><h1>Add New Point</h1></div>
                    <div class="col-sm-6">
                        <a href="points_settings.php" class="btn btn-secondary float-right">Back to Points</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Point Details</h3>
                    </div>
                    <form method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Point Type</label>
                                <input type="text" name="point_type" class="form-control" placeholder="Enter point type" required>
                            </div>
                            <div class="form-group">
                                <label>Point Value</label>
                                <input type="number" name="point_value" class="form-control" placeholder="Enter point value" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="save" class="btn btn-success">Save</button>
                            <a href="points_settings.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
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
