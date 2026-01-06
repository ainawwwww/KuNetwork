<?php

//Total Products
$query1="SELECT count(*) as total FROM `products`;";
    $result1=mysqli_query($conn,$query1);
     $row1=mysqli_fetch_assoc($result1);
    $total_products=$row1['total'];
//Total Products

//Total CAtegory 1
$query2="SELECT count(*) as total FROM `categories_level1`;";
    $result2=mysqli_query($conn,$query2);
     $row2=mysqli_fetch_assoc($result2);
    $total_cat1=$row2['total'];
//Total CAtegory 1

//Total CAtegory 2
$query3="SELECT count(*) as total FROM `categories_level2`;";
    $result3=mysqli_query($conn,$query3);
     $row3=mysqli_fetch_assoc($result3);
    $total_cat2=$row3['total'];
//Total CAtegory 2

//Total CAtegory 3
$query4="SELECT count(*) as total FROM `categories_level3`;";
    $result4=mysqli_query($conn,$query4);
     $row4=mysqli_fetch_assoc($result4);
    $total_cat3=$row4['total'];
//Total CAtegory 3

//Total Product Last 30 Days
$query5="SELECT product_clicks.product_id, COUNT(*) AS total_clicks,products.product_name FROM product_clicks join products on product_clicks.product_id=products.id_p WHERE product_clicks.created_at >= NOW() - INTERVAL 30 DAY GROUP BY product_clicks.product_id ORDER BY total_clicks DESC LIMIT 1;
";
    $result5=mysqli_query($conn,$query5);
     $row5=mysqli_fetch_assoc($result5);
    $top_product=$row5['product_name'];
//Total Product Last 30 Days

//Total Category 1 Last 30 Days
$query6="SELECT category_clicks1.category, COUNT(*) AS total_clicks,categories_level1.cat_name FROM category_clicks1 join categories_level1 on category_clicks1.category=categories_level1.cat_id WHERE category_clicks1.click_time >= NOW() - INTERVAL 30 DAY GROUP BY category_clicks1.category ORDER BY total_clicks DESC LIMIT 1
";
    $result6=mysqli_query($conn,$query6);
     $row6=mysqli_fetch_assoc($result6);
    $top_category=$row6['cat_name'];
//Total Category 1 Last 30 Days

//Total revenue Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query7="SELECT sum(total_amount) as total_amount FROM `orders` WHERE placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{
    $query7="SELECT sum(total_amount) as total_amount FROM `orders` WHERE placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}


    $result7=mysqli_query($conn,$query7);
     $row7=mysqli_fetch_assoc($result7);
     $total_revenue = isset($row7['total_amount']) ? $row7['total_amount'] : 0;
//Total revenue Last 30 Days

//Total deleivered revenue Last 30 Days

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query8="SELECT sum(total_amount) as total_delivered_amount FROM `orders` WHERE payment_status='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{
    $query8="SELECT sum(total_amount) as total_delivered_amount FROM `orders` WHERE payment_status='Paid' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}

    $result8=mysqli_query($conn,$query8);
     $row8=mysqli_fetch_assoc($result8);
    $total_paid_revenue = isset($row8['total_delivered_amount']) ? $row8['total_delivered_amount'] : 0;
    $revenue_percentage = isset($row8['total_delivered_amount']) ? (int)(($total_paid_revenue / $total_revenue) * 100) : 0;
   
    
//Total delivered revenue Last 30 Days

//Total orders Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query9="SELECT count(*) as total_orders FROM `orders` WHERE placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{
    $query9="SELECT count(*) as total_orders FROM `orders` WHERE placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}


    $result9=mysqli_query($conn,$query9);
     $row9=mysqli_fetch_assoc($result9);
    $total_orders=$row9['total_orders'];
  
// //Total orders Last 30 Days

// // //Total order completed Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query10="SELECT count(*) as total_paid_orders FROM `orders` WHERE payment_status='Paid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{

    $query10="SELECT count(*) as total_paid_orders FROM `orders` WHERE payment_status='Paid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}

    $result10=mysqli_query($conn,$query10);
     $row10=mysqli_fetch_assoc($result10);
    $total_delivered_orders=$row10['total_paid_orders'];
    
    // Prevent division by zero
        if ($total_orders > 0) {
            $delivered_order_percentage = (int)(($total_delivered_orders / $total_orders) * 100);
        } else {
            $delivered_order_percentage = 0; // Set percentage to 0 if no orders exist
        }
      
    
// //Total order completed Last 30 Days

// // //Total order Pending Last 30 Days

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query11="SELECT count(*) as total_pp_orders FROM `orders` WHERE payment_status='Partially Paid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{
    $query11="SELECT count(*) as total_pp_orders FROM `orders` WHERE payment_status='Partially Paid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}


    $result11=mysqli_query($conn,$query11);
     $row11=mysqli_fetch_assoc($result11);
    $total_pending_orders=$row11['total_pp_orders'];
     // Prevent division by zero
        if ($total_orders > 0) {
            $pending_order_percentage = (int)(($total_pending_orders / $total_orders) * 100);
        } else {
            $pending_order_percentage = 0; // Set percentage to 0 if no orders exist
        }
      
// // //Total order Pending Last 30 Days

