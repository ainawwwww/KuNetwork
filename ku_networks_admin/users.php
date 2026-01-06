<?php
include 'db.php';      // Database connection ($conn)
include 'check_login.php'; // Login check


// --- Consistent Constant Definitions ---
// Path 1: Admin Upload Directory (Absolute Web Path)
// Yeh aapke admin panel ka web path hai jahan images hain
if (!defined('PROFILE_IMAGE_UPLOAD_DIR_ADMIN')) {
    define('PROFILE_IMAGE_UPLOAD_DIR_ADMIN', '/ku_networks_admin/images/uploads/profile_images/'); // Example: /admin_project_folder/path/to/images/
}

// Path 2: User Upload Directory (Main project ke andar, BASE_WEB_PATH_MAIN_PROJECT ke relative)
if (!defined('PROFILE_IMAGE_UPLOAD_DIR')) {
    define('PROFILE_IMAGE_UPLOAD_DIR', 'images/uploads/profile_images/'); // Example: images/profile_pics/
}

// Main project ka base web path (document root se)
if (!defined('BASE_WEB_PATH_MAIN_PROJECT')) {
    define('BASE_WEB_PATH_MAIN_PROJECT', '/ku_networks/'); // Example: /main_project_folder/
}

// Default avatar ka filename
if (!defined('DEFAULT_AVATAR_FILENAME')) {
    define('DEFAULT_AVATAR_FILENAME', 'default.png');
}

// --- Data Fetching ---
$countries_list = mysqli_query($conn, "SELECT id, name FROM countries ORDER BY name ASC");

$where = '';
if (isset($_GET['country_id']) && $_GET['country_id'] != '') {
    $country_id = intval($_GET['country_id']);
    $where = "WHERE users.country_id = $country_id";
}

// Query mein users.image aur users.points (agar points dikhane hain) select karein
$query = "
    SELECT users.*, countries.name AS country_name
    FROM users
    LEFT JOIN countries ON users.country_id = countries.id
    $where
    ORDER BY users.id ASC
";
// Note: Aapke users table mein 'points' column hai ya 'total_balance' user_wallets se join karna hai?
// Agar 'points' users table mein hai, toh query theek hai.
// Agar 'points' user_wallets se aane hain, toh JOIN karna hoga jaisa leaderboard mein tha.
// Filhaal, main farz kar raha hoon 'points' users table mein hai. Agar nahi, toh query adjust karein.
$result = mysqli_query($conn, $query);

// --- Image Path Function ---
// Yeh function ab in constants ko sahi tareeqe se istemaal karega
function get_player_image_src($image_filename)
{
    $doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

    // Path 1: Admin Upload Directory (Absolute Web Path for src, Server path for file_exists)
    $admin_web_path = PROFILE_IMAGE_UPLOAD_DIR_ADMIN . $image_filename;
    $admin_server_path = $doc_root . PROFILE_IMAGE_UPLOAD_DIR_ADMIN . $image_filename;

    // Path 2: User Upload Directory (Main project ke relative, prefixed by BASE_WEB_PATH_MAIN_PROJECT)
    $user_web_path = BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . $image_filename;
    $user_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . $image_filename;

    // Default image URLs (agar specific user image na mile)
    $default_admin_web_path = PROFILE_IMAGE_UPLOAD_DIR_ADMIN . DEFAULT_AVATAR_FILENAME;
    $default_admin_server_path = $doc_root . PROFILE_IMAGE_UPLOAD_DIR_ADMIN . DEFAULT_AVATAR_FILENAME;

    $default_user_web_path = BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . DEFAULT_AVATAR_FILENAME;
    $default_user_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . DEFAULT_AVATAR_FILENAME;

    // Ek ultimate fallback default image
    $ultimate_default_web_path = BASE_WEB_PATH_MAIN_PROJECT . 'images/default_avatar.png'; // Make sure this exists
    $ultimate_default_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . 'images/default_avatar.png';


    if (!empty($image_filename) && $image_filename !== DEFAULT_AVATAR_FILENAME) {
        if (file_exists($admin_server_path)) {
            return $admin_web_path;
        }
        if (file_exists($user_server_path)) {
            return $user_web_path;
        }
    }

    // Check for default images if specific image not found or was the default filename
    if (file_exists($default_user_server_path)) {
        return $default_user_web_path;
    }
    if (file_exists($default_admin_server_path)) {
        return $default_admin_web_path;
    }

    // Ultimate fallback
    if (file_exists($ultimate_default_server_path)) {
        return $ultimate_default_web_path;
    }
    // Agar ultimate fallback bhi nahi hai, toh ek generic path de dein ya error handle karein
    return BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . DEFAULT_AVATAR_FILENAME; // Last resort: user default path
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

</head>
<style>
/* Height, border & alignment fix */
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

/* On focus same as AdminLTE */
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #80bdff;
    box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
}

