<?php 
include 'db.php';
include 'check_login.php';

if(isset($_POST['submit']))
{
  if($_POST['cat2']==0 && $_POST['cat3']==0)
  {
    $table="category_clicks1";
    $cat_id=$_POST['cat1'];
  }
  elseif($_POST['cat3']==0)
  {
    $table="category_clicks2";
    $cat_id=$_POST['cat2'];
  }
  else{
    $table="category_clicks3";
    $cat_id=$_POST['cat3'];
  }

  //now getting graph data

  
$array_label=array();
$array_label1=array();
$array_label7=array();
$array_label30=array();
$array_label180=array();
$array_data1=array();
$array_data7=array();
$array_data30=array();
$array_data180=array();
$array_data=array();
$array_data_temp1=array();
$array_label_temp1=array();
$array_data_temp7=array();
$array_label_temp7=array();
$array_data_temp30=array();
$array_label_temp30=array();






//for 1 year

$click_query1="SELECT 
    COUNT(id) AS total, 
    category, 
    DATE_FORMAT(click_time, '%b-%Y') AS row_month 
FROM $table 
WHERE category = $cat_id
AND click_time > DATE_SUB(NOW(), INTERVAL 12 MONTH) 
GROUP BY YEAR(click_time), MONTH(click_time) 
ORDER BY click_time asc;";
    $result_click1=mysqli_query($conn,$click_query1);
    $i=0;
    
while ($row_click1 = mysqli_fetch_assoc($result_click1)) {
   
    $array_label1[$i]=$row_click1['row_month'];
    $array_data1[$i]=$row_click1['total'];
    $i++;
}

// for 5 years
$click_query7="SELECT 
    COUNT(id) AS total, 
    category, 
    DATE_FORMAT(click_time, '%Y') AS row_year 
FROM $table 
WHERE category = $cat_id
AND click_time > DATE_SUB(NOW(), INTERVAL 5 YEAR) 
GROUP BY YEAR(click_time) 
ORDER BY click_time asc;
";
    $result_click7=mysqli_query($conn,$click_query7);
    $i=0;
    
while ($row_click7 = mysqli_fetch_assoc($result_click7)) {
   
    $array_label7[$i]=$row_click7['row_year'];
    $array_data7[$i]=$row_click7['total'];
    $i++;
}


// for 30 days

$click_query30="SELECT count(id) as total,category,click_time,DATE(click_time) as row_day FROM $table WHERE category=$cat_id AND click_time > DATE_SUB(NOW(), INTERVAL 1 MONTH) group By DATE(click_time) ORDER by row_day desc";
    $result_click30=mysqli_query($conn,$click_query30);
    $i=mysqli_num_rows($result_click30)-1;
    
while ($row_click30 = mysqli_fetch_assoc($result_click30)) {
   
    $array_label_temp30[$i]=$row_click30['row_day'];
    $array_data_temp30[$i]=$row_click30['total'];
    $i--;
}
$i=0;
$k=0;
for($j=29;$j>=0;$j--){
    if (in_array(date('Y-m-d', strtotime('-'.$j.' days')), $array_label_temp30))
  {
 $array_data30[$i]=$array_data_temp30[$k];
 $k++;
  }
else
  {
   $array_data30[$i]=0;
  }

    $array_label30[$i]=date('d-M ', strtotime(date('Y-m-d', strtotime('-'.$j.' days'))));
    $i++;
}

// 6 months
$click_query365="SELECT 
    COUNT(id) AS total, 
    category, 
    DATE_FORMAT(click_time, '%b-%Y') AS row_month 
FROM $table 
WHERE category = $cat_id 
AND click_time > DATE_SUB(NOW(), INTERVAL 6 MONTH) 
GROUP BY YEAR(click_time), MONTH(click_time) 
ORDER BY click_time asc;";
    $result_click365=mysqli_query($conn,$click_query365);
    $i=0;
while ($row_click365 = mysqli_fetch_assoc($result_click365)) {
   
 $array_data180[$i]=$row_click365['total'];
 
    $array_label180[$i]=$row_click365['row_month'];
    $i++;
}

}



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
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <form method="post" id="category_form">

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
                  <select class="select2" id="category1" name="cat1"   data-placeholder="Select Level One Category" style="width: 100%;" required>
                  <option value="0">Select Level One Category</option>
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
          </div>

          <div class="col-sm-6">
            <div class="form-group">
                  <select class="select2" id="category2" name="cat2"   data-placeholder="Select Level Two Category" style="width: 100%;">
                  <option value="0">Select Level Two Category</option>
                    
                  </select>
                </div>
          </div>


          <div class="col-sm-6">
            <div class="form-group">
                  <select class="select2" id="category3" name="cat3"   data-placeholder="Select Level THree Category" style="width: 100%;">
                  <option value="0">Select Level Three Category</option>
                    
                  </select>
                </div>
          </div>


          <div class="col-sm-6">
            <div class="form-group">
                  <button class="btn btn-primary btn-sm" name="submit">submit</button>
                </div>
          </div>


          

        </div>
        </form>
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
                  Category Clicks 
                </h3>
                <div class="card-tools">
                  
                  
                  <button type="button" class="btn bg-info active category_clicks" id="30">
                    Monthly
                  </button>
                  <button type="button" class="btn bg-info category_clicks" id="180">
                    6 Months
                  </button>
                  <button type="button" class="btn bg-info  category_clicks " id="1" >
                    1 Year
                  </button>
                  <button type="button" class="btn bg-info category_clicks " id="7">
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
            var label_array180=[<?php 
              $length = count($array_label180);
            for ($i = 0; $i < $length; $i++) {
            echo "'".$array_label180[$i]."',";
            
            }
            ?>];
            var data_array180=[<?php 
              $length = count($array_data180);
            for ($i = 0; $i < $length; $i++) {
            echo $array_data180[$i].',';
            }
            ?>];
            
