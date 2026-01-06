<?php include 'db.php';
include 'check_login.php';
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
                <form action="action/add_ordernew.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" class="form-control" name="c_name" required  id="exampleInputEmail1" placeholder="Enter Customer Name">
                  </div>
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone_number" required id="exampleInputEmail1" placeholder="Enter Phone Number">
                  </div>
                  <div class="form-group clearfix">
                    <label>Order Priority</label>
                    <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary1" name="priority" checked value="Normal">
                        <label for="radioPrimary1">
                          Normal
                        </label>
                    </div>
                    <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary2" name="priority" value="Express">
                        <label for="radioPrimary2">
                          Express
                        </label>
                    </div>
                  </div>

                    <!-- radio -->
                    <div class="form-group clearfix">
                      <label>Order Status</label>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary11" name="status" checked  value="In Design">
                        <label for="radioPrimary11">
                          In Design
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary22" name="status"  value="In Printing">
                        <label for="radioPrimary22">
                          In Printing
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary33" name="status"  value="In Delivery">
                        <label for="radioPrimary33">
                          In Delivery
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary44" name="status"  value="Delivered">
                        <label for="radioPrimary44">
                        Delivered
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary55" name="status" value="Rejected">
                        <label for="radioPrimary55">
                          Rejected
                        </label>
                      </div>
                    </div>
                <div class="form-group">
                  <label>Order Date</label>
                  <input type="date" class="form-control" name="placement_date" required id="exampleInputEmail1" >
                </div>

                <div class="form-group">
                  <label>Valid Until</label>
                  <input type="date" class="form-control" name="validity_date" required id="exampleInputEmail1" >
                </div>
                
               
                <!-- textarea -->
                <div class="form-group">
                  <label>Address(Not Compulsory)</label>
                  <textarea name="c_address" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                  <input type="hidden" name="total_product" id="total_product" value="1" >
                </div>
                
                <hr class="border border-3 border-dark"><hr class="border border-3 border-dark">
                <div class="product-group">
                  <h4 class="text-center text-primary">Product No. <span>1</span></h4>
                    <div class="form-group">
                    <label>Product</label>
                    <select class="select2" required   name="product_1" id="product_1"  data-placeholder="Select Product" style="width: 100%;">
                    <?php
                            $records = mysqli_query($conn, "SELECT * FROM `products`");  // Use select query here 

                            while($data = mysqli_fetch_array($records))
                            {
                                echo "<option value='". $data['id_p'] ."'>" .$data['product_name'] ."</option>";  // displaying data in option menu
                            } 
                        ?>   
                    </select>
                    </div>

                    <div class="form-group">
                      <label>Description</label>
                      <input type="text" class="form-control" name="p_description_1" required id="exampleInputEmail1" placeholder="Enter Description of Product">
                    </div>

                    <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" class="form-control" name="p_quantity_1" required id="exampleInputEmail1" placeholder="Enter Product Quantity" >
                    </div>
                    <div class="form-group">
                      <label>Final Price</label>
                      <input type="number" class="form-control price-input" name="p_final_price_1" required id="exampleInputEmail1" placeholder="Enter Price">
                    </div>

                    <div class="form-group">
                        <label for="customFile">File input</label>
                        <div class="custom-file">
                        <input type="file"  name="image_1" class="custom-file-input" id="customFile_1">
                        <label class="custom-file-label" for="customFile_1">Choose file</label>
                        <label for="customFile_1" id="fileLabel">No file selected</label>
                        </div>
                    </div>
                    <hr>
                  </div>

                  <button type="button" id="addMore" class="btn btn-secondary mb-3">Add More Product</button>

                  <hr class="border border-3 border-dark">
                <h3 class="text-center text-danger" id="total_amount_span">Total Amount: <span id="total_amount">0</span> AED</h3>
                <h4 class="text-center text-danger" id="total_vat_amount_span">VAT Amount: <span id="vat_amount">0</span> AED</h4>
                <div class="row">
                  <div class="col-6">
                  <div class="form-group clearfix">
                      <label>VAT (5 %)</label>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary1vat" name="vat"  value="Yes">
                        <input type="hidden" value="0" name="total_vat_amount" id="total_vat_amount">
                        <label for="radioPrimary1vat">
                          Yes
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary2vat" checked name="vat" value="No">
                        <label for="radioPrimary2vat">
                          No
                        </label>
                      </div>
                </div>
                  </div>
                  <div class="col-6">
                  <div class="form-group clearfix">
                      <label>Payment Status</label>
                      
                      <select class="form-control" name="payment_status" style="width: 100%;" disabled>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Unpaid" selected>Unpaid</option> 
                      </select>
                </div>
                <input type="hidden" id="total_amount_without_vat" name="total_amount_without_vat" value="0">
                
                  </div>

                </div>
                <hr class="border border-3 border-dark">
                <div class="row">
                  <div class="col-6 border border-3 border-dark">
                    <div class="form-group">
                      <label>Advanced Payment</label>
                      <select class="form-control"     name="advance_payment" style="width: 100%;">
                        <option value="Bank Transfer" selected>Bank Transfer</option>
                        <option value="Link Payment">Link Payment</option>
                        <option value="Card Machine Payment">Card Machine Payment</option>
                        <option value="Cash">Cash</option>
                        <option value="None">None/0</option>      
                      </select>
                    </div>
                    <div class="form-group" id="transaction_id_div">
                      <label>Transaction ID</label>
                      <input type="text" class="form-control" name="advanced_transaction_id"  id="exampleInputEmail1" placeholder="Enter Transaction ID">
                    </div>
                    <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" name="advanced_payment_amount"  id="exampleInputEmail1" placeholder="Enter Amount" >
                    </div>
                  </div>
                  <div class="col-6 border border-3 border-dark">
                    <div class="form-group">
                      <label>Balance Payment</label>
                      <select class="form-control"   name="balance_payment"  style="width: 100%;">
                        <option value="Bank Transfer" selected>Bank Transfer</option>
                        <option value="Link Payment">Link Payment</option>
                        <option value="Card Machine Payment">Card Machine Payment</option>
                        <option value="Cash">Cash</option>
                        <option value="None" >None/0</option>      
                      </select>
                    </div>
                    <div class="form-group" id="transaction_id_div_balance">
                      <label>Transaction ID</label>
                      <input type="text" class="form-control" name="balance_transaction_id" id="exampleInputEmail1" placeholder="Enter Transaction ID">
                    </div>
                    <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" name="balance_payment_amount"  id="amount" placeholder="Enter Amount">
                    </div>
                  </div>
                </div>

                


                
                
                
                <!-- /.button -->
                <div class="form-group d-flex justify-content-between">
                  <button type="submit" name="insert" class="btn btn-primary">Submit</button>
                  <div class="ms-auto">
                      <button type="submit" name="generate_pdf" class="btn btn-danger">Generate PDF</button>
                      <button type="reset" name="cancel" class="btn btn-default">Cancel</button>
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


