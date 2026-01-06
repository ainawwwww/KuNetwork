<?php
include 'db.php';
include 'check_login.php';

// Fetch users for dropdown
$users_list = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");

// Filter logic (improved) - build per-select filters so UNION works correctly
$where_user_id = null;
$filterWh = $filterWr = $filterPc = $filterBh = '';

if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
    $where_user_id = intval($_GET['user_id']);
    $filterWh = "WHERE wh.user_id = {$where_user_id}";
    $filterWr = "WHERE wr.user_id = {$where_user_id}";
    $filterPc = "WHERE pc.user_id = {$where_user_id}";
    $filterBh = "WHERE bh.user_id = {$where_user_id}";
}

// Combined query: wallet + withdraw + points_claims + bonus_history
// Note: Each SELECT includes an extra 'source' column so we can detect bonus rows in PHP.
$query = "
(
    SELECT 
        wh.id,
        wh.user_id,
        u.name AS username,
        wh.type,
        wh.amount,
        wh.description,
        wh.created_at,
        'wallet' AS source
    FROM wallet_history AS wh
    LEFT JOIN users AS u ON wh.user_id = u.id
    {$filterWh}
)
UNION ALL
(
    SELECT 
        wr.id,
        wr.user_id,
        u.name AS username,
        'withdraw' AS type,
        wr.amount,
        CONCAT('Withdraw request (', wr.status, ')') AS description,
        wr.created_at,
        'withdraw' AS source
    FROM withdrawals AS wr
    LEFT JOIN users AS u ON wr.user_id = u.id
    {$filterWr}
)
UNION ALL
(
    SELECT
        pc.id,
        pc.user_id,
        u.name AS username,
        'points_claim' AS type,
        pc.claim_amount AS amount,
        CONCAT('Points claimed - used: ', COALESCE(pc.points_used,0), ' pts (before: ', COALESCE(pc.points_before,0), ', after: ', COALESCE(pc.points_after,0), ')') AS description,
        pc.created_at,
        'points' AS source
    FROM points_claims AS pc
    LEFT JOIN users AS u ON pc.user_id = u.id
    {$filterPc}
)
UNION ALL
(
    SELECT
        bh.id,
        bh.user_id,
        u.name AS username,
        bh.bonus_type AS type,
        bh.bonus_amount AS amount,
        -- put raw meta in description column; we'll parse it in PHP to show a short related text
        COALESCE(bh.meta, '') AS description,
        bh.created_at,
        'bonus' AS source
    FROM bonus_history AS bh
    LEFT JOIN users AS u ON bh.user_id = u.id
    {$filterBh}
)
ORDER BY created_at DESC
";

