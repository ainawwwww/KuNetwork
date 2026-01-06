<?php
include './db.php';
include 'check_login.php';

$success_message = '';
$error_message = '';

if (isset($_POST['insert'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $announcement_date = $_POST['announcement_date'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = $conn->prepare("
        INSERT INTO announcements 
        (title, announcement_date, description, status)
        VALUES (?, ?, ?, ?)
    ");
    $query->bind_param("sssi", $title, $announcement_date, $description, $status);

    if ($query->execute()) {
        header("Location: add_announcement.php?success_message=Announcement added successfully");
        exit;
    } else {
        $error_message = "Error: " . $query->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Add Announcement</title>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- /.sidebar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Announcement</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Announcement</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
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
                            window.history.replaceState({}, document.title, url.pathname);
                        }
                    </script>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Add Announcement</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="" method="post" id="announcement_form">

                            <!-- Title -->
                            <div class="form-group mb-3">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <!-- Announcement Date -->
                            <div class="form-group mb-3">
                                <label>Announcement Date</label>
                                <input type="date" class="form-control" name="announcement_date" required>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="status" checked>
                                <label class="form-check-label">Active</label>
                            </div>

                            <!-- Submit -->
                            <div class="form-group">
                                <button type="submit" name="insert" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
