<?php
include 'db.php';
include 'check_login.php';
$array_label=array();
$array_data1=array();
$array_data7=array();
$array_data30=array();
$array_data365=array();
$array_data=array();


$click_query="SELECT COUNT(*) as total_clicks ,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p  GROUP BY product_clicks.product_id ORDER BY total_clicks DESC limit 20";
    $result_click=mysqli_query($conn,$click_query);
    $i=0;



    
while ($row_dvst = mysqli_fetch_assoc($result_click)) {

   
        $array_label[$i]=$row_dvst['product_name'];


    $click_query1="SELECT COALESCE(COUNT(*), 0) as total_clicks  FROM `product_clicks`  WHERE `product_id` = '".$row_dvst['product_id']."' AND `created_at` > DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $result_click1=mysqli_query($conn,$click_query1);
    $row_click1 = mysqli_fetch_assoc($result_click1);
    $array_data1[$i]=$row_click1['total_clicks'];
    // echo $click_query1."<br>";

    $click_query7="SELECT COALESCE(COUNT(*), 0) as total_clicks  FROM `product_clicks`  WHERE `product_id` = '".$row_dvst['product_id']."' AND `created_at` > DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        $result_click7=mysqli_query($conn,$click_query7);
        $row_click7 = mysqli_fetch_assoc($result_click7);
        $array_data7[$i]=$row_click7['total_clicks'];

        $click_query30="SELECT COALESCE(COUNT(*), 0) as total_clicks  FROM `product_clicks`  WHERE `product_id` = '".$row_dvst['product_id']."' AND `created_at` > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        $result_click30=mysqli_query($conn,$click_query30);
        $row_click30 = mysqli_fetch_assoc($result_click30);
        $array_data30[$i]=$row_click30['total_clicks'];

        $click_query365="SELECT COALESCE(COUNT(*), 0) as total_clicks  FROM `product_clicks`  WHERE `product_id` = '".$row_dvst['product_id']."' AND `created_at` > DATE_SUB(NOW(), INTERVAL 5 YEAR)";
        $result_click365=mysqli_query($conn,$click_query365);
        $row_click365 = mysqli_fetch_assoc($result_click365);
        $array_data365[$i]=$row_click365['total_clicks'];



    $i++;
}
//   // echo $array_data1;
//    echo "<pre>"; 
// print_r($array_label);
// echo "</pre>";


            




?>