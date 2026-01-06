<?php
session_start();
require 'config.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=" . urlencode("Please log in to view your certificate."));
    exit();
}

$loggedInUserIdentifier = $_SESSION['user_id'];

$userName = "Guest User"; // Default
$userRank = "No Rank";   // Default

if ($conn instanceof mysqli) {
    // Fetch User Name from users table
    $sqlUserName = "SELECT name FROM users WHERE id = ?";
    if ($stmtName = $conn->prepare($sqlUserName)) {
        $stmtName->bind_param("i", $loggedInUserIdentifier);
        $stmtName->execute();
        $resultName = $stmtName->get_result();
        if ($userData = $resultName->fetch_assoc()) {
            
            $userName = $userData['name'];
        }
        $stmtName->close();
    } else {
        error_log("Certificate: User name query prep failed: " . $conn->error);
    }

    // Fetch User Rank from rank_assignment_summary table
    $sqlUserRank = "SELECT assigned_rank FROM rank_assignment_summary WHERE user_id = ?";
    if ($stmtRank = $conn->prepare($sqlUserRank)) {
        $stmtRank->bind_param("i", $loggedInUserIdentifier);
        $stmtRank->execute();
        $resultRank = $stmtRank->get_result();
        if ($rankData = $resultRank->fetch_assoc()) {
            if (!empty($rankData['assigned_rank']) && $rankData['assigned_rank'] !== "No Rank") {
                $userRank = $rankData['assigned_rank'];
            } else {
                // If rank is "No Rank" or empty, redirect or show error,
                // as they shouldn't be here if the button was conditional
                error_log("User ID $loggedInUserIdentifier accessed certificate page with 'No Rank'.");
                // Optionally, redirect or display a message:
                // header("Location: account.php?error=" . urlencode("Certificate not available for your current rank."));
                // exit();
                // For now, we'll let it display "No Rank" if they somehow get here.
            }
        } else {
             error_log("Certificate: Rank not found for user ID $loggedInUserIdentifier. Defaulting to 'No Rank'.");
        }
        $stmtRank->close();
    } else {
        error_log("Certificate: User rank query prep failed: " . $conn->error);
    }
} else {
    error_log("Certificate: DB connection error.");
    // Handle error, maybe redirect
}


