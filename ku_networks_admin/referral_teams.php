<?php
include 'db.php';
include 'check_login.php';


$users_list = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");


$filter_sql = '';
if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
    $referred_by = intval($_GET['referred_by']);
    $filter_sql .= " AND rt.user_id = $referred_by";
}
if (isset($_GET['referred_user']) && $_GET['referred_user'] != '') {
    $referred_user = intval($_GET['referred_user']);
    $filter_sql .= " AND rt.referral_userid = $referred_user";
}

$query = "
SELECT 
    rt.id,
    u1.name AS referred_by,
    u2.name AS referred_user,
    rt.referral_code,
    rt.created_at
FROM 
    referal_teams rt
JOIN users u1 ON rt.user_id = u1.id
JOIN users u2 ON rt.referral_userid = u2.id
WHERE 1=1 $filter_sql
ORDER BY rt.created_at DESC
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
                            <h1>Referral Teams</h1>
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
                                    <h3 class="card-title">All Referral Teams</h3>
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
                                    <form method="get" class="mb-3">
                                            <div class="form-group">
                                                <label >Filter by Referred By:</label>
                                                
                  <select class="select2" name="referred_by" id="referred_by"    data-placeholder="Select User" style="width: 100%;" onchange="this.form.submit()">
                                                    <option value="">-- All --</option>
                                                    <?php
                                                    mysqli_data_seek($users_list, 0);
                                                    while ($user = mysqli_fetch_assoc($users_list)): ?>
                                                        <option value="<?= $user['id'] ?>" <?= (isset($_GET['referred_by']) && $_GET['referred_by'] == $user['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($user['name']) ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="referred_user">Filter by Referred User:</label>
                                                <select name="referred_user" id="referred_user" class="form-control" onchange="this.form.submit()">
                                                    <option value="">-- All --</option>
                                                    <?php
                                                    mysqli_data_seek($users_list, 0);
                                                    while ($user = mysqli_fetch_assoc($users_list)): ?>
                                                        <option value="<?= $user['id'] ?>" <?= (isset($_GET['referred_user']) && $_GET['referred_user'] == $user['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($user['name']) ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                       
                                    </form>
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>Referred By</th>
                                                <th>Referred User </th>
                                                <th>Referral Code</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (mysqli_num_rows($result) > 0): ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                    <tr>
                                                        <td><?= $row['id'] ?></td>
                                                        <td><?= htmlspecialchars($row['referred_by']) ?></td>
                                                        <td><?= htmlspecialchars($row['referred_user']) ?></td>
                                                        <td><?= htmlspecialchars($row['referral_code']) ?: 'N/A' ?></td>
                                                        <td><?= $row['created_at'] ?></td>
                                                        <td>
                                                            <form method="POST" action="delete_referral_team.php"
                                                                onsubmit="return confirm('Are you sure you want to delete this level bonus?');">
                                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                                <button type="submit" name="delete"
                                                                    class="btn btn-danger btn-sm" style="border-radius:15px;">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No Referral Teams Found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>Referred By</th>
                                                <th>Referred User </th>
                                                <th>Referral Code</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
      <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
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
            
            //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    
    
            if ($("#example2").length) {
                var table = $("#example2").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                });
                table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
            }
        });
    </script>

    </script>
</body>

</html>