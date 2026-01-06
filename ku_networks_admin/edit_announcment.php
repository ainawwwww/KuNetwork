<?php
include 'db.php';
// include 'check_login.php';

// 1. Check if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM announcements WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Announcement not found!");
    }
} else {
    header("Location: announcment.php");
    exit();
}

// 2. Update Logic
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $announcement_date = $_POST['announcement_date'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;

    $update_query = "UPDATE announcements SET 
                     title='$title', 
                     announcement_date='$announcement_date', 
                     description='$description', 
                     status='$status' 
                     WHERE id='$id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Updated Successfully!'); window.location.href='announcment.php';</script>";
    } else {
        echo "<script>alert('Update Failed: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Edit Announcement</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Update Details</h3>
                    </div>
                    <form method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="announcement_date" class="form-control" value="<?php echo $row['announcement_date']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="status" <?php echo ($row['status'] == 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label">Active (Show to users)</label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="update" class="btn btn-warning">Update Announcement</button>
                            <a href="announcment.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>