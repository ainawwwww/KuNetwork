<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php?error=" . urlencode("Please log in to withdraw."));
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch available balance for the logged-in user
$sqlWallet = "SELECT available_balance FROM user_wallets WHERE user_id = ? LIMIT 1";
$stmt = $conn->prepare($sqlWallet);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wallet = $result->fetch_assoc();
$stmt->close();

$available_balance = isset($wallet['available_balance']) ? number_format($wallet['available_balance'], 2) : "0.00";
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
        /* == Withdrawal Page Styles - V4  == */

        .withdraw-section-v4 {

            background: linear-gradient(170deg, #FFF0E5 0%, #FDFBFB 60%, #FFF5F0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 4rem;
            padding-bottom: 4rem;
        }

        .withdraw-card-v4 {
            background-color: #FFFFFF;
            border-radius: var(--border-radius-xl) !important;
            border: none !important;
            box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.1), 0 5px 15px -5px rgba(0, 0, 0, 0.05) !important;
            position: relative;
            overflow: hidden;
            border-radius: 10px !important;
            transition: all 0.3s ease-in-out;
        }

        .withdraw-card-v4:hover {
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.15), 0 8px 20px -6px rgba(0, 0, 0, 0.06) !important;
            transform: translateY(-5px);
        }


        .withdraw-card-header-shape {
            height: 100px;

            background: linear-gradient(135deg, #f47656 0%, #0cace7 100%);

            border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
            margin-bottom: -50px;
            position: relative;
            z-index: 1;
        }

        .withdraw-card-v4 .card-body {
            position: relative;
            z-index: 2;
            background-color: transparent;
            padding-top: 1.5rem !important;
        }

        .withdraw-icon-v4 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #FFFFFF;
            color: #f47656;

            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            margin-top: -40px;
            font-size: 2.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 4px solid #FFFFFF;
            position: relative;
            z-index: 3;
        }

        .withdraw-title-v4 {
            font-weight: 700;
            color: var(--text-heading);
            font-size: 1.75rem;
            margin-top: 1rem;
        }

        .withdraw-form-label-v4 {
            color: #f47656;

            font-size: 0.85rem;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .withdraw-input-wrapper-v4 {
            position: relative;
        }

        .withdraw-input-prefix-v4 {
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #E2E8F0;
            color: #718096;
            border-radius: 0 !important;
            padding: 0 0.75rem 0 0.25rem;
            transition: border-color 0.2s ease;
        }

        .withdraw-input-prefix-v4 i {
            line-height: 1;
            font-size: 1rem;
        }

        .withdraw-form-control-v4 {
            border: none;
            border-bottom: 2px solid #E2E8F0;
            border-radius: 0 !important;
            padding: 0.85rem 0.5rem;
            font-size: 1.1rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.15s ease-in-out;
            background-color: transparent !important;
            box-shadow: none !important;
            position: relative;
            z-index: 1;
        }

        .withdraw-form-control-v4:focus {
            border-color: #f47656;

            box-shadow: none !important;
            background-color: transparent !important;
            color: #2d3748;
        }


        .withdraw-form-control-v4:focus+.withdraw-placeholder-label,
        .withdraw-form-control-v4:not(:placeholder-shown)+.withdraw-placeholder-label {
            transform: translateY(-110%) scale(0.85);
            color: #f47656;

            background-color: #FFFFFF;

            padding: 0 0.25rem;
        }

        .withdraw-placeholder-label {
            position: absolute;
            top: 50%;
            left: 40px;

            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
            pointer-events: none;
            transition: all 0.2s ease-out;
            z-index: 0;
            background-color: transparent;
            white-space: nowrap;
        }

        .withdraw-form-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: right;
        }

        .btn-withdraw-v4 {

            background: linear-gradient(135deg, #0cace7, #0a98c4);

            color: #FFFFFF !important;
            border: none;
            border-radius: 50px !important;
            padding: 0.9rem 1.5rem;
            font-weight: 700;

            font-size: 1.1rem;
            transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);

            box-shadow: 0 6px 15px -3px rgba(12, 172, 231, 0.5);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .btn-withdraw-v4 .btn-text {
            transition: transform 0.3s ease;
        }

        .btn-withdraw-v4 .btn-icon {
            position: absolute;
            right: -40px;
            opacity: 0;
            transition: all 0.35s ease;
            font-size: 1.1em;
        }

        .btn-withdraw-v4:hover,
        .btn-withdraw-v4:focus {
            transform: translateY(-4px) scale(1.01);

            box-shadow: 0 10px 20px -5px rgba(12, 172, 231, 0.6);
            background: linear-gradient(135deg, #0a98c4, #0cace7);

            color: #FFFFFF !important;
        }


        .btn-withdraw-v4:hover .btn-text {
            transform: translateX(-18px);

        }

        .btn-withdraw-v4:hover .btn-icon {
            right: 22px;

            opacity: 1;
        }

        .withdraw-footer-v4 {
            background-color: transparent !important;
            border-top: 1px solid var(--border-color-light) !important;
            font-size: 0.85rem;
            color: #718096 !important;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .withdraw-footer-v4 i {
            margin-right: 0.3rem;
            color: #718096;
        }


        .form-control::placeholder {
            color: #a0aec0;
            opacity: 1;
        }

        /* == End of Withdrawal Page Styles == */
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
                                <h1>Withdraw</h1>
                                <p>Anim pariatur cliche reprehenderit</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->

            <section aria-label="section" class="withdraw-section-v4 py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">




                            <div class="card withdraw-card-v4 shadow-xl border-0 rounded-xl overflow-hidden">

                                <div class="withdraw-card-header-shape"></div>

                                <!-- Card Body -->
                                <div class="card-body p-4 p-md-5 position-relative">

                                    <div class="withdraw-icon-v4 text-center mb-4">
                                        <i class="fas fa-money-bill"></i>
                                    </div>

                                    <h2 class="text-center mb-4 withdraw-title-v4">Withdraw Funds</h2>


                                    <form method="POST" action="withdraw.php" class="withdraw-form-v4">

                                        <div class="mb-4 position-relative">
                                            <label for="amount"
                                                class="form-label withdraw-form-label-v4 fw-medium mb-2">Withdrawal
                                                Amount (USD)</label>
                                            <div class="input-group input-group-lg withdraw-input-wrapper-v4">
                                                <span class="input-group-text withdraw-input-prefix-v4"><i
                                                        class="fas fa-dollar-sign fa-sm"></i></span>
                                                <input type="number" step="0.01" max="50" name="amount" id="amount"
                                                    class="form-control withdraw-form-control-v4" required
                                                    placeholder=" " />

                                                <label for="amount" class="withdraw-placeholder-label">Max:
                                                    50.00</label>
                                            </div>
                                           <div class="form-text withdraw-form-text mt-2">Available Balance: ₹ <?php echo $available_balance; ?></div>
                                        </div>

                                        <!-- Submit Button with Gradient -->
                                        <div class="d-grid mt-5">
                                            <button type="submit" name="submit" class="btn btn-withdraw-v4 btn-lg">
                                                <span class="btn-text">Submit Request</span>
                                                <span class="btn-icon"><i class="fas fa-paper-plane"></i></span>
                                                <!-- Changed icon -->
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer withdraw-footer-v4 text-center text-muted py-3">
                                    <i class="fas fa-hourglass-half fa-xs me-1"></i> Request submitted. Awaiting admin
                                    approval.
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
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0">
                <!-- Modal Header with Gradient -->
                <div id="modalHeader" class="modal-header text-white rounded-top-4 px-4 py-3"
                    style="background: linear-gradient(135deg, #0cace7, #f47656) !important;">
                    <h5 class="modal-title fw-bold fs-4" id="statusModalLabel">
                        Withdrawal Request Submitted 📨
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body text-center py-4 px-5">
                    <p class="fs-5 fw-semibold text-dark" id="modalMessage">
                        Your withdrawal request has been submitted. It will be processed as soon as the admin approves
                        it.
                    </p>
                </div>
                <!-- Modal Footer with Gradient Button -->
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn text-white px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal"
                        style="background:#f47656;">
                        Okay, Got It
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript Files
    ================================================== -->
    <!-- <script src="script.js"></script> -->
    <!-- <script src="registration.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/owl.carousel.js"></script>
    <!-- <script src="js/validation.js"></script> -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/enquire.min.js"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script src="js/jquery.lazy.min.js"></script>
    <script src="js/jquery.lazy.plugins.min.js"></script>
    <script src="js/designesia.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage || errorMessage) {
            const modalMessage = document.getElementById("modalMessage");
            const modalTitle = document.getElementById("statusModalLabel");
            const modalHeader = document.getElementById("modalHeader");

            if (successMessage) {
                modalMessage.textContent = "Your withdrawal request has been submitted. It will be processed as soon as the admin approves it.";
                modalTitle.textContent = "Withdrawal Request Submitted 📨";
                modalHeader.style.backgroundColor = "#0cace7";
            } else {
                modalMessage.textContent = errorMessage;
                modalTitle.textContent = "Withdrawal Failed ";
                modalHeader.style.backgroundColor = "#0cace7";
            }

            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            statusModal.show();

            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

</body>

</html>