<?php
include 'db.php';
include 'sidebar.php'; // ← Your sidebar file

// Fetch all points records
$result = mysqli_query($conn, "SELECT * FROM points_settings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Points Settings</title>
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    <!-- Main Content -->
    <div class="content-wrapper p-4">

        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success text-center">
                <?= $_GET['success']; ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger text-center">
                <?= $_GET['error']; ?>
            </div>
        <?php } ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Points Settings List</h3>

                <a href="points_add.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>

            <div class="card-body">

                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Point Title</th>
                            <th>Points</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['title'] ?></td>
                                <td><?= $row['points'] ?></td>

                                <td>
                                    <?php if ($row['status'] == 1) { ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <a href="points_edit.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="points_delete.php?id=<?= $row['id'] ?>"
                                       onclick="return confirm('Are you sure?');"
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>

</body>
</html>
