<?php include 'db.php';
include 'check_login.php';
if(!isset($_GET['id']))
{
  echo "<Script>window.location='order_history.php'</script>";
}
else{
  $fetch="SELECT order_detail.order_id,order_detail.description,order_detail.quantity,order_detail.price,order_detail.product_id, orders.`id`, orders.`orderTrackingId`, orders.`customer_name`, orders.`phone_number`, orders.`placement_date`, orders.`validity_date`, orders.`customer_address`, orders.`total_amount_without_vat`, orders.`vat_amount`, orders.`total_amount`, orders.`advanced_payment_amount`, orders.`balance_payment_amount`, orders.`sales_personid`,admin.fname,admin.lname FROM `order_detail` JOIN orders on order_detail.order_id=orders.id JOIN admin on orders.sales_personid=admin.id WHERE orders.orderTrackingId=".$_GET['id'];
  $sql=mysqli_query($conn,$fetch);
  $row_order=mysqli_fetch_assoc($sql);
  $paid_amount=$row_order['advanced_payment_amount']+$row_order['balance_payment_amount'];
  $balance_amount=$row_order['total_amount']-$paid_amount;
//echo $fetch;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Invoice</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
        body { font-family: Arial, sans-serif; }
        .invoice-container { max-width: 800px; margin: auto; background: #fff; padding: 20px; }
        .invoice-header img, .invoice-footer img { width: 100%; height: auto; }
        .table th, .table td { text-align: center; }
        .table th, .table td {
    padding: 5px !important; /* Reduce padding */
    font-size: 14px; /* Adjust font size */
    line-height: 1; /* Reduce line height */
    vertical-align: middle; /* Align content to middle */
}
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include 'sidebar.php'; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a href="order_history.php" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            <h1>Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
   
    ?>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <button class="btn btn-primary mt-3 w-100" onclick="downloadPDF()">Download PDF</button>
<div class="invoice-container" id="invoice">
        <div class="invoice-header text-center">
            <img src="images/RN invoice format header.png" alt="Invoice Header">
        </div>
        
        <div class="row mt-1">
            <div class="col-7 border border-2 border-black p-2" style="line-height: 1;">
                <p><strong>Name:</strong> <?=$row_order['customer_name']?></p>
                <p><strong>Number:</strong> <?=$row_order['phone_number']?></p>
                <p><strong>Location:</strong><?=$row_order['customer_address']?></p>
                <p><strong>Date:</strong><?= date("d-M-Y", strtotime($row_order['placement_date']))?></p>
            </div>
            <div class="col-5 border border-2 border-black p-2" style="line-height: 1;">
                <p><strong>Valid Until:</strong> <?= date("d-M-Y", strtotime($row_order['validity_date']))?></p>
                <p><strong>INVOICE #:</strong> <span class="text-danger"><?=$row_order['orderTrackingId']?></span></p>
                <p><strong>SALES:</strong> <?=$row_order['fname']." ".$row_order['lname']?></p>
                <p><strong>TERMS:</strong> </p>
                <p><strong>Note:</strong> </p><br>
                
            </div>
        </div>

        <table class="table table-bordered border-black table-striped" style="border-bottom: 2px solid black;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>DESCRIPTION</th>
                    <th>QTY</th>
                    <th>U. PRICE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $fetch_pro="SELECT * FROM `order_detail` WHERE `order_id`=".$row_order['id'];
            $sql_pro=mysqli_query($conn,$fetch_pro);
              
              if(mysqli_num_rows($sql_pro)>0){
                $num=1;
        while($row=mysqli_fetch_assoc($sql_pro)){?>
                <tr>
                  <td><?=$num?></td>
                  <td><?=$row['description']?></td>
                  <td><?=$row['quantity']?> pcs</td>
                  <td><?=  number_format($row['price'] / $row['quantity'], 2) ?> AED</td>
                  
                  <td><?=$row['price']?> AED</td>
              </tr>
                <?php
      $num++; 
     }
      }
        ?>
            </tbody>
        </table>
        <div class="row ">
            <div class="col-8 text-center">
            <p><strong>THANK YOU FOR YOUR BUSINESS..!</strong></p>
            </div>
            <div class="col-4">
            <div class="text-end" style="margin-top: -15px; "> 
                <table class="table table-bordered table-striped border-black border-2" style="border: 3px solid black;">
                    <tr>
                        <td class="table-dark"><strong>VAT 5%:</strong></td>
                        <td><?=$row_order['vat_amount']?> AED</td>
                    </tr>
                    <tr>
                        <td class="table-dark"><strong>TOTAL:</strong></td>
                        <td><?=$row_order['total_amount']?> AED</td>
                    </tr>
                    <tr>
                        <td class="table-dark"><strong>Paid:</strong></td>
                        <td><?=$paid_amount?> AED</td>
                    </tr>
                    <tr>
                        <td class="table-dark"><strong>Balance:</strong></td>
                        <td><?=$balance_amount?> AED</td>
                    </tr>
                </table>
            <!-- <p><strong>TOTAL:</strong> 1900 AED</p>
            <p><strong>Paid:</strong> 1000 AED</p>
            <p><strong>Balance:</strong> 900 AED</p> -->
        </div>
            </div>

        </div>
        
       

        <!-- <div class="mt-1">
            <h6><b>TERMS & CONDITIONS</b></h6>
            <ol>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
                <li>Once artwork is approved, we are not responsible for any printing mistakes.</li>
            </ol>
            
        </div> -->

        <!-- <div class="mt-1 d-flex justify-content-between">
            <div>
                <h6>Receiver</h6>
                <p>___________________</p>
            </div>
            <div>
                <h6>Signature</h6>
                <p>___________________</p>
            </div>
        </div> -->

        <div class="invoice-footer mt-1">
            <img src="images/RN invoice format footer.png" alt="Invoice Footer" class="w-100">
        </div>

        
    </div>
    
    <script>
        function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'p', // Portrait mode
        unit: 'mm',
        format: 'a4'
    });

    const invoice = document.querySelector("#invoice");

    html2canvas(invoice, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const imgWidth = 190; // Width for the image inside the PDF
        const pageHeight = 297; // A4 Page height in mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio

        let position = 10;

        doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

        let remainingHeight = imgHeight;
        while (remainingHeight > pageHeight - 20) {
            position = position - pageHeight + 20;
            doc.addPage();
            doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            remainingHeight -= pageHeight - 20;
        }

        doc.save("invoice.pdf");
    });
}

    </script>
              
              <!-- this row will not appear when printing -->
              
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include 'footer.php';?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
   $(document).ready(function(){
$(".order_status").change(function(){
  var rowid=$(this).attr('id');
  var selected_status=$(this).val();
  var menuId = $("ul.nav").first().attr("id");
 $.ajax({
  url: "action/change_order_status.php",
  type: "POST",
  data: {id : rowid,
        selected_status:selected_status},
        success: function(data){
     //if(data==1)
      //window.location='order_history.php';
  }
});

});
  });

        // Add an event listener to the form's submit event
        document.getElementById('delete_form').addEventListener('submit', function (event) {
            // Show confirmation dialog
            const isConfirmed = confirm("Are you sure you want to Delete this order Permanently?");
            
            // If the user clicks "Cancel", prevent form submission
            if (!isConfirmed) {
                event.preventDefault();
            }
        });
</script>
</body>
</html>
