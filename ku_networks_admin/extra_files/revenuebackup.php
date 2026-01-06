<?php
include 'db.php';
include 'check_login.php';
$array_label=array();
$array_label1=array();
$array_label7=array();
$array_label30=array();
$array_label365=array();
$array_data1=array();
$array_data7=array();
$array_data30=array();
$array_data365=array();
$array_data=array();
$array_data_temp1=array();
$array_label_temp1=array();
$array_data_temp7=array();
$array_label_temp7=array();
$array_data_temp30=array();
$array_label_temp30=array();

// SELECT sum(total_payment),delivery_date,created_at FROM `custom_order` WHERE `status`='Delivered' group By DATE(delivery_date);






$click_query1="SELECT sum(total_payment) as total,DATE(delivery_date) as d_date,created_at FROM `custom_order` WHERE `status`='Delivered' AND delivery_date > DATE_SUB(NOW(), INTERVAL 3 DAY) group By DATE(delivery_date) ORDER BY delivery_date DESC";
    $result_click1=mysqli_query($conn,$click_query1);
    $i=mysqli_num_rows($result_click1)-1;
    
while ($row_click1 = mysqli_fetch_assoc($result_click1)) {
   
    $array_label_temp1[$i]=$row_click1['d_date'];
    $array_data_temp1[$i]=$row_click1['total'];
    $i--;
}
$i=0;
$k=0;
for($j=2;$j>=0;$j--){
    if (in_array(date('Y-m-d', strtotime('-'.$j.' days')), $array_label_temp1))
  {
 $array_data1[$i]=$array_data_temp1[$k];
 $k++;
  }
else
  {
   $array_data1[$i]=0;
  }

    $array_label1[$i]=date('d-M ', strtotime(date('Y-m-d', strtotime('-'.$j.' days'))));
    $i++;
}

// for 7 days
$click_query7="SELECT sum(total_payment) as total,DATE(delivery_date) as d_date,created_at FROM `custom_order` WHERE `status`='Delivered' AND delivery_date > DATE_SUB(NOW(), INTERVAL 7 DAY) group By DATE(delivery_date) ORDER BY delivery_date DESC";
    $result_click7=mysqli_query($conn,$click_query7);
    $i=mysqli_num_rows($result_click7)-1;
    
while ($row_click7 = mysqli_fetch_assoc($result_click7)) {
   
    $array_label_temp7[$i]=$row_click7['d_date'];
    $array_data_temp7[$i]=$row_click7['total'];
    $i--;
}
$i=0;
$k=0;
for($j=6;$j>=0;$j--){
    if (in_array(date('Y-m-d', strtotime('-'.$j.' days')), $array_label_temp7))
  {
 $array_data7[$i]=$array_data_temp7[$k];
 $k++;
  }
else
  {
   $array_data7[$i]=0;
  }

    $array_label7[$i]=date('d-M ', strtotime(date('Y-m-d', strtotime('-'.$j.' days'))));
    $i++;
}


// for 30 days
$click_query30="SELECT sum(total_payment) as total,DATE(delivery_date) as d_date,created_at FROM `custom_order` WHERE `status`='Delivered' AND delivery_date > DATE_SUB(NOW(), INTERVAL 30 DAY) group By DATE(delivery_date) ORDER BY delivery_date DESC";
    $result_click30=mysqli_query($conn,$click_query30);
    $i=mysqli_num_rows($result_click30)-1;
    
while ($row_click30 = mysqli_fetch_assoc($result_click30)) {
   
    $array_label_temp30[$i]=$row_click30['d_date'];
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

// 1 year
$click_query365="select date_format(delivery_date,'%b') as label,sum(total_payment) as total from custom_order WHERE `status`='Delivered' AND delivery_date > DATE_SUB(NOW(), INTERVAL 11 MONTH) group by date_format(delivery_date,'%b') ORDER BY delivery_date ASC";
    $result_click365=mysqli_query($conn,$click_query365);
    $i=0;
while ($row_click365 = mysqli_fetch_assoc($result_click365)) {
   
 $array_data365[$i]=$row_click365['total'];
 
    $array_label365[$i]=$row_click365['label'];
    $i++;
}




            




?>