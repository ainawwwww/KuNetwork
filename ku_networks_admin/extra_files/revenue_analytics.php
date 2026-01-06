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

$click_query1="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10";
    $result_click1=mysqli_query($conn,$click_query1);
    $i=0;
while ($row_click1 = mysqli_fetch_assoc($result_click1)) {
    $array_data1[$i]=$row_click1['group_product'];
    $array_label1[$i]=$row_click1['product_name'];
    $i++;
}


$click_query7="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10";
    $result_click7=mysqli_query($conn,$click_query7);
    $i=0;
while ($row_click7 = mysqli_fetch_assoc($result_click7)) {
    $array_data7[$i]=$row_click7['group_product'];
    $array_label7[$i]=$row_click7['product_name'];
    $i++;
}

$click_query30="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10";
    $result_click30=mysqli_query($conn,$click_query30);
    $i=0;
while ($row_click30 = mysqli_fetch_assoc($result_click30)) {
    $array_data30[$i]=$row_click30['group_product'];
    $array_label30[$i]=$row_click30['product_name'];
    $i++;
}

$click_query365="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 365 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10";
    $result_click365=mysqli_query($conn,$click_query365);
    $i=0;
while ($row_click365 = mysqli_fetch_assoc($result_click365)) {
    $array_data365[$i]=$row_click365['group_product'];
    $array_label365[$i]=$row_click365['product_name'];
    $i++;
}

$click_query="SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10";
    $result_click=mysqli_query($conn,$click_query);
    $i=0;
while ($row_click = mysqli_fetch_assoc($result_click)) {
    $array_data[$i]=$row_click['group_product'];
    $array_label[$i]=$row_click['product_name'];
    $i++;
}
   


            




?>