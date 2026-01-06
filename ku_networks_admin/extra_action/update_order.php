<?php
include '../db.php';
session_start();

if(isset($_POST['insert']) || isset($_POST['generate_pdf'])){
    $id=$_POST['id'];
    $c_name=$_POST['c_name'];
    $phone_number=$_POST['phone_number'];
    $priority=$_POST['priority'];
    $status=$_POST['status'];
    $placement_date=$_POST['placement_date'];
    $validity_date=$_POST['validity_date'];
    $c_address = $_POST['c_address'] ?? '';

    $total_vat_amount=$_POST['total_vat_amount'];
    $total_amount_without_vat=$_POST['total_amount_without_vat'];
    $total_amount=$total_amount_without_vat+$total_vat_amount;
    $advance_payment=$_POST['advance_payment'] ?? 'None';
    $advanced_transaction_id=$_POST['advanced_transaction_id'] ?? '';
    $advanced_payment_amount=$_POST['advanced_payment_amount'] ?? 0;
    $balance_payment=$_POST['balance_payment'] ?? 'None';
    $balance_transaction_id=$_POST['balance_transaction_id'] ?? '';
    $balance_payment_amount=$_POST['balance_payment_amount'] ?? 0;
    $total_paid_amount=$advanced_payment_amount+$balance_payment_amount;
    if($advanced_payment_amount==0)
    {
        $payment_status="Unpaid";
    }
    elseif($advanced_payment_amount==$total_amount)
    {
        $payment_status="Paid";
    }
    elseif($total_paid_amount==$total_amount)
    {
        $payment_status="Paid";
    }
    else{
        $payment_status="Partially Paid";
    }
    
    
    
    $update_query="UPDATE `orders` SET `customer_name`='$c_name',`phone_number`='$phone_number',`priority`='$priority',`status`='$status',`placement_date`='$placement_date',`validity_date`='$validity_date',`customer_address`='$c_address',`advance_payment`='$advance_payment',`advanced_transaction_id`='$advanced_transaction_id',`advanced_payment_amount`='$advanced_payment_amount',`balance_payment`='$balance_payment',`balance_transaction_id`='$balance_transaction_id',`balance_payment_amount`='$balance_payment_amount',`payment_status`='$payment_status', `updated_at`=CURRENT_TIMESTAMP WHERE `orderTrackingId`=".$_POST['id'];

//echo $update_query;
   
    if(mysqli_query($conn,$update_query)) {
        if(isset($_POST['generate_pdf'])){
            echo "<script>
            alert('Your Order is updated in Database');
            window.location='../order_detail.php?id=$id';
        </script>";
        }
        else
        {
            header("location:../order_history.php");
        }
        
       
        exit;
    }
    else{
        echo "error";
    }
      
  

}

?>