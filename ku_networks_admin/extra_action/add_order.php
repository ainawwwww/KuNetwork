<?php
include '../db.php';
session_start();

if(isset($_POST['insert'])){
     $products=$_POST['products'];
    $c_name=$_POST['c_name'];
    $file = $_FILES['image']['name'];
    $tmp_file = $_FILES['image']['tmp_name'];
    $phone_number=$_POST['phone_number'];
    $p_price=$_POST['p_price'];
    $advanced_price=$_POST['advanced_price'];
    $pending_price=$_POST['pending_price'];
    $p_quantity=$_POST['p_quantity'];
    $p_size=$_POST['p_size'];
    $priority=$_POST['priority'];
    $status=$_POST['status'];
    $c_address=$_POST['c_address'];
    $delivery_date=$_POST['delivery_date'];

    $query="SELECT * FROM `admin` WHERE `id`=".$_SESSION['A_id'];
    $data=mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($data);
    $role=$row['role'];
    $sales_person=$row['fname']." ".$row['lname'];
    $sales_personid=$row['id'];
    


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


    $insert_query="INSERT INTO `custom_order`(`order_tracking_id`, `pro_id`, `customer_name`, `file`, `phone`, `cod`, `total_payment`, `advanced_payment`, `pending_payment`, `quantity`, `size`, `priority`, `status`, `delivery_date`, `address`, `sales_personid`, `sales_person`, `role`)
    VALUES ('$uniqueTrackingId','$products','$c_name','$file','$phone_number','0','$p_price','$advanced_price','$pending_price','$p_quantity','$p_size','$priority','$status','$delivery_date','$c_address','$sales_personid','$sales_person','$role')";

//echo $insert_query;
if(move_uploaded_file($tmp_file,"../custom_order_files/".$file)){
    if(mysqli_query($conn,$insert_query)) {
       
        echo "<script>
        alert('Your Order is saved in Database');
                             window.location='../create_order.php';
                             </script>"; 
       
        exit;
    }
    else{
        echo "error";
    }

}
    
    

   
    
      
  

}

?>