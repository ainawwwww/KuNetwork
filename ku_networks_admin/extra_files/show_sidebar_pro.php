<?php include 'db.php';
include 'check_login.php';

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
            <h1>Sidebar Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sidebar Products Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Content Header (Page header) -->
     <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Add a new Sidebar Products</h3>

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
          <form action="show_sidebar_pro.php" method="get">
          <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Category</label>
                  <select class="select2"  data-placeholder="Select Category" name="category" id="category" style="width: 100%;">
                    <option>Select Category</option>
                        <?php
                        $records = mysqli_query($conn, "SELECT menu_category.*, categories.* FROM `menu_category` INNER JOIN categories ON menu_category.cat_id=categories.cat_id;");  // Use select query here 

                        while($data = mysqli_fetch_array($records))
                        {
                            echo "<option value='". $data['cat_id'] ."'>" .$data['cat_name'] ."</option>";  // displaying data in option menu
                        } 
                    ?> 
                  </select>
                </div>
                <!-- /.form-group -->

              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="form-group">
                  <button type="submit"    class="btn btn-primary">Submit</button>
              </div>
            <!-- /.row -->
          </form>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

       


      
      </div>
      <!-- /.container-fluid -->
    </section>
<?php 
if (isset($_GET['category'])) {
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
                 <th>Action</th>
               <th>ID</th>
                <th>Product Name</th>
                <th>Image</th>
                  </tr>
                  </thead>
                  <tbody>
                  	<?php
            if(isset($_GET['category']))
            {
      $fetch_query="SELECT cat_pro.product_id, cat_pro.id, products.* FROM `cat_pro` INNER JOIN products ON cat_pro.product_id=products.id_p WHERE `category_id`= ".$_GET['category'];
      $sql=mysqli_query($conn,$fetch_query);
      $i=1;
      if(mysqli_num_rows($sql)>0){
          while($row=mysqli_fetch_assoc($sql)){
      ?>
       <tr>
       <td>
               <div class="d-flex">

               <form method="POST" action="action/delete_sidebar_pro.php">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                
            </form>
            </div>
           </td>

           <td><?=$row['id_p']?></td>
           <td><?=$row['product_name']?></td>
           <td><img src="images/<?= $row['pro_image']?>" height="100px" width="100px" alt=""></td>
           
         
       </tr>
       
      




       <?php
          }
      }
            }
       ?>


                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Action</th>
               <th>ID</th>
                <th>Product Name</th>
                <th>Image</th>
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
 
</script>
</body>
</html>
