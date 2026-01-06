<?php
include '../db.php';
session_start();

if(isset($_POST['insert']) ||  isset($_POST['generate_pdf'])){

    function generateOrderTrackingId($conn) {
        do {
            // Generate a 15-digit random number
            $trackingId = str_pad(mt_rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);
    
            // Check if the ID already exists in the database
            $resultid = mysqli_query($conn,"SELECT * FROM custom_order WHERE order_tracking_id =$trackingId");
            $count=mysqli_num_rows($resultid);
            
        } while ($count > 0); // Repeat if a duplicate is found
    
        return $trackingId;
    }   
    
    // Generate a unique tracking ID
   $uniqueTrackingId = generateOrderTrackingId($conn);
   //echo $uniqueTrackingId."<br>";
    $c_name=$_POST['c_name'];
    $phone_number=$_POST['phone_number'];
    $priority=$_POST['priority'];
    $status=$_POST['status'];
    $placement_date=$_POST['placement_date'];
    $validity_date=$_POST['validity_date'];
    $c_address = $_POST['c_address'] ?? '';

    $total_product=$_POST['total_product'];
    // echo $c_name."<br>";
    // echo $phone_number."<br>";
    // echo $priority."<br>";
    // echo $placement_date."<br>";
    // echo $validity_date."<br>";
    // echo $c_address."<br><hr>";

    $total_vat_amount=$_POST['total_vat_amount'];
    $total_amount_without_vat=$_POST['total_amount_without_vat'];
    $total_amount=$total_amount_without_vat+$total_vat_amount;
    $vat=$_POST['vat'];
    $advance_payment=$_POST['advance_payment'] ?? 'None';
    $advanced_transaction_id=$_POST['advanced_transaction_id'] ?? '';
    $advanced_payment_amount=$_POST['advanced_payment_amount'] ?? 0;
    $balance_payment=$_POST['balance_payment'] ?? 'None';
    $balance_transaction_id=$_POST['balance_transaction_id'] ?? '';
    $balance_payment_amount=$_POST['balance_payment_amount'] ?? 0;
    if($advanced_payment_amount==0)
    {
        $payment_status="Unpaid";
    }
    elseif($advanced_payment_amount==$total_amount)
    {
        $payment_status="Paid";
    }
    else{
        $payment_status="Partially Paid";
    }
    
     // echo $total_product."<br><hr>";
    // echo $total_amount_without_vat."<br>";
    // echo $total_vat_amount."<br>";
    // echo $total_amount_without_vat+$total_vat_amount."<br><hr>";
    // echo $vat."<br><hr>";
    // echo $advance_payment."<br>";
    // echo $advanced_transaction_id."<br>";
    // echo $advanced_payment_amount."<br><hr>";
    // echo $balance_payment."<br>";
    // echo $balance_transaction_id."<br>";
    // echo $balance_payment_amount."<br><hr>";
    // echo $payment_status."<br><hr>";

    $query="SELECT * FROM `admin` WHERE `id`=".$_SESSION['A_id'];
    $data=mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($data);
    $role=$row['role'];
    $sales_person=$row['fname']." ".$row['lname'];
    $sales_personid=$row['id'];

    //product section
    $products = [];
    for ($i = 1; $i <= $total_product; $i++) {
        // Check if the product field exists
        
            $product_id = $_POST["product_$i"];
            $description = $_POST["p_description_$i"] ?? '';
            $quantity = $_POST["p_quantity_$i"] ?? 0;
            $final_price = $_POST["p_final_price_$i"] ?? 0;

             // Handling file upload
             $image_name = '';
             if (!empty($_FILES["image_$i"]["name"])) {
                 $image_tmp = $_FILES["image_$i"]["tmp_name"];
                 //$image_name = $_FILES["image_$i"]["name"]; 
    
                // Generate a unique file name (with timestamp & random string)
                $ext = pathinfo($_FILES["image_$i"]["name"], PATHINFO_EXTENSION); // Get file extension
                $image_name = uniqid('img_', true) . '.' . $ext; // Unique file name
    
             }

             // Store product details in an array
            $products[] = [
                'product_id' => $product_id,
                'description' => $description,
                'quantity' => $quantity,
                'final_price' => $final_price,
                'image' => $image_name,
                'image_tmp' => $image_tmp
            ];
        
        
    }
    //product section

    $order_insert_query="INSERT INTO `orders`(`orderTrackingId`, `customer_name`, `phone_number`, `priority`, `status`, `placement_date`, `validity_date`, `customer_address`, `total_amount_without_vat`, `vat_amount`, `total_amount`, `advance_payment`, `advanced_transaction_id`, `advanced_payment_amount`, `balance_payment`, `balance_transaction_id`, `balance_payment_amount`, `payment_status`, `role`, `sales_personid`) VALUES ('$uniqueTrackingId','$c_name','$phone_number','$priority','$status','$placement_date','$validity_date','$c_address','$total_amount_without_vat','$total_vat_amount','$total_amount','$advance_payment','$advanced_transaction_id','$advanced_payment_amount','$balance_payment','$balance_transaction_id','$balance_payment_amount','$payment_status','$role','$sales_personid')";
    //echo $order_insert_query."<br>";
    $success_status="no";
    if(mysqli_query($conn,$order_insert_query)) {
        $last_id = mysqli_insert_id($conn);
        // Now insert each product into the database
        foreach ($products as $index => $product) {
            // echo "Product " . ($index + 1) . ":<br>";
            // echo "ID: " . $product['product_id'] . "<br>";
            // echo "Description: " . $product['description'] . "<br>";
            // echo "Quantity: " . $product['quantity'] . "<br>";
            // echo "Final Price: $" . $product['final_price'] . "<br>";
            // echo "Image: " . ($product['image'] ? $product['image'] : "No Image") . "<br>";
            // echo "<hr>";
            $order_detail_insert_query="INSERT INTO `order_detail`(`order_id`, `product_id`, `description`, `quantity`, `price`, `file`) VALUES ('$last_id','" .$product['product_id']."','".$product['description']."','".$product['quantity']."','".$product['final_price']."','".$product['image']."')";
            //echo $order_detail_insert_query."<br>";
            if(mysqli_query($conn,$order_detail_insert_query)) {
                if (!empty($product['image'])) {
                    move_uploaded_file($product['image_tmp'],"../custom_order_files/".$product['image']);
                }
                $success_status="yes";
                
            }
            
        }
        if($success_status=='yes'){
            if(isset($_POST['generate_pdf']))
            {
                echo "<script>
                    alert('Your Order is saved in Database');
                    window.location='../order_detail.php?id=$uniqueTrackingId';
                </script>"; 
            }
            else
            {
                echo "<script>
                    alert('Your Order is saved in Database');
                    window.location='../create_new_order.php';
                </script>"; 
            }
            
        }
        

    }

   

    


    


    

  

    






   
    


    
    


    

   

    // $insert_query="INSERT INTO `custom_order`(`order_tracking_id`, `pro_id`, `customer_name`, `file`, `phone`, `cod`, `total_payment`, `advanced_payment`, `pending_payment`, `quantity`, `size`, `priority`, `status`, `delivery_date`, `address`, `sales_personid`, `sales_person`, `role`)
    // VALUES ('$uniqueTrackingId','$products','$c_name','$file','$phone_number','0','$p_price','$advanced_price','$pending_price','$p_quantity','$p_size','$priority','$status','$delivery_date','$c_address','$sales_personid','$sales_person','$role')";

//echo $insert_query;
// if(move_uploaded_file($tmp_file,"../custom_order_files/".$file)){
//     if(mysqli_query($conn,$insert_query)) {
       
//         echo "<script>
//         alert('Your Order is saved in Database');
//                              window.location='../create_order.php';
//                              </script>"; 
       
//         exit;
//     }
//     else{
//         echo "error";
//     }

// }
    
    

   
    
      
  

}

?>