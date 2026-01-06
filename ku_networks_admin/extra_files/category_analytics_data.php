<?php
include 'db.php';
include 'check_login.php';

$cat_query="SELECT * FROM `categories_level1`";
$result_cat=mysqli_query($conn,$cat_query);
$i=0;

$array_label=array();
$array_data1=array();
$array_data7=array();
$array_data30=array();
$array_data180=array();

// SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10;
while ($row_dvst = mysqli_fetch_assoc($result_cat)) {
    $click_query1="SELECT COUNT(*) as total_clicks FROM `category_clicks1` WHERE `category`='".$row_dvst['cat_id']."' AND click_time > DATE_SUB(NOW(), INTERVAL 30 DAY);";
    $result_click1=mysqli_query($conn,$click_query1);
    $row_click1 = mysqli_fetch_assoc($result_click1);
    $array_data1[$i]=$row_click1['total_clicks'];
    
    $click_query7="SELECT COUNT(*) as total_clicks FROM `category_clicks1` WHERE `category`='".$row_dvst['cat_id']."' AND click_time >= DATE(NOW()) - INTERVAL 6 MONTH";
    $result_click7=mysqli_query($conn,$click_query7);
    $row_click7 = mysqli_fetch_assoc($result_click7);
    $array_data7[$i]=$row_click7['total_clicks'];
    
    $click_query30="SELECT COUNT(*) as total_clicks FROM `category_clicks1` WHERE `category`='".$row_dvst['cat_id']."' AND click_time >= DATE(NOW()) - INTERVAL 1 YEAR";
    $result_click30=mysqli_query($conn,$click_query30);
    $row_click30 = mysqli_fetch_assoc($result_click30);
    $array_data30[$i]=$row_click30['total_clicks'];

    $click_query365="SELECT COUNT(*) as total_clicks FROM `category_clicks1` WHERE `category`='".$row_dvst['cat_id']."' AND click_time >= DATE(NOW()) - INTERVAL 5 YEAR";
    $result_click365=mysqli_query($conn,$click_query365);
    $row_click365 = mysqli_fetch_assoc($result_click365);
    $array_data180[$i]=$row_click365['total_clicks'];

    $click_query="SELECT COUNT(*) as total_clicks FROM `category_clicks1` WHERE `category`='".$row_dvst['cat_id']."'";
    $result_click=mysqli_query($conn,$click_query);
    $row_click = mysqli_fetch_assoc($result_click);
    $array_data[$i]=$row_click['total_clicks'];
    
    
    $array_label[$i]="'".$row_dvst['cat_name']."'";
    $i++;
    
}

//Level two category

$cat_query="SELECT COUNT(*) as total_clicks,category_clicks2.category,categories_level2.name FROM `category_clicks2` 
join categories_level2 on category_clicks2.category=categories_level2.id
GROUP BY category_clicks2.category ORDER by total_clicks DESC LIMIT 20";
$result_cat=mysqli_query($conn,$cat_query);
$i=0;

$cat2_array_label=array();
$cat2_array_data1=array();
$cat2_array_data7=array();
$cat2_array_data30=array();
$cat2_array_data180=array();

// SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10;
while ($row_dvst = mysqli_fetch_assoc($result_cat)) {
    $click_query1="SELECT COUNT(*) as total_clicks FROM `category_clicks2` WHERE `category`='".$row_dvst['category']."' AND click_time > DATE_SUB(NOW(), INTERVAL 30 DAY);";
    $result_click1=mysqli_query($conn,$click_query1);
    $row_click1 = mysqli_fetch_assoc($result_click1);
    $cat2_array_data1[$i]=$row_click1['total_clicks'];
    
    $click_query7="SELECT COUNT(*) as total_clicks FROM `category_clicks2` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 6 MONTH";
    $result_click7=mysqli_query($conn,$click_query7);
    $row_click7 = mysqli_fetch_assoc($result_click7);
    $cat2_array_data7[$i]=$row_click7['total_clicks'];
    
    $click_query30="SELECT COUNT(*) as total_clicks FROM `category_clicks2` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 1 YEAR";
    $result_click30=mysqli_query($conn,$click_query30);
    $row_click30 = mysqli_fetch_assoc($result_click30);
    $cat2_array_data30[$i]=$row_click30['total_clicks'];

    $click_query365="SELECT COUNT(*) as total_clicks FROM `category_clicks2` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 5 YEAR";
    $result_click365=mysqli_query($conn,$click_query365);
    $row_click365 = mysqli_fetch_assoc($result_click365);
    $cat2_array_data180[$i]=$row_click365['total_clicks'];

    
    
    
    $cat2_array_label[$i]="'".$row_dvst['name']."'";
    $i++;
    
}


//Level two category


//Level three category

$cat_query="SELECT COUNT(*) as total_clicks,category_clicks3.category,categories_level3.name FROM `category_clicks3` 
join categories_level3 on category_clicks3.category=categories_level3.id
GROUP BY category_clicks3.category ORDER by total_clicks DESC LIMIT 20";
$result_cat=mysqli_query($conn,$cat_query);
$i=0;

$cat3_array_label=array();
$cat3_array_data1=array();
$cat3_array_data7=array();
$cat3_array_data30=array();
$cat3_array_data180=array();

// SELECT COUNT(products.product_name) as group_product,product_clicks.product_id,product_clicks.created_at,products.id_p,products.product_name FROM `product_clicks` JOIN products ON product_clicks.product_id=products.id_p WHERE product_clicks.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY products.product_name ORDER BY group_product DESC LIMIT 10;
while ($row_dvst = mysqli_fetch_assoc($result_cat)) {
    $click_query1="SELECT COUNT(*) as total_clicks FROM `category_clicks3` WHERE `category`='".$row_dvst['category']."' AND click_time > DATE_SUB(NOW(), INTERVAL 30 DAY);";
    $result_click1=mysqli_query($conn,$click_query1);
    $row_click1 = mysqli_fetch_assoc($result_click1);
    $cat3_array_data1[$i]=$row_click1['total_clicks'];
    
    $click_query7="SELECT COUNT(*) as total_clicks FROM `category_clicks3` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 6 MONTH";
    $result_click7=mysqli_query($conn,$click_query7);
    $row_click7 = mysqli_fetch_assoc($result_click7);
    $cat3_array_data7[$i]=$row_click7['total_clicks'];
    
    $click_query30="SELECT COUNT(*) as total_clicks FROM `category_clicks3` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 1 YEAR";
    $result_click30=mysqli_query($conn,$click_query30);
    $row_click30 = mysqli_fetch_assoc($result_click30);
    $cat3_array_data30[$i]=$row_click30['total_clicks'];

    $click_query365="SELECT COUNT(*) as total_clicks FROM `category_clicks3` WHERE `category`='".$row_dvst['category']."' AND click_time >= DATE(NOW()) - INTERVAL 5 YEAR";
    $result_click365=mysqli_query($conn,$click_query365);
    $row_click365 = mysqli_fetch_assoc($result_click365);
    $cat3_array_data180[$i]=$row_click365['total_clicks'];

    
    
    
    $cat3_array_label[$i]="'".$row_dvst['name']."'";
    $i++;
    
}



// echo "<pre>"; 
// print_r($cat2_array_label);
// echo "</pre>";


?>