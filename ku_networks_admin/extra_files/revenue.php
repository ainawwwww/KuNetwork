<?php
include 'db.php';
include 'check_login.php';
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
FROM category_clicks1 
WHERE category = 5 
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
FROM category_clicks1 
WHERE category = 5 
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

$click_query30="SELECT count(id) as total,category,click_time,DATE(click_time) as row_day FROM `category_clicks1` WHERE category=5 AND click_time > DATE_SUB(NOW(), INTERVAL 1 MONTH) group By DATE(click_time) ORDER by row_day desc";
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
FROM category_clicks1 
WHERE category = 5 
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


?>