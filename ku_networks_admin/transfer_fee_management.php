<?php
// transfer_fee_management.php
include 'db.php';
include 'check_login.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle POST: add or delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add') {
            $name = trim($_POST['name'] ?? '');
            $fee_input = $_POST['fee'] ?? '';
if (!is_numeric($fee_input)) {
    header("Location: transfer_fee_management.php?error=" . urlencode("Fee must be a number."));
    exit();
}
$fee_percent_input = floatval($fee_input);
if ($fee_percent_input < 0 || $fee_percent_input > 100) {
    header("Location: transfer_fee_management.php?error=" . urlencode("Fee must be between 0 and 100."));
    exit();
}
$fee = $fee_percent_input / 100.0;

$stmt = $conn->prepare("INSERT INTO transferfee (name, fee) VALUES (?, ?)");
$stmt->bind_param("sd", $name, $fee);
$stmt->execute();
            $stmt->close();

            header("Location: transfer_fee_management.php?success=" . urlencode("Fee added successfully."));
            exit();

        } elseif ($action === 'delete') {
            $sno = intval($_POST['sno'] ?? 0);
            if ($sno <= 0) {
                header("Location: transfer_fee_management.php?error=" . urlencode("Invalid ID for delete."));
                exit();
            }

            $stmt = $conn->prepare("DELETE FROM transferfee WHERE sno = ?");
            $stmt->bind_param("i", $sno);
            $stmt->execute();
            $stmt->close();

            header("Location: transfer_fee_management.php?success=" . urlencode("Fee deleted successfully."));
            exit();
        }
    } catch (Exception $e) {
        header("Location: transfer_fee_management.php?error=" . urlencode("DB Error: " . $e->getMessage()));
        exit();
    }
}

// Fetch all fees
$query = "SELECT * FROM transferfee ORDER BY sno ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transfer Fee Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
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
                        <div class="col-sm-6"><h1>Transfer Fee Management</h1></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Transfer Fees</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Messages -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success" id="success-alert"><?= htmlspecialchars($_GET['success']) ?></div>
                        <script>
                            if (window.history.replaceState) {
                                const url = new URL(window.location);
                                url.searchParams.delete('success');
                                window.history.replaceState({}, document.title, url.pathname + url.search);
                            }
                        </script>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger" id="error-alert"><?= htmlspecialchars($_GET['error']) ?></div>
                        <script>
                            if (window.history.replaceState) {
                                const url = new URL(window.location);
                                url.searchParams.delete('error');
                                window.history.replaceState({}, document.title, url.pathname + url.search);
                            }
                        </script>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">All Transfer Fees</h3>
                                    <div>
                                        <!-- Button to open Add Modal -->
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFeeModal">
                                            <i class="fas fa-plus"></i> Add New Fee
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="feeTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>Name</th>
                                                    <th>Fee (%)</th>
                                                    <th>Fee (decimal)</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php $sn = 1; while ($row = $result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                                            <td><?= htmlspecialchars(number_format(floatval($row['fee']) * 100, 2)) ?>%</td>
                                                            <td><?= htmlspecialchars(number_format(floatval($row['fee']), 4)) ?></td>
                                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="edit_transferfee.php?id=<?= intval($row['sno']) ?>"
                                                                        class="btn btn-info btn-sm mx-1" style="border-radius: 15px;">
                                                                        <i class="fas fa-pencil-alt"></i>
                                                                    </a>

                                                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                        <input type="hidden" name="action" value="delete">
                                                                        <input type="hidden" name="sno" value="<?= intval($row['sno']) ?>">
                                                                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 15px;">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr><td colspan="6" class="text-center">No records found</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>Name</th>
                                                    <th>Fee (%)</th>
                                                    <th>Fee (decimal)</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <?php include 'footer.php'; ?>

        <!-- Add Fee Modal -->
        <div class="modal fade" id="addFeeModal" tabindex="-1" role="dialog" aria-labelledby="addFeeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <form method="POST" action="transfer_fee_management.php">
                <input type="hidden" name="action" value="add">
                <div class="modal-header">
                  <h5 class="modal-title" id="addFeeModalLabel">Add New Transfer Fee</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="feeName">Name</label>
                      <input type="text" class="form-control" id="feeName" name="name" required placeholder="e.g. default_fee">
                    </div>
                    <div class="form-group">
                      <label for="feeValue">Fee (decimal)</label>
                      <input type="number" step="0.0001" min="0" class="form-control" id="feeValue" name="fee" required placeholder="e.g. 0.05">
                      <small class="form-text text-muted">Enter value as decimal (e.g. 0.05 for 5%).</small>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add Fee</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div><!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script>
    $(function () {
        if ($("#feeTable").length) {
            $("#feeTable").DataTable({
                "scrollX": true,
                "lengthChange": false,
                "autoWidth": false,
                "responsive": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#feeTable_wrapper .col-md-6:eq(0)');
        }
    });
    </script>
</body>
</html>