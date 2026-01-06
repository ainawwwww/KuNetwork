<?php
include 'db.php';
include 'check_login.php';


$users_list = $conn->query("SELECT id, name FROM users ORDER BY name ASC");


$where = '';
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $user_id = intval($_GET['user_id']);
    $where = "WHERE bh.user_id = $user_id";
}

$query = "
    SELECT bh.*, u.name AS user_name, 
           lf.name AS level_from_name, 
           lt.name AS level_to_name
    FROM bonus_history bh
    JOIN users u ON bh.user_id = u.id
    LEFT JOIN levels lf ON bh.level_from = lf.id
    LEFT JOIN levels lt ON bh.level_to = lt.id
    $where
    ORDER BY bh.created_at DESC
";
$result = $conn->query($query);
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
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
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
                            <h1>Bonus History Management</h1>
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
                                    <h3 class="card-title">All Bonus Histories</h3>
                                </div>

                                <!-- Success Message -->
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success">
                                        <?= htmlspecialchars($_GET['success']) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <form method="get" id="userFilterForm" class="mb-3">
                                        <div class="form-group" style="max-width:300px;">
                                            <label for="user_id">Filter by User:</label>
                                            <select name="user_id" id="user_id" class="form-control"
                                                onchange="document.getElementById('userFilterForm').submit();">
                                                <option value="" <?= (!isset($_GET['user_id']) || $_GET['user_id'] == '') ? 'selected' : '' ?>>-- All Users --</option>
                                                <?php
                                                mysqli_data_seek($users_list, 0);
                                                while ($user = $users_list->fetch_assoc()): ?>
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
                                                    <th>S.No</th>
                                                    <th>User</th>
                                                    <th>Bonus Amount</th>
                                                    <th>Type</th>
                                                    <th>Level From</th>
                                                    <th>Level To</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $sn = 1; ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                    <tr>
                                                        <td><?= $sn++ ?></td>
                                                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                                                        <td>$<?= number_format($row['bonus_amount'], 2) ?></td>
                                                        <td><?= ucfirst($row['bonus_type']) ?></td>
                                                        <td><?= $row['level_from_name'] ?? '-' ?></td>
                                                        <td><?= $row['level_to_name'] ?? ($row['bonus_type'] === 'upgrade' ? 'Upgraded' : '-') ?>
                                                        </td>
                                                        <td><?= $row['created_at'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>User</th>
                                                    <th>Bonus Amount</th>
                                                    <th>Type</th>
                                                    <th>Level From</th>
                                                    <th>Level To</th>
                                                    <th>Date</th>
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
                    "scrollX": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "responsive": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });
        
        $(document).ready(function () {
    $('#user_id').select2({
        placeholder: "Select User",
        allowClear: true,
        width: '100%'
    });
});


    </script>

</body>

</html>