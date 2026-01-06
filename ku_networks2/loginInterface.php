<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>KU Network - Login</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />

    <style>
        /* Custom Header Styles */
        .custom-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand img {
            max-height: 50px;
        }

        .btn-login-nav {
            background-color: #f47656 !important; /* KU Network Orange */
            color: #fff !important;
            padding: 10px 30px !important;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            border: none;
            box-shadow: 0 4px 15px rgba(244, 118, 86, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-login-nav:hover {
            background-color: #e06040 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 118, 86, 0.5);
            color: #fff !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <header class="navbar navbar-expand-lg fixed-top custom-navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.png" onerror="this.src='images/icon.png'; this.style.height='40px';" alt="KU Network">
                </a>

                <div class="ms-auto">
                    <a href="registerinterface.php" class="btn-login-nav">Register</a>
                </div>
            </div>
        </header>
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
            
            <section id="subheader" class="text-light" data-bgimage="url(images/background/bg.png) top">
                    <div class="center-y relative text-center">
                        <div class="container">
                            <div class="row">
                                
                                <div class="col-md-12 text-center">
                                    <h1>User Login</h1>
                                    <p>Welcome back to KU Network</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
            </section>
            <section aria-label="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <form name="contactForm" id="contact_form" class="form-border" method="post" action="login.php">
                                <h3>Login to your account</h3>
                                
                                <div class="field-set">
                                    <label>Username/Email</label>
                                    <input type="text" name="username_or_email" id="username_or_email" class="form-control" required>
                                </div>
                                
                                <div class="field-set">
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                
                                <div id="submit">
                                    <input type="submit" id="send_message" value="Login" class="btn btn-main color-2">
                                </div>

                                <div class="clearfix"></div>
                                
                                <div class="spacer-single"></div>

                                <div class="text-center">
                                    Don't have an account? <a href="registerinterface.php" style="color: var(--accent-orange); font-weight: bold;">Register Now</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            
        </div>
        <a href="#" id="back-to-top"></a>
        
        <?php include 'Assets/footer.php'; ?>
        
        </div>

    
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/enquire.min.js"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script src="js/jquery.lazy.min.js"></script>
    <script src="js/jquery.lazy.plugins.min.js"></script>
    <script src="js/designesia.js"></script>

</body>
</html>