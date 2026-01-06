<?php

error_reporting(E_ALL);
ini_set('display_error',1);
include 'db.php';

include 'check_login.php';


// $withdrawals = $conn->query("
//     SELECT w.*, u.name AS user_name
//     FROM withdrawals w
//     INNER JOIN users u ON w.user_id = u.id
//     WHERE w.status = 'pending'
// ");
$withdrawals = $conn->query("
    SELECT 
        w.*, 
        u.name AS user_name,
        m.status AS membership_status,
        mem.plan_name,
        CAST(REPLACE(REPLACE(SUBSTRING_INDEX(mem.plan_name, ' ', -1), '$', ''), ',', '') AS DECIMAL(10,2)) AS price_decimal
    FROM withdrawals w
    INNER JOIN users u ON w.user_id = u.id
    LEFT JOIN enrolleduserspackages m ON m.user_id = u.id AND m.status = 'active'
    LEFT JOIN membership mem ON mem.id = m.package_id
    WHERE w.status = 'pending'
    ORDER BY 
        (mem.plan_name IS NULL),
        price_decimal DESC,
        w.created_at ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $withdrawal_id = intval($_POST['withdrawal_id']);
    $action = $_POST['action'];

    $withdrawal = $conn->query("SELECT * FROM withdrawals WHERE id = $withdrawal_id")->fetch_assoc();
    $user_id = $withdrawal['user_id'];
    $amount = $withdrawal['amount'];

    if ($action === 'approve') {
        // Deduct the balance from the wallet
        $stmt_update = $conn->prepare("
            UPDATE user_wallets
            SET available_balance = available_balance - ?, 
                total_balance = total_balance - ?, 
                balance = balance - ?
            WHERE user_id = ?
        ");
        $stmt_update->bind_param("dddi", $amount, $amount, $amount, $user_id);
        if ($stmt_update->execute()) {
            // Update the withdrawal status to approved
            $conn->query("UPDATE withdrawals SET status = 'approved', processed_at = NOW() WHERE id = $withdrawal_id");

            echo json_encode(['status' => 'success', 'message' => 'Withdrawal approved successfully.', 'action' => 'approved']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update wallet balance.']);
        }
    } elseif ($action === 'reject') {
        // Update the withdrawal status to rejected
        $conn->query("UPDATE withdrawals SET status = 'rejected', processed_at = NOW() WHERE id = $withdrawal_id");

        echo json_encode(['status' => 'success', 'message' => 'Withdrawal rejected successfully.', 'action' => 'rejected']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | DataTables</title>

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
    <script src="plugins/jquery/jquery.min.js"></script>
</head>
<style>
.member-active {
    background-color: #d4edda !important; /* light green */
}
.non-member {
    background-color: #f8d7da !important; /* light pink */
}
</style>

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
                            <h1>Approve Withdrawals Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">DataTables</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Requested Withdrawals</h3>
                                </div>

                                <div class="card-body">
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">

                                            <thead>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>User Name</th>
                                                    <th>Membership Plan</th>
                                                    <th>Amount</th>
                                                    <th>Requested At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="withdrawals-table">
                                                <?php if ($withdrawals->num_rows > 0): ?>
                                                    <?php $s_no = 1; ?>
                                                    <?php while ($row = $withdrawals->fetch_assoc()): ?>
                                                        <?php
                                                        $colorClass = '';
                                                        if ($row['membership_status'] === 'active') {
                                                            $colorClass = 'member-active'; // green
                                                        } else {
                                                            $colorClass = 'non-member'; // pink
                                                        }
                                                        ?>
                                                        <tr id="withdrawal-<?= $row['id'] ?>" class="<?= $colorClass ?>">
    <td><?= $s_no++ ?></td>
    <td><?= htmlspecialchars($row['user_name']) ?></td>
    <td><?= htmlspecialchars($row['plan_name'] ?? 'No Membership') ?></td> <!-- New column -->
    <td><?= number_format($row['amount'], 2) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
        <button class="btn btn-success btn-sm approve-btn" data-id="<?= $row['id'] ?>">Approve</button>
        <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $row['id'] ?>">Reject</button>
    </td>
</tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No pending withdrawals</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>User Name</th>
                                                    <th>Membership Plan</th>
                                                    <th>Amount</th>
                                                    <th>Requested At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include 'footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
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
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            if ($("#example1").length) {
                $("#example1").DataTable({
                    "scrollX": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "responsive": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });

        $(document).ready(function () {
            // Handle approve button click
            $('.approve-btn').click(function () {
                const withdrawalId = $(this).data('id');
                const row = $(`#withdrawal-${withdrawalId}`);

                $.ajax({
                    url: 'approve_withdrawals.php',
                    type: 'POST',
                    data: { withdrawal_id: withdrawalId, action: 'approve' },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            row.find('td:last').html('<span class="badge badge-success">Approved</span>');
                        }
                    }
                });
            });

            // Handle reject button click
            $('.reject-btn').click(function () {
                const withdrawalId = $(this).data('id');
                const row = $(`#withdrawal-${withdrawalId}`);

                $.ajax({
                    url: 'approve_withdrawals.php',
                    type: 'POST',
                    data: { withdrawal_id: withdrawalId, action: 'reject' },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            row.find('td:last').html('<span class="badge badge-danger">Rejected</span>');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>