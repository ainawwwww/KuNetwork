<?php
include 'db.php';
include 'check_login.php';

$query = "
   SELECT t.*, 
       u_from.name AS from_user_name, 
       u_to.name AS to_user_name 
FROM team_commission_history t
JOIN users u_from ON t.from_user_id = u_from.id
JOIN users u_to ON t.to_user_id = u_to.id
ORDER BY t.id DESC;
";
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
                            <h1>Team Commission History Management</h1>
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
                                    <h3 class="card-title">All Team Commission History</h3>
                                </div>

                                <!-- Success Message -->
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= htmlspecialchars($_GET['success']) ?>

                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= htmlspecialchars($_GET['error']) ?>

                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <form method="GET" class="mb-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="filter_user">Filter by To User:</label>
                                                <select name="filter_user" id="filter_user" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">All Users</option>
                                                    <?php
                                                    $user_result = $conn->query("SELECT DISTINCT u_to.id, u_to.name FROM team_commission_history t JOIN users u_to ON t.to_user_id = u_to.id ORDER BY u_to.name");
                                                    while ($u = $user_result->fetch_assoc()) {
                                                        $selected = (isset($_GET['filter_user']) && $_GET['filter_user'] == $u['id']) ? 'selected' : '';
                                                        echo "<option value='{$u['id']}' $selected>{$u['name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="from_user">Filter by From User:</label>
                                                <select name="from_user" id="from_user" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">All Users</option>
                                                    <?php
                                                    $from_result = $conn->query("SELECT DISTINCT u_from.id, u_from.name FROM team_commission_history t JOIN users u_from ON t.from_user_id = u_from.id ORDER BY u_from.name");
                                                    while ($fu = $from_result->fetch_assoc()) {
                                                        $selected = (isset($_GET['from_user']) && $_GET['from_user'] == $fu['id']) ? 'selected' : '';
                                                        echo "<option value='{$fu['id']}' $selected>{$fu['name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>From User</th>
                                                    <th>To User</th>
                                                    <th>Amount (USDT)</th>
                                                    <th>Level</th>
                                                    <th>Percentage</th>
                                                    <th>Commission Type</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sn = 1;
                                                $filter_sql = "";
                                                if (isset($_GET['filter_user']) && $_GET['filter_user'] != '') {
                                                    $filter_id = intval($_GET['filter_user']);
                                                    $filter_sql = " AND t.to_user_id = $filter_id ";
                                                }
                                                if (isset($_GET['from_user']) && $_GET['from_user'] != '') {
                                                    $from_id = intval($_GET['from_user']);
                                                    $filter_sql .= " AND t.from_user_id = $from_id ";
                                                }
                                                $query = "
                                                   SELECT t.*, 
                                                       u_from.name AS from_user_name, 
                                                       u_to.name AS to_user_name 
                                                    FROM team_commission_history t
                                                    JOIN users u_from ON t.from_user_id = u_from.id
                                                    JOIN users u_to ON t.to_user_id = u_to.id
                                                    WHERE 1=1 $filter_sql
                                                    ORDER BY t.id DESC
                                                    ";
                                                $result = mysqli_query($conn, $query);
                                                if (mysqli_num_rows($result) > 0):
                                                    while ($row = mysqli_fetch_assoc($result)):

                                                       $badge = '';
                                                        if ($row['level'] == 1)
                                                            $badge = '<span class="badge badge-success">TEAM X</span>';
                                                        elseif ($row['level'] == 2)
                                                            $badge = '<span class="badge badge-warning">TEAM Y</span>';
                                                        else
                                                            $badge = '<span class="badge badge-info">TEAM Z</span>';
                                                        ?>

                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= htmlspecialchars($row['from_user_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['to_user_name']) ?></td>
                                                            <td><?= $row['amount'] ?></td>
                                                            <td><?= $row['level'] ?></td>
                                                            <td><?= $row['percentage'] ?>%</td>
                                                            <td><?= $badge ?></td>
                                                            <td><?= $row['created_at'] ?></td>
                                                        </tr>
                                                    <?php endwhile; else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>From User</th>
                                                    <th>To User</th>
                                                    <th>Amount (USDT)</th>
                                                    <th>Level</th>
                                                    <th>Percentage</th>
                                                    <th>Commission Type</th>
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
    $('#filter_user, #from_user').select2({
        placeholder: "Select User",
        allowClear: true,
        width: '100%'
    });
});

    </script>



</body>

</html>