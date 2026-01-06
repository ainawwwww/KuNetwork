<?php include 'db.php';
include 'check_login.php';

// if (!isset($_SESSION['A_id'])) {
//     header("location:pages/examples/login.php");
//   exit();
// }
if (isset($_SESSION['role']) && $_SESSION['role']==1){
  $fetch="UPDATE `orders` SET `design_read_status`=1 , `updated_at`=CURRENT_TIMESTAMP WHERE `design_read_status`=0 AND status='In Design'";
      $sql=mysqli_query($conn,$fetch);

      $fetch="UPDATE `orders` SET `print_read_status`=1 , `updated_at`=CURRENT_TIMESTAMP WHERE `print_read_status`=0 AND status='In Printing'";
      $sql=mysqli_query($conn,$fetch);

      $fetch="UPDATE `orders` SET `indelivery_read_status`=1 , `updated_at`=CURRENT_TIMESTAMP WHERE `indelivery_read_status`=0 AND status='In Delivery'";
      $sql=mysqli_query($conn,$fetch);

      $fetch="UPDATE `orders` SET `delivered_read_status`=1 , `updated_at`=CURRENT_TIMESTAMP WHERE `delivered_read_status`=0 AND status='Delivered'";
      $sql=mysqli_query($conn,$fetch);

      $fetch="UPDATE `orders` SET `reject_read_status`=1 , `updated_at`=CURRENT_TIMESTAMP WHERE `reject_read_status`=0 AND status='Rejected'";
      $sql=mysqli_query($conn,$fetch);

      if(isset($_POST['user']) && isset($_POST['status'])){
        $status=$_POST['status'];
        $user=$_POST['user'];
        if($status=='All' && $user!='All')
        {
          $orderquery="select * FROM `orders` WHERE `sales_personid`='$user' ";
          $selected_status="All";
        $selected_user=$user;
        }
        elseif($status!='All' && $user=='All')
        {
          $orderquery="select * FROM `orders` WHERE `status`='$status'";
          $selected_status=$status;
        $selected_user="All";
        }
        elseif($status!='All' && $user!='All')
        {
          $orderquery="select * FROM `orders` WHERE `status`='$status'  and sales_personid='$user'";
          $selected_status=$status;
        $selected_user=$user;
        }
        else
        {
          $orderquery="select * FROM `orders`";
          $selected_status="All";
        $selected_user="All";
        }
      
      }
      else{
            $orderquery="select * FROM `orders`";
            $selected_status="All";
            $selected_user="All";
      
      }

}
else{
  
      $orderquery="SELECT * FROM `orders` WHERE `sales_personid`=".$_SESSION['A_id'];
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
            <h1>Order Management</h1>
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
                <h3 class="card-title">All Orders</h3>
                <a href="create_order.php" style="float: right;" class="btn btn-success">Add+</a>
              </div>
              <?php
              if (isset($_SESSION['role']) && $_SESSION['role']==1){
                ?>
              <div class="card-body">
              <form action="" method="post" >

            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <label>Sales Person</label>
                        <select class="custom-select" name="user">
                          <option>All</option>
                          <?php
                      $user_fetch="SELECT * FROM `admin`";
                      $usersql=mysqli_query($conn,$user_fetch);
                      if(mysqli_num_rows($usersql)>0){
                        while($row_user=mysqli_fetch_assoc($usersql)){
                          ?>
                          <option value="<?=$row_user['id']?>" <?=$selected_user==$row_user['id'] ? 'selected' : ''?>>
                            <?php echo $row_user['fname']." ".$row_user['lname']?>
                          </option>
                    <?php
                        }
                      }
                    ?>
                        </select>
                      </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                        <label>Order Status</label>
                        <select class="custom-select" name="status">
                          <option value="All" <?=$selected_status=='All' ? 'selected' : ''?>>All</option>
                          <option value="In Design" <?=$selected_status=='In Design' ? 'selected' : ''?>>In Design</option>
                          <option value="In Printing" <?=$selected_status=='In Printing' ? 'selected' : ''?>>In Printing</option>
                          <option value="In Delivery" <?=$selected_status=='In Delivery' ? 'selected' : ''?>>In Delivery</option>
                          <option value="Delivered" <?=$selected_status=='Delivered' ? 'selected' : ''?>>Delivered</option>
                          <option value="Rejected" <?=$selected_status=='Rejected' ? 'selected' : ''?>>Rejected</option>
                        </select>
                  </div>
              </div>
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-6">

              <div class="form-group">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </div>
</form>

          </div>
          <?php }?>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>S.No</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Priority</th>            
                    <th>Total</th>
                    <th>VAT</th>
                    <th>Advanced</th>
                    <th>Balance</th>  
                    <th>Date</th>
                    <th>Sales</th> 
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Valid Till</th>
                    <th>Advance Payment</th>
                    <th>Transaction ID</th>
                    <th>Balance Payment</th>
                    <th>Transaction ID</th>
                    <th>Status Update Date</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $orderquery.=" ORDER BY placement_date DESC";
      //echo $orderquery;
      $sql_order=mysqli_query($conn,$orderquery);
      $i=1;
      if(mysqli_num_rows($sql_order)>0){
        $num=1;
          while($row=mysqli_fetch_assoc($sql_order)){
            $salesrquery="SELECT * FROM `admin` WHERE `id`=".$row['sales_personid'];
            $sales_result=mysqli_query($conn,$salesrquery);
            $row_sales=mysqli_fetch_assoc($sales_result);

      ?>

       <tr>
       <td><?=$num?></td>
       <td>
            <a href="order_detail.php?id=<?=$row['orderTrackingId']?>"><?=$row['orderTrackingId']?></a>
        </td>
        <td>
            <select class="order_status"  name="status" id="<?=$row['id']?>" >
                    
                        <option value="In Design" <?php if ($row['status']=='In Design') echo 'selected';?>>In Design</option>
                        <option value="In Printing" <?php if ($row['status']=='In Printing') echo 'selected';?>>In Printing</option>
                        <option value="In Delivery" <?php if ($row['status']=='In Delivery') echo 'selected ';?>>In Delivery</option>
                        <option value="Delivered" <?php if ($row['status']=='Delivered') echo 'selected disabled';?>>Delivered</option>
                        <option value="Rejected" <?php if ($row['status']=='Rejected') echo 'disabled selected';?>>Rejected</option>
                  </select>           
           </td>
           <td><?=$row['payment_status']?></td>
           <td><?=$row['priority']?></td>
           <td><?=$row['total_amount']?> AED</td>
           <td><?=$row['vat_amount']?> AED</td>
           <td><?=$row['advanced_payment_amount']?> AED</td>
           <td><?php echo $row['total_amount']-$row['advanced_payment_amount']." AED"?></td>
           <td><?=$row['placement_date']?></td>
           <td><?= ($row['role'] == 1 ? 'Admin' : 'SubAdmin') ?> : <?= $row_sales['fname']." ".$row_sales['lname'] ?></td>
           <td><?=$row['customer_name']?></td>
           <td><?=$row['phone_number']?></td> 
           <td><?=$row['customer_address']?></td> 
           <td><?=$row['validity_date']?></td>
           <td><?=$row['advance_payment']?></td>
           <td><?=$row['advanced_transaction_id']?></td>
           <td><?=$row['balance_payment']?></td>
           <td><?=$row['balance_transaction_id']?></td>
           <td><?=$row['delivery_date']?></td>
           <td><?=$row['created_at']?></td>

           
           <td>
              
               <a href="update_order.php?id=<?=$row['orderTrackingId']?>"><button class="btn btn-info btn-sm mx-2"  style="border-radius:15px;"  > <i class="fas fa-pencil-alt"></i> Update</button></a>
               
               <!-- <form method="POST" action="action/delete_order.php">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                
            </form> -->
            
           </td>
         </tr> 






         <!-- <tr>
       
          
       <td>Imran Ali</td>
       <td><form method="POST" action="action/download_customorder_file.php">
            <input type="hidden" name="file" value="">
           <button class="btn btn-primary btn-sm"  style="border-radius:1px;" type="submit" name="download"><i class="fas fa-download"></i></button>
            
        </form></td>
       <td>034426023833</td>
       <td>55</td>
       <td>100</td>
       <td>Small</td>
       <td>Normal</td>
       <td>
        <select class="order_status"  name="category" id="" >
                
                    <option value="In Process" >In Process</option>
                    <option value="Delivered" >Delivered</option>
                    <option value="Rejected" >Rejected</option>
              </select>           
       </td>
       <td>5 December 2024</td>
       <td>Faizan sabir</td>

       
       <td>28 Nov 2024</td>
       <td>
          <div class="d-flex">
           <a href="update_order.php?id="><button class="btn btn-info btn-sm mx-2"  style="border-radius:15px;"  ><i class="fas fa-pencil-alt"></i></button></a>
           
           <form method="POST" action="action/delete_order.php">
            <input type="hidden" name="id" value="">
            <button class="btn btn-danger btn-sm"  style="border-radius:15px;" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
            
        </form>
        </div>
       </td>
       <td>dbgfrjg rgner gtjergterjte tneioty n</td>
     </tr> -->



       <?php
               $num++;
         }
      }
       ?>


                  </tbody>
                  <tfoot>
                  <tr>
                  <th>S.No</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Priority</th>            
                    <th>Total</th>
                    <th>VAT</th>
                    <th>Advanced</th>
                    <th>Balance</th>  
                    <th>Date</th>
                    <th>Sales</th> 
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Valid Till</th>
                    <th>Advance Payment</th>
                    <th>Transaction ID</th>
                    <th>Balance Payment</th>
                    <th>Transaction ID</th>
                    <th>Status Update Date</th>
                    <th>Created At</th>
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
 $.ajax({
  url: "action/change_order_status.php",
  type: "POST",
  data: {id : rowid,
        selected_status:selected_status},
        success: function(data){
     if(data==1)
     alert("Order Status Updated Succesfully!!")
      
  }
});

});
  });
</script>
