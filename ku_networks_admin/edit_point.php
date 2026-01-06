<?php
include 'db.php';
include 'check_login.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: points_settings.php?error=Invalid Point ID");
    exit;
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM points_settings WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: points_settings.php?error=Point not found");
    exit;
}

if (isset($_POST['update'])) {
    $type = $_POST['point_type'];
    $value = $_POST['point_value'];

    $stmt = $conn->prepare("UPDATE points_settings SET point_type=?, point_value=? WHERE id=?");
    $stmt->bind_param("sii", $type, $value, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: points_settings.php?success=Point updated successfully");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Point</title>
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
                    <div class="col-sm-6"><h1>Edit Point</h1></div>
                    <div class="col-sm-6">
                        <a href="points_settings.php" class="btn btn-secondary float-right">Back to Points</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Point Details</h3>
                    </div>
                    <form method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Point Type</label>
                                <input type="text" name="point_type" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['point_type']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Point Value</label>
                                <input type="number" name="point_value" class="form-control" 
                                       value="<?php echo $row['point_value']; ?>" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-info">Update</button>
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
