<?php
// transfer_approval.php


include 'db.php';
include 'check_login.php';

// Optional: enable mysqli exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Load dynamic transfer fee (use latest entry)
    $fee_percent = 0.05; // fallback default (5%)
    $feeStmt = $conn->query("SELECT fee FROM transferfee ORDER BY sno DESC LIMIT 1");
    if ($feeStmt && $feeRow = $feeStmt->fetch_assoc()) {
    $fee_percent = floatval($feeRow['fee']);
    // If someone stored whole percent (e.g. 5), convert it to decimal
    if ($fee_percent > 1) {            // >1 means likely 5, 100 etc.
        $fee_percent = $fee_percent / 100.0;
    } elseif ($fee_percent >= 1.0 && $fee_percent <= 100.0) {
        // (another safe branch, but first covers it)
        $fee_percent = $fee_percent / 100.0;
    }
}
} catch (Exception $e) {
    // If fee table missing or any DB error, keep default fee_percent
    $fee_percent = 0.05;
}

// Fetch pending transfers for display
$query = "SELECT b.*, 
          u1.name AS sender_name, 
          u2.name AS receiver_name 
          FROM balance_transfers b
JOIN users u1 ON b.sender_id = u1.id
JOIN users u2 ON b.receiver_id = u2.id
WHERE b.receiver_accept_status = 'received' AND b.admin_approval_status = 'pending'
ORDER BY b.created_at DESC";

$result = $conn->query($query);

