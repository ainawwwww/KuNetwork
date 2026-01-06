<?php 
include 'db.php';
include 'check_login.php';
include 'products_analytics_data.php';
$array_data1_temp=$array_data1;
$array_data7_temp=$array_data7;
$array_data30_temp=$array_data30;
$array_data180_temp=$array_data365;
$array_label_temp=$array_label;
// echo "<pre>"; 
// print_r($array_label_temp);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <script>
    
    var label_array=[<?php 
      $length = count($array_label_temp);
    for ($i = 0; $i < $length; $i++) {
    echo "'".$array_label_temp[$i]."',";
    
    }
    ?>];
   
   
    
    var data_array1=[<?php 
      $length = count($array_data1_temp);
    for ($i = 0; $i < $length; $i++) {
    echo $array_data1_temp[$i].',';
    }
    ?>];

var data_array7=[<?php 
      $length = count($array_data7_temp);
    for ($i = 0; $i < $length; $i++) {
    echo $array_data7_temp[$i].',';
    }
    ?>];
    
    
    var data_array30=[<?php 
      $length = count($array_data30_temp);
    for ($i = 0; $i < $length; $i++) {
    echo $array_data30_temp[$i].',';
    }
    ?>];
    var data_array180=[<?php 
      $length = count($array_data180_temp);
    for ($i = 0; $i < $length; $i++) {
    echo $array_data180_temp[$i].',';
    }
    ?>];

     
    
    
    
var xValues1 = label_array;
var yValues1 = data_array1;
var xValues7 = label_array;
var yValues7 = data_array7;
var xValues30 = label_array;
var yValues30 = data_array30;
var xValues180 = label_array;
var yValues180 = data_array180;


  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include 'sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
           
            <!-- /.card -->

            <!-- solid sales graph -->
            <div class="card bg-gradient-dark" >
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Top Product Clicks 
                </h3>
                <div class="card-tools">
                  
                  
                  <button type="button" class="btn bg-dark active category_clicks" id="1">
                    Monthly
                  </button>
                  <button type="button" class="btn bg-dark category_clicks" id="7">
                    6 Months
                  </button>
                  <button type="button" class="btn bg-dark  category_clicks " id="30" >
                    1 Year
                  </button>
                  <button type="button" class="btn bg-dark category_clicks " id="180">
                    5 Years
                  </button>
                  <!-- <button type="button" class="btn bg-info category_clicks " id="all">
                    All Time
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart1" style="min-height: 500px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart7" style="min-height: 500px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart30" style="min-height: 500px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart180" style="min-height: 500px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                <!-- <canvas class="chart" id="line-chartall" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
              </div>
              <!-- /.card-body -->
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->

            <!-- Calendar -->
            
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>

        <!-- all products table -->

        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Products Clicks</h3>
                <a href="add_category1.php" style="float: right;" class="btn btn-success">Add+</a>
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
                    <th>Clicks</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
      $fetch_query="SELECT COUNT(*) as total_clicks ,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p  GROUP BY product_clicks.product_id ORDER BY total_clicks DESC";
      $sql=mysqli_query($conn,$fetch_query);
      if(mysqli_num_rows($sql)>0){
        $num=1;
          while($row=mysqli_fetch_assoc($sql)){
      
    ?>
       <tr>
          <td><?=$num?></td>
          <td><?=$row['product_name']?></td>
          <td><?=$row['total_clicks']?></td>
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
                    <th>Name</th>
                    <th>Clicks</th>
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





        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
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
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

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

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
</body>
</html>
<script type="text/javascript">

                $(function () {

   

//  24 hours
  var salesGraphChartCanvas = $('#line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: xValues1,
    datasets: [
      {
        label: 'Clicks',
        fill: true,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues1
      }
    ]
  }

  var salesGraphChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffff',
          fontSize:'8'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData,
    options: salesGraphChartOptions
  })

   //7 days
  var salesGraphChartCanvas7 = $('#line-chart7').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData7 = {
    labels: xValues7,
    datasets: [
      {
        label: 'Clicks',
        fill: true,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues7
      }
    ]
  }

  var salesGraphChartOptions7 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
         fontColor: '#ffff',
          fontSize:'8'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas7, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData7,
    options: salesGraphChartOptions7
  })


//30 days
  var salesGraphChartCanvas30 = $('#line-chart30').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData30 = {
    labels: xValues30,
    datasets: [
      {
        label: 'Clicks',
        fill: true,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues30
      }
    ]
  }

  var salesGraphChartOptions30 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffff',
          fontSize:'8'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas30, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData30,
    options: salesGraphChartOptions30
  })


  //6 months 
  var salesGraphChartCanvas180 = $('#line-chart180').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData180 = {
    labels: xValues180,
    datasets: [
      {
        label: 'Clicks',
        fill: true,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues180
      }
    ]
  }

  var salesGraphChartOptions180 = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffff',
          fontSize:'8'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas180, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData180,
    options: salesGraphChartOptions180
  })




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

  



})
    
</script>

<script>
  $(document).ready(function(){
    $(".chart").hide();
$("#line-chart1").show();
$(".category_clicks").click(function(){

  $(".category_clicks").removeClass("active");
   var id=$(this).attr('id');
   $(this).addClass("active");
   $(".chart").hide();
   $("#line-chart"+id).show();
});

          
  });

</script>