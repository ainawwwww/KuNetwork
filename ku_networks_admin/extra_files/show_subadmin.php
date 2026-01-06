<?php include 'db.php';
include 'check_login.php';

$fetch="UPDATE `custom_order` SET `process_read_status`=1 WHERE `status`='In Process' AND process_read_status=0";
      $sql=mysqli_query($conn,$fetch);
      $fetch="UPDATE `custom_order` SET `delivery_read_status`=1, `process_read_status`=1 WHERE `status`='Delivered' AND delivery_read_status=0";
      $sql=mysqli_query($conn,$fetch);
      $fetch="UPDATE `custom_order` SET `reject_read_status`=1 WHERE `status`='Rejected' AND reject_read_status=0";
      $sql=mysqli_query($conn,$fetch);
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
            <h1>DataTables</h1>
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
                <h3 class="card-title">All Sub Admin </h3>
                <a href="pages/examples/register.php" style="float: right;" class="btn btn-success">Add+</a>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Password</th>
                  <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $fetch_query="SELECT * FROM `admin` where role=2";
      $sql=mysqli_query($conn,$fetch_query);
      $i=1;
      if(mysqli_num_rows($sql)>0){
          while($row=mysqli_fetch_assoc($sql)){
      ?>
       <tr>
           <td><?=$row['fname']?></td>
           
           <td><?=$row['lname']?></td>
           <td><?=$row['email']?></td>
           <td><?=$row['password']?></td>
           <td>
              <div class="d-flex">
               
               <form method="POST" action="action/delete_subadmin.php">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                
            </form>
            </div>
           </td>
                    </tr>



       <?php
          }
      }
       ?>


                  </tbody>
                  <tfoot>
                  <tr>
                    <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Password</th>
                  <th>Action</th>
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
<script>
  $(document).ready(function(){
$(".order_status").change(function(){
  var rowid=$(this).attr('id');
  var selected_status=$(this).val();
  var menuId = $("ul.nav").first().attr("id");
 $.ajax({
  url: "action/change_order_status.php",
  type: "POST",
  data: {id : rowid,
        selected_status:selected_status},
        success: function(data){
     if(data==1)
      window.location='order_history.php';
  }
});

});
  });
</script>
