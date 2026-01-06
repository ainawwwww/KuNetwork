<?php include 'db.php';
include 'check_login.php';

if(isset($_GET['id'])) {
    $id=$_GET['id'];
    
    
    }
    else{
       header("location:order_history.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Advanced form elements</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
            <h1>Create Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Order Management</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Create a new Order</h3>

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
              <form action="action/update_order.php" method="post" enctype="multipart/form-data">
                <?php
                if(isset($_GET['id'])) {
                  $id=$_GET['id'];
                  $sql="SELECT * FROM `orders` WHERE orderTrackingId=$id";
  $data=mysqli_query($conn,$sql);
  $row=mysqli_fetch_assoc($data);
                  
                  }
                ?>
                  <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" class="form-control" value="<?=$row['customer_name']?>" name="c_name" required  id="exampleInputEmail1" placeholder="Enter Customer Name">
                    <input type="hidden" class="form-control" value="<?=$row['orderTrackingId']?>" name="id" >
                  </div>
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" value="<?=$row['phone_number']?>" name="phone_number" required id="exampleInputEmail1" placeholder="Enter Phone Number">
                  </div>
                  <div class="form-group clearfix">
                    <label>Order Priority</label>
                    <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary1" <?= ($row['priority'] == 'Normal') ? 'checked' : ''; ?> name="priority" checked value="Normal">
                        <label for="radioPrimary1">
                          Normal
                        </label>
                    </div>
                    <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary2" <?= ($row['priority'] == 'Express') ? 'checked' : ''; ?> name="priority" value="Express">
                        <label for="radioPrimary2">
                          Express
                        </label>
                    </div>
                  </div>

                    <!-- radio -->
                    <div class="form-group clearfix">
                      <label>Order Status</label>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary11" name="status" <?= ($row['status'] == 'In Design') ? 'checked' : ''; ?>  value="In Design">
                        <label for="radioPrimary11">
                          In Design
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary22" name="status" <?= ($row['status'] == 'In Printing') ? 'checked' : ''; ?>  value="In Printing">
                        <label for="radioPrimary22">
                          In Printing
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary33" name="status" <?= ($row['status'] == 'In Delivery') ? 'checked' : ''; ?>  value="In Delivery">
                        <label for="radioPrimary33">
                          In Delivery
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary44" name="status" <?= ($row['status'] == 'Delivered') ? 'checked' : ''; ?> value="Delivered">
                        <label for="radioPrimary44">
                        Delivered
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary55" name="status" <?= ($row['status'] == 'Rejected') ? 'checked' : ''; ?> value="Rejected">
                        <label for="radioPrimary55">
                          Rejected
                        </label>
                      </div>
                    </div>
                <div class="form-group">
                  <label>Order Date</label>
                  <input type="date" class="form-control" name="placement_date" value="<?= $row['placement_date'] ?>"  required id="exampleInputEmail1" >
                </div>

                <div class="form-group">
                  <label>Valid Until</label>
                  <input type="date" value="<?= $row['validity_date'] ?>" class="form-control" name="validity_date" required id="exampleInputEmail1" >
                </div>
                
               
                <!-- textarea -->
                <div class="form-group">
                  <label>Address(Not Compulsory)</label>
                  <textarea name="c_address" class="form-control" rows="3" placeholder="Enter ..."><?= $row['customer_address'] ?></textarea>
                </div>
                
                <hr class="border border-3 border-dark">
                


                  <hr class="border border-3 border-dark">
                <h3 class="text-center text-danger" id="total_amount_span">Total Amount: <span id="total_amount"><?= $row['total_amount'] ?></span> AED</h3>
                <h4 class="text-center text-danger" id="total_vat_amount_span">VAT Amount: <span id="vat_amount"><?= $row['vat_amount'] ?></span> AED</h4>
                <div class="row">
                  <div class="col-6">
                  <div class="form-group clearfix">
                      <label>VAT (5 %)</label>
                      <div class="icheck-dark d-inline">
                        <input type="radio" disabled <?= ($row['vat_amount'] > 0) ? 'checked' : ''; ?> id="radioPrimary1vat" name="vat"  value="Yes">
                        <input type="hidden" name="total_vat_amount" id="total_vat_amount" value="<?= $row['vat_amount'] ?>">
                        <label for="radioPrimary1vat">
                          Yes
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" disabled id="radioPrimary2vat" <?= ($row['vat_amount'] ==0) ? 'checked' : ''; ?> name="vat" value="No">
                        <label for="radioPrimary2vat">
                          No
                        </label>
                      </div>
                </div>
                  </div>
                  <div class="col-6">
                  <div class="form-group clearfix" >
                      <label>Payment Status</label>
                      
                      <select class="form-control" disabled name="payment_status" style="width: 100%;">
                        <option value="Paid" <?= ($row['payment_status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                        <option value="Partially Paid" <?= ($row['payment_status'] == 'Partially Paid') ? 'selected' : ''; ?>>Partially Paid</option>
                        <option value="Unpaid" <?= ($row['payment_status'] == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option> 
                      </select>
                </div>
                <input type="hidden" id="total_amount_without_vat" name="total_amount_without_vat" value="<?= $row['total_amount_without_vat'] ?>">
                
                  </div>

                </div>
                <hr class="border border-3 border-dark">
                <div class="row">
                  <div class="col-6 border border-3 border-dark">
                    <div class="form-group">
                      <label>Advanced Payment</label>
                      <select class="form-control"     name="advance_payment" style="width: 100%;">
                        <option value="Bank Transfer" <?= ($row['advance_payment'] == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                        <option value="Link Payment" <?= ($row['advance_payment'] == 'Link Payment') ? 'selected' : ''; ?>>Link Payment</option>
                        <option value="Card Machine Payment" <?= ($row['advance_payment'] == 'Card Machine Payment') ? 'selected' : ''; ?>>Card Machine Payment</option>
                        <option value="Cash" <?= ($row['advance_payment'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                        <option value="None" <?= ($row['advance_payment'] == 'None') ? 'selected' : ''; ?>>None</option>      
                      </select>
                    </div>
                    <div class="form-group" id="transaction_id_div">
                      <label>Transaction ID</label>
                      <input type="text" class="form-control" value="<?= $row['advanced_transaction_id']?>" name="advanced_transaction_id"  id="exampleInputEmail1" placeholder="Enter Transaction ID">
                    </div>
                    <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" value="<?= $row['advanced_payment_amount']?>" name="advanced_payment_amount"  id="exampleInputEmail1" placeholder="Enter Amount" value="5000">
                    </div>
                  </div>
                  <div class="col-6 border border-3 border-dark">
                    <div class="form-group">
                      <label>Balance Payment</label>
                      <select class="form-control"   name="balance_payment"  style="width: 100%;">
                      <option value="Bank Transfer" <?= ($row['balance_payment'] == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                        <option value="Link Payment" <?= ($row['balance_payment'] == 'Link Payment') ? 'selected' : ''; ?>>Link Payment</option>
                        <option value="Card Machine Payment" <?= ($row['balance_payment'] == 'Card Machine Payment') ? 'selected' : ''; ?>>Card Machine Payment</option>
                        <option value="Cash" <?= ($row['balance_payment'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                        <option value="None" <?= ($row['balance_payment'] == 'None') ? 'selected' : ''; ?>>None</option>     
                      </select>
                    </div>
                    <div class="form-group" id="transaction_id_div_balance">
                      <label>Transaction ID</label>
                      <input type="text" class="form-control" value="<?= $row['balance_transaction_id']?>" name="balance_transaction_id" id="exampleInputEmail1" placeholder="Enter Transaction ID">
                    </div>
                    <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" value="<?= $row['balance_payment_amount']?>" name="balance_payment_amount"  id="amount" placeholder="Enter Amount">
                    </div>
                  </div>
                </div>

                


                
                
                
                <!-- /.button -->
                <div class="form-group d-flex justify-content-between">
                  <button type="submit" name="insert" class="btn btn-primary">Update</button>
                  <div class="ms-auto">
                  <button type="submit" name="generate_pdf" class="btn btn-danger">Generate PDF</button>
                      <a href="order_history.php"><button type="button" name="cancel" class="btn btn-default">Cancel</button></a>
                  </div>
              </div>
             </form>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

      
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Invoice Products</h3>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>S.No</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>            
                    <th>File</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $orderquery="SELECT order_detail.*,products.product_name FROM `order_detail` 
JOIN products on order_detail.product_id=products.id_p
WHERE `order_id`=".$row['id'];
      //echo $orderquery;
      $sql_order=mysqli_query($conn,$orderquery);
      $i=1;
      if(mysqli_num_rows($sql_order)>0){
        $num=1;
          while($row=mysqli_fetch_assoc($sql_order)){
            // $salesrquery="SELECT * FROM `admin` WHERE `id`=".$row['sales_personid'];
            // $sales_result=mysqli_query($conn,$salesrquery);
            // $row_sales=mysqli_fetch_assoc($sales_result);

      ?>

       <tr>
       <td><?=$num?></td>
      
          
           <td><?=$row['product_name']?></td> 
           <td><?=$row['description']?></td> 
           <td><?=$row['quantity']?></td>
           <td><?=$row['price']?></td>
           <td>
            <?php if(!empty($row['file'])){
              ?>
              <form action="action/download_customorder_file.php" method="post">
              <input type="hidden" value="<?=$row['file']?>" name="file">
              <button class="btn btn-info" type="submit" name="download"><i class="fas fa-download"></i> Download</button>
            </form>
            <?php }
              ?>
            
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
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>            
                    <th>File</th>
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
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- data table -->
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
<!-- Page specific script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
</script>
<script>
  $(document).ready(function(){
     // Hide the Transaction ID field initially if the selected value is not 'bank_transfer'
     if ($('select[name="advance_payment"]').val() !== 'Bank Transfer') {
      $('#transaction_id_div').hide();
    }
    
    // Listen for changes on the dropdown
    $('select[name="advance_payment"]').on('change', function(){
      
      if ($(this).val() === 'Bank Transfer') {
        $('#transaction_id_div').show();
      } else {
        $('#transaction_id_div').hide();
      }
    });

    if ($('select[name="balance_payment"]').val() !== 'Bank Transfer') {
      $('#transaction_id_div_balance').hide();
    }

    // Listen for changes on the dropdown
    $('select[name="balance_payment"]').on('change', function(){
      
      if ($(this).val() === 'Bank Transfer') {
        $('#transaction_id_div_balance').show();
      } else {
        $('#transaction_id_div_balance').hide();
      }
    });
  });
</script>
</body>
</html>
