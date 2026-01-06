<?php include 'db.php';
include 'check_login.php';
if(!isset($_GET['id']))
{
  echo "<script>window.location='all_product.php';</script>";
  exit;
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
            <h1>Product Details Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product Detail</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Content Header (Page header) -->
     <section class="content">
      
    </section>
<?php 
if (isset($_GET['id'])) {
  ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Detail</h3>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Delivery</th>
                <th>Urgent Price</th>
                <th>Urgent Delivery</th>
                <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                     <?php
      $fetch_query="SELECT * FROM `quantity_products` JOIN products ON products.id_p =  quantity_products.products WHERE products.id_p='".$_GET['id']."'";
      
      
      $sql=mysqli_query($conn,$fetch_query);
      $i=1;
      if(mysqli_num_rows($sql)>0){
          while($row=mysqli_fetch_assoc($sql)){
      ?>
       <tr>
       

        <td><?=$row['size']?></td>
          <td><?=$row['quantity']?></td>
          <td><?=$row['price']?></td>
          <td><?=$row['time']?></td>
          <td><?=$row['urgent_price']?></td>
          <td><?=$row['urgent_time']?></td>
          <td>
               <div class="d-flex">
               <a href="update_product_detail_form.php?id=<?= $row['id'] ?>&pro_id=<?= $_GET['id'] ?>"><button type="button" class="btn btn-info btn-sm mx-2" style="border-radius:15px;"  data-toggle="modal" data-target="#exampleModal"><i class="fas fa-pencil-alt"></i></button></a>
               <a href="action/del_pro_detail.php?id=<?= $row['id'] ?>&pro_id=<?= $_GET['id'] ?>"><button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="button" name="delete"><i class="fas fa-trash-alt"></i></button></a>

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
                    <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Delivery</th>
                <th>Urgent Price</th>
                <th>Urgent Delivery</th>
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
  <?php } ?>
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
<script>
  $(document).ready(function(){
  $("#category").change(function () {
            var selectedValue = $(this).val();
                 $.ajax({
          type: "POST",
          url: "fetch_product_from_category.php",
          data: {selectedValue:selectedValue},
          success: function(result) {
             $('#product').empty().append(result);
            
          }
          });
              });
  });
</script>
</body>
</html>
