<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/log/php-error.log');

include 'db.php';
include 'check_login.php';


$all_ranks_data = [];
$ranks_query_result = $conn->query("SELECT * FROM rank_bonuses ORDER BY self_invest DESC, team_business DESC");
if ($ranks_query_result) {
    while ($rank_row = $ranks_query_result->fetch_assoc()) {
        $all_ranks_data[] = $rank_row;
    }
} else {
    error_log("CRITICAL: Error fetching ranks from rank_bonuses: " . $conn->error);
}
if (empty($all_ranks_data)) {
   error_log("WARNING: No rank data loaded from rank_bonuses table. Rank assignment will not work.");
}

$filter_user_id = isset($_GET['filter_user']) && $_GET['filter_user'] != '' ? intval($_GET['filter_user']) : null;


$sql_fetch_users_for_calc = "SELECT id, name FROM users";
if ($filter_user_id) {

    $sql_fetch_users_for_calc .= " WHERE id = " . $filter_user_id;
}
$users_to_process_result = $conn->query($sql_fetch_users_for_calc);

if ($users_to_process_result) {
    error_log("Starting calculation and storage phase. Processing " . $users_to_process_result->num_rows . " user(s).");
    while ($user = $users_to_process_result->fetch_assoc()) {
        $user_id = $user['id'];
        $user_name = $user['name'];
        error_log("--- Processing & Storing for User ID: {$user_id}, Name: {$user_name} ---");

     
        $self_invest_result = $conn->query("SELECT capital_locked_balance FROM user_wallets WHERE user_id = $user_id");
        $self_invest = 0.0;
        if ($self_invest_result && $row_si = $self_invest_result->fetch_assoc()) {
            $self_invest = isset($row_si['capital_locked_balance']) ? floatval($row_si['capital_locked_balance']) : 0.0;
        }
        error_log("User ID: {$user_id}, Self Invest (capital_locked_balance): {$self_invest}");

        $team_business = getTeamBusiness($conn, $user_id);
        error_log("User ID: {$user_id}, Calculated Team Business: {$team_business}");

        $wallet_details_result = $conn->query("SELECT available_balance FROM user_wallets WHERE user_id = $user_id");
        $user_available_balance = 0.0;
        if ($wallet_details_result && $wallet_row = $wallet_details_result->fetch_assoc()) {
            $user_available_balance = isset($wallet_row['available_balance']) ? floatval($wallet_row['available_balance']) : 0.0;
        } else {
            error_log("User ID: {$user_id} - No wallet details found or query failed for available_balance. DB error: " . $conn->error);
        }
        error_log("User ID: {$user_id}, Fetched User Available Balance from DB: {$user_available_balance}");

        $assigned_rank = "No Rank";
        foreach ($all_ranks_data as $rank_definition) {
            $rank_req_self_invest = isset($rank_definition['self_invest']) ? floatval($rank_definition['self_invest']) : 0.0;
            $rank_req_team_business = isset($rank_definition['team_business']) ? floatval($rank_definition['team_business']) : 0.0;
            $current_rank_name = isset($rank_definition['rank_name']) ? $rank_definition['rank_name'] : "Unnamed Rank";

            if ($self_invest >= $rank_req_self_invest && $team_business >= $rank_req_team_business) {
                $assigned_rank = $current_rank_name;
                break;
            }
        }
        error_log("User ID: {$user_id}, Final Assigned Rank: '{$assigned_rank}'");

        $stmt_upsert = $conn->prepare(
            "INSERT INTO rank_assignment_summary (user_id, user_name, assigned_rank, self_invest, team_business, user_available_balance)
             VALUES (?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                user_name = VALUES(user_name),
                assigned_rank = VALUES(assigned_rank),
                self_invest = VALUES(self_invest),
                team_business = VALUES(team_business),
                user_available_balance = VALUES(user_available_balance),
                calculation_timestamp = CURRENT_TIMESTAMP"
        );

        if ($stmt_upsert) {
            $stmt_upsert->bind_param("issddd",
                $user_id,
                $user_name,
                $assigned_rank,
                $self_invest,
                $team_business,
                $user_available_balance
            );
            if (!$stmt_upsert->execute()) {
                error_log("Error upserting rank summary for user_id {$user_id}: " . $stmt_upsert->error);
            } else {
                error_log("Successfully upserted rank summary for user_id {$user_id}.");
            }
            $stmt_upsert->close();
        } else {
            error_log("Error preparing statement for upserting rank summary: " . $conn->error);
        }
    }
} else {
    error_log("CRITICAL: Error fetching users for processing: " . $conn->error);
}
error_log("Calculation and storage phase completed.");



$display_data = []; 


$sql_select_display = "SELECT user_id, user_name, assigned_rank, self_invest, team_business, user_available_balance 
                       FROM rank_assignment_summary";

$params_select = [];
$types_select = "";

if ($filter_user_id) {
    $sql_select_display .= " WHERE user_id = ?";
    $params_select[] = $filter_user_id;
    $types_select .= "i";
} else {
    $sql_select_display .= " ORDER BY user_name ASC"; 
}

error_log("Fetching display data with SQL: " . $sql_select_display . ($filter_user_id ? " (Filtered by user_id: {$filter_user_id})" : ""));

$stmt_select_display = $conn->prepare($sql_select_display);

if ($stmt_select_display) {
    if (!empty($params_select)) {
        $stmt_select_display->bind_param($types_select, ...$params_select);
    }
    if ($stmt_select_display->execute()) {
        $result_display = $stmt_select_display->get_result();
        while ($row_display = $result_display->fetch_assoc()) {
            $display_data[] = $row_display;
        }
        error_log("Fetched " . count($display_data) . " rows for display.");
    } else {
        error_log("Error executing statement for fetching rank summary for display: " . $stmt_select_display->error);
    }
    $stmt_select_display->close();
} else {
     error_log("Error preparing statement for fetching rank summary for display: " . $conn->error);
}



function getTeamBusiness($conn, $user_id, $exclude = [])
{
    if (in_array($user_id, $exclude)) {
        return 0.0;
    }
    $current_exclude = $exclude;
    $current_exclude[] = $user_id;

    $total_balance_result = $conn->query("SELECT total_balance FROM user_wallets WHERE user_id = $user_id");
    $total = 0.0;
    if ($total_balance_result && $total_balance_row = $total_balance_result->fetch_assoc()) {
        $total = isset($total_balance_row['total_balance']) ? floatval($total_balance_row['total_balance']) : 0.0;
    }

    $downlines = $conn->query("SELECT referral_userid FROM referal_teams WHERE user_id = $user_id");
    if ($downlines) {
        while ($row = $downlines->fetch_assoc()) {
            $downline_id = $row['referral_userid'];
            $total += getTeamBusiness($conn, $downline_id, $current_exclude);
        }
    }
    return floatval($total);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rank Assignment Summary</title>

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
    <style>
        /* Optional: for easier reading of logs if displayed on page */
        .php-error { background-color: #fdd; border: 1px solid red; padding: 5px; margin: 5px; }
    </style>
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
                            <h1>Rank Assignment Summary</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Rank Summary</li>
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
                                    <h3 class="card-title">Stored Rank Assignment Summary</h3> <!-- Title updated -->
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
                                            <label for="filter_user">Filter by User:</label>
                                            <select name="filter_user" id="filter_user" class="form-control" onchange="document.getElementById('userFilterForm').submit();">
                                                <option value="">-- All Users --</option>
                                                <?php
                                                // Re-fetch users for dropdown
                                                $users_for_dropdown_result = $conn->query("SELECT id, name FROM users ORDER BY name ASC");
                                                if ($users_for_dropdown_result) {
                                                    while ($u = $users_for_dropdown_result->fetch_assoc()):
                                                ?>
                                                    <option value="<?= $u['id'] ?>" <?= (isset($_GET['filter_user']) && $_GET['filter_user'] == $u['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($u['name']) ?>
                                                    </option>
                                                <?php
                                                    endwhile;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </form>
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>User Name</th>
                                                <th>Rank</th>
                                                <th>Self Invest</th>
                                                <th>Team Business</th>
                                                <th>Available Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $s_no = 1;
                                            // Use $display_data which is fetched from rank_assignment_summary table
                                            if (empty($display_data)) {
                                                echo "<tr><td colspan='6'>No summary data found. Data might be calculating or no users match the filter.</td></tr>";
                                            }
                                            foreach ($display_data as $row): ?>
                                                <tr>
                                                    <td><?= $s_no++ ?></td>
                                                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                                                    <td><?= htmlspecialchars($row['assigned_rank']) ?></td>
                                                    <td><?= number_format($row['self_invest'], 2) ?></td>
                                                    <td><?= number_format($row['team_business'], 2) ?></td>
                                                    <td><?= number_format($row['user_available_balance'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>User Name</th>
                                                <th>Rank</th>
                                                <th>Self Invest</th>
                                                <th>Team Business</th>
                                                <th>Available Balance</th>
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

    <!-- jQuery and other JS includes remain the same -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            if ($("#example2").length) {
                var table = $("#example2").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                });
                if ($('#example2_wrapper .col-md-6:eq(0)').length) {
                     table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                }
            }
        });
        $(document).ready(function () {
    $('#filter_user').select2({
        placeholder: "Select User",
        allowClear: true,
        width: '100%'
    });
});

    </script>
</body>
</html>
