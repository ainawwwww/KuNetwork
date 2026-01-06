<?php
include 'db.php';
include 'check_login.php';

$query = "SELECT * FROM monthly_tournament_competition";
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
                            <h1>Monthly Tournament Competition Management</h1>
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
                                    <h3 class="card-title">All Monthly Tournament Competitions</h3>
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
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>Tournament Range</th>
                                                    <th>Entry Fee (USD)</th>
                                                    <th>Team Business (Minimum)</th>
                                                    <th>1st Prize (USDT)</th>
                                                    <th>2nd Prize (USDT)</th>
                                                    <th>3rd Prize (USDT)</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($result) > 0): ?>
                                                    <?php $sn = 1; ?>
                                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td>
                                                                <?= htmlspecialchars($row['tournament_range']) . (strpos($row['tournament_range'], 'Member') === false ? ' Member' : '') ?>
                                                            </td>
                                                            <td><?= $row['entry_fee'] ?> USD</td>
                                                            <td><?= $row['team_business_min'] ?> USDT</td>
                                                            <td><?= $row['first_prize'] ?> USDT</td>
                                                            <td><?= $row['second_prize'] ?> USDT</td>
                                                            <td><?= $row['third_prize'] ?> USDT</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="edit_tournament_competition.php?id=<?= $row['id'] ?>"
                                                                        class="btn btn-info btn-sm mx-1"
                                                                        style="border-radius: 15px;">
                                                                        <i class="fas fa-pencil-alt"></i>
                                                                    </a>
                                                                    <form method="POST"
                                                                        action="delete_tournament_competition.php"
                                                                        onsubmit="return confirm('Are you sure you want to delete this monthly salary bonus?');">
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
                                                        <td colspan="8" class="text-center">No records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>Tournament Range</th>
                                                    <th>Entry Fee (USD)</th>
                                                    <th>Team Business (Minimum)</th>
                                                    <th>1st Prize (USDT)</th>
                                                    <th>2nd Prize (USDT)</th>
                                                    <th>3rd Prize (USDT)</th>
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

    </script>

</body>

</html>