// Static Certificate Details (can be customized further if needed)
$courseName = "Excellence in Platform Engagement";
$completionDate = date("F j, Y"); // Or a fixed date, or fetched if course completion is tracked
$certificateID = "UC-" . date("Ymd") . "-" . $loggedInUserIdentifier . "-" . substr(strtoupper(md5(uniqid(rand(), true))), 0, 4); // More unique ID
$issuingAuthority = "kunetworks.com";
$signerName = "Dr. Authorizing Person";

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Certificate Specific Styles - Enhanced Design */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');

        :root {
            --cert-bg: #fdf6e3;
            /* Classic creamy paper */
            --cert-border-outer: #8B4513;
            /* SaddleBrown - a rich brown */
            --cert-border-inner: #DAA520;
            /* Goldenrod */
            --cert-accent-blue: #003366;
            /* Dark navy blue */
            --cert-text-main: #3D2B1F;
            /* Dark coffee brown for text */
            --cert-text-light: #5a4d41;
            --cert-highlight-name: var(--cert-accent-blue);
            /* Recipient name color */

            /* Original Theme Colors (can be used for buttons etc. if needed) */
            --theme-blue: #0cace7;
            --theme-orange: #f47656;
        }

        #content .certificate-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
            margin: 100px 0px;
            /* Reduced padding a bit */
            width: 100%;
            /* Light grey backdrop for the page */
        }

        .certificate-container {
            margin: 0 auto;
            width: 880px;
            /* Slightly wider for landscape feel */
            min-height: 620px;
            /* Adjusted height for proportion */
            background-color: var(--cert-bg);
            /* Intricate Border Effect */
            border: 1px solid #c8bca8;
            /* Outermost subtle line */
            padding: 10px;
            /* Padding for the border effect layers */
            box-shadow: 0 0 0 5px var(--cert-border-outer),
                /* Thick outer border */
                0 0 0 7px var(--cert-border-inner),
                /* Thin gold line */
                0 0 0 8px var(--cert-border-outer),
                /* Another brown line */
                0 0 15px rgba(0, 0, 0, 0.3);
            /* Soft outer shadow */
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-family: 'Merriweather', serif;
            /* Default serif for certificate */
            color: var(--cert-text-main);
            margin: 0 auto;
            /* Fallback centering */
        }

        /* Inner decorative border */
        .certificate-inner-frame {
            border: 1.5px dashed var(--cert-border-inner);
            padding: 25px 35px;
            /* Spacing inside the dashed border */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            /* For positioning elements like flourishes if added */
        }


        .certificate-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-logo-placeholder {
            /* For KU Network Logo at top */
            position: absolute;
            top: 30px;
            /* Adjust based on inner-frame padding */
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            /* Adjust size as needed */
            height: auto;
            opacity: 0.8;
        }

        .top-logo-placeholder img {
            max-width: 100%;
            height: auto;
        }

        .certificate-header h1 {
            font-family: 'Playfair Display', serif;
            color: var(--cert-accent-blue);
            font-size: 38px;
            /* Increased size */
            margin: 60px 0 5px 0;
            /* More space above due to logo */
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 900;
            border-bottom: 2px solid var(--cert-border-inner);
            display: inline-block;
            padding-bottom: 10px;
        }

        .certificate-body {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 15px 0;
        }

        .recipient-label {
            font-size: 18px;
            color: var(--cert-text-light);
            margin-bottom: 8px;
            font-family: 'Lato', sans-serif;
            font-style: italic;
        }

        .recipient-name {
            font-family: 'Dancing Script', cursive;
            /* Elegant script for name */
            font-size: 52px;
            /* Prominent name */
            color: var(--cert-highlight-name);
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 10px;
            display: inline-block;
            line-height: 1.1;
        }

        .user-rank-header {
            font-size: 16px;
            color: var(--cert-text-light);
            margin-top: 15px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'Lato', sans-serif;
        }

        .user-rank-value {
            font-family: 'Merriweather', serif;
            font-size: 26px;
            color: var(--cert-text-main);
            font-weight: 700;
            margin-bottom: 25px;
        }

        .achievement-text {
            font-size: 17px;
            color: var(--cert-text-main);
            line-height: 1.8;
            margin-bottom: 10px;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .course-name {
            font-size: 28px;
            /* More prominent */
            font-weight: 700;
            color: var(--cert-accent-blue);
            margin-bottom: 30px;
            font-family: 'Playfair Display', serif;
        }

        /* Decorative Line */
        .decorative-line {
            width: 60%;
            height: 1px;
            background-color: var(--cert-border-inner);
            margin: 15px auto 25px auto;
            opacity: 0.7;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-around;
            /* Space out more */
            align-items: flex-end;
            padding-top: 20px;
            margin-top: auto;
        }

        .signature-block,
        .date-block {
            text-align: center;
            width: 40%;
            /* Adjust width */
        }

        .signature-line {
            border-bottom: 1.5px solid var(--cert-text-main);
            height: 50px;
            margin-bottom: 8px;
            font-family: 'Dancing Script', cursive;
            /* For placeholder signature text */
            font-size: 20px;
            color: #444;
            padding-top: 10px;
            line-height: 1;
        }

        .date-block .signature-line {
            /* For date */
            font-family: 'Merriweather', serif;
            font-style: italic;
            font-size: 17px;
            padding-top: 12px;
        }

        .signature-block p,
        .date-block p {
            font-size: 12px;
            /* Smaller for labels */
            color: var(--cert-text-light);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Lato', sans-serif;
        }

        .certificate-id {
            position: absolute;
            bottom: 5px;
            /* Inside the inner padding of certificate-container */
            left: 15px;
            font-size: 9px;
            color: #aaa;
            font-family: 'Lato', sans-serif;
        }

        .seal-placeholder {
            /* Enhanced Seal */
            position: absolute;
            bottom: 25px;
            /* Position relative to inner-frame */
            right: 35px;
            width: 90px;
            height: 90px;
            background: radial-gradient(ellipse at center, var(--cert-border-inner) 0%, #B8860B 100%);
            /* Gold gradient */
            border: 3px double var(--cert-bg);
            /* Paper color border for embossed feel */
            outline: 1.5px solid #805500;
            /* Darker gold outline */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #4a3000;
            /* Dark brown for text/icon on seal */
            text-align: center;
            border-radius: 50%;
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3), inset 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .seal-placeholder i.fa-award {
            font-size: 38px;
            color: var(--cert-accent-blue);
            /* Icon color on seal */
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        }

        .seal-placeholder .seal-text {
            position: absolute;
            width: 100%;
            height: 100%;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Example for circular text on seal - basic approach */
        .seal-placeholder .seal-text span {
            position: absolute;
            transform-origin: bottom center;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white !important;
                /* Override KU Network body bg for print */
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                /* Ensures backgrounds and colors print in Chrome/Safari */
                print-color-adjust: exact !important;
                /* Standard property */
            }

            #wrapper>header,
            #wrapper>footer,
            #back-to-top,
            .menu_side_area,
            #quick_search,
            #mainmenu {
                display: none !important;
                /* Hide header/footer of KU page */
            }

            #content {
                padding: 0 !important;
                margin: 0 !important;
            }

            #content .certificate-wrapper {
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                /* Override flex for print if it causes issues */
            }

            .certificate-container {
                box-shadow: none !important;
                margin: 20px auto !important;
                /* Center on print page with some margin */
                width: 95% !important;
                /* Adjust width for print if needed */
                height: auto !important;
                border: 10px solid var(--theme-blue) !important;
                /* Ensure border prints */
                outline: none !important;
                /* Outline may not print well, rely on border */
                border-radius: 0 !important;
                /* Often better for print */
            }

            .no-print {
                display: none !important;
            }

            /* Ensure colors print */
            .certificate-header h1,
            .recipient-name,
            .course-name,
            .logo-placeholder-bottom,
            .certificate-container::before,
            .certificate-container::after {
                -webkit-print-color-adjust: exact !important;
               print-color-adjust: exact !important;
            }
        }
    </style>

