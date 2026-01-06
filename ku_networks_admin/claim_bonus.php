<?php
include 'db.php';
include 'check_login.php';


function creditToWallet($conn, $user_id, $amount, $description = '')
{
    error_log("creditToWallet called for user $user_id, amount $amount, description: $description");
    $wallet = $conn->query("SELECT * FROM user_wallets WHERE user_id = $user_id")->fetch_assoc();

    if ($wallet) {
        $new_balance = $wallet['balance'] + $amount;
        $stmt = $conn->prepare("
            UPDATE user_wallets 
            SET balance = ?, 
                total_balance = total_balance + ?, 
                available_balance = available_balance + ?, 
                last_transaction = NOW() 
            WHERE user_id = ?
        ");
        $stmt->bind_param("dddi", $new_balance, $amount, $amount, $user_id);
        $result = $stmt->execute();
        if ($result) {
            error_log("Wallet updated for user $user_id. New balance: $new_balance");
        } else {
            error_log("Failed to update wallet for user $user_id: " . $stmt->error);
        }
    } else {
        $stmt = $conn->prepare("
            INSERT INTO user_wallets (user_id, balance, total_balance, available_balance, currency, last_transaction) 
            VALUES (?, ?, ?, ?, 'USD', NOW())
        ");
        $stmt->bind_param("iddd", $user_id, $amount, $amount, $amount);
        $result = $stmt->execute();
        if ($result) {
            error_log("Wallet created for user $user_id. Balance: $amount");
        } else {
            error_log("Failed to create wallet for user $user_id: " . $stmt->error);
        }
    }
}


function giveTeamCommission($conn, $from_user_id, $bonus_amount)
{
    $current_user_id = $from_user_id;

    for ($ref_level = 1; $ref_level <= 3; $ref_level++) {

        $ref_query = $conn->query("SELECT user_id FROM referal_teams WHERE referral_userid = $current_user_id");
        if (!$ref_query || $ref_query->num_rows == 0)
            break;
        $upline_id = $ref_query->fetch_assoc()['user_id'];
        if (!$upline_id)
            break;

    
        $payment_query = $conn->query("SELECT level_id FROM payment WHERE user_id = $upline_id ORDER BY id DESC LIMIT 1");
        if (!$payment_query || $payment_query->num_rows == 0)
            break;
        $level_id = $payment_query->fetch_assoc()['level_id'];
        if (!$level_id)
            break;


        $level_query = $conn->query("SELECT team_type FROM levels WHERE id = $level_id");
        if (!$level_query || $level_query->num_rows == 0)
            break;
        $team_type = $level_query->fetch_assoc()['team_type'];
        if (!$team_type)
            break;


        $team_commission = $conn->query("SELECT * FROM team_earning_commission WHERE team_name = '$team_type'")->fetch_assoc();
        if (!$team_commission)
            break;

        $level_col = 'level_' . ($ref_level + 1); 
        if (!isset($team_commission[$level_col]))
            break;

        $percent = floatval(str_replace('%', '', $team_commission[$level_col]));
        $commission_amount = ($bonus_amount * $percent) / 100;

      
        $stmt = $conn->prepare("INSERT INTO team_commission_history (from_user_id, to_user_id, level, percentage, amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $from_user_id, $upline_id, $ref_level, $percent, $commission_amount);
        if ($stmt->execute()) {
            error_log("Team commission of $commission_amount credited to upline $upline_id at level $ref_level.");
        } else {
            error_log("Failed to insert team commission for upline $upline_id at level $ref_level: " . $stmt->error);
        }

        creditToWallet($conn, $upline_id, $commission_amount, "Team Commission L$ref_level ($team_type)");


        $current_user_id = $upline_id;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim'])) {
    $user_id = intval($_POST['user_id']);
    $current_date = date('Y-m-d');

    $already_claimed = $conn->query("SELECT id FROM bonus_history WHERE user_id = $user_id AND DATE(created_at) = '$current_date' AND bonus_type = 'daily'");
    if ($already_claimed->num_rows > 0) {
        echo "<script>alert('You have already claimed your daily bonus today.'); window.location='bonus_history.php';</script>";
        exit();
    }

    $payment = $conn->query("SELECT * FROM payment WHERE user_id = $user_id ORDER BY id DESC LIMIT 1")->fetch_assoc();
    if (!$payment) {
        echo "<script>alert('No payment found for this user.'); window.location='bonus_history.php';</script>";
        exit();
    }

    $amount = $payment['amount'];
    $current_level_id = $payment['level_id'];

    $level = $conn->query("SELECT * FROM levels WHERE id = $current_level_id")->fetch_assoc();
    $bonus_percent = ($level['minimum_profit'] + $level['maximum_profit']) / 2;
    $daily_bonus = ($amount * $bonus_percent) / 100;


    $stmt = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, level_to, created_at) VALUES (?, ?, 'daily', ?, NULL, NOW())");
    $stmt->bind_param("idi", $user_id, $daily_bonus, $current_level_id);
    $stmt->execute();


    creditToWallet($conn, $user_id, $daily_bonus, 'Daily Bonus');
    giveTeamCommission($conn, $user_id, $daily_bonus);


    $new_level = $conn->query("SELECT * FROM levels WHERE $amount BETWEEN minimum_amount AND maximum_amount ORDER BY id DESC LIMIT 1")->fetch_assoc();
    if ($new_level && $new_level['id'] > $current_level_id) {
        $new_level_id = $new_level['id'];


        $upgrade_bonus_info = $conn->query("SELECT * FROM level_upgrade_bonus WHERE level_id = $new_level_id")->fetch_assoc();
        if ($upgrade_bonus_info) {
            $upgrade_bonus = ($upgrade_bonus_info['min_bonus'] + $upgrade_bonus_info['max_bonus']) / 2;

            $stmt2 = $conn->prepare("INSERT INTO bonus_history (user_id, bonus_amount, bonus_type, level_from, level_to, created_at) VALUES (?, ?, 'upgrade', ?, ?, NOW())");
            $stmt2->bind_param("idii", $user_id, $upgrade_bonus, $current_level_id, $new_level_id);
            if ($stmt2->execute()) {
                error_log("Upgrade bonus of $upgrade_bonus added to bonus history for user $user_id.");
            } else {
                error_log("Failed to insert upgrade bonus into bonus history for user $user_id: " . $stmt2->error);
            }


            creditToWallet($conn, $user_id, $upgrade_bonus, 'Upgrade Bonus');
            giveTeamCommission($conn, $user_id, $upgrade_bonus);


            $update_payment = $conn->query("UPDATE payment SET level_id = $new_level_id WHERE user_id = $user_id");
            if ($update_payment) {
                error_log("User $user_id upgraded to level $new_level_id.");
            } else {
                error_log("Failed to update level for user $user_id: " . $conn->error);
            }
        } else {
            error_log("No upgrade bonus information found for level $new_level_id.");
        }
    }

    echo "<script>alert('Daily bonus claimed successfully.'); window.location='bonus_history.php';</script>";
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Advanced form elements</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
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
                            <h1>Claim Bonus</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Claim Bonus Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Claim Bonus</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" class="mb-4">
                                        <div class="form-group">
                                            <label for="user_id">Select User:</label>
                                            <select name="user_id" required>
                                                <option value="">-- Select User --</option>
                                                <?php
                                                $users = $conn->query("SELECT DISTINCT users.id, users.name FROM users INNER JOIN payment ON users.id = payment.user_id");
                                                while ($user = $users->fetch_assoc()) {
                                                    echo "<option value='{$user['id']}'>{$user['name']} (ID: {$user['id']})</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="claim">Claim</button>
                                    </form>

                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
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
    <!-- Bootstrap4 Duallistbox -->
    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- dropzonejs -->
    <script src="plugins/dropzone/min/dropzone.min.js"></script>
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

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function (event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function () {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function () {
                myDropzone.enqueueFile(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function (file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function (progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function () {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function () {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End
    </script>

</body>

</html>