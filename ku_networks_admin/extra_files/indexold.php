<?php 
include 'db.php';
include 'check_login.php';
include 'category_analytics_data.php';


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

<?php

//total products
$total_products_query="SELECT COUNT(*) as total_products FROM `products` ";
$result_total_products=mysqli_query($conn,$total_products_query);
$row_total_products = mysqli_fetch_assoc($result_total_products);
$total_products=$row_total_products['total_products'];


//total orders
$total_orders_query="SELECT COUNT(*) as total_orders FROM `custom_order`";
$result_total_orders=mysqli_query($conn,$total_orders_query);
$row_total_orders = mysqli_fetch_assoc($result_total_orders);
$total_orders=$row_total_orders['total_orders'];


//total inquiries
$total_inquiries_query="SELECT COUNT(*) as total_inquiries FROM `inquiries` WHERE read_status=0";
$result_total_inquiries=mysqli_query($conn,$total_inquiries_query);
$row_total_inquiries = mysqli_fetch_assoc($result_total_inquiries);
$total_inquiries=$row_total_inquiries['total_inquiries'];

//top category

$topcat_query="SELECT COUNT(*) as total_click,category FROM `category_clicks` GROUP BY category ORDER by total_click DESC LIMIT 1";
$result_topcat=mysqli_query($conn,$topcat_query);
$row_topcat = mysqli_fetch_assoc($result_topcat);
$cat_id=$row_topcat['category'];
$topcat_query="SELECT * FROM `categories` WHERE `cat_id`=".$cat_id;
$result_topcat=mysqli_query($conn,$topcat_query);
$row_topcat = mysqli_fetch_assoc($result_topcat);
$top_cat_name=$row_topcat['cat_name'];

//sales this month
$sales_query="SELECT SUM(`price`) as sales FROM custom_order WHERE `status`='Delivered' AND MONTH(delivery_date) = MONTH(CURRENT_DATE()) AND YEAR(delivery_date) = YEAR(CURRENT_DATE());";
$result_sales=mysqli_query($conn,$sales_query);
$row_sales = mysqli_fetch_assoc($result_sales);
$sales_this_month=$row_sales['sales'];

//order in process
$process_query="SELECT COUNT(*) as total_orderprocess FROM `custom_order` WHERE status='In Process';";
$result_process=mysqli_query($conn,$process_query);
$row_process = mysqli_fetch_assoc($result_process);
$orders_in_process=$row_process['total_orderprocess'];

//order delivered
$deliver_query="SELECT COUNT(*) as total_orderprocess FROM `custom_order` WHERE status='Delivered';";
$result_deliver=mysqli_query($conn,$deliver_query);
$row_deliver = mysqli_fetch_assoc($result_deliver);
$orders_deliver=$row_deliver['total_orderprocess'];

//Top Product
$toppro_query="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p GROUP BY products.product_name ORDER BY group_product DESC LIMIT 1;";
$result_toppro=mysqli_query($conn,$toppro_query);
$row_toppro = mysqli_fetch_assoc($result_toppro);
$toppro=$row_toppro['product_name'];


?>
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=$total_orders?></h3>

                <p>Total Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="order_history.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$total_inquiries?></h3>

                <p>Inquiries</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="inquiries.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$total_products?></h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="all_product.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h5><b>Top Category</b></h5>
                

                <p><?=$top_cat_name?></p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="all_cat.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php if (isset($_SESSION['role']) && $_SESSION['role']==1) echo $sales_this_month." AED"; else echo 'N/A  AED'; ?></h3>

                <p>SALES THIS MONTH</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="index3.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$orders_in_process?></h3>

                <p>Orders In Process</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="order_history.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$orders_deliver?></h3>

                <p>Orders Delivered</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="order_history.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h5><b>Top Product</b></h5>

                <p><?=$toppro?></p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>  
              <a href="index2.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        
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
                  Category Clicks 
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
                <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Categories</h3>
              </div>
             
              <!-- /.card-header -->
              <div class="card-body">
                <!-- <button class="btn btn-primary" onclick="stepper.next()">Today</button>
                <button class="btn btn-primary" onclick="stepper.next()">7 Days</button>
                <button class="btn btn-primary" onclick="stepper.next()">30 Days</button> -->
                <table id="example1" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                  <th>Category</th>
                  <th>Total Clicks</th>
                  
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    
$cat_query="SELECT * FROM `categories`";

$result_cat=mysqli_query($conn,$cat_query);

while ($row_dvst = mysqli_fetch_assoc($result_cat)) {
    $click_query="SELECT COUNT(*) as total_clicks FROM `category_clicks` WHERE `category`='".$row_dvst['cat_id']."'";
    $result_click=mysqli_query($conn,$click_query);
    $row_click = mysqli_fetch_assoc($result_click);
    ?>
    <tr>
        <td><?php echo $row_dvst['cat_name']; ?></td>
        <td><?php echo $row_click['total_clicks']; ?></td>
    </tr>
    <?php
}
     
       ?>


                  </tbody>
                  <tfoot>
                  <tr>
                      
                  <th>Category</th>
                  <th>Total Clicks</th>
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
</body>
</html>
<script type="text/javascript">
     var label_array=[<?php 
              $length = count($array_label);
            for ($i = 0; $i < $length; $i++) {
            echo $array_label[$i].',';
            
            }
            ?>];
             var data_array1=[<?php 
              $length = count($array_data1);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data1[$i].',';
            }
            ?>];
            var data_array7=[<?php 
              $length = count($array_data7);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data7[$i].',';
            }
            ?>];
            var data_array30=[<?php 
              $length = count($array_data30);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data30[$i].',';
            }
            ?>];
            var data_array365=[<?php 
              $length = count($array_data365);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data365[$i].',';
            }
            ?>];
            var data_array=[<?php 
              $length = count($array_data);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data[$i].',';
            }
            ?>];

     
     
     
var xValues = label_array;
var yValues1 = data_array1;
var yValues7 = data_array7;
var yValues30 = data_array30;
var yValues365 = data_array365;
var yValues = data_array;
    $(function () {
    //  24 hours
  var salesGraphChartCanvas = $('#line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
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