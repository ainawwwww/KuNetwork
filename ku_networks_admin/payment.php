<?php
include 'db.php';
include 'check_login.php';

// Users for dropdown
$users_list = $conn->query("SELECT id, name FROM users ORDER BY name ASC");

// Filter logic
$where = '';
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $user_id = intval($_GET['user_id']);
    $where = "WHERE payment.user_id = $user_id";
}

$result = $conn->query("
   SELECT payment.*, users.name AS username, levels.name AS levelname 
FROM payment
LEFT JOIN users ON payment.user_id = users.id
LEFT JOIN levels ON payment.level_id = levels.id
$where
ORDER BY payment.created_at DESC
");
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
                            <h1>Payments Management</h1>
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
                                    <h3 class="card-title">All Payments</h3>
                                </div>

                                <!-- Success Message -->
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success" id="success-alert">
                                        <?= htmlspecialchars($_GET['success']) ?>
                                    </div>
                                    <script>
                                        if (window.history.replaceState) {
                                            const url = new URL(window.location);
                                            url.searchParams.delete('success');
                                            window.history.replaceState({}, document.title, url.pathname + url.search);
                                        }
                                    </script>
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
                                                    <th>S.NO</th>
                                                    <th>User</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Level</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($result) > 0): ?>
                                                    <?php $sn = 1; ?>
                                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                                            <td><?= $row['amount'] ?></td>
                                                            <td><?= $row['levelname'] ?? 'N/A' ?></td>
                                                            <td><?= $row['created_at'] ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <form method="POST" action="delete_payment.php"
                                                                        onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $row['id'] ?>">
                                                                        <button type="submit" name="delete"
                                                                            class="btn btn-danger btn-sm"
                                                                            style="border-radius: 15px;">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>User</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Level</th>
                                                    <th>Date</th>
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