// Handle POST (approve/reject)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transfer_id = intval($_POST['transfer_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($transfer_id <= 0) {
        header("Location: transfer_approval.php?error=" . urlencode("Invalid transfer ID."));
        exit();
    }

    if ($action !== 'approve' && $action !== 'reject') {
        header("Location: transfer_approval.php?error=" . urlencode("Invalid action."));
        exit();
    }

    try {
        // Start transaction
        $conn->begin_transaction();

        // Lock and fetch the transfer row
        $stmt = $conn->prepare("SELECT * FROM balance_transfers WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $transfer_id);
        $stmt->execute();
        $transfer = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$transfer) {
            $conn->rollback();
            header("Location: transfer_approval.php?error=" . urlencode("Transfer not found."));
            exit();
        }

        // Optional: validate status to be 'pending'
        if ($transfer['admin_approval_status'] !== 'pending') {
            $conn->rollback();
            header("Location: transfer_approval.php?error=" . urlencode("Transfer is not pending."));
            exit();
        }

        $sender_id = intval($transfer['sender_id']);
        $receiver_id = intval($transfer['receiver_id']);
        $amount = floatval($transfer['amount']);
        $transfer_fee = round($amount * $fee_percent, 2);

        if ($action === 'approve') {
            // Lock and fetch sender wallet
            $stmt = $conn->prepare("SELECT balance, total_balance, available_balance FROM user_wallets WHERE user_id = ? FOR UPDATE");
            $stmt->bind_param("i", $sender_id);
            $stmt->execute();
            $sender_wallet = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$sender_wallet) {
                $conn->rollback();
                header("Location: transfer_approval.php?error=" . urlencode("Sender wallet not found."));
                exit();
            }

            $required = round($amount + $transfer_fee, 2);
            if (floatval($sender_wallet['balance']) < $required) {
                $conn->rollback();
                header("Location: transfer_approval.php?error=" . urlencode("Transfer failed: Sender does not have enough balance."));
                exit();
            }

            // Lock receiver wallet (if exists) - ensure row exists
            $stmt = $conn->prepare("SELECT balance, total_balance, available_balance FROM user_wallets WHERE user_id = ? FOR UPDATE");
            $stmt->bind_param("i", $receiver_id);
            $stmt->execute();
            $receiver_wallet = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$receiver_wallet) {
                // If receiver wallet doesn't exist, rollback (or optionally insert a wallet row)
                $conn->rollback();
                header("Location: transfer_approval.php?error=" . urlencode("Receiver wallet not found."));
                exit();
            }

            // 1) Deduct from sender
            $stmt = $conn->prepare("
                UPDATE user_wallets SET 
                    balance = balance - ?,
                    total_balance = total_balance - ?,
                    available_balance = available_balance - ?
                WHERE user_id = ?
            ");
            $stmt->bind_param("dddi", $required, $required, $required, $sender_id);
            $stmt->execute();
            $stmt->close();

            // 2) Credit receiver
            $creditAmount = round($amount, 2);
            $stmt = $conn->prepare("
                UPDATE user_wallets SET
                    balance = balance + ?,
                    total_balance = total_balance + ?,
                    available_balance = available_balance + ?
                WHERE user_id = ?
            ");
            $stmt->bind_param("dddi", $creditAmount, $creditAmount, $creditAmount, $receiver_id);
            $stmt->execute();
            $stmt->close();

            // 3) Update admin wallet fee collected
            // Ensure admin_wallet table has a single row. Here we assume single row; adjust if your schema differs.
            $stmt = $conn->prepare("UPDATE admin_wallet SET total_fee_collected = total_fee_collected + ?");
            $stmt->bind_param("d", $transfer_fee);
            $stmt->execute();
            $stmt->close();

            // 4) Insert wallet_history entries
            $now = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO wallet_history (user_id, type, amount, description, created_at) VALUES (?, ?, ?, ?, ?)");
            $descSender = "Transfer to user {$receiver_id} (including fee)";
            $debitAmount = $required;
            $typeDebit = 'debit';
            $stmt->bind_param("isdss", $sender_id, $typeDebit, $debitAmount, $descSender, $now);
            $stmt->execute();
            // receiver entry
            $descReceiver = "Received transfer from user {$sender_id}";
            $typeCredit = 'credit';
            $stmt->bind_param("isdss", $receiver_id, $typeCredit, $creditAmount, $descReceiver, $now);
            $stmt->execute();
            // fee entry: we set user_id = 0 for admin/system per your original code
            $descFee = "Transfer fee collected from user {$sender_id}";
            $feeUserId = 0;
            $stmt->bind_param("isdss", $feeUserId, $typeCredit, $transfer_fee, $descFee, $now);
            $stmt->execute();
            $stmt->close();

            // 5) Update transfer status to approved
            $stmt = $conn->prepare("UPDATE balance_transfers SET admin_approval_status = 'approved' WHERE id = ?");
            $stmt->bind_param("i", $transfer_id);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();
            header("Location: transfer_approval.php?success=" . urlencode("Transfer approved successfully"));
            exit();

        } elseif ($action === 'reject') {
            // Update transfer status to rejected
            $stmt = $conn->prepare("UPDATE balance_transfers SET admin_approval_status = 'rejected' WHERE id = ?");
            $stmt->bind_param("i", $transfer_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            header("Location: transfer_approval.php?success=" . urlencode("Transfer rejected successfully"));
            exit();
        }

    } catch (Exception $e) {
        // Rollback and redirect with error
        if ($conn->in_transaction) {
            $conn->rollback();
        }
        $err = "Error processing transfer: " . $e->getMessage();
        header("Location: transfer_approval.php?error=" . urlencode($err));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transfer Admin Approval Management</title>

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
                            <h1>Transfer Admin Approval Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Transfers</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Show current fee for admin awareness -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="alert alert-info">
                                Current transfer fee: <?= htmlspecialchars(number_format($fee_percent * 100, 2)) ?>%
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Transfer Admin Approvals</h3>
                                </div>

                                <!-- Success/Error Message -->
                                <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success" id="success-alert">
                                        <?= htmlspecialchars($_GET['success']) ?></div>
                                    <script>
                                        if (window.history.replaceState) {
                                            const url = new URL(window.location);
                                            url.searchParams.delete('success');
                                            window.history.replaceState({}, document.title, url.pathname + url.search);
                                        }
                                    </script>
                                <?php endif; ?>
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger" id="error-alert"><?= htmlspecialchars($_GET['error']) ?>
                                    </div>
                                    <script>
                                        if (window.history.replaceState) {
                                            const url = new URL(window.location);
                                            url.searchParams.delete('error');
                                            window.history.replaceState({}, document.title, url.pathname + url.search);
                                        }
                                    </script>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div style="overflow-x: auto; width: 100%; display: block;">
                                        <table id="example1" class="table table-bordered table-striped">

                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Sender</th>
                                                    <th>Receiver</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sn = 1;
                                                if ($result):
                                                    while ($row = $result->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= htmlspecialchars($row['sender_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['receiver_name']) ?></td>
                                                            <td><?= htmlspecialchars(number_format($row['amount'], 2)) ?></td>
                                                            <td>
                                                                <form method="POST" onsubmit="return confirm('Are you sure?');">
                                                                    <input type="hidden" name="transfer_id"
                                                                        value="<?= intval($row['id']) ?>">
                                                                    <button type="submit" name="action" value="approve"
                                                                        class="btn btn-success btn-sm">Approve</button>
                                                                    <button type="submit" name="action" value="reject"
                                                                        class="btn btn-danger btn-sm">Reject</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Sender</th>
                                                    <th>Receiver</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
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
                    "lengthChange": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });
    </script>

</body>

</html>