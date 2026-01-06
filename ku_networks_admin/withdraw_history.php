<?php
include 'db.php';
include 'check_login.php';

// Fetch users for dropdown
$users_list = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");


// Filter logic
$where = '';
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $user_id = intval($_GET['user_id']);
    $where = "WHERE w.user_id = $user_id";
}

$result = $conn->query("
    SELECT 
        w.*, 
        u.name AS user_name
    FROM withdrawals w
    LEFT JOIN users u ON w.user_id = u.id
    $where
    ORDER BY w.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Withdrawal History Management</title>

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
                        <div class="col-sm-6">
                            <h1>Withdrawal History Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Withdrawals</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Withdrawals</h3>
                                </div>

                                <div class="card-body">
                                    <form method="get" id="userFilterForm" class="mb-3">
                                        <div class="form-group">
                                            <label for="user_id">Select User:</label>
                                            <select name="user_id" id="user_id" class="form-control"
                                                onchange="document.getElementById('userFilterForm').submit();">
                                                <option value="" <?= (!isset($_GET['user_id']) || $_GET['user_id'] == '') ? 'selected' : '' ?>>-- All Users --</option>
                                                <?php
                                                mysqli_data_seek($users_list, 0);
                                                while ($user = mysqli_fetch_assoc($users_list)): ?>
                                                    <option value="<?= $user['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($user['name']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </form>

                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Fee %</th>
                                                    <th>Fee Amount</th>
                                                    <th>Status</th>
                                                    <th>Requested At</th>
                                                    <th>Processed At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="withdrawals-table">
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php $s_no = 1; ?>
                                                    <?php while ($row = $result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= $s_no++ ?></td>
                                                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                                                            <td>$<?= number_format($row['amount'], 2) ?></td>
                                                            <td><?= number_format($row['fee_percent'], 2) ?>%</td>
                                                            <td>$<?= number_format($row['fee_amount'], 2) ?></td>
                                                            <td>
                                                                <?php if ($row['status'] == 'approved'): ?>
                                                                    <span class="badge badge-success">Approved</span>
                                                                <?php elseif ($row['status'] == 'rejected'): ?>
                                                                    <span class="badge badge-danger">Rejected</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-warning">Pending</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= $row['created_at'] ?></td>
                                                            <td><?= $row['processed_at'] ?? '-' ?></td>
                                                            <td>
                                                                <form method="GET" action="delete_withdrawal_history.php"
                                                                    onsubmit="return confirm('Are you sure you want to delete this withdrawal?');">
                                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                                    <button class="btn btn-danger btn-sm" style="border-radius:15px;" type="submit" name="delete">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center">No withdrawal history found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>User Name</th>
                                                    <th>Amount</th>
                                                    <th>Fee %</th>
                                                    <th>Fee Amount</th>
                                                    <th>Status</th>
                                                    <th>Requested At</th>
                                                    <th>Processed At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "lengthChange": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

</body>
</html>
