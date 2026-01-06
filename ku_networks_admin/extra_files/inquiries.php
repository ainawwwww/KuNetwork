<?php include 'db.php';
include 'check_login.php';

$fetch="UPDATE `inquiries` SET `read_status`=1 WHERE read_status=0";
      $sql_inquiries=mysqli_query($conn,$fetch);
// if (!isset($_SESSION['A_id'])) {
//     header("location:pages/examples/login.php");
//   exit();
// } 
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
            <h1>Inquiries Management</h1>
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
                <h3 class="card-title">All Inquiries</h3>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>Action</th>
                <th>File</th>
               <th>Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Product</th>
                <th>Printing Type</th>
                <th>Design Ready</th>
                <th>Deleivery Type</th>
                <th>Phone Number</th>
                <th>Quantity</th>
                <th>Comment</th>
                <th>Created at</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $fetch_query="SELECT * FROM `inquiries` ORDER BY `id` DESC";
      $sql=mysqli_query($conn,$fetch_query);
      $i=1;
      if(mysqli_num_rows($sql)>0){
          while($row1=mysqli_fetch_assoc($sql)){
            
            $cat_fetch_query="SELECT * FROM `categories_level1` WHERE cat_id=".$row1['category'];
            $cat_sql=mysqli_query($conn,$cat_fetch_query);
            $cat_row=mysqli_fetch_assoc($cat_sql);
            $pro_fetch_query="SELECT * FROM `products` WHERE id_p=".$row1['product'];
            $pro_sql=mysqli_query($conn,$pro_fetch_query);
            $pro_row=mysqli_fetch_assoc($pro_sql);
      ?>
       <tr>
       <td>
               <div class="d-flex">

               <form method="POST" action="action/del_inquiry.php">
                <input type="hidden" name="id" value="<?= $row1['id'] ?>">
                <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                
            </form>
            </div>
           </td>
           <td>
               <form method="POST" action="action/download_inquiry_file.php"> 
                <!-- action="action/download_file.php" -->
                <input type="hidden" name="file" value="<?= $row1['file'] ?>">
               <button class="btn btn-primary btn-sm"  style="border-radius:1px;" type="submit" name="download"><i class="fas fa-download"></i></button>
                
            </form>
               </td>
           <td><?=$row1['full_name']?></td>
           <td><?=$row1['email']?></td>
           <td><?=$cat_row['cat_name']?></td>
           <td><?=$pro_row['product_name']?></td>
           
           <td><?=$row1['printing_type']?></td>
           <td><?=$row1['design_ready']?></td>
           <td><?=$row1['delivery_type']?></td>
           
           <td><?=$row1['phone_no']?></td>
           <td><?=$row1['quantity']?></td>
           <td><?=$row1['comment']?></td>
           
           <td><?=$row1['created_at']?></td>

         
       </tr>
       
      




       <?php
          }
      }
       ?>



                  </tbody>
                  <tfoot>
                  <tr>
                   <th>Action</th>
                <th>File</th>
               <th>Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Product</th>
                <th>Printing Type</th>
                <th>Design Ready</th>
                <th>Deleivery Type</th>
                <th>Phone Number</th>
                <th>Quantity</th>
                <th>Comment</th>
                <th>Created at</th>
                  </tr>
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
