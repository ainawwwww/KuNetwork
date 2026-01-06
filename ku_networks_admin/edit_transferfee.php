<?php
include './db.php';
include 'check_login.php';

// Get id from GET (or redirect)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: transfer_fee_management.php?error=" . urlencode("Invalid fee ID."));
    exit();
}

// Fetch fee row safely
try {
    $stmt = $conn->prepare("SELECT sno, name, fee, created_at FROM transferfee WHERE sno = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $fee_row = $res->fetch_assoc();
    $stmt->close();

    if (!$fee_row) {
        header("Location: transfer_fee_management.php?error=" . urlencode("Fee record not found."));
        exit();
    }
} catch (Exception $e) {
    header("Location: transfer_fee_management.php?error=" . urlencode("DB Error: " . $e->getMessage()));
    exit();
}

// Safe values for form
$fee_name = $fee_row['name'] ?? '';
$fee_value = isset($fee_row['fee']) ? number_format((float)$fee_row['fee'], 4, '.', '') : '';
$created_at = $fee_row['created_at'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Transfer Fee</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include 'sidebar.php'; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Transfer Fee</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="transfer_fee_management.php">Fees</a></li>
                            <li class="breadcrumb-item active">Edit Fee</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Messages (optional) -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                    <script>
                        if (window.history.replaceState) {
                            const url = new URL(window.location);
                            url.searchParams.delete('error');
                            window.history.replaceState({}, document.title, url.pathname + url.search);
                        }
                    </script>
                <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                    <script>
                        if (window.history.replaceState) {
                            const url = new URL(window.location);
                            url.searchParams.delete('success');
                            window.history.replaceState({}, document.title, url.pathname + url.search);
                        }
                    </script>
                <?php endif; ?>

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Update this Transfer Fee</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Form posts to update_transferfee.php (create this handler) -->
                                <form method="POST" action="update_transferfee.php" class="form">
                                    <input type="hidden" name="sno" value="<?= intval($fee_row['sno']) ?>">

                                    <div class="form-group">
                                        <label for="name">Fee Name</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                               value="<?= htmlspecialchars($fee_name) ?>" required
                                               placeholder="e.g. default_fee">
                                    </div>

                                    <div class="form-group">
    <label for="fee">Fee (%)</label>
    <input type="number" id="fee" name="fee" class="form-control" step="0.01" min="0"
           value="<?= htmlspecialchars(number_format((float)$fee_row['fee'] * 100, 2, '.', '')) ?>" required
           placeholder="e.g. 5">
    <small class="form-text text-muted">Enter fee as percent (e.g. 5 for 5%).</small>
</div>

                                    <div class="form-group">
                                        <label for="created_at">Created At</label>
                                        <input type="text" id="created_at" class="form-control" value="<?= htmlspecialchars($created_at) ?>" readonly>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">Update Fee</button>
                                </form>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include 'footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- InputMask / Date plugins (kept for consistency with your stage page) -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements if you add any select2 fields later
        $('.select2').select2();
        $('.select2bs4').select2({theme: 'bootstrap4'});

        // Input masks (kept as in your stage page)
        $('[data-mask]').inputmask();
    });
</script>
</body>
</html>