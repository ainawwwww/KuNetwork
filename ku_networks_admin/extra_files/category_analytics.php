<?php 
include 'db.php';
include 'check_login.php';
include 'category_analytics_data.php';
$array_data1_temp=$array_data1;
$array_data7_temp=$array_data7;
$array_data30_temp=$array_data30;
$array_data180_temp=$array_data180;
$array_label_temp=$array_label;
// echo "<pre>"; 
// print_r($array_data30_temp);
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
  <script>
    
    var label_array=[<?php 
      $length = count($array_label_temp);
    for ($i = 0; $i < $length; $i++) {
    echo $array_label_temp[$i].",";
    
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
    echo $array_data7[$i].',';
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

// for category level two
var cat2_label_array=[<?php 
      $length = count($cat2_array_label);
    for ($i = 0; $i < $length; $i++) {
    echo $cat2_array_label[$i].",";
    
    }
    ?>];
   
    
    var cat2_data_array1=[<?php 
      $length = count($cat2_array_data1);
    for ($i = 0; $i < $length; $i++) {
    echo $cat2_array_data1[$i].',';
    }
    ?>];
     
    
    var cat2_data_array7=[<?php 
      $length = count($cat2_array_data7);
    for ($i = 0; $i < $length; $i++) {
    echo $cat2_array_data7[$i].',';
    }
    ?>];
    
    
    var cat2_data_array30=[<?php 
      $length = count($cat2_array_data30);
    for ($i = 0; $i < $length; $i++) {
    echo $cat2_array_data30[$i].',';
    }
    ?>];
    var cat2_data_array180=[<?php 
      $length = count($cat2_array_data180);
    for ($i = 0; $i < $length; $i++) {
    echo $cat2_array_data180[$i].',';
    }
    ?>];

    
var cat2_xValues1 = cat2_label_array;
var cat2_yValues1 = cat2_data_array1;
var cat2_xValues7 = cat2_label_array;
var cat2_yValues7 = cat2_data_array7;
var cat2_xValues30 = cat2_label_array;
var cat2_yValues30 = cat2_data_array30;
var cat2_xValues180 = cat2_label_array;
var cat2_yValues180 = cat2_data_array180;


// for category level three

var cat3_label_array=[<?php 
      $length = count($cat3_array_label);
    for ($i = 0; $i < $length; $i++) {
    echo $cat3_array_label[$i].",";
    
    }
    ?>];
   
    
    var cat3_data_array1=[<?php 
      $length = count($cat3_array_data1);
    for ($i = 0; $i < $length; $i++) {
    echo $cat3_array_data1[$i].',';
    }
    ?>];
     
    
    var cat3_data_array7=[<?php 
      $length = count($cat3_array_data7);
    for ($i = 0; $i < $length; $i++) {
    echo $cat3_array_data7[$i].',';
    }
    ?>];
    
    
    var cat3_data_array30=[<?php 
      $length = count($cat3_array_data30);
    for ($i = 0; $i < $length; $i++) {
    echo $cat3_array_data30[$i].',';
    }
    ?>];
    var cat3_data_array180=[<?php 
      $length = count($cat3_array_data180);
    for ($i = 0; $i < $length; $i++) {
    echo $cat3_array_data180[$i].',';
    }
    ?>];

    
var cat3_xValues1 = cat3_label_array;
var cat3_yValues1 = cat3_data_array1;
var cat3_xValues7 = cat3_label_array;
var cat3_yValues7 = cat3_data_array7;
var cat3_xValues30 = cat3_label_array;
var cat3_yValues30 = cat3_data_array30;
var cat3_xValues180 = cat3_label_array;
var cat3_yValues180 = cat3_data_array180;



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
                  Top Level One Category Clicks 
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
                <canvas class="chart" id="line-chart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart7" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart30" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart180" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                  Top Level Two Category Clicks 
                </h3>
                <div class="card-tools">
                  
                  
                  <button type="button" class="btn bg-dark active category_clicks2" id="1">
                    Monthly
                  </button>
                  <button type="button" class="btn bg-dark category_clicks2" id="7">
                    6 Months
                  </button>
                  <button type="button" class="btn bg-dark  category_clicks2 " id="30" >
                    1 Year
                  </button>
                  <button type="button" class="btn bg-dark category_clicks2 " id="180">
                    5 Years
                  </button>
                  <!-- <button type="button" class="btn bg-info category_clicks " id="all">
                    All Time
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart2" id="cat2_line-chart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart2" id="cat2_line-chart7" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart2" id="cat2_line-chart30" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart2" id="cat2_line-chart180" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                  Top Level Three Category Clicks 
                </h3>
                <div class="card-tools">
                  
                  
                  <button type="button" class="btn bg-dark active category_clicks3" id="1">
                    Monthly
                  </button>
                  <button type="button" class="btn bg-dark category_clicks3" id="7">
                    6 Months
                  </button>
                  <button type="button" class="btn bg-dark  category_clicks3 " id="30" >
                    1 Year
                  </button>
                  <button type="button" class="btn bg-dark category_clicks3 " id="180">
                    5 Years
                  </button>
                  <!-- <button type="button" class="btn bg-info category_clicks " id="all">
                    All Time
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart3" id="cat3_line-chart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart3" id="cat3_line-chart7" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart3" id="cat3_line-chart30" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart3" id="cat3_line-chart180" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
          fontColor: '#efefef'
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
          fontColor: '#efefef'
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
          fontColor: '#efefef'
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
          fontColor: '#efefef'
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

  

// charts for category level 2


//  24 hours
var salesGraphChartCanvas = $('#cat2_line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: cat2_xValues1,
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
        data: cat2_yValues1
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas7 = $('#cat2_line-chart7').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData7 = {
    labels: cat2_xValues7,
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
        data: cat2_yValues7
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas30 = $('#cat2_line-chart30').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData30 = {
    labels: cat2_xValues30,
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
        data: cat2_yValues30
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas180 = $('#cat2_line-chart180').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData180 = {
    labels: cat2_xValues180,
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
        data: cat2_yValues180
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
          fontColor: '#efefef'
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



  // charts for category level 3


//  24 hours
var salesGraphChartCanvas = $('#cat3_line-chart1').get(0).getContext('2d')
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas7 = $('#cat3_line-chart7').get(0).getContext('2d')
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas30 = $('#cat3_line-chart30').get(0).getContext('2d')
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas180 = $('#cat3_line-chart180').get(0).getContext('2d')
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
          fontColor: '#efefef'
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

  


  
// charts for category level 2


//  24 hours
var salesGraphChartCanvas = $('#cat2_line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: cat2_xValues1,
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
        data: cat2_yValues1
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas7 = $('#cat2_line-chart7').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData7 = {
    labels: cat2_xValues7,
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
        data: cat2_yValues7
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas30 = $('#cat2_line-chart30').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData30 = {
    labels: cat2_xValues30,
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
        data: cat2_yValues30
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas180 = $('#cat2_line-chart180').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData180 = {
    labels: cat2_xValues180,
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
        data: cat2_yValues180
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
          fontColor: '#efefef'
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



  // charts for category level 3


//  24 hours
var salesGraphChartCanvas = $('#cat3_line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: cat3_xValues1,
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
        data: cat3_yValues1
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas7 = $('#cat3_line-chart7').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData7 = {
    labels: cat3_xValues7,
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
        data: cat3_yValues7
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas30 = $('#cat3_line-chart30').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData30 = {
    labels: cat3_xValues30,
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
        data: cat3_yValues30
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
          fontColor: '#efefef'
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
  var salesGraphChartCanvas180 = $('#cat3_line-chart180').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData180 = {
    labels: cat3_xValues180,
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
        data: cat3_yValues180
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
          fontColor: '#efefef'
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

$(".chart2").hide();
$("#cat2_line-chart1").show();
$(".category_clicks2").click(function(){
  alert($(this).attr('id'));

  $(".category_clicks2").removeClass("active");
   var id=$(this).attr('id');
   $(this).addClass("active");
   $(".chart2").hide();
   $("#cat2_line-chart"+id).show();
});


$(".chart3").hide();
$("#cat3_line-chart1").show();
$(".category_clicks3").click(function(){

  $(".category_clicks3").removeClass("active");
   var id=$(this).attr('id');
   $(this).addClass("active");
   $(".chart3").hide();
   $("#cat3_line-chart"+id).show();
});
          
  });

</script>