<?php
include 'config.php';
session_start();

if (!defined('PROFILE_IMAGE_UPLOAD_DIR')) {
    define('PROFILE_IMAGE_UPLOAD_DIR', 'images/uploads/profile_images/');
}
if (!defined('PROFILE_IMAGE_UPLOAD_DIR_ADMIN')) {
    define('PROFILE_IMAGE_UPLOAD_DIR_ADMIN', '/ku_networks_admin/images/uploads/profile_images/');
}
if (!defined('BASE_WEB_PATH_MAIN_PROJECT')) {
    define('BASE_WEB_PATH_MAIN_PROJECT', '/ku_networks/');
}
if (!defined('DEFAULT_AVATAR_FILENAME')) {
    define('DEFAULT_AVATAR_FILENAME', 'default.png');
}

$sql = "SELECT u.name, u.image, uw.total_balance
        FROM users u
        JOIN user_wallets uw ON u.id = uw.user_id
        ORDER BY uw.total_balance DESC";

$result = $conn->query($sql);

$players = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
}

function get_player_image_src($image_filename)
{
    $doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

    $admin_web_path = PROFILE_IMAGE_UPLOAD_DIR_ADMIN . $image_filename;
    $admin_server_path = $doc_root . PROFILE_IMAGE_UPLOAD_DIR_ADMIN . $image_filename;

    $user_web_path = BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . $image_filename;
    $user_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . $image_filename;

    $default_admin_web_path = PROFILE_IMAGE_UPLOAD_DIR_ADMIN . DEFAULT_AVATAR_FILENAME;
    $default_admin_server_path = $doc_root . PROFILE_IMAGE_UPLOAD_DIR_ADMIN . DEFAULT_AVATAR_FILENAME;

    $default_user_web_path = BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . DEFAULT_AVATAR_FILENAME;
    $default_user_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . PROFILE_IMAGE_UPLOAD_DIR . DEFAULT_AVATAR_FILENAME;

    $ultimate_default_web_path = BASE_WEB_PATH_MAIN_PROJECT . 'images/default_avatar.png';
    $ultimate_default_server_path = $doc_root . BASE_WEB_PATH_MAIN_PROJECT . 'images/default_avatar.png';

    if (!empty($image_filename) && $image_filename !== DEFAULT_AVATAR_FILENAME) {
        if (file_exists($admin_server_path)) {
            return $admin_web_path;
        }
        if (file_exists($user_server_path)) {
            return $user_web_path;
        }
    }

    if (file_exists($default_user_server_path)) {
        return $default_user_web_path;
    }
    if (file_exists($default_admin_server_path)) {
        return $default_admin_web_path;
    }

    return $ultimate_default_web_path;
}

$membership = "SELECT * FROM membership";
$memberresult = mysqli_query($conn, $membership);

$users = "SELECT * FROM `users`";
$res = mysqli_query($conn,$users);
$usersLenghth = mysqli_num_rows($res);
 
