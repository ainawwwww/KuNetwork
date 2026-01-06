<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../bootstrap.min.css">

  <style>
    .login-box, .register-box {
    width: 600px !important;
}
body.login-page {
    background-image: url(bg.jpg) !important;
    background-position: center ;
    /*background-repeat: no-repeat;*/
    background-size: cover;
}

  </style>

</head>
<body class="hold-transition login-page">
    <br>
    <br>
    <br>
     <br>
    <br>
    <br>
    <div class="card">
       <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>ADMIN</b> PORTAL</a>
    </div>
    <div class=card-body>
      
    <form  action="../../action/login.php" method="post">
         <p class="login-box-msg">Sign in to start your session</p>
  <!-- Email input -->
  <div class="form-outline mb-4">
    <input type="email" id="form2Example1" class="form-control" value="<?php if(isset($_COOKIE["email"])) { echo $_COOKIE["email"]; } ?>"   name="email"/>
  
  </div>

  <!-- Password input -->
  <div class="form-outline mb-4">
    <input type="password" id="form2Example2" class="form-control" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>"  name="password" />
    
  </div>

  <!-- 2 column grid layout for inline styling -->
  <div class="row mb-4">
    <div class="col d-flex justify-content-center">
      <!-- Checkbox -->
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="form2Example31"  />
        <label class="form-check-label" for="form2Example31"> Remember me </label>
      </div>
    </div>
<?php
/**
* Website: www.TutorialsClass.com
**/

if(!empty($_POST["remember"])) {
	setcookie ("email",$_POST["email"],time()+ 3600);
	setcookie ("password",$_POST["password"],time()+ 3600);
	echo "Cookies Set Successfuly";
} else {
	setcookie("email","");
	setcookie("password","");
// 	echo "Cookies Not Set";
}

?>

<!--<p><a href="pages/examples/login.php"> Go to Login Page </a> </p>-->
  <div class="col">
      <!-- Simple link -->
      <a href="#!"></a>
    </div>
  </div>

  <!-- Submit button -->
  <button  type="submit" class="btn btn-primary btn-block mb-4" name="login">Sign in</button>

  <!-- Register buttons -->
  <div class="text-center">
     
    <p>Not a member? <a href="register.php" class="text-center">Register a new membership</a></p>
    
  </div>
</form>
</div>
</div>









<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