$result = $conn->query($query);
if ($result === false) {
    // Log silently; page will show "No transaction history found." if query fails.
    error_log("Wallet history query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Wallet & Withdraw History</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        /* optional: smaller description font to keep table tidy */
        .tx-desc { font-size: 13px; color: #444; }
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
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Users' Wallet & Withdraw History</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Users' Transactions</h3>
                    </div>
                    <div class="card-body">
                        <form method="get" id="userFilterForm" class="mb-3">
                            <label for="user_id">Select User:</label>
                            <select name="user_id" id="user_id" class="form-control"
                                onchange="document.getElementById('userFilterForm').submit();">
                                <option value=""
                                    <?= (!isset($_GET['user_id']) || $_GET['user_id'] == '') ? 'selected' : '' ?>>
                                    -- All Users --</option>
                                <?php
                                mysqli_data_seek($users_list, 0);
                                while ($user = mysqli_fetch_assoc($users_list)): ?>
                                    <option value="<?= $user['id'] ?>"
                                        <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </form>

                        <?php if ($result && $result->num_rows > 0): ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User</th>
                                        <th>Type</th>
                                        <th>Amount ($)</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno = 1;
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $sno++ ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>

                                            <!-- Type column with badges -->
                                            <td>
                                                <?php
                                                $type = strtolower((string)$row['type']);
                                                $source = isset($row['source']) ? $row['source'] : '';

                                                // Bonus rows: treat specially (show badge based on bonus_type)
                                                if ($source === 'bonus') {
                                                    // if type is something like 'daily' or 'upgrade' show those badges
                                                    if ($type === 'daily') {
                                                        echo "<span class='badge badge-info'>Daily</span>";
                                                    } elseif ($type === 'upgrade') {
                                                        echo "<span class='badge badge-success'>Upgrade</span>";
                                                    } else {
                                                        // generic bonus badge
                                                        echo "<span class='badge badge-warning'>Bonus</span>";
                                                    }
                                                } else {
                                                    // non-bonus rows keep existing logic
                                                    if ($type == 'withdraw') {
                                                        echo "<span class='badge badge-danger'>Withdraw</span>";
                                                    } elseif ($type == 'transfer') {
                                                        echo "<span class='badge badge-primary'>Transfer</span>";
                                                    } elseif ($type == 'received') {
                                                        echo "<span class='badge badge-success'>Received</span>";
                                                    } elseif ($type == 'points_claim' || $type == 'points-claim') {
                                                        echo "<span class='badge badge-warning'>Points Claim</span>";
                                                    } else {
                                                        echo "<span class='badge badge-info'>" . htmlspecialchars(ucfirst($type)) . "</span>";
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <!-- Amount -->
                                            <td>
                                                <?php
                                                $amt = is_numeric($row['amount']) ? $row['amount'] : 0;
                                                echo '$' . number_format($amt, 2);
                                                ?>
                                            </td>

                                            <!-- Description (truncate & for bonus show small related info parsed from meta) -->
                                            <td class="tx-desc">
                                                <?php
                                                $desc_display = '';
                                                $raw_desc = $row['description'] ?? '';

                                                if ($source === 'bonus') {
                                                    // raw_desc contains bh.meta (possibly JSON or plain text)
                                                    $metaText = trim($raw_desc);
                                                    $related = 'N/A';

                                                    // Try to parse JSON safely
                                                    $json = json_decode($metaText, true);
                                                    if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                                                        // Try common keys
                                                        if (!empty($json['rank_claimed'])) {
                                                            $related = (string)$json['rank_claimed'];
                                                        } elseif (!empty($json['reason'])) {
                                                            $related = (string)$json['reason'];
                                                        } elseif (!empty($json['related'])) {
                                                            $related = (string)$json['related'];
                                                        } else {
                                                            // if meta has many keys, pick a short summary (first key)
                                                            $first = reset($json);
                                                            if (is_scalar($first)) {
                                                                $related = (string)$first;
                                                            } else {
                                                                $related = 'details available';
                                                            }
                                                        }
                                                    } else {
                                                        // meta was not JSON or empty; use raw text but keep it short
                                                        if ($metaText !== '') {
                                                            $related = mb_substr($metaText, 0, 60);
                                                        } else {
                                                            $related = 'N/A';
                                                        }
                                                    }

                                                    $displayType = ucfirst($type);
                                                    if ($displayType === '') $displayType = 'Bonus';
                                                    $desc_display = "Bonus ({$displayType}) - amount: " . number_format($amt, 2) . " | related: {$related}";
                                                } else {
                                                    // normal row, use provided description
                                                    $desc_display = trim($raw_desc);
                                                }

                                                // escape and truncate for UI
                                                $safe = htmlspecialchars($desc_display, ENT_QUOTES, 'UTF-8');
                                                if (mb_strlen($safe) > 120) {
                                                    echo mb_substr($safe, 0, 120) . '...';
                                                } else {
                                                    echo $safe;
                                                }
                                                ?>
                                            </td>

                                            <td><?= date('F j, Y, g:i A', strtotime($row['created_at'])) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No transaction history found.</p>
                        <?php endif; ?>
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
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
$(function () {
    $("#example1").DataTable({
        "lengthChange": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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