// // //Total order declined Last 30 Days

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query12="SELECT count(*) as total_unpaid_orders FROM `orders` WHERE payment_status='Unpaid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);";
}
else
{
    
    $query12="SELECT count(*) as total_unpaid_orders FROM `orders` WHERE payment_status='Unpaid' and placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND sales_personid=".$_SESSION['A_id'];

}


    $result12=mysqli_query($conn,$query12);
     $row12=mysqli_fetch_assoc($result12);
    $total_declined_orders=$row12['total_unpaid_orders'];
     // Prevent division by zero
        if ($total_orders > 0) {
            $declined_order_percentage = (int)(($total_declined_orders / $total_orders) * 100);
        } else {
            $declined_order_percentage = 0; // Set percentage to 0 if no orders exist
        }
      
// //Total order declined Last 30 Days


// //Total total expected revenue Last 30 Days

     $total_Expected_revenue = $total_revenue;
    
// //Total total revenue Last 30 Days

//Total total IN DESIGN revenue Last 30 Days

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query14="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Design' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}
else
{
    $query14="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Design' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)  AND sales_personid=".$_SESSION['A_id'];

}


    $result14=mysqli_query($conn,$query14);
     $row14=mysqli_fetch_assoc($result14);
    $total_indesign_revenue = isset($row14['total_revenue']) ? $row14['total_revenue'] : 0;
    $indesign_percentage = isset($row14['total_revenue']) ? (int)(($total_indesign_revenue / $total_Expected_revenue) * 100) : 0;
    

// //Total total  delivered revenue Last 30 Days

// //Total total in print revenue Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query15="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Printing' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}
else
{
    $query15="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Printing' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)  AND sales_personid=".$_SESSION['A_id'];

}

    $result15=mysqli_query($conn,$query15);
     $row15=mysqli_fetch_assoc($result15);
    $total_inPrinting_revenue = isset($row15['total_revenue']) ? $row15['total_revenue'] : 0;
    $inprint_percentage = isset($row15['total_revenue']) ? (int)(($total_inPrinting_revenue / $total_Expected_revenue) * 100) : 0;

// //Total total  inprint revenue Last 30 Days

// //Total total indelivery revenue Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Delivery' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}
else
{
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='In Delivery' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)  AND sales_personid=".$_SESSION['A_id'];
}

    $result16=mysqli_query($conn,$query16);
     $row16=mysqli_fetch_assoc($result16);
    
    $total_indelivery_revenue = isset($row16['total_revenue']) ? $row16['total_revenue'] : 0;
    $indelivery_percentage = isset($row16['total_revenue']) ? (int)(($total_indelivery_revenue / $total_Expected_revenue) * 100) : 0;

// //Total total  indelivery revenue Last 30 Days


// //Total total DELIVERED revenue Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='Delivered' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}
else
{
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='Delivered' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)  AND sales_personid=".$_SESSION['A_id'];
}
    $result16=mysqli_query($conn,$query16);
     $row16=mysqli_fetch_assoc($result16);
    
    $total_delivered_revenue = isset($row16['total_revenue']) ? $row16['total_revenue'] : 0;
    $delivered_percentage = isset($row16['total_revenue']) ? (int)(($total_delivered_revenue / $total_Expected_revenue) * 100) : 0;

// //Total total  DELIVERED revenue Last 30 Days


// //Total total rejected revenue Last 30 Days
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='Rejected' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}
else
{
    $query16="SELECT sum(total_amount) as total_revenue FROM `orders` WHERE status='Rejected' AND placement_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)  AND sales_personid=".$_SESSION['A_id'];
}

    $result16=mysqli_query($conn,$query16);
     $row16=mysqli_fetch_assoc($result16);
    
    $total_rejected_revenue = isset($row16['total_revenue']) ? $row16['total_revenue'] : 0;
    $rejected_percentage = isset($row16['total_revenue']) ? (int)(($total_rejected_revenue / $total_Expected_revenue) * 100) : 0;

// //Total total  rejected revenue Last 30 Days


// //Total  sales current month

if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query17="SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM `orders` WHERE payment_status='Paid' AND YEAR(placement_date) = YEAR(NOW()) AND MONTH(placement_date) = MONTH(NOW());";
}
else
{
    $query17="SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM `orders` WHERE payment_status='Paid' AND YEAR(placement_date) = YEAR(NOW()) AND MONTH(placement_date) = MONTH(NOW()) AND sales_personid=".$_SESSION['A_id'];

}


    $result17=mysqli_query($conn,$query17);
     $row17=mysqli_fetch_assoc($result17);
    
    $Current_month_sales = isset($row17['total_sales']) ? $row17['total_sales'] : 0;

// //Total  sales current month

//Overall Order Pending
if (isset($_SESSION['role']) && $_SESSION['role']==1){
    
    $query18="SELECT count(*) as total_pending_orders FROM `orders` WHERE payment_status!='Paid'";
}
else
{
    $query18="SELECT count(*) as total_pending_orders FROM `orders` WHERE payment_status!='Paid' AND sales_personid=".$_SESSION['A_id'];

}


$result18=mysqli_query($conn,$query18);
     $row18=mysqli_fetch_assoc($result18);
    
    $overall_pending_orders = isset($row18['total_pending_orders']) ? $row18['total_pending_orders'] : 0;

// //Overall Order Pending

?>