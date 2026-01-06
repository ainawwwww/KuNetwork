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

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    $click_query1="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 6 MONTH) group by date_format(placement_date,'%b') ORDER BY placement_date ASC;";
    $click_query7="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 YEAR) group by date_format(placement_date,'%b') ORDER BY placement_date ASC;";
    $click_query30="SELECT sum(total_amount) as total,DATE(placement_date) as d_date,created_at FROM `orders` WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 30 DAY) group By DATE(placement_date) ORDER BY placement_date DESC;";
    $click_query365="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE payment_status='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 12 MONTH) group by date_format(placement_date,'%b') ORDER BY placement_date ASC";
}
else
{
    $click_query1="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 6 MONTH) AND sales_personid=".$_SESSION['A_id']." group by date_format(placement_date,'%b') ORDER BY placement_date ASC;";
    $click_query7="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 YEAR) AND sales_personid=".$_SESSION['A_id']." group by date_format(placement_date,'%b') ORDER BY placement_date ASC;";
    $click_query30="SELECT sum(total_amount) as total,DATE(placement_date) as d_date,created_at FROM `orders` WHERE `payment_status`='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 30 DAY) AND sales_personid=".$_SESSION['A_id']." group By DATE(placement_date) ORDER BY placement_date DESC;";
    $click_query365="select date_format(placement_date,'%b') as label,sum(total_amount) as total from orders WHERE payment_status='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 12 MONTH) AND sales_personid=".$_SESSION['A_id']." group by date_format(placement_date,'%b') ORDER BY placement_date ASC";
}



//for 6 months

    $result_click1=mysqli_query($conn,$click_query1);
    $i=0;
    
while ($row_click1 = mysqli_fetch_assoc($result_click1)) {
   
    $array_label1[$i]=$row_click1['label'];
    $array_data1[$i]=$row_click1['total'];
    $i++;
}

$start_date = date("j M, Y", strtotime("-6 months")); // 6 months ago
$end_date = date("j M, Y"); // Today's date

$graph_timeperiod2 = "Sales: $start_date - $end_date";


// for 5 years

    $result_click7=mysqli_query($conn,$click_query7);
    $i=0;
    
while ($row_click7 = mysqli_fetch_assoc($result_click7)) {
   
    $array_label7[$i]=$row_click7['label'];
    $array_data7[$i]=$row_click7['total'];
    $i++;
}

$start_date = date("j M, Y", strtotime("-5 year")); // 6 months ago
$end_date = date("j M, Y"); // Today's date

$graph_timeperiod4 = "Sales: $start_date - $end_date";
// for 30 days

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

$start_date = date("j M, Y", strtotime("-1 months")); // 6 months ago
$end_date = date("j M, Y"); // Today's date

$graph_timeperiod1 = "Sales: $start_date - $end_date";

// 1 year

    $result_click365=mysqli_query($conn,$click_query365);
    $i=0;
while ($row_click365 = mysqli_fetch_assoc($result_click365)) {
   
 $array_data365[$i]=$row_click365['total'];
 
    $array_label365[$i]=$row_click365['label'];
    $i++;
}

$start_date = date("j M, Y", strtotime("-1 year")); // 6 months ago
$end_date = date("j M, Y"); // Today's date

$graph_timeperiod3 = "Sales: $start_date - $end_date";




            




?>