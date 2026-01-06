<?php
include 'db.php';
// Agar apke paas check_login.php hai to uncomment karein
// include 'check_login.php';

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $del_query = "DELETE FROM announcements WHERE id='$id'";
    if (mysqli_query($conn, $del_query)) {
        echo "<script>alert('Announcement Deleted Successfully'); window.location.href='announcment.php';</script>";
    } else {
        echo "<script>alert('Failed to delete');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | All Announcements</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Announcements</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Announcements</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Announcements List</h3>
                                <a href="add_announcment.php" class="btn btn-primary float-right btn-sm">Add New</a>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Data Fetch Query
                                    $query = "SELECT * FROM announcements ORDER BY id DESC";
                                    $result = mysqli_query($conn, $query);

                                    if(mysqli_num_rows($result) > 0){
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Status Badge Logic
                                            $status_badge = ($row['status'] == 1) 
                                                ? '<span class="badge badge-success">Active</span>' 
                                                : '<span class="badge badge-danger">Inactive</span>';
                                    ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo $row['announcement_date']; ?></td>
                                        <td><?php echo substr(htmlspecialchars($row['description']), 0, 50) . '...'; ?></td>
                                        <td><?php echo $status_badge; ?></td>
                                        <td>
                                            <a href="edit_announcment.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            
                                            <a href="announcment.php?delete_id=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this announcement?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No Announcements Found</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </div>
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
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>