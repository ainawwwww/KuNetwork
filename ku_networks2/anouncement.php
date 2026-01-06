<?php
include 'config.php'; 

// "LIMIT 5" ka matlab hai sirf latest 5 records aayenge
$query = "SELECT * FROM announcements WHERE status=1 ORDER BY announcement_date DESC, id DESC LIMIT 5";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>KU Network - Announcements</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16" />
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
        .notification-card {
            background: #fff;
            border-left: 5px solid #007bff; /* Blue line on left */
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 5px;
            transition: transform 0.2s;
        }
        .notification-card:hover {
            transform: translateY(-5px); /* Hover effect */
        }
        .notif-title {
            color: #333;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .notif-date {
            color: #888;
            font-size: 14px;
            font-style: italic;
            margin-bottom: 15px;
            display: block;
        }
        .notif-desc {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <section id="subheader" class="text-light" data-bgimage="url(images/background/bg.png) top">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Latest Announcements</h1>
                                <p>Stay updated with the latest news</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="announcement">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="notification-list">

                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        
                                        <div class="notification-card wow fadeInUp">
                                            
                                            <h2 class="notif-title"><?= htmlspecialchars($row['title']) ?></h2>
                                            
                                            <span class="notif-date">
                                                <i class="fa fa-calendar"></i> 
                                                <?= date("d M, Y", strtotime($row['announcement_date'])) ?>
                                            </span>
                                            
                                            <div class="notif-desc">
                                                <?= nl2br(htmlspecialchars($row['description'])) ?>
                                            </div>

                                        </div>
                                        <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">
                                        <h3>No announcements available at the moment.</h3>
                                        <p>Please check back later.</p>
                                    </div>
                                <?php endif; ?>

                            </div>
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