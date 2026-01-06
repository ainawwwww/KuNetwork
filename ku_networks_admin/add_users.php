<?php

include './db.php';
include 'check_login.php';

$countries = mysqli_query($conn, "SELECT id, name FROM countries WHERE status = 1 ORDER BY name ASC");

$success_message = '';
$error_message = '';


define('ADMIN_SAVES_IMAGES_HERE', '../images/uploads/profile_images/');

if (!is_dir(ADMIN_SAVES_IMAGES_HERE)) {
    if (!mkdir(ADMIN_SAVES_IMAGES_HERE, 0775, true) && !is_dir(ADMIN_SAVES_IMAGES_HERE)) {
        error_log('Admin Add User: Failed to create directory: ' . ADMIN_SAVES_IMAGES_HERE);
    }
}


if (isset($_POST['insert'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];



    if ($password_raw !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $country_id = (int) $_POST['country_id'];
        $referral_code = !empty($_POST['referral_code']) ? mysqli_real_escape_string($conn, $_POST['referral_code']) : NULL;
        $status = isset($_POST['status']) ? 1 : 0;
        $points = 0;
        $image_name = 'default.png';

        $image_filename_for_db = 'default.png';


        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $uploaded_image_details = $_FILES['image'];
            $original_filename = basename($uploaded_image_details["name"]);
            $temp_filepath = $uploaded_image_details["tmp_name"];
            $filesize = $uploaded_image_details["size"];
            $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $max_size_mb = 5; 
            $max_size_bytes = $max_size_mb * 1024 * 1024;

            if (!in_array($file_extension, $allowed_extensions)) {
                $image_error_message = "Wrong image type. Only JPG, JPEG, PNG, GIF allowed.";
            } elseif ($filesize > $max_size_bytes) {
                $image_error_message = "Image too big. Max " . $max_size_mb . "MB allowed.";
            } else {

                $new_unique_filename = uniqid('userimg_admin_', true) . '.' . $file_extension;


                $destination_server_path = ADMIN_SAVES_IMAGES_HERE . $new_unique_filename;

                if (move_uploaded_file($temp_filepath, $destination_server_path)) {
                    $image_filename_for_db = $new_unique_filename;
                } else {
                    $image_error_message = "Could not save uploaded image. Using default.";
                    error_log("Admin Add User: Failed to move uploaded file to " . $destination_server_path);
                }
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {

            $image_error_message = "Image upload error (code: " . $_FILES['image']['error'] . "). Using default.";
        }

        $query = $conn->prepare("INSERT INTO users 
    (name, user_id, email, password, phone, country_id, referral_code, status, image, points)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param(
            "sssssisisi",
            $name,
            $user_id,
            $email,
            $password,
            $phone,
            $country_id,
            $referral_code,
            $status,
            $image_filename_for_db,
            $points
        );
        if ($query->execute()) {
            $success_message = "User added successfully.";


            $new_user_id = $query->insert_id;


            if (!empty($referral_code)) {

                $codeCheck = $conn->prepare("SELECT * FROM user_referal_codes WHERE referral_code = ? AND used_status = 0 AND expiration > NOW()");
                $codeCheck->bind_param("s", $referral_code);
                $codeCheck->execute();
                $result = $codeCheck->get_result();

                if ($result->num_rows > 0) {
                    $referralData = $result->fetch_assoc();
                    $referrer_id = $referralData['user_id'];


                    $update_referral_code = $conn->prepare("UPDATE user_referal_codes SET used_status = 1 WHERE id = ?");
                    $update_referral_code->bind_param("i", $referralData['id']);
                    $update_referral_code->execute();

                    $insert_referral_team = $conn->prepare("INSERT INTO referal_teams (user_id, referral_userid, referral_code) VALUES (?, ?, ?)");
                    $insert_referral_team->bind_param("iis", $referrer_id, $new_user_id, $referral_code);
                    $insert_referral_team->execute();
                }
            }

        } else {
            $error_message = "Error: " . $query->error;
        }
    }
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
                            <h1>Add Users</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Users Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Success Message -->
                    <?php if (isset($_GET['success_message'])): ?>
                        <div class="alert alert-success" id="success-alert">
                            <?= htmlspecialchars($_GET['success_message']) ?>
                        </div>
                        <script>
                            if (window.history.replaceState) {
                                const url = new URL(window.location);
                                url.searchParams.delete('success_message');
                                window.history.replaceState({}, document.title, url.pathname + url.search);
                            }
                        </script>
                    <?php endif; ?>
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Add a User</h3>

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
                                    <form action="" method="post" enctype="multipart/form-data" id="country_form">
                                        <div class="row">
                                            <!-- Name Field -->
                                            <div class="col-md-6 mb-3">
                                                <div class="field-set">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" name="name" required>
                                                </div>
                                            </div>

                                            <!-- User Name Field -->
                                            <div class="col-md-6 mb-3">
                                                <div class="field-set">
                                                    <label>User Name</label>
                                                    <input type="text" class="form-control" name="user_id" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>

                                        <div class="row">
                                            <!-- Password Field -->
                                            <div class="col-md-6 mb-3">
                                                <div class="field-set">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" name="password"
                                                        required>
                                                </div>
                                            </div>

                                            <!-- Confirm Password Field -->
                                            <div class="col-md-6 mb-3">
                                                <div class="field-set">
                                                    <label>Confirm Password</label>
                                                    <input type="password" class="form-control" name="confirm_password"
                                                        required>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <!-- Phone Field -->
                                            <div class="col-md-6 mb-3">
                                                <div class="ield-set">
                                                    <label>Phone</label>
                                                    <input type="text" class="form-control" name="phone">
                                                </div>
                                            </div>

                                            <!-- Country Dropdown -->
                                            <div class="col-md-6 mb-3">
                                                <div class="field-set">
                                                    <label>Country:</label>
                                                    <select name="country_id" id="country_id" class="form-control select2"
                                                        required>
                                                        <option value="">Select Country</option>
                                                        <?php while ($row = mysqli_fetch_assoc($countries)): ?>
                                                            <option value="<?= $row['id'] ?>">
                                                                <?= htmlspecialchars($row['name']) ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>

                                        <!-- Referral Code Field -->
                                        <div class="form-group mb-3">
                                            <label>Referral Code</label>
                                            <input type="text" class="form-control" name="referral_code">
                                        </div>

                                        <div>
                                            <label for="image_upload_admin">Profile Image:</label>
                                            <input type="file" name="image" id="image_upload_admin"
                                                class="form-control">
                                            <?php if (!empty($image_error_message)): ?>
                                                <small
                                                    style="color: red;"><?php echo htmlspecialchars($image_error_message); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <!-- Active Status Checkbox -->
                                        <div class="form-group mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" name="status" checked>
                                            <label class="form-check-label">Active Status</label>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group">
                                            <button type="submit" name="insert" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.col -->
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
        <script>
$(document).ready(function () {
    $('#country_id').select2({
        placeholder: "Select Country",
        allowClear: true,
        width: '100%'
    });
});
</script>

    </script>

</body>

</html>