$chechAuth = $_SESSION["user_id"]??false;
if ($chechAuth) {
    $showStageTime = false;
    $stageText = "";

    if ($usersLenghth <= 10000) {
        $stageText = "You are in Stage 1 — Excellent!";
    } elseif ($usersLenghth <= 25000) {
        $stageText = "You are in Stage 2 — Very Good!";
    } elseif ($usersLenghth <= 50000) {
        $stageText = "You are in Stage 3 — Great Job!";
    } elseif ($usersLenghth <= 1000000) {
        $stageText = "You are in Stage 4 — Amazing!";
    } else {
        $stageText = "You are in the Unlimited Stage — Keep Going!";
    }

    if (!empty($stageText)) {
        $showStageTime = true;
        echo "<div class=\"modal fade\" id=\"tokenModal\" tabindex=\"-1\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
                <div class=\"modal-content bg-transparent border-0\">
                    <div class=\"position-relative text-center\" style=\"width: 350px;\">
                        <img src=\"images/wallet/Token.png\" alt=\"Token\" class=\"img-fluid w-100 mb-5\" style=\"border-radius: 100px;\" />
                        <button type=\"button\" class=\"btn-close position-absolute top-0 end-0 m-2\" data-bs-dismiss=\"modal\"></button>
                        <div class=\"position-absolute top-50 start-50 translate-middle w-100 px-5 pt-5 text-start\">
                            <h5><span>Congratulations!</span></h5>
                            <p class=\"fw-bold text-dark\">$stageText</p>
                            <div class=\"text-center mt-5 pt-4\">
<p class=\"text-dark btn-claim-text mb-1\" >You are currently in Stage . Please check your wallet for the tokens.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    }
}
?>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@600;700&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap" rel="stylesheet">
    
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
            /* Adjust this if logo is too big or small */
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
        }

        .btn-login-nav:hover {
            background-color: #e06040 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 118, 86, 0.5);
            color: #fff !important;
        }

        /* Membership Button Styles */
        .membership-buy-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: inline-block;
            padding: 10px 138px;
            border: 2px solid;
            white-space: nowrap;
            word-break: keep-all;
        }

        .membership-buy-btn:hover {
            transform: scale(1.03);
            background-color: #0cace7 !important;
            padding-right: 150px !important;
        }

        .membership-buy-btn::after {
            content: "\f061";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.3s ease, right 0.3s ease;
        }

        .membership-buy-btn:hover::after {
            opacity: 1;
            right: 20px;
        }

        .membership-feature-list i {
            color: #007bff;
            margin-right: 10px;
        }

        .membership-feature-list li {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .custom-card {
            max-width: max-content;
            border: none;
            box-shadow: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .membership-buy-btn {
                padding: 10px 100px;
                text-align: center;
            }
            .membership-buy-btn:hover {
                padding-right: 110px !important;
            }
        }

        @media (max-width: 768px) {
            .membership-buy-btn {
                padding: 10px 80px;
                font-size: 14px;
                text-align: center;
            }
            .membership-buy-btn:hover {
                padding-right: 90px !important;
            }
        }

        @media (max-width: 576px) {
            .membership-buy-btn {
               font-size: 13px;
               text-align: center;
            }
            .membership-buy-btn:hover {
               padding-right: 70px !important;
            }
            .membership-feature-list li {
               font-size: 13px;
            }
            .custom-card {
               max-width: 100%;
            }
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="account.php" class="btn-login-nav">Dashboard</a>
                    <?php else: ?>
                        <a href="loginInterface.php" class="btn-login-nav">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
            <section id="section-hero" aria-label="section" class="no-top no-bottom vh-100">
                <div class="v-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="spacer-single"></div>
                                <h6 class="wow fadeInUp" data-wow-delay=".5s"><span class="text-uppercase id-color-2">KU
                                            Network</span></h6>
                                <div class="spacer-10"></div>
                                <h1 class="wow fadeInUp" data-wow-delay=".75s">Create, sell or collect digital items.
                                </h1>
                                <p class="wow fadeInUp lead" data-wow-delay="1s">
                                    Unit of data stored on a digital ledger, called a blockchain, that certifies a
                                    digital asset to be unique and therefore not interchangeable</p>
                                <div class="spacer-10"></div>
                                <a href="explore.php" class="btn-main wow fadeInUp lead"
                                    data-wow-delay="1.25s">Explore</a>
                                <div class="mb-sm-30"></div>
                            </div>
                            <div class="col-md-5 xs-hide">
                                <img src="images/misc/nft3.png" class="lazy img-fluid wow fadeIn" data-wow-delay="1.25s"
                                    alt="" height="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="section-intro" class="no-top no-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-sm-30">
                            <div class="feature-box f-boxed style-3">
                                <i class="wow fadeInUp bg-color-2 i-boxed icon_wallet"></i>
                                <div class="text">
                                    <h4 class="wow fadeInUp">Set up your wallet</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".25s">Sed ut perspiciatis unde omnis iste
                                        natus error sit voluptatem accusantium doloremque laudantium, totam rem.</p>
                                </div>
                                <i class="wm icon_wallet"></i>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-sm-30">
                            <div class="feature-box f-boxed style-3">
                                <i class="wow fadeInUp bg-color-2 i-boxed icon_cloud-upload_alt"></i>
                                <div class="text">
                                    <h4 class="wow fadeInUp">Add your NFT's</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".25s">Sed ut perspiciatis unde omnis iste
                                        natus error sit voluptatem accusantium doloremque laudantium, totam rem.</p>
                                </div>
                                <i class="wm icon_cloud-upload_alt"></i>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-sm-30">
                            <div class="feature-box f-boxed style-3">
                                <i class="wow fadeInUp bg-color-2 i-boxed icon_tags_alt"></i>
                                <div class="text">
                                    <h4 class="wow fadeInUp">Sell your NFT's</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".25s">Sed ut perspiciatis unde omnis iste
                                        natus error sit voluptatem accusantium doloremque laudantium, totam rem.</p>
                                </div>
                                <i class="wm icon_tags_alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="page-center-wrapper">
                <section>
                    <div class="leaderboard-container">
                        <div class="leaderboard-header">
                            <h1>LEADERBOARD</h1>
                        </div>

                        <?php if (!empty($players)): ?>
                            <?php
                            $top_player = array_shift($players);
                            $rank = 1;
                            $players = array_slice($players, 0, 4);

                            $top_player_image_src = get_player_image_src($top_player['image']);
                            ?>
                            <div class="top-player-card">
                                <div class="rank-badge">#<?php echo str_pad($rank, 2, '0', STR_PAD_LEFT); ?></div>
                                <div class="player-avatar-container">
                                    <img src="<?php echo htmlspecialchars($top_player_image_src); ?>"
                                        alt="<?php echo htmlspecialchars($top_player['name']); ?> Avatar"
                                        class="player-avatar">
                                    <div class="player-shield"><i class="fas fa-crown"></i></div>
                                </div>
                                <p class="player-name"><?php echo htmlspecialchars($top_player['name']); ?></p>
                                <p class="player-score">
                                    <?php echo number_format(floatval($top_player['total_balance']), 0); ?></p>
                                <div class="player-social-share">
                                    <a href="#" title="Share Profile"><i class="fas fa-share-nodes"></i></a>
                                </div>
                            </div>

                            <ul class="leaderboard-list">
                                <?php
                                $rank = 2;
                                foreach ($players as $player):

                                    $player_image_src = get_player_image_src($player['image']);
                                    $badge_class = 'badge-green';
                                    $badge_icon = 'fa-medal';
                                    if ($rank == 2 || $rank == 3) {
                                        $badge_class = 'badge-blue';
                                        $badge_icon = 'fa-shield-halved';
                                    } elseif ($rank == 4 || $rank == 5) {
                                        $badge_class = 'badge-orange';
                                        $badge_icon = 'fa-star';
                                    } elseif ($rank == 6) {
                                        $badge_class = 'badge-pink';
                                        $badge_icon = 'fa-award';
                                    }
                                    ?>
                                    <li class="leaderboard-item">
                                        <span class="rank"><?php echo str_pad($rank, 2, '0', STR_PAD_LEFT); ?></span>
                                        <div class="player-info">
                                            <img src="<?php echo htmlspecialchars($player_image_src); ?>"
                                                alt="<?php echo htmlspecialchars($player['name']); ?> Avatar"
                                                class="player-avatar">
                                            <span class="player-name"><?php echo htmlspecialchars($player['name']); ?></span>
                                        </div>
                                        <span
                                            class="player-score"><?php echo number_format(floatval($player['total_balance']), 0); ?></span>
                                        <div class="player-badge <?php echo $badge_class; ?>"><i
                                                class="fas <?php echo $badge_icon; ?>"></i></div>
                                        <div class="player-social-share">
                                            <a href="#" title="Share Profile"><i class="fas fa-share-nodes"></i></a>
                                        </div>
                                    </li>
                                    <?php
                                    $rank++;
                                endforeach;
                                ?>
                            </ul>
                        <?php else: ?>
                            <p class="no-players">Leaderboard par abhi koi players nahi hain.</p>
                        <?php endif; ?>
                    </div>
                      
                </section>
            </div>


            <section id="section-collections" class="no-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h2>Hot Collections</h2>
                                <div class="small-border bg-color-2"></div>
                            </div>
                        </div>
                        <div id="collection-carousel" class="owl-carousel wow fadeIn">

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-1.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-1.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Abstraction</h4>
                                    </a>
                                    <span>ERC-192</span>
                                </div>
                            </div>

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-2.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-2.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Patternlicious</h4>
                                    </a>
                                    <span>ERC-61</span>
                                </div>
                            </div>

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-3.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-3.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Skecthify</h4>
                                    </a>
                                    <span>ERC-126</span>
                                </div>
                            </div>

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-4.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-4.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Cartoonism</h4>
                                    </a>
                                    <span>ERC-73</span>
                                </div>
                            </div>

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-5.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-5.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Virtuland</h4>
                                    </a>
                                    <span>ERC-85</span>
                                </div>
                            </div>

                            <div class="nft_coll">
                                <div class="nft_wrap">
                                    <a href="collection.html"><img src="images/collections/coll-6.jpg"
                                            class="lazy img-fluid" alt=""></a>
                                </div>
                                <div class="nft_coll_pp">
                                    <a href="collection.html"><img class="lazy" src="images/author/author-6.jpg"
                                            alt=""></a>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="nft_coll_info">
                                    <a href="collection.html">
                                        <h4>Papercut</h4>
                                    </a>
                                    <span>ERC-42</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section id="section-items" class="no-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h2>New Items</h2>
                                <div class="small-border bg-color-2"></div>
                            </div>
                        </div>
                        <div id="items-carousel" class="owl-carousel wow fadeIn">

                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="de_countdown" data-year="2021" data-month="9" data-day="16"
                                        data-hour="8"></div>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-1.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/static-1.jpg" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
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
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-10.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/static-2.jpg" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>Deep Sea Phantasy</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.06 ETH<span>1/22</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>80</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="de_countdown" data-year="2021" data-month="9" data-day="14"
                                        data-hour="8"></div>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-11.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/static-3.jpg" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>Rainbow Style</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.05 ETH<span>1/11</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>97</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-12.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/static-4.jpg" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>Two Tigers</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.02 ETH<span>1/15</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>73</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-9.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/anim-4.webp" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>The Truth</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.06 ETH<span>1/20</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>26</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="de_countdown" data-year="2021" data-month="9" data-day="20"
                                        data-hour="8"></div>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-2.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/anim-2.webp" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>Running Puppets</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.03 ETH<span>1/24</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>45</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-item">
                                <div class="nft__item">
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-3.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="nft__item_wrap">
                                        <a href="item-details.html">
                                            <img src="images/items/anim-1.webp" class="lazy nft__item_preview" alt="">
                                        </a>
                                    </div>
                                    <div class="nft__item_info">
                                        <a href="item-details.html">
                                            <h4>USA Wordmation</h4>
                                        </a>
                                        <div class="nft__item_price">
                                            0.09 ETH<span>1/25</span>
                                        </div>
                                        <div class="nft__item_action">
                                            <a href="#">Place a bid</a>
                                        </div>
                                        <div class="nft__item_like">
                                            <i class="fa fa-heart"></i><span>76</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
            <section id="section-packages" class="no-bottom">
   <div class="container py-5">
  <div class="row">
       <div class="col-lg-12">
                            <div class="text-center">
                                <h2>Memberships</h2>
                                <div class="small-border bg-color-2"></div>
                            </div>
                        </div>
  </div>
  <div class="row justify-content-center g-4">  <?php

$count = 0;
if ($memberresult) {
  while (($membershipdata = mysqli_fetch_assoc($memberresult)) && $count < 3) {
    $count++;

    // Extract string and numeric value from plan_name
    preg_match('/^(.*?)(?:\s*[-:]?\s*\$?(\d+(?:\.\d+)?))?$/', $membershipdata['plan_name'], $matches);
    $planTitle = isset($matches[1]) ? trim($matches[1]) : $membershipdata['plan_name'];
    $planRate = isset($matches[2]) ? $matches[2] : $membershipdata['plan_rate']; // fallback to DB plan_rate
?>
    <div class="col-md-4 mb-4">
      <div class="card h-100" style="max-width: max-content; border: none; box-shadow: none;">
        <div class="card-body text-start">
          <h2 class="card-title" style="font-weight: 1000">
            <?php echo htmlspecialchars($planTitle); ?>
          </h2>
          

          <div class="pricesection mt-4">
            <h4 class="price" style="white-space: nowrap;">
              <span style="font-family: serif; font-size: 50px;">
                <b>$<?php echo htmlspecialchars($planRate);?></b>
              </span><span style="font-size: 20px;">/mo</span>
            </h4>
            <p class="mt-3 d-inline-block" style="background-color: rgb(230, 230, 76); color: grey;">
              This plan is a perfect choice for you.
            </p>
            <p class="text-muted" style="margin-top: -10px;">
              Pay now and enjoy uninterrupted service.
            </p>
            <a href="membershipdetail.php?id=<?php echo $membershipdata['id']; ?>" 
               class="btn btn-outline-dark mt-2 membership-buy-btn" 
               style="padding-left: 138px; padding-right: 138px; padding-top: 10px; padding-bottom: 10px; border: 2px solid; display: block; text-align: center;">
               <b>Buy Now</b>
            </a>
          </div>

          <ul class="list-unstyled mt-4 fw-normal membership-feature-list">
            <li><i class="fas fas fa-wallet"></i>Withdrawal Fee: $<?php echo htmlspecialchars($membershipdata['withdraw_fee']); ?> </li>
            <li><i class="fas fa-globe"></i> Team Visibility: <?php echo htmlspecialchars($membershipdata['member_detail']); ?></li>
            <li><i class="fas fa-hdd"></i>Available:<?php echo htmlspecialchars($membershipdata['withdraw_capital']); ?></li>
            <li><i class="fas fa-undo"></i>Withdraw:  <?php echo htmlspecialchars($membershipdata['withdraw_processing_time']); ?></li>
          </ul>
        </div>
      </div>
    </div>
<?php
  }
}
?>

  </div>
</div>

</section>
<section id="section-staking" class="no-top" style="padding-bottom: 60px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <h2 style="color: var(--primary-color);">Staking Plans</h2>
                    <div class="small-border"></div>
                </div>
            </div>
            
            <?php
            // Database Connection check
            if(!isset($conn)) { include 'config.php'; }

            $sqlStake = "SELECT * FROM staking_packages WHERE status='active' ORDER BY min_amount ASC";
            $resultStake = $conn->query($sqlStake);

            if ($resultStake->num_rows > 0) {
                while($pkg = $resultStake->fetch_assoc()) {
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="pricing-s1 mb30" style="border: 1px solid #eee; border-radius: 10px; overflow: hidden; height: 100%;">
                    <div class="top" style="background: #f8f9fa; padding: 20px; text-align: center;">
                        <h3 style="margin: 0; color: #333;"><?php echo htmlspecialchars($pkg['package_name']); ?></h3>
                        <p class="price" style="font-size: 24px; margin-top: 10px; color: var(--accent-orange); font-weight: bold;">
                            <span class="currency">$</span>
                            <?php echo number_format($pkg['min_amount']); ?>
                        </p>
                        <p class="text-muted small">Capital Locked Amount</p>
                    </div>
                    <div class="bottom" style="padding: 20px;">
                        <ul style="list-style: none; padding: 0; margin-bottom: 20px;">
                            <li style="border-bottom: 1px dashed #ddd; padding: 8px 0; display:flex; justify-content:space-between;">
                                <span><i class="fas fa-chart-line text-success"></i> Daily Profit:</span>
                                <strong><?php echo $pkg['daily_profit_percentage']; ?>%</strong>
                            </li>
                            <li style="border-bottom: 1px dashed #ddd; padding: 8px 0; display:flex; justify-content:space-between;">
                                <span><i class="fas fa-clock text-primary"></i> Duration:</span>
                                <strong><?php echo $pkg['duration_days']; ?> Days</strong>
                            </li>
                            <li style="border-bottom: 1px dashed #ddd; padding: 8px 0; display:flex; justify-content:space-between;">
                                <span><i class="fas fa-money-bill-wave text-warning"></i> Total Return:</span>
                                <strong>Capital + Profit</strong>
                            </li>
                        </ul>

                        <form action="process_stake.php" method="POST">
                            <input type="hidden" name="package_id" value="<?php echo $pkg['id']; ?>">
                            <button type="submit" class="btn-main btn-fullwidth" style="width: 100%; cursor: pointer; border-radius: 5px;">
                                Invest Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center"><p>No staking plans available.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

            <section id="section-popular" class="pb-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h2>Top Sellers</h2>
                                <div class="small-border bg-color-2"></div>
                            </div>
                        </div>
                        <div class="col-md-12 wow fadeIn">
                            <ol class="author_list">
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-1.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Monica Lucas</a>
                                        <span>3.2 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-2.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Mamie Barnett</a>
                                        <span>2.8 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-3.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Nicholas Daniels</a>
                                        <span>2.5 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-4.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Lori Hart</a>
                                        <span>2.2 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-5.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Jimmy Wright</a>
                                        <span>1.9 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-6.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Karla Sharp</a>
                                        <span>1.6 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-7.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Gayle Hicks</a>
                                        <span>1.5 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-8.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Claude Banks</a>
                                        <span>1.3 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-9.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Franklin Greer</a>
                                        <span>0.9 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-10.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Stacy Long</a>
                                        <span>0.8 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-11.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Ida Chapman</a>
                                        <span>0.6 ETH</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="author_list_pp">
                                        <a href="author.html">
                                            <img class="lazy" src="images/author/author-12.jpg" alt="">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                    <div class="author_list_info">
                                        <a href="author.html">Fred Ryan</a>
                                        <span>0.5 eth</span>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section id="section-category" class="no-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h2>Browse by category</h2>
                                <div class="small-border bg-color-2"></div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".1s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-image"></i>
                                <span>Art</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".2s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-music"></i>
                                <span>Music</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".3s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-search"></i>
                                <span>Domain Names</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".4s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-globe"></i>
                                <span>Virtual Worlds</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".5s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-vcard"></i>
                                <span>Trading Cards</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-sm-30 wow fadeInRight" data-wow-delay=".6s">
                            <a href='explore.html' class="icon-box style-2 rounded">
                                <i class="fa fa-th"></i>
                                <span>Collectibles</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <a href="#" id="back-to-top"></a>
        <?php include 'Assets/footer.php'; ?>

        <div class="modal fade" id="tokenModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">

                    <div class="position-relative text-center" style="width: 350px;">

                        <img src="images/wallet/Token.png" alt="Token" class="img-fluid w-100 mb-5 "
                            style="border-radius: 100px;">

                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                            data-bs-dismiss="modal"></button>

                        <div class="position-absolute top-50 start-50 translate-middle w-100 px-5 pt-5 text-start">
                            <h5><span>Congratulations!</span></h5>
                            <p class="fw-bold text-dark">You are eligible to receive tokens</p>
                            <div class="text-center mt-5 pt-4">
                                <p class="text-dark btn-claim-text mb-1">Tap the button to view the number of tokens</p>
                                <button class="btn btn-claim mt-0">Claim your tokens</button>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
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
    <script>
        const tokenModal = new bootstrap.Modal(document.getElementById('tokenModal'));

        // Show after 10 seconds
        setTimeout(() => {
            tokenModal.show();
        }, 10000);

        // Optional: Trigger manually via button
        document.getElementById('showPopupBtn')?.addEventListener('click', () => {
            tokenModal.show();
        });
    </script>
    
    
</body>
</html>