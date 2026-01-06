<?php
include 'db.php';
include 'check_login.php';

// Fetch users for dropdown
$users_list = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");

// Filter logic
$where = '';
if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $user_id = intval($_GET['user_id']);
    $where = "WHERE pb.user_id = $user_id";
}

$sql = "SELECT pb.id, pb.user_id, u.name AS user_full_name, pb.product_name, pb.product_price, pb.purchase_date
        FROM product_buy pb
        LEFT JOIN users u ON pb.user_id = u.id  
        $where
        ORDER BY pb.purchase_date ASC";

$result = $conn->query($sql);

$products_bought = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products_bought[] = $row;
    }
} else {
    $message = "No products have been bought yet.";
}

$conn->close();
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
                            <h1>Purchased Products Report</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">User Wallets</li>
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
                                    <h3 class="card-title">List of All Purchased Products</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php if (!empty($message) && empty($products_bought)): ?>
                                        <div class="alert alert-info"><?php echo $message; ?></div>
                                    <?php endif; ?>
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
                                    <?php if (!empty($products_bought)): ?>
                                        <div style="overflow-x: auto; width: 100%; display: block;">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.NO </th>
                                                        <th>User ID</th>
                                                        <th>Username/Name</th>
                                                        <th>Product Name</th>
                                                        <th>Product Price</th>
                                                        <th>Purchase Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sno = 1;
                                                    foreach ($products_bought as $product):
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $sno++ ?></td>
                                                            <td><?php echo htmlspecialchars($product['user_id']); ?></td>
                                                            <td>
                                                                <?php

                                                                if (isset($product['user_full_name']) && !empty($product['user_full_name'])) {
                                                                    echo htmlspecialchars($product['user_full_name']);
                                                                } else {
                                                                    echo 'N/A';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                                            <td>$<?php echo htmlspecialchars(number_format($product['product_price'], 2)); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars(date('M d, Y H:i:s', strtotime($product['purchase_date']))); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>S.NO (ID)</th>
                                                        <th>User ID</th>
                                                        <th>Username/Name</th>
                                                        <th>Product Name</th>
                                                        <th>Product Price</th>
                                                        <th>Purchase Date</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <?php elseif (empty($message)): ?>
                                        <div class="alert alert-warning">Could not retrieve product data or no data to
                                            display.</div>
                                    <?php endif; ?>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

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
    $('#user_id').select2({
        placeholder: "Select User",
        allowClear: true,
        width: '100%'
    });
});
    </script>
</body>

</html>