var xValues1 = label_array1;
var yValues1 = data_array1;
var xValues7 = label_array7;
var yValues7 = data_array7;
var xValues30 = label_array30;
var yValues30 = data_array30;
var xValues180 = label_array180;
var yValues180 = data_array180;
                $(function () {

   

//  24 hours
  var salesGraphChartCanvas = $('#line-chart1').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: xValues1,
    datasets: [
      {
        label: 'Revenue',
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
        label: 'Revenue',
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
        label: 'Revenue',
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
        label: 'Revenue',
        fill: true,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#007bff',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#007bff',
        pointBackgroundColor: '#007bff',
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
          drawBorder: true
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

  


$(".chart").hide();
$("#line-chart30").show();
$(".category_clicks").click(function(){
  $(".category_clicks").removeClass("active");
   var id=$(this).attr('id');
   $(this).addClass("active");
   $(".chart").hide();
   $("#line-chart"+id).show();

});

$("#category_form").submit(function(event){
        var selectedCategory = $("#category1").val(); // Get selected value
        if(selectedCategory == "0"){
            alert("Please select atleaset Level One Category before submitting.");
            event.preventDefault(); // Prevent form submission
        }
    });


$('#category_form').on('change', '#category1', function () {
  alert("hbhjads");
            var selectedValue = $(this).val();

            $.ajax({
                  type: "POST",
                  url: "action/fetch_cat2_from_cat1.php",
                  data: {selectedValue:selectedValue},
                  success: function(result) {
                    var result="<option value='0' disable>Select Level Two Category</option>"+result;
                    $('#category2').empty().append(result);
                  }
                });
              });

              $('#category_form').on('change', '#category2', function () {
            var selectedValue = $(this).val();

            $.ajax({
                  type: "POST",
                  url: "action/fetch_cat3_from_cat2.php",
                  data: {selectedValue:selectedValue},
                  success: function(result) {
                    var result="<option value='0' disable>Select Level Three Category</option>"+result;
                    $('#category3').empty().append(result);
                  }
                });
              });

        









})
    
</script>

<script>
  $(document).ready(function(){
          
  });

</script>