</head>

<body>
    <div id="wrapper">
        <!-- header begin -->
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex">
                            <div class="de-flex-col">
                                <div class="de-flex-col">
                                    <!-- logo begin -->
                                    <div id="logo">
                                        <a href="index.php">
                                            <img alt="" id="logo-k" class="logo" src="images/logo-k.png" />
                                            <img alt="" id="logo-k" class="logo-2" src="images/logo-k.png" />
                                        </a>
                                    </div>
                                    <!-- logo close -->
                                </div>
                                <div class="de-flex-col">
                                    <input id="quick_search" class="xs-hide" name="quick_search" placeholder="search item here..." type="text" />
                                </div>
                            </div>
                            <div class="de-flex-col header-col-mid">
                                <!-- mainmenu begin -->
                                <ul id="mainmenu">
                                    <li>
                                        <a href="index.php">Home<span></span></a>

                                    </li>
                                    <!-- <li>
                                        <a href="explore.html">Explore<span></span></a>
                                        <ul>
                                            <li><a href="explore.html">Explore</a></li>
                                            <li><a href="explore-2.html">Explore 2</a></li>
                                            <li><a href="collection.html">Collections</a></li>
                                            <li><a href="item-details.html">Item Details</a></li>
                                        </ul>
                                    </li> -->
                                    <li>
                                        <a href="#">Pages<span></span></a>
                                        <ul>
                                            <li><a href="account.html">Account</a></li>
                                            <!-- <li><a href="reserve.html">Reserve</a></li> -->
                                            <li><a href="wallet.html">Wallet</a></li>
                                            <li><a href="claimbonus.html">Claim Daily Bonus</a></li>
                                            <!-- <li><a href="create.html">Create</a></li>
                                            <li><a href="news.html">News</a></li> -->
                                            <li><a href="gallery.html">Gallery</a></li>
                                            <li><a href="balance_transfer.html">Balance Transfer</a></li>
                                            <li><a href="login.html">Login</a></li>
                                            <li> <a href="logout.php">Logout</a></li>
                                            <li> <a href="referalcode.html">Referral code</a></li>
                                            <li><a href="register.html">Register</a></li>
                                            <li><a href="contact.html">Contact Us</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="about.html">About Us<span></span></a>
                                    </li>
                                    <li>
                                        <a href="#">Elements<span></span></a>
                                        <ul>
                                            <!-- <li><a href="activity.html">Activity</a></li> -->

                                            <li><a href="pricing-table.html">Pricing Table</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="menu_side_area">
                                    <a href="wallet.html" class="btn-main"><i class="icon_wallet_alt"></i><span>Connect Wallet</span></a>
                                    <span id="menu-btn"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header close -->
       <!-- content begin -->
        <div class="no-bottom" id="content">
            <div id="top"></div>

            <div class="certificate-wrapper">
                <div class="certificate-container" id="certificateToPrint">
                    <div class="certificate-inner-frame">
                        <div class="top-logo-placeholder ">
                            <img src="images/logo-k.png" alt="KU Network Logo">
                        </div>
                        <div class="seal-placeholder">
                            <i class="fas fa-award"></i>
                        </div>

                        <div class="certificate-header my-5">
                            <h1>Certificate of Achievement</h1>
                        </div>

                        <div class="certificate-body">
                            <p class="recipient-label">This certificate is proudly presented to</p>
                            <p class="recipient-name"><?php echo htmlspecialchars($userName); ?></p> <!-- REAL USER NAME -->

                            <p class="user-rank-header">Awarded Rank</p>
                            <p class="user-rank-value"><?php echo htmlspecialchars($userRank); ?></p> <!-- REAL USER RANK -->

                            <div class="decorative-line"></div>

                            <p class="achievement-text">
                                For outstanding performance, dedication, and successful completion of the requirements for
                            </p>
                            <p class="course-name"><?php echo htmlspecialchars($courseName); ?></p>
                        </div>

                        <div class="certificate-footer">
                            <div class="date-block">
                                <div class="signature-line"><?php echo htmlspecialchars($completionDate); ?></div>
                                <p>Date of Issuance</p>
                            </div>
                            <div class="signature-block">
                                <div class="signature-line"><?php echo htmlspecialchars($signerName); ?></div>
                                <p><?php echo htmlspecialchars($issuingAuthority); ?> <br>Authorized Signatory</p>
                            </div>
                        </div>
                        <div class="certificate-id">Certificate ID: <?php echo htmlspecialchars($certificateID); ?></div>
                    </div>
                </div>
            </div>


            <div class="text-center my-4 action-buttons no-print"> <!-- Added no-print class -->
                <button onclick="window.print()" class="btn btn-lg btn-primary mx-2">
                    <i class="fas fa-print"></i> Print Certificate
                </button>
                <button onclick="downloadCertificateAsPDF(event)" class="btn btn-lg btn-success mx-2">
                    <i class="fas fa-file-pdf"></i> Download as PDF
                </button>
                 <a href="account.php" class="btn btn-lg btn-secondary mx-2">
                    <i class="fas fa-arrow-left"></i> Back to Account
                </a>
            </div>

        </div>
        <!-- content close -->


        <a href="#" id="back-to-top"></a>

        <!-- footer begin -->
        <footer class="footer-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Marketplace</h5>
                            <ul>
                                <li><a href="#">All NFTs</a></li>
                                <li><a href="#">Art</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Domain Names</a></li>
                                <li><a href="#">Virtual World</a></li>
                                <li><a href="#">Collectibles</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Resources</h5>
                            <ul>
                                <li><a href="#">Help Center</a></li>
                                <li><a href="#">Partners</a></li>
                                <li><a href="#">Suggestions</a></li>
                                <li><a href="#">Discord</a></li>
                                <li><a href="#">Docs</a></li>
                                <li><a href="#">Newsletter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Community</h5>
                            <ul>
                                <li><a href="#">Community</a></li>
                                <li><a href="#">Documentation</a></li>
                                <li><a href="#">Brand Assets</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Forum</a></li>
                                <li><a href="#">Mailing List</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-1">
                        <div class="widget">
                            <h5>Newsletter</h5>
                            <p>Signup for our newsletter to get the latest news in your inbox.</p>
                            <form action="blank.php" class="row form-dark" id="form_subscribe" method="post" name="form_subscribe">
                                <div class="col text-center">
                                    <input class="form-control" id="txt_subscribe" name="txt_subscribe" placeholder="enter your email" type="text" /> <a href="#" id="btn-subscribe"><i class="arrow_right bg-color-secondary"></i></a>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                            <div class="spacer-10"></div>
                            <small>Your email is safe with us. We don't spam.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="subfooter">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="de-flex">
                                <div class="de-flex-col">
                                    <a href="index.html">
                                        <img alt="" id="logo-k" class="logo-2" src="images/logo-k.png" /><span class="copy">©
                                            Copyright 2025 - KU Network</span> </a>
                                </div>
                                <div class="de-flex-col">
                                    <div class="social-icons">
                                        <a href="#"><i class="fa fa-facebook fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-twitter fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-linkedin fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-pinterest fa-lg"></i></a>
                                        <a href="#"><i class="fa fa-rss fa-lg"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer close -->

    </div>

    <!-- html2pdf.js for PDF Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() { 

            function downloadCertificateAsPDF(event) {
                const certificateElement = document.getElementById('certificateToPrint');

                if (!certificateElement) {
                    console.error("Error: Certificate element #certificateToPrint not found even after DOMContentLoaded!");
                    alert("Sorry, an error occurred: Could not find the certificate content.");
                    return;
                }

                const userNameForFile = <?php echo json_encode(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $userName)); ?>;
                const courseNameForFile = <?php echo json_encode(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $courseName)); ?>;

                const opt = {
                    margin: [0.5, 0.9165, 0.5, 0.9165],
                    filename: `Certificate-${userNameForFile}-${courseNameForFile}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    }, 
                    html2canvas: {
                        scale: 2, 
                        logging: false,
                        useCORS: true,
                        letterRendering: true,
                        scrollX: 0,
                        scrollY: 0,
                        width: certificateElement.scrollWidth,
                        height: certificateElement.scrollHeight
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'landscape'
                    },
                    pagebreak: {
                        mode: ['avoid-all', 'css', 'legacy']
                    } 
                };

                const downloadButton = event.target.closest('button');
                const originalButtonText = downloadButton.innerHTML;
                downloadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                downloadButton.disabled = true;

                html2pdf().from(certificateElement).set(opt).save()
                    .then(() => {
                        downloadButton.innerHTML = originalButtonText;
                        downloadButton.disabled = false;
                    })
                    .catch(err => {
                        console.error("Error generating PDF:", err);
                        downloadButton.innerHTML = originalButtonText;
                        downloadButton.disabled = false;
                        alert("Sorry, an error occurred while generating the PDF. Please check the console for details.");
                    });
            }


            window.downloadCertificateAsPDF = downloadCertificateAsPDF;

        }); 
    </script>

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