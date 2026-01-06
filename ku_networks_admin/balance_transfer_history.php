<?php

include 'db.php';
include 'check_login.php';


$users_list = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");


$where = [];
if (isset($_GET['sender_id']) && $_GET['sender_id'] != '') {
    $sender_id = intval($_GET['sender_id']);
    $where[] = "b.sender_id = $sender_id";
}
if (isset($_GET['receiver_id']) && $_GET['receiver_id'] != '') {
    $receiver_id = intval($_GET['receiver_id']);
    $where[] = "b.receiver_id = $receiver_id";
}
$where_sql = '';
if (count($where) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
}

$query = "SELECT b.*, 
          u1.name AS sender_name, 
          u2.name AS receiver_name 
          FROM balance_transfers b
JOIN users u1 ON b.sender_id = u1.id
JOIN users u2 ON b.receiver_id = u2.id
$where_sql
ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

</head>
<style>
.select2-container--default .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #80bdff;
    box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
}
.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 4px;
}
.select2-search__field {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ced4da;
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
                            <h1>Balance Transfer History Management</h1>
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
                                    <h3 class="card-title">All Balance Transfer Histories</h3>
                                </div>


                                <div class="card-body">
                                    <form method="get" class="mb-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="sender_id">Filter by Sender:</label>
                                                <select name="sender_id" id="sender_id" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">-- All Senders --</option>
                                                    <?php
                                                    mysqli_data_seek($users_list, 0);
                                                    while ($user = mysqli_fetch_assoc($users_list)): ?>
                                                        <option value="<?= $user['id'] ?>" <?= (isset($_GET['sender_id']) && $_GET['sender_id'] == $user['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($user['name']) ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="receiver_id">Filter by Receiver:</label>
                                                <select name="receiver_id" id="receiver_id" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">-- All Receivers --</option>
                                                    <?php
                                                    mysqli_data_seek($users_list, 0);
                                                    while ($user = mysqli_fetch_assoc($users_list)): ?>
                                                        <option value="<?= $user['id'] ?>" <?= (isset($_GET['receiver_id']) && $_GET['receiver_id'] == $user['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($user['name']) ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">

                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Sender</th>
                                                    <th>Receiver</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Receiver Status</th>
                                                    <th>Admin Approval Status</th>
                                                    <th>Code</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sn = 1;
                                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                                    <tr>
                                                        <td><?= $sn++ ?></td>
                                                        <td><?= $row['sender_name'] ?> (ID: <?= $row['sender_id'] ?>)</td>
                                                        <td><?= $row['receiver_name'] ?> (ID: <?= $row['receiver_id'] ?>)
                                                        </td>
                                                        <td><?= number_format($row['amount'], 2) ?></td>
                                                        <td><?= ucfirst($row['receiver_accept_status']) ?></td>
                                                        <td><?= ucfirst($row['admin_approval_status']) ?></td>
                                                        <td><?= $row['code'] ?></td>
                                                        <td><?= $row['created_at'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Sender</th>
                                                    <th>Receiver</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Receiver Status</th>
                                                    <th>Admin Approval Status</th>
                                                    <th>Code</th>
                                                    <th>Created At</th>
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
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            if ($("#example1").length) {
                $("#example1").DataTable({
                    // "scrollX": true,
                    "lengthChange": false,
                    // "autoWidth": false,
                    // "responsive": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });


$(document).ready(function () {
    $('#sender_id, #receiver_id').select2({
        placeholder: "Select User",
        allowClear: true,
        width: '100%'
    });
});
    </script>

</body>

</html>