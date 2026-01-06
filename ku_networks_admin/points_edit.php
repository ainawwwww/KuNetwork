<?php
include 'db.php';
include 'sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: points_list.php?error=Invalid Request");
    exit;
}

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM points_settings WHERE id=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    header("Location: points_list.php?error=Record Not Found");
    exit;
}

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $points = $_POST['points'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE points_settings 
            SET title='$title', points='$points', status='$status' 
            WHERE id=$id");

    header("Location: points_list.php?success=Point Updated Successfully");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Point</title>
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    <div class="content-wrapper p-4">

        <div class="card">
            <div class="card-header">
                <h3>Edit Point Type</h3>
            </div>

            <form method="POST" class="card-body">

                <label>Point Title</label>
                <input type="text" name="title" class="form-control" value="<?= $data['title'] ?>" required>

                <label class="mt-2">Points Value</label>
                <input type="number" name="points" class="form-control" value="<?= $data['points'] ?>" required>

                <label class="mt-2">Status</label>
                <select name="status" class="form-control">
                    <option value="1" <?= $data['status'] == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $data['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                </select>

                <button class="btn btn-primary mt-3" name="update">Update</button>
                <a href="points_list.php" class="btn btn-secondary mt-3">Cancel</a>

            </form>
        </div>

    </div>

</div>

</body>
</html>
