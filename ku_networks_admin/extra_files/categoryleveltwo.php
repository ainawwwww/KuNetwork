<?php include 'db.php';
include 'check_login.php';
if (isset($_SESSION['role']) && $_SESSION['role']==1){
  $fetch="UPDATE `categories_level2` SET `read_status`=1 WHERE read_status=0";
      $sql=mysqli_query($conn,$fetch);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
            <h1>Categories Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTables</li>
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
                <h3 class="card-title">All Level Two Categories</h3>
                <a href="add_category2.php" style="float: right;" class="btn btn-success">Add+</a>

              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Filter</th>
                    <th>Menu</th>
                    <th>Parent Category</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $fetch_query="SELECT categories_level2.*,categories_level1.cat_name as parent_cat FROM `categories_level2` 
join categories_level1 on categories_level2.parent_catid=categories_level1.cat_id";
      $sql=mysqli_query($conn,$fetch_query);
      if(mysqli_num_rows($sql)>0){
        $num=1;
          while($row=mysqli_fetch_assoc($sql)){
      
    ?>
       <tr>
          <td><?=$num?></td>
          <td><?=$row['name']?></td>
          <td><img src="images/categories/<?=$row['image']?>" height="100px" width="100px" alt="">
          
        </td>
        <td><a href="action/update_category2_filter_status.php?id=<?=$row['id']?>">
          <span class="badge <?php if($row['product_filter_sidebar_status']==1) echo 'bg-success'; else echo 'bg-danger'; ?>">
          <?php if($row['product_filter_sidebar_status']==1) echo 'Active'; else echo 'Hidden'; ?>
          </span>
          </a>
        </td>
        <td><a href="action/update_menu_status_level2.php?id=<?=$row['id']?>">
          <span class="badge <?php if($row['menu_status']==1) echo 'bg-success'; else echo 'bg-danger'; ?>">
          <?php if($row['menu_status']==1) echo 'Active'; else echo 'Hidden'; ?>
          </a></span>
        </td>
        <td><?=$row['parent_cat']?></td>

          <td><?=$row['created_at']?></td>
          
              <td>
               <div class="d-flex">
               <!-- <button type="button" class="btn btn-info btn-sm mx-2" style="border-radius:15px;"  data-toggle="modal" data-target="#exampleModal<?=$row['cat_id']?>"><i class="fas fa-pencil-alt"></i></button> -->
               <a href="update_category2.php?id=<?= $row['id'] ?>"><button class="btn btn-info btn-sm mx-2"  style="border-radius:15px;"  ><i class="fas fa-pencil-alt"></i></button></a>
               
               <a href="action/delete_cat2.php?id=<?= $row['id'] ?>">
               <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="button" name="delete"><i class="fas fa-trash-alt"></i></button>
               </a>
            </div>
           </td>

      
       </tr>
     
 <?php
    $num++;
      }
    }
 ?>

                  
                  </tbody>
                  <tfoot>
                  <th>S.No</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Filter</th>
                    <th>Menu</th>
                    <th>Parent Category</th>
                    <th>Created At</th>
                    <th>Action</th>
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
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
