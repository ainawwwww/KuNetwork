<?php
// claimbonus.php
include 'config.php';
session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please log in to claim daily bonus."));
    
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$remaining = 0;
$last_claim = null;

// Fetch latest created_at from bonus_history for this user
if (isset($conn)) {
    $sql = "SELECT created_at FROM bonus_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $last_claim = $row['created_at'];
            $elapsed = time() - strtotime($last_claim);
            $remaining = max(0, 24*3600 - intval($elapsed));
        } else {
            // never claimed -> remaining = 0 (available now)
            $remaining = 0;
            $last_claim = null;
        }
        $stmt->close();
    } else {
        // on prepare error, default to available
        $remaining = 0;
    }
} else {
    // no DB connection found
    $remaining = 0;
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
        /* == Claim Bonus Page Styles - V2 (Realistic Icon Colors & Orange Button) == */

        .claim-bonus-section {
            background: linear-gradient(170deg, rgba(244, 118, 86, 0.03) 0%, #F7FAFC 50%, rgba(12, 172, 231, 0.05) 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .claim-bonus-card {
            background-color: var(--bg-card);
            border-radius: var(--border-radius-xl) !important;
            border: none !important;
            box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.15), 0 8px 20px -8px rgba(0, 0, 0, 0.1) !important;
            position: relative;
            overflow: visible;
        }

        .confetti-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
            border-radius: inherit;
        }

        .claim-bonus-card .card-body {
            position: relative;
            z-index: 1;
        }

        .claim-bonus-icon-wrapper {
            margin-top: -65px;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .claim-bonus-icon {
            width: 100px;
            height: 100px;
            border-radius: 25px;
            background: linear-gradient(145deg, #0cace7 50%, #f47656 50%);
            color: var(--text-on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            box-shadow: 0 12px 25px -6px rgba(12, 172, 231, 0.3), 0 8px 15px -5px rgba(244, 118, 86, 0.2);
            border: 5px solid var(--bg-card);
            position: relative;
            overflow: hidden;
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
            animation: subtle-popup 2s ease-in-out infinite;
        }

        .claim-bonus-icon i {
            position: relative;
            z-index: 1;
            text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3);
        }

        @keyframes subtle-popup {
            0%,
            100% {
                transform: rotate(-5deg) scale(1);
                box-shadow: 0 12px 25px -6px rgba(12, 172, 231, 0.3), 0 8px 15px -5px rgba(244, 118, 86, 0.2);
            }
            50% {
                transform: rotate(-5deg) scale(1.05);
                box-shadow: 0 15px 30px -8px rgba(12, 172, 231, 0.4), 0 10px 20px -7px rgba(244, 118, 86, 0.3);
            }
        }

        .claim-bonus-icon-wrapper:hover .claim-bonus-icon {
            transform: rotate(5deg) scale(1.08);
            animation-play-state: paused;
        }

        .claim-bonus-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 100%;
            background: #f47656;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .claim-bonus-icon::after {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 15px;
            background: #f47656;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .claim-bonus-icon i {
            position: relative;
            z-index: 1;
            text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3);
            animation: fa-beat 1.5s ease infinite;
        }

        .claim-bonus-title {
            font-weight: 800;
            color: #0cace7;
            font-size: 2.2rem;
            margin-top: 0.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .claim-bonus-subtitle {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .bonus-btn {
            background: linear-gradient(135deg, #f47656, #e68062);
            color: #FFFFFF !important;
            border: none;
            border-radius: 50px !important;
            padding: 1rem 2.5rem;
            font-weight: 700;
            font-size: 1.15rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 18px -4px rgba(244, 118, 86, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .bonus-btn .btn-text {
            transition: transform 0.3s ease;
        }

        .bonus-btn .btn-icon {
            position: absolute;
            right: -40px;
            opacity: 0;
            transition: all 0.35s ease;
            font-size: 1.1em;
            color: rgba(255, 255, 255, 0.8);
        }

        .bonus-btn:hover,
        .bonus-btn:focus {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 12px 25px -6px rgba(244, 118, 86, 0.6);
            background: linear-gradient(135deg, #e68062, #f47656);
            color: #FFFFFF !important;
        }

        .bonus-btn:hover .btn-text {
            transform: translateX(-15px);
        }

        .bonus-btn:hover .btn-icon {
            right: 25px;
            opacity: 1;
        }

        .bonus-btn i {
            vertical-align: middle;
            margin-left: 0.5rem;
        }

        .next-bonus-info {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .next-bonus-info i {
            color: #0cace7;
        }

        .claim-bonus-footer {
            height: 8px;
            background: linear-gradient(90deg, #0cace7 0%, #f47656 100%);
            border-radius: 0 0 var(--border-radius-xl) var(--border-radius-xl);
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
                                <h1>Claim Daily Bonus</h1>
                                <p>Anim pariatur cliche reprehenderit</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->


            <section aria-label="section" class="claim-bonus-section py-5">
                <div class="container">
                    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
                        <div class="col-md-9 col-lg-7 col-xl-6">

                            <div
                                class="card claim-bonus-card text-center shadow-xl border-0 rounded-xl overflow-hidden">

                                <div class="confetti-background"></div> 

                                <div class="card-body p-4 p-md-5 position-relative">

                           
                                    <div class="claim-bonus-icon-wrapper mb-4">
                                        <div class="claim-bonus-icon">
                                            <i class="fas fa-gift"></i> 
                                        </div>
                                    </div>

                                    <h2 class="claim-bonus-title mb-2">Claim Your Daily Bonus!</h2>
                                    <p class="claim-bonus-subtitle text-muted mb-4">
                                        Don't miss out on your free daily earnings. Click below!
                                    </p>

                                    <form method="POST" action="claim_bonus.php" class="mt-4" id="claimForm">
                                        <div class="d-grid">
                                     
                                            <button type="submit" name="claim" id="claimBtn" class="btn bonus-btn btn-lg">
                                                <span class="btn-text">Claim Bonus Now</span>
                                                <span class="btn-icon"><i class="fas fa-coins"></i></span>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="next-bonus-info text-muted small mt-4">
                                        <i class="fas fa-clock fa-xs me-1"></i> Next bonus available in: <span
                                            class="fw-medium" id="bonus-timer">--</span>
                                    </div>
                                </div>

                                <div class="claim-bonus-footer"></div>
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
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div id="modalHeader" class="modal-header text-white rounded-top-4 px-4 py-3"
                    style="background: linear-gradient(135deg, #0cace7, #f47656);">
                    <h5 class="modal-title fw-bold fs-4" id="statusModalLabel">
                        Bonus Status 🎁
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-5 ">
                    <p class="fs-5 fw-semibold text-dark" id="modalMessage">
                        You've successfully claimed your daily bonus!
                    </p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn text-white px-4 py-2 rounded-3 fw-semibold"
                        style="background: linear-gradient(135deg, #f47656, #0cace7);" data-bs-dismiss="modal">
                        Awesome!
                    </button>
                </div>
            </div>
        </div>
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
    <!-- <script src="js/validation.js"></script> -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/enquire.min.js"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script src="js/jquery.lazy.min.js"></script>
    <script src="js/jquery.lazy.plugins.min.js"></script>
    <script src="js/designesia.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Pass server-side remaining seconds to JS -->
    <script>
        const serverRemainingSeconds = <?php echo intval($remaining); ?>;
        const serverLastClaim = <?php echo $last_claim ? json_encode($last_claim) : 'null'; ?>;

        function formatTime(sec) {
            if (sec <= 0) return 'Available now';
            const h = Math.floor(sec / 3600);
            const m = Math.floor((sec % 3600) / 60);
            const s = Math.floor(sec % 60);
            const parts = [];
            if (h > 0) parts.push(h + 'h');
            if (m > 0 || h > 0) parts.push(m + 'm');
            parts.push(s + 's');
            return parts.join(' ');
        }

        (function () {
            const timerEl = document.getElementById('bonus-timer');
            const claimBtn = document.getElementById('claimBtn');
            let remaining = parseInt(serverRemainingSeconds, 10) || 0;

            if (remaining > 0) {
                claimBtn.disabled = true;
                claimBtn.classList.add('disabled');
            } else {
                claimBtn.disabled = false;
                claimBtn.classList.remove('disabled');
            }

            timerEl.textContent = formatTime(remaining);

            if (remaining > 0) {
                const countdownInterval = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(countdownInterval);
                        timerEl.textContent = "Available now";
                        claimBtn.disabled = false;
                        claimBtn.classList.remove('disabled');
                    } else {
                        timerEl.textContent = formatTime(remaining);
                    }
                }, 1000);
            }

            document.getElementById('claimForm').addEventListener('submit', function (e) {
                if (claimBtn.disabled) {
                    e.preventDefault();
                    alert('Bonus not available yet. Next bonus after: ' + formatTime(remaining));
                    return false;
                }
                // allow submit; claim_bonus.php will validate server-side
            });

        })();
    </script>

    <script>
        // existing modal-success/error handling preserved
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage || errorMessage) {
            const modalMessage = document.getElementById("modalMessage");
            const modalTitle = document.getElementById("statusModalLabel");
            const modalHeader = document.getElementById("modalHeader");
            const modalButton = document.querySelector('.modal-footer button');

            if (successMessage) {
                modalMessage.textContent = successMessage;
                modalTitle.textContent = "Bonus Claimed";
                modalHeader.style.background = "linear-gradient(135deg, #0cace7, #f47656)";
                modalButton.textContent = "Awesome!";

                let duration = 1 * 1000;
                let animationEnd = Date.now() + duration;
                let defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                let interval = setInterval(function () {
                    let timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }

                    confetti({
                        ...defaults,
                        particleCount: 50,
                        origin: {
                            x: randomInRange(0.1, 0.9),
                            y: Math.random() - 0.2
                        }
                    });
                }, 200);
            } else {
                modalMessage.textContent = errorMessage;
                modalTitle.textContent = "Error Claiming Bonus";
                modalHeader.style.background = "linear-gradient(135deg, #f47656, #0cace7)";
                modalButton.textContent = "Got It";
            }

            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            statusModal.show();

            window.history.replaceState({}, document.title, window.location.pathname);
        }

    </script>

</body>

</html>