<!-- SweetAlert2 -->
<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
  <script>
    document.getElementById('customFile').addEventListener('change', function() {
    var fileNames = [];
    for (var i = 0; i < this.files.length; i++) {
        fileNames.push(this.files[i].name);
    }

    //alert(fileNames.join(', '));
    document.getElementById('fileLabel').textContent = fileNames.join(', ');
});
  
</script>
<script>
    $(document).ready(function(){
    var proquantity=1;
    $('#addMore').on('click', function () {
        
      proquantity=proquantity+1;
      
      $("#total_product").val(proquantity);

            const newCategoryGroup = `
                <div class="product-group">
                <h4 class="text-center text-primary">Product No. <span>`+proquantity+`</span></h4>
                    <div class="form-group">
                    <label>Product</label>
                    <select class="select2" required   name="product_`+proquantity+`" id="product_`+proquantity+`"  data-placeholder="Select Product" style="width: 100%;">
                    <?php
                            $records = mysqli_query($conn, "SELECT * FROM `products`");  // Use select query here 

                            while($data = mysqli_fetch_array($records))
                            {
                                echo "<option value='". $data['id_p'] ."'>" .$data['product_name'] ."</option>";  // displaying data in option menu
                            } 
                        ?>   
                    </select>
                    </div>

                    <div class="form-group">
                    <label>Description</label>
                  <input type="text" class="form-control" name="p_description_`+proquantity+`" required id="exampleInputEmail1" placeholder="Enter Description of Product">
                    </div>
                    <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" class="form-control" name="p_quantity_`+proquantity+`" required id="exampleInputEmail1" placeholder="Enter Product Quantity">
                    </div>
                    <div class="form-group">
                    <label>Final Price</label>
                    <input type="number" class="form-control price-input" name="p_final_price_`+proquantity+`" required id="exampleInputEmail1" placeholder="Enter Price">
                    </div>

                    <div class="form-group">
                        <label for="customFile">File input</label>

                        <div class="custom-file">
                        <input type="file"  name="image_`+proquantity+`"  class="custom-file-input" id="customFile_`+proquantity+`">
                        <label class="custom-file-label" for="customFile_`+proquantity+`">Choose file</label>
                        <label for="customFile_`+proquantity+`" id="fileLabel">No file selected</label>

                        </div>
                    </div>
                    <hr>
                
                
                </div>
                
            `;
            // Append the new category group to the form
            $('#addMore').before(newCategoryGroup);
            //Initialize Select2 Elements
                $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })

            $(".price-input").on("input", function () {
                let total = 0;
                $(".price-input").each(function () {
                    let value = parseFloat($(this).val()) || 0; // Convert to number or use 0 if empty
                    total += value;
                });
                $("#total_amount").text(total); // Show total with 2 decimal places
                $("#total_amount_without_vat").val(total); 
                $('#vat_amount').text(0);
                $('#total_vat_amount').val(0); // Reset to 0 amount
                $('input[name="vat"][value="Yes"]').prop("checked", false); // Check "Yes"
                $('input[name="vat"][value="No"]').prop("checked", true); // Uncheck "No"
            });

        });

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

    

    $('input[name="vat"]').on('change', function () {
      let baseAmount = parseFloat($('#total_amount').text()) || 0; // Convert text to number
      
        if ($(this).val() === 'Yes') {
            let vatAmount = baseAmount * 0.05; // Calculate 5% VAT
            let total = baseAmount + vatAmount; // Add VAT to base amount

            $('#vat_amount').text(vatAmount.toFixed(2));
            $('#total_vat_amount').val(vatAmount.toFixed(2)); // Update total with 2 decimal places
        } else {
          $('#vat_amount').text(0);
          $('#total_vat_amount').val(0); // Reset to 0 amount
                
        }
    });


    $(".price-input").on("input", function () {
        let total = 0;
        $(".price-input").each(function () {
            let value = parseFloat($(this).val()) || 0; // Convert to number or use 0 if empty
            total += value;
        });
        $("#total_amount").text(total); // Show total with 2 decimal places
        $("#total_amount_without_vat").val(total); 
        $('#vat_amount').text(0);
        $('#total_vat_amount').val(0); // Reset to 0 amount
        $('input[name="vat"][value="Yes"]').prop("checked", false); // Check "Yes"
        $('input[name="vat"][value="No"]').prop("checked", true); // Uncheck "No"
    });


  });
</script>

</body>
</html>
