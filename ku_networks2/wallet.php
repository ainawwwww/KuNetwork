<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>KU Network</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <!-- CSS Files
    ================================================== -->
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
    <!-- color scheme -->
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">




</head>

<body>
    <div id="wrapper">

        <!-- header begin -->
      <?php include 'Assets/header.php'; ?>

        <!-- header close -->
        <!-- content begin -->
        <div class="no-bottom no-top" id="content">

            <div id="top"></div>

            <!-- section begin -->
            <section id="subheader" class="text-light" data-bgimage="url(images/background/bg.png) top">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">

                            <div class="col-md-12 text-center">
                                <h1>Wallet</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->
            <!-- Wallet Section -->

            <div class="container py-5">
                <div class="row  d-flex align-items-center justify-content-around">
                    <div class="col-md-7 col-sm-12 d-flex align-items-center justify-content-around ">

                        <!-- Left Side Content -->
                        <div class="w-75 pe-3">

                            <!-- Dropdown -->
                            <div class="mb-4">
                                <select class="form-select custom-select">
                                    <option selected>Select Network</option>
                                    <option value="1">BEP-20</option>
                                    <option value="2">TRC-20</option>
                                    <option value="3">Solana</option>
                                    <option value="4">TUFT BEP-20</option>
                                </select>
                            </div>

                            <!-- Wallet Addresses -->
                            <div class="address-block">
                                <div class="address-title">USDT Deposit Address (BEP-20)</div>
                                <div class="masked-address">*******************************</div>
                            </div>

                            <div class="address-block">
                                <div class="address-title">USDT Deposit Address (TRC-20)</div>
                                <div class="masked-address">*******************************</div>
                            </div>

                            <div class="address-block">
                                <div class="address-title">USDT Deposit Address (Solana)</div>
                                <div class="masked-address">*******************************</div>
                            </div>

                            <div class="address-block">
                                <div class="address-title">TUFT Deposit Address (BEP-20)</div>
                                <div class="masked-address">*******************************</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons mt-4">
                                
                                <a href="deposit.php" class="btn">
                                    <i class="bi bi-wallet2 mb-1"></i>
                                    Deposit
                                </a>
                           
                                <a href="withdrawinterface.php" class="btn">
                                    <i class="bi bi-cash-stack mb-1"></i>
                                    Withdraw
                                </a>
                                
                                <a href="#" class="btn">
                                    <i class="bi bi-gear mb-1"></i>
                                    Settings
                                </a>
                            </div>

                            <!-- Gradient Button -->
                            <button class="gradient-button mt-3">
                                Deposit in fiat currency
                            </button>

                            <!-- History Section -->
                            <div class="text-start d-flex justify-content-between">
                                <div class="history-title">History</div>
                                <div class="history-icon">
                                    <a href="#"><i class="bi bi-file-earmark-text"></i></a>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-5 col-sm-12">
                        <img src="images/wallet/wallet-img.jpg" class="img img-fluid" alt="">
                    </div>
                </div>
            </div>



            <section aria-label="section">

                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <span class="box-url-label">Most Popular</span>
                                <img src="images/wallet/1.png" alt="" class="mb20">
                                <h4>Metamask</h4>
                                <p>Start exploring blockchain applications in seconds. Trusted by over 1 million users
                                    worldwide.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/2.png" alt="" class="mb20">
                                <h4>Bitski</h4>
                                <p>Bitski connects communities, creators and brands through unique, ownable digital
                                    content.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/3.png" alt="" class="mb20">
                                <h4>Fortmatic</h4>
                                <p>Let users access your Ethereum app from anywhere. No more browser extensions.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/4.png" alt="" class="mb20">
                                <h4>WalletConnect</h4>
                                <p>Open source protocol for connecting decentralised applications to mobile wallets.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/5.png" alt="" class="mb20">
                                <h4>Coinbase Wallet</h4>
                                <p>The easiest and most secure crypto wallet. ... No Coinbase account required.
                                </p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/6.png" alt="" class="mb20">
                                <h4>Arkane</h4>
                                <p>Make it easy to create blockchain applications with secure wallets solutions.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <img src="images/wallet/7.png" alt="" class="mb20">
                                <h4>Authereum</h4>
                                <p>Your wallet where you want it. Log into your favorite dapps with Authereum.</p>
                            </a>
                        </div>

                        <div class="col-lg-3 mb30">
                            <a class="box-url" href="login.html">
                                <span class="box-url-label">Most Simple</span>
                                <img src="images/wallet/8.png" alt="" class="mb20">
                                <h4>Torus</h4>
                                <p>Open source protocol for connecting decentralised applications to mobile wallets.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </section>


        </div>

        <!-- content close -->

        <a href="#" id="back-to-top"></a>

        <!-- footer begin -->
       <?php include 'Assets/footer.php'; ?>

        <!-- footer close -->

    </div>



    <!-- Javascript Files
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/validation.js"></script>
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