/* Dropdown */
.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 4px;
}

/* Search box inside dropdown */
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
                            <h1>Users Management</h1>
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
                                    <h3 class="card-title">All Users</h3>
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
                                    <!-- Country Filter Form -->
                                    <form method="get" id="countryFilterForm" class="mb-3">
                                        <div class="form-group">
                                            <label for="country_id">Select Country:</label>
                                            <select name="country_id" id="country_id" class="form-control"
                                                onchange="document.getElementById('countryFilterForm').submit();">
                                                <option value="" <?= (!isset($_GET['country_id']) || $_GET['country_id'] == '') ? 'selected' : '' ?>>-- All Countries --
                                                </option>
                                                <?php
                                                mysqli_data_seek($countries_list, 0);
                                                while ($country = mysqli_fetch_assoc($countries_list)): ?>
                                                    <option value="<?= $country['id'] ?>" <?= (isset($_GET['country_id']) && $_GET['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($country['name']) ?>
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
                                                    <th>Name</th>
                                                    <th>User ID</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Country</th>
                                                    <th>Referral Code</th>
                                                    <th>Status</th>
                                                    <th>Points</th>
                                                    <th>Image</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($result) > 0): ?>
                                                    <?php $sn = 1;


                                                    while ($row = mysqli_fetch_assoc($result)):
                                                        $current_user_image_src = get_player_image_src($row['image']);
                                                    ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                                            <td><?= htmlspecialchars($row['phone']) ?></td>
                                                            <td><?= htmlspecialchars($row['country_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['referral_code']) ?></td>
                                                            <td>
                                                                <?php if ($row['status'] == 1): ?>
                                                                    <span class="badge bg-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">Inactive</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['points']) ?></td>
                                                            <td>
                                                                <img src="<?php echo htmlspecialchars($current_user_image_src); // Ab sahi variable use karein 
                                                                            ?>"
                                                                    width="40" height="40" style="border-radius:50%;">
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="edit_users.php?id=<?= $row['id'] ?>"
                                                                        class="btn btn-info btn-sm mx-1"
                                                                        style="border-radius:15px;">
                                                                        <i class="fas fa-pencil-alt"></i>
                                                                    </a>
                                                                    <form method="POST" action="delete_users.php"
                                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $row['id'] ?>">
                                                                        <button type="submit" name="delete"
                                                                            class="btn btn-danger btn-sm"
                                                                            style="border-radius:15px;">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="12" class="text-center">No Users Found</td> <!-- Colspan 11 tha, ab 12 columns hain -->
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.NO</th>
                                                    <th>Name</th>
                                                    <th>User ID</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Country</th>
                                                    <th>Referral Code</th>
                                                    <th>Status</th>
                                                    <th>Points</th>
                                                    <th>Image</th>
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
        $(function() {
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
    $('#country_id').select2({
        placeholder: "Select Country",
        allowClear: true,
        width: '100%'
    });
});
</script>


</body>

</html>