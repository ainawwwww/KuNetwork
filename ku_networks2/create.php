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
            <section id="subheader" class="text-light" data-bgimage="url(images/background//bg.png) top">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">

                            <div class="col-md-12 text-center">
                                <h1>Create</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->


            <!-- section begin -->
            <section aria-label="section">
                <div class="container">
                    <div class="row wow fadeIn">
                        <div class="col-lg-7 offset-lg-1">
                            <form id="form-create-item" class="form-border" method="post" action="email.php">
                                <div class="field-set">
                                    <h5>Upload file</h5>

                                    <div class="d-create-file">
                                        <p id="file_name">PNG, JPG, GIF, WEBP or MP4. Max 200mb.</p>
                                        <input type="button" id="get_file" class="btn-main" value="Browse">
                                        <input type="file" id="upload_file">
                                    </div>

                                    <div class="spacer-single"></div>

                                    <h5>Select method</h5>
                                    <div class="de_tab tab_methods">
                                        <ul class="de_nav">
                                            <li class="active"><span><i class="fa fa-tag"></i>Fixed price</span>
                                            </li>
                                            <li><span><i class="fa fa-hourglass-1"></i>Timed auction</span>
                                            </li>
                                            <li><span><i class="fa fa-users"></i>Open for bids</span>
                                            </li>
                                        </ul>

                                        <div class="de_tab_content">

                                            <div id="tab_opt_1">
                                                <h5>Price</h5>
                                                <input type="text" name="item_price" id="item_price"
                                                    class="form-control" placeholder="enter price for one item (ETH)" />
                                            </div>

                                            <div id="tab_opt_2">
                                                <h5>Minimum bid</h5>
                                                <input type="text" name="item_price_bid" id="item_price_bid"
                                                    class="form-control" placeholder="enter minimum bid" />

                                                <div class="spacer-10"></div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Starting date</h5>
                                                        <input type="date" name="bid_starting_date"
                                                            id="bid_starting_date" class="form-control"
                                                            min="1997-01-01" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Expiration date</h5>
                                                        <input type="date" name="bid_expiration_date"
                                                            id="bid_expiration_date" class="form-control" />
                                                    </div>
                                                    <div class="spacer-single"></div>
                                                </div>
                                            </div>

                                            <div id="tab_opt_3">
                                            </div>

                                        </div>

                                    </div>

                                    <h5>Title</h5>
                                    <input type="text" name="item_title" id="item_title" class="form-control"
                                        placeholder="e.g. 'Crypto Funk" />

                                    <div class="spacer-10"></div>

                                    <h5>Description</h5>
                                    <textarea data-autoresize name="item_desc" id="item_desc" class="form-control"
                                        placeholder="e.g. 'This is very limited item'"></textarea>

                                    <div class="spacer-10"></div>

                                    <h5>Royalties</h5>
                                    <input type="text" name="item_royalties" id="item_royalties" class="form-control"
                                        placeholder="suggested: 0, 10%, 20%, 30%. Maximum is 70%" />

                                    <div class="spacer-single"></div>

                                    <input type="button" id="submit" class="btn-main" value="Create Item">
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-xs-12">
                            <h5>Preview item</h5>
                            <div class="nft__item">
                                <div class="de_countdown" data-year="2021" data-month="9" data-day="16" data-hour="8">
                                </div>
                                <div class="author_list_pp">
                                    <a href="#">
                                        <img class="lazy" src="images/author/author-1.jpg" alt="">
                                        <i class="fa fa-check"></i>
                                    </a>
                                </div>
                                <div class="nft__item_wrap">
                                    <a href="#">
                                        <img src="images/collections/coll-item-3.jpg" id="get_file_2"
                                            class="lazy nft__item_preview" alt="">
                                    </a>
                                </div>
                                <div class="nft__item_info">
                                    <a href="#">
                                        <h4>Pinky Ocean</h4>
                                    </a>
                                    <div class="nft__item_price">
                                        0.08 ETH<span>1/20</span>
                                    </div>
                                    <div class="nft__item_action">
                                        <a href="#">Place a bid</a>
                                    </div>
                                    <div class="nft__item_like">
                                        <i class="fa fa-heart"></i><span>50</span>
                                    </div>
                                </div>
                            </div>
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