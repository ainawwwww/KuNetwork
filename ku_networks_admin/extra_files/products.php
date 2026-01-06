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
            <h1>Add Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product Form</li>
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
            <h3 class="card-title">Add a new Specific Product</h3>

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
                  <form action="action/insert_product.php" method="post" enctype="multipart/form-data" id="product_form">
                <div class="category-group">
                <div class="form-group">
                  <label>Level one Category</label>
                  <select class="select2" id="category1_1" name="cat1_1"   data-placeholder="Select Level One Category" style="width: 100%;">
                  <option value="">Select Level One Category</option>
                    <?php
                      $cat_fetch="SELECT * FROM `categories_level1`";
                      $sql=mysqli_query($conn,$cat_fetch);
                      if(mysqli_num_rows($sql)>0){
                        while($row=mysqli_fetch_assoc($sql)){
                          ?>
                          <option value="<?=$row['cat_id']?>"><?=$row['cat_name']?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Level Two Category</label>
                  <select class="select2" name="cat2_1" id="category2_1"    data-placeholder="Select Level Two Category" style="width: 100%;">
                 
                      
                          <option value="">Select Level Two Category</option>
                    
                  </select>
                </div>
                <div class="form-group">
                  <label>Level Three Category</label>
                  <select class="select2" name="cat3_1" id="category3_1"    data-placeholder="Select Level Three Category" style="width: 100%;">
                 
                      
                          <option value="" selected>Select Level Three Category</option>
                    
                  </select>
                </div>
                <hr>
                </div>

                <button type="button" id="addMore" class="btn btn-secondary mb-3">Add More Category</button>

                  <div class="form-group">
                  <label>Product Name</label>
                  <input type="text" class="form-control" name="p_name"   id="exampleInputEmail1" placeholder="Enter Product Name">
                  <input type="hidden" name="total_category" id="total_category" value="1">
                </div>
                
                <div class="form-group">
                  <label>Product Price</label>
                  <input type="number" class="form-control" name="p_price"  id="exampleInputEmail1" placeholder="Enter  Price" >
                  <input type="number" class="form-control" name="p_discount_price"  id="exampleInputEmail1" placeholder="Enter Discount Price" >
                </div>

                <div class="form-group">
                  <label>Product Stock</label>
                  <input type="number" class="form-control" name="p_stock" value="1" id="exampleInputEmail1"  >
                </div>
                
               
                <!-- textarea -->
                <div class="form-group">
                  <label>Product Description</label>
                  <textarea name="Pro_description" class="form-control"  rows="3" placeholder="Enter ..."></textarea>
                </div>
                <!-- textarea -->
                <div class="form-group">
                  <label>Product Detailed Description</label>
                  <textarea name="Pro_detailed_description" class="form-control"  rows="3" placeholder="Enter ..."></textarea>
                </div>
                <div class="form-group clearfix">
                      <label>Product Badge:-</label>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary1" name="p_badge" checked="" value="No Badge">
                        <label for="radioPrimary1">
                          No Badge
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary2" name="p_badge" value="New">
                        <label for="radioPrimary2">
                          New
                        </label>
                      </div>
                      <div class="icheck-dark d-inline">
                        <input type="radio" id="radioPrimary2" name="p_badge" value="Sale">
                        <label for="radioPrimary2">
                          Sale
                        </label>
                      </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" multiple name="images[]"  class="form-control custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                  </div>
                <div class="form-group">
                  <button type="submit" name="insert" class="btn btn-primary">Submit</button>
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
<script>
  $(document).ready(function(){

    var catquantity=1;
    $('#addMore').on('click', function () {
      catquantity=catquantity+1;
      
      $("#total_category").val(catquantity);

            const newCategoryGroup = `
                <div class="category-group">
                <div class="form-group">
                  <label>Level one Category</label>
                  <select class="select2" id="category1_`+catquantity+`" name="cat1_`+catquantity+`"   data-placeholder="Select Level One Category" style="width: 100%;">
                  <option value="">Select Level One Category</option>
                    <?php
                      $cat_fetch="SELECT * FROM `categories_level1`";
                      $sql=mysqli_query($conn,$cat_fetch);
                      if(mysqli_num_rows($sql)>0){
                        while($row=mysqli_fetch_assoc($sql)){
                          ?>
                          <option value="<?=$row['cat_id']?>"><?=$row['cat_name']?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Level Two Category</label>
                  <select class="select2" name="cat2_`+catquantity+`" id="category2_`+catquantity+`"    data-placeholder="Select Level Two Category" style="width: 100%;">
                 
                      
                          <option value="">Select Level Two Category</option>
                    
                  </select>
                </div>
                <div class="form-group">
                  <label>Level Three Category</label>
                  <select class="select2" name="cat3_`+catquantity+`" id="category3_`+catquantity+`"    data-placeholder="Select Level Three Category" style="width: 100%;">
                 
                      
                          <option value="" selected>Select Level Three Category</option>
                    
                  </select>
                </div>
                </div>
                <hr>
            `;
            // Append the new category group to the form
            $('#addMore').before(newCategoryGroup);

            //Initialize Select2 Elements
            $('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
theme: 'bootstrap4'
})
        });




        $('#product_form').on('change', '.select2', function () {
                  var thisid=$(this).attr("id");
          
          var split_id = thisid.split("_")
          //alert(split_id[0]);
          if(split_id[0]=="category1")
        {
          var selectedValue = $(this).val();
          //alert(selectedValue);
                $.ajax({
                  type: "POST",
                  url: "action/fetch_cat2_from_cat1.php",
                  data: {selectedValue:selectedValue},
                  success: function(result) {
                    var result="<option value='0' disable>Select Level Two Category</option>"+result;
                    $('#category2_'+split_id[1]).empty().append(result);
                  }
                });
        }
        else if(split_id[0]=="category2")
        {
          var selectedValue = $(this).val();
          //alert(selectedValue);

                $.ajax({
                  type: "POST",
                  url: "action/fetch_cat3_from_cat2.php",
                  data: {selectedValue:selectedValue},
                  success: function(result) {
                    //alert(result)
                    var result="<option value='0'>No Level Three Category</option>"+result;
                    $('#category3_'+split_id[1]).empty().append(result);
                  }
                });
        } 
        });

  });
</script>
</body>
</html>
