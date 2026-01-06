<?php 
include 'db.php';
include 'check_login.php';
include 'fetch_a.php';


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
            <a href="all_pro_clicks.php"><button type="button" class="btn btn-block btn-secondary ">All Products Analytics</button></a>
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
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Products Clicks 
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn bg-info active category_clicks " id="1" >
                    24 Hours
                  </button>
                  <button type="button" class="btn bg-info category_clicks " id="7">
                    7 Days
                  </button>
                  <button type="button" class="btn bg-info category_clicks" id="30">
                    30 Days
                  </button>
                  <button type="button" class="btn bg-info category_clicks" id="365">
                    1 Year
                  </button>
                  <button type="button" class="btn bg-info category_clicks " id="all">
                    All Time
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart7" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart30" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chart365" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <canvas class="chart" id="line-chartall" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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

     var label_array=[<?php 
              $length = count($array_label);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label[$i]."',";
            
            }
            ?>];
            var data_array=[<?php 
              $length = count($array_data);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data[$i].',';
            }
            ?>];
            var label_array1=[<?php 
              $length = count($array_label1);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label1[$i]."',";
            
            }
            ?>];
            var data_array1=[<?php 
              $length = count($array_data1);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data1[$i].',';
            }
            ?>];
            var label_array7=[<?php 
              $length = count($array_label7);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label7[$i]."',";
            
            }
            ?>];
            var data_array7=[<?php 
              $length = count($array_data7);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data7[$i].',';
            }
            ?>];
            var label_array30=[<?php 
              $length = count($array_label30);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label30[$i]."',";
            
            }
            ?>];
            var data_array30=[<?php 
              $length = count($array_data30);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data30[$i].',';
            }
            ?>];
            var label_array365=[<?php 
              $length = count($array_label365);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label365[$i]."',";
            
            }
            ?>];
            var data_array365=[<?php 
              $length = count($array_data365);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data365[$i].',';
            }
            ?>];
var xValues = label_array;
var yValues = data_array;
var xValues1 = label_array1;
var yValues1 = data_array1;
var xValues7 = label_array7;
var yValues7 = data_array7;
var xValues30 = label_array30;
var yValues30 = data_array30;
var xValues365 = label_array365;
var yValues365 = data_array365;
                $(function () {
//  24 hours
  var salesGraphChartCanvas = $('#line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: xValues1,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
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
        fill: false,
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
        fill: false,
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

//365 days
  var salesGraphChartCanvas365 = $('#line-chart365').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData365 = {
    labels: xValues365,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues365
      }
    ]
  }

  var salesGraphChartOptions365 = {
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
  var salesGraphChart = new Chart(salesGraphChartCanvas365, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData365,
    options: salesGraphChartOptions365
  })


//all times
  var salesGraphChartCanvasall = $('#line-chartall').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartDataall = {
    labels: xValues,
    datasets: [
      {
        label: 'Clicks',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: yValues
      }
    ]
  }

  var salesGraphChartOptionsall = {
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
  var salesGraphChart = new Chart(salesGraphChartCanvasall, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartDataall,
    options: salesGraphChartOptionsall
  })
$(".chart").hide();
$("#line-chart1").show();
$(".category_clicks").click(function(){
  $(".category_clicks").removeClass("active");
   var id=$(this).attr('id');
   $(this).addClass("active");
   $(".chart").hide();
   $("#line-chart"+id).show();

});

})
    
</script>