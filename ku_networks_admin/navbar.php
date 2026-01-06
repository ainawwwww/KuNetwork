<?php include 'db.php';

// if (!isset($_SESSION['A_id'])) {
//     header("location:pages/examples/login.php");
//   exit();
// }





// if (isset($_SESSION['role']) && $_SESSION['role']==1){
//   $total_notification=0;
//   if(mysqli_num_rows($sql_inquiries)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_in_design)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_in_print)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_indelivery)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_delivered)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_rejected)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_cat1)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_cat2)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_cat3)){
//     $total_notification++;
//   }
//   if(mysqli_num_rows($sql_pro)){
//     $total_notification++;
//   }




// }
// else{
//   $total_notification=0;
//   if(mysqli_num_rows($sql_inquiries)){
//     $total_notification++;
//   }
// }

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index.php" class="nav-link">Home</a>
    </li>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1) { ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="pages/examples/register.php" class="nav-link">Register a New user</a>
      </li>
    <?php } ?>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="logout.php" class="nav-link">Logout</a>
    </li>
  </ul>
  <!-- <button type="button" class="btn btn-success toastsDefaultSuccess">
                  Launch Success Toast
    </button> -->
</nav>