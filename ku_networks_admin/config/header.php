<?php 

ob_start();

session_start();





include "db.php";

//$_SESSION['admin_id']='1';

// $user_query = mysqli_query($conn,"SELECT * FROM `user_detail` WHERE 'user_id`='".$_SESSION['u_id']."'");

 //$admin_data = mysqli_fetch_assoc($user_query); ?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Admin</title>

    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 11]>

    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    	<![endif]-->

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />

    <!-- Favicon icon -->

    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">    <!-- vendor css -->

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

  

    



</head>

<style type="text/css">

li.nav-item.pcoded-menu-caption {

    display: none;

}
.pcoded-inner-navbar{
    /*display: none;*/
}
.in{
    display: none;
}

.pcoded-main-container{

	margin-top: 50px;

}

.navbar-wrapper {

    background-color: #fff !important;

}
.pcoded-navbar.menu-light {
    
    height: 1000px;
}

/* .m-header {

    background-color: #fff !important;

} */

/* .collapse.navbar-collapse {

    background-color: #fff;

} */

a.nav-link {

    color: #fff !important;

}

a.b-brand {

    color: #fff;

}

.pcoded-header .dropdown .dropdown-toggle {

    color: #fff;

}

header.navbar.pcoded-header.navbar-expand-lg.navbar-light.header-blue.position-fixed {

    padding: 0px;

}

/* button.btn.btn-primary.btn-lg {

    /* border-color: #d5adfb;

    background-color: #d5adfb; 

}*/



.m1{

	font-weight: bold !important;

	font-family: Roboto;

}

@media screen and (max-width:991px){
    .in{
    display: block;
}

}

@media screen and (max-width:767px){
 

}

</style>

<body class="">

	<!-- [ Pre-loader ] start -->

	<div class="loader-bg">

		<div class="loader-track">

			<div class="loader-fill"></div>

		</div>

	</div>

	<!-- [ Pre-loader ] End -->

	<!-- [ navigation menu ] start -->

	<nav class="pcoded-navbar menu-light position-fixed">

		<?php include "sidebar.php"; ?>

	</nav>

	<!-- [ navigation menu ] end -->

	<!-- [ Header ] start -->

	<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue position-fixed">

		<div class="m-header">

			<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>

			<a href="dashboard.php" class="b-brand mt-3">

				<!-- ========   change your logo hear   ============ -->

				 <img src="images/logo11.jpg" style="width:60xp; height:55px; border-radius:30px; margin-top:-15px;margin-left:-18px;" class="mr-2 logs"> 

				<h5 class="text-muted m1" style="color:#f56a6a !important;"><b>Creative Print</b></h5>

				<!-- <img src="assets/images/logo-icon.png" alt="" class="logo-thumb"> -->

			</a>

			<a href="#!" class="mob-toggler">

				<i class="feather icon-more-vertical"></i>

			</a>

		</div>

		

		<div class="collapse navbar-collapse">

			<ul class="navbar-nav mr-auto">

				<li class="nav-item">

					<div class="search-bar">

						<input type="text" class="form-control border-0 shadow-none" placeholder="Search hear">

						<button type="button" class="close" aria-label="Close">

							<span aria-hidden="true">&times;</span>

						</button>

					</div>

				</li>

				

			</ul>

			<ul class="navbar-nav ">



			

			

				<!-- <li><p class="douoo">Do you need help? Please call us at:<span class="pp12">021-111-BLUE-EX (021-111-258339)</span> </p>  </li> -->

				<!-- <li>

					<div class="dropdown">

						<a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>

						<div class="dropdown-menu dropdown-menu-right notification">

							<div class="noti-head">

								<h6 class="d-inline-block m-b-0">Notifications</h6>

								<div class="float-right">

									<a href="#!" class="m-r-10">mark as read</a>

									<a href="#!">clear all</a>

								</div>

							</div>

							<ul class="noti-body">

								<li class="n-title">

									<p class="m-b-0">NEW</p>

								</li>

								<li class="notification">

									<div class="media">

										<img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">

										<div class="media-body">

											<p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>

											<p>New ticket Added</p>

										</div>

									</div>

								</li>

								<li class="n-title">

									<p class="m-b-0">EARLIER</p>

								</li>

								<li class="notification">

									<div class="media">

										<img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">

										<div class="media-body">

											<p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>

											<p>Prchace New Theme and make payment</p>

										</div>

									</div>

								</li>

								<li class="notification">

									<div class="media">

										<img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">

										<div class="media-body">

											<p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>

													<p>currently login</p>

										</div>

									</div>

								</li>

								<li class="notification">

									<div class="media">

										<img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">

										<div class="media-body">

											<p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>

											<p>Prchace New Theme and make payment</p>

										</div>

									</div>

								</li>

							</ul>

							<div class="noti-footer">

								<a href="#!">show all</a>

							</div>

						</div>

					</div>

				</li> -->

				

				<li>

					<div class="dropdown drp-user">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

							<i class="feather text-muted icon-user fa-2x"  style="color:#f56a6a !important;"></i>

						</a>

						<div class="dropdown-menu dropdown-menu-right profile-notification">

						

							<ul class="pro-body">

								<!-- <li><a href="#" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li> -->

								<li><a href="logout.php" class="dropdown-item"><i class="feather icon-log-out"  style="color:#f56a6a !important;"></i> Log Out</a></li>

							</ul>

						</div>

					</div>

				</li>

			</ul>

		</div>	

	</header>



	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

	<!-- [ Header ] end -->