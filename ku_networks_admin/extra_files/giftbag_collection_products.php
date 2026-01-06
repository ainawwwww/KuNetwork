<?php include 'db.php';
include 'check_login.php';

if(isset($_POST['delete'])) {
  $id=$_POST['id'];
  $sql="DELETE FROM `giftbag_collection_products` WHERE `id`='$id'";
  if(mysqli_query($conn,$sql)) {
  header("location:giftbag_collection_products.php");
  }
  else{
      echo "error";
  }
}

if(isset($_POST['add_pro'])){

  $products=$_POST['pro'];


  $insert_query="INSERT INTO `giftbag_collection_products`(`product_id`) VALUES ('$products')";
 
  if(mysqli_query($conn,$insert_query)) {
      echo "<script>window.location='giftbag_collection_products.php';</script>";
     
      exit;
  }
  else{
      echo "error";
  }
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
          <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Add a new specific Gift Bag Collection Product</h3>

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
            <div class="row">
              <div class="col-md-12">
                <form  method="post" >
                <div class="form-group">
                  <label>Products</label>
                  <select class="select2" id="category1_1" name="pro"   data-placeholder="Select Any Products" style="width: 100%;">
                  <option value="">Select Any Products</option>
                    <?php
                      $pro_fetch="SELECT * FROM `products` ORDER by  product_name asc;";
                      $sql=mysqli_query($conn,$pro_fetch);
                      if(mysqli_num_rows($sql)>0){
                        while($row=mysqli_fetch_assoc($sql)){
                          ?>
                          <option value="<?=$row['id_p']?>"><?=$row['product_name']?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>
                
               
                <div class="form-group">
                  <button type="submit" name="add_pro" class="btn btn-primary">Submit</button>
                </div>
                <!-- /.form-group -->
             </form>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

          </div>
          <!-- /.card-body -->
          
        </div>



            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Gift Bag Collection products</h3>
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
                    <th>Product</th>
                    <th>Categories</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    $num=1;
      $fetch_query="SELECT * FROM `giftbag_collection_products`";
      $sql=mysqli_query($conn,$fetch_query);
      $i=1;
      if(mysqli_num_rows($sql)>0){
          while($row=mysqli_fetch_assoc($sql)){
              $fetch_query1="SELECT 
    p.product_name AS ProductName,
    COALESCE(l1.cat_name, 'N/A') AS Level1Category,
    COALESCE(l2.name, 'N/A') AS Level2Category,
    COALESCE(l3.name, 'N/A') AS Level3Category,
    pi.image AS ProductImage
FROM 
    products p
LEFT JOIN 
    category_assign_to_product cap ON p.id_p = cap.pro_id
LEFT JOIN 
    categories_level3 l3 ON cap.level = 3 AND cap.cat_id = l3.id
LEFT JOIN 
    categories_level2 l2 ON (cap.level = 2 AND cap.cat_id = l2.id) 
                           OR (cap.level = 3 AND l3.parent_catid = l2.id)
LEFT JOIN 
    categories_level1 l1 ON l2.parent_catid = l1.cat_id
LEFT JOIN 
    product_images pi ON p.id_p = pi.product_id
WHERE 
    p.id_p = ".$row['product_id']." -- Replace '?' with the specific product ID
LIMIT 1";
                $sql1=mysqli_query($conn,$fetch_query1);
                $row1=mysqli_fetch_assoc($sql1)
      ?>
       <tr>
       <td><?=$num?></td>
        <td><?=$row1['ProductName']?></td>
        <td><?php echo $row1['Level1Category']." -> ".$row1['Level2Category']." -> ".$row1['Level3Category'];?></td>
           <td><img src="images/<?= $row1 ['ProductImage']?>" height="100px" width="100px" alt=""></td>
       <td>
               <div class="d-flex">

               <form method="POST" >
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                
            </form>
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
                    <tr>
                      <th>S.No</th>
                      <th>Product</th>
                      <th>Categories</th>
                      <th>Image</th>
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
