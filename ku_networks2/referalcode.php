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
        /* == Referral Section Styles - REFINED == */

        :root {
            --primary-blue: #0cace7;

            --accent-orange: #f47656;

            --primary-blue-dark: #0a8db8;

            --accent-orange-dark: #d96344;




            --text-on-primary: #FFFFFF;
            --text-on-accent: #FFFFFF;


        }



        .referral-section-card {
            border-radius: var(--border-radius-xl);
            border: 1px solid var(--border-color);
            background: linear-gradient(160deg, var(--bg-card) 95%, rgba(12, 172, 231, 0.05) 100%);

            overflow: hidden;
            transition: all 0.3s ease-in-out;
            box-shadow: var(--shadow-md);
        }

        .referral-section-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-6px);
            border-color: rgba(12, 172, 231, 0.5);
        }

        .referral-section-card .card-body {
            position: relative;
            padding: 2.5rem;
        }

        .referral-icon-container {
            width: 85px;
            height: 85px;
            border-radius: 50%;

            background: linear-gradient(145deg, var(--primary-blue), var(--primary-blue-dark));

            color: var(--text-on-primary);

            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            font-size: 2.8rem;

            box-shadow: 0 10px 20px -5px rgba(12, 172, 231, 0.45), 0 6px 6px -3px rgba(12, 172, 231, 0.25);
            border: 4px solid var(--bg-card);
            margin-bottom: 1.75rem;
        }

        .referral-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--text-heading);
            margin-top: 0;
            margin-bottom: 0.75rem;
        }

        .referral-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .btn-generate-referral {

            background: linear-gradient(145deg, var(--accent-orange), var(--accent-orange-dark));

            color: var(--text-on-accent);

            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;

            box-shadow: 0 6px 12px -3px rgba(244, 118, 86, 0.4);
        }

        .btn-generate-referral:hover,
        .btn-generate-referral:focus {
            transform: translateY(-4px) scale(1.02);

            box-shadow: 0 10px 20px -5px rgba(244, 118, 86, 0.5);

            background: linear-gradient(145deg, var(--accent-orange-dark), var(--accent-orange));

            color: var(--text-on-accent);
        }

        .btn-generate-referral i {
            font-size: 1em;
            vertical-align: middle;
            margin-right: 0.5rem;
        }

        #referralCodeResult {
            min-height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease-in-out;
            margin-top: 2rem;
        }

        .referral-result-container {
            background-color: var(--bg-card-alt);

            border: 2px dashed var(--primary-blue);
            font-weight: 600;
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.25rem;

            color: var(--primary-blue-dark);
            word-break: break-all;
            border-radius: var(--border-radius-md);
            box-shadow: none;
            padding: 1rem 1.5rem;
            text-align: center;
            width: 100%;
            transition: all 0.3s ease;
        }

        .referral-result-container.has-code {

            background-color: rgba(12, 172, 231, 0.08);

            border-color: var(--primary-blue);
            border-style: solid;
            color: var(--text-heading);
        }

        .referral-result-container .placeholder-text {
            font-family: var(--font-family-sans-serif);
            font-style: normal;
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* == End of Referral Section Styles == */


        /* Widen the referral result box */
#referralCodeResult.referral-result-container {
    max-width: 100%;
    padding-right: 3.5rem !important; /* space for copy button */
    position: relative;
    
}

/* Copy button inside referral result */
.copy-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-blue);
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s ease;
    box-shadow: 0 4px 10px rgba(12, 172, 231, 0.25);
}

.copy-btn:hover {
    background: var(--primary-blue-dark);
    transform: translateY(-50%) scale(1.05);
}

    </style>


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
                                <h1>Referral Code</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->

            <section class="py-4 py-lg-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-md-9">
                            <div class="card referral-section-card text-center shadow-lg">
                                <div class="card-body p-4 p-md-5">
                                    <div class="referral-icon-container mb-4">
                                        <i class="fas fa-share-nodes"></i>
                                    </div>
                                    <h2 class="referral-title mb-3">Generate Your Referral Link</h2>
                                    <p class="referral-subtitle text-muted mb-4">
                                        Share your unique referral link with friends and earn rewards when they join!
                                    </p>
                                    <button id="generateReferralBtn" class="btn btn-generate-referral w-100 py-2 mb-3">
                                        <i class="fas fa-gift me-2"></i> Generate My Link
                                    </button>
                                    <div id="referralCodeResult" class="mt-4 referral-result-container p-3 rounded">
                                        <!-- Referral code/link will appear here -->
                                        <span class="placeholder-text">Your referral link will appear here...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


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
        <script>
            $('#generateReferralBtn').click(function () {
                var button = $(this);
                button.prop('disabled', true);

                $.ajax({
                    url: 'generate_referral_code.php',
                    type: 'POST',
                    dataType: 'text',
                    success: function (response) {

             if (response.trim()) {

    $('#referralCodeResult').html(`
        <strong style="color: var(--accent-orange);">Your Referral Link:</strong><br>
        <span id="referralLink">${response.trim()}</span>

        <button class="copy-btn" id="copyReferral">
            <i class="fas fa-copy"></i>
        </button>
    `);

    $('#referralCodeResult').addClass('has-code').show();
}
 else {
    $('#referralCodeResult').html(' Failed to generate referral link.');
    $('#referralCodeResult').show();
}

                        button.prop('disabled', false);
                    },
                    error: function (xhr, status, error) {

                        if (xhr.status === 401) {
                            $('#referralCodeResult').html(' User not logged in.');
                            $('#referralCodeResult').show();

                            setTimeout(function () {
                                window.location.href = 'login.html';
                            }, 2000);
                        } else {
                            $('#referralCodeResult').html('Something went wrong. Error: ' + error);
                            $('#referralCodeResult').show();
                        }

                        button.prop('disabled', false);
                    }
                });
            });

            
        </script>


</body>

</html>