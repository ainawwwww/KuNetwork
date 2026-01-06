<?php
include 'db.php';
include 'check_login.php';

$query = "SELECT * FROM points_settings ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Points Settings</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                    <div class="col-sm-6"><h1>Points Settings</h1></div>
                    <div class="col-sm-6">
                        <a href="add_point.php" class="btn btn-success float-right">Add New Point</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <?php if(isset($_GET['success'])): ?>
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
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Point Type</th>
                                    <th>Point Value</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['point_type']) ?></td>
                                        <td><?= $row['point_value'] ?></td>
                                        <td><?= $row['created_at'] ?></td>
                                        <td>
                                            <a href="edit_point.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm mx-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="delete_point.php?id=<?= $row['id'] ?>"
                                               class="btn btn-danger btn-sm mx-1"
                                               onclick="return confirm('Are you sure you want to delete this point?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Point Type</th>
                                    <th>Point Value</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</div>

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
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
    $("#example1").DataTable({
        "scrollX": true,
        "lengthChange": false,
        "autoWidth": false,
        "responsive": false,
        "buttons": ["copy","csv","excel","pdf","print","colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
</body>
</html>
