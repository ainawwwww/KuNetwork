<?php
$claim_bonus = isset($_GET['claim_bonus']) && $_GET['claim_bonus'] == 1;
if ($claim_bonus) {
    // Yahan aap modal ya message show kar sakte hain
    echo "<script>console.log('Claim bonus flag detected on deposit page');</script>";
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>KU Network</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16" />
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
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
        .deposit-section-v4 {
            

            background: linear-gradient(170deg, #F0F8FF 0%, #F7FAFC 60%, #e6f7ff 100%);

            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 4rem;
            padding-bottom: 4rem;
        }

        .deposit-card-v4 {
            background-color: #FFFFFF;
            border-radius: var(--border-radius-xl) !important;
            border: none !important;
            box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.1), 0 5px 15px -5px rgba(0, 0, 0, 0.05) !important;
            border-radius: 10px !important;
            position: relative;

        }


        .deposit-card-header-shape {
            height: 100px;

            background: linear-gradient(135deg, #0cace7 0%, #f47656 100%);

            border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;

            margin-bottom: -50px;

            position: relative;
            z-index: 1;
        }

        .deposit-card-v4 .card-body {
            position: relative;

            z-index: 2;
            background-color: transparent;

            padding-top: 1.5rem !important;

        }

        .deposit-icon-v4 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #FFFFFF;

            color: #0cace7;

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

        .deposit-title-v4 {
            font-weight: 700;
            color: var(--text-heading);
            font-size: 1.75rem;

            margin-top: 1rem;

        }

        .deposit-form-label-v4 {
            color: #0cace7;

            font-size: 0.85rem;
            font-weight: 600 !important;

            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .deposit-input-wrapper-v4 {
            position: relative;
        }

        .deposit-input-prefix-v4 {
            background-color: transparent;

            border: none;
            border-bottom: 2px solid #E2E8F0;

            color: #718096;
            border-radius: 0 !important;

            padding: 0 0.75rem 0 0.25rem;

            transition: border-color 0.2s ease;
        }

        .deposit-input-prefix-v4 i {
            line-height: 1;
            font-size: 1rem;

        }

        .deposit-form-control-v4 {
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

        .deposit-form-control-v4:focus {
            border-color: #0cace7;

            box-shadow: none !important;
            background-color: transparent !important;
            color: #2d3748;
        }


        .deposit-form-control-v4:focus+.deposit-placeholder-label,
        .deposit-form-control-v4:not(:placeholder-shown)+.deposit-placeholder-label {
            transform: translateY(-110%) scale(0.85);
            color: #0cace7;
            background-color: #FFFFFF;
            padding: 0 0.25rem;

        }


        .deposit-form-control-v4:focus {
            border-color: #0cace7;
        }


        .deposit-placeholder-label {
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


        .btn-deposit-v4 {
            background: linear-gradient(135deg, #f47656, #e68062);

            color: #FFFFFF !important;
            border: none;
            border-radius: 50px !important;
            padding: 0.9rem 1.5rem;

            font-weight: 700;

            font-size: 1.1rem;

            transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);

            box-shadow: 0 6px 15px -3px rgba(244, 118, 86, 0.5);

            letter-spacing: 0.5px;
            display: flex;

            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .btn-deposit-v4 .btn-text {
            transition: transform 0.3s ease;
        }

        .btn-deposit-v4 .btn-icon {
            position: absolute;
            right: -40px;

            opacity: 0;
            transition: all 0.35s ease;
            font-size: 1.1em;
        }

        .btn-deposit-v4:hover,
        .btn-deposit-v4:focus {
            transform: translateY(-4px) scale(1.01);

            box-shadow: 0 10px 20px -5px rgba(244, 118, 86, 0.6);
            background: linear-gradient(135deg, #e06a4b, #f47656);

            color: #FFFFFF !important;
        }


        .btn-deposit-v4:hover .btn-text {
            transform: translateX(-15px);
        }

        .btn-deposit-v4:hover .btn-icon {
            right: 20px;

            opacity: 1;
        }


        .deposit-footer-v4 {
            background-color: transparent !important;

            border-top: 1px solid var(--border-color-light) !important;

            font-size: 0.85rem;
            color: #718096 !important;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .deposit-footer-v4 i {
            margin-right: 0.3rem;
            color: #718096;
        }


        .form-control::placeholder {
            color: #a0aec0;
            opacity: 1;
        }

        /*  Product Modal  */

        /* Main Modal Content Styling */
        .product-modal-content {
            border-radius: 1.5rem !important;
            /* Softer, larger border radius */
            background: #f8f9fa;
            /* Light background for the modal */
            /* Optional: Add a subtle background pattern or texture */
            /* background-image: url('path/to/subtle-pattern.png'); */
        }

        /* Optional Decorative Header Band */
        .product-modal-header-band {
            height: 10px;
            background: linear-gradient(135deg, #0cace7, #f47656, #e70c7a);
            /* Tri-color gradient */
            /* Or use your brand's primary color */
            /* background-color: #0cace7; */
        }

        /* Modal Header Adjustments */
        .modal-header.border-0 {
            /* Ensure Bootstrap's border is removed */
            padding-top: 2rem;
            /* More space at the top */
        }

        /* Icon Styling */
        .product-modal-icon {
            font-size: 3rem;
            /* Larger icon */
            color: #f47656;
            /* Accent color */
            /* Gradient color for icon (advanced, browser support varies) */
            /* background: linear-gradient(135deg, #0cace7, #f47656);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent; */
        }

        /* Modal Title Styling */
        .product-modal-title {
            color: #343a40;
            /* Darker, more readable title */
        }


        .product-modal-close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 50% !important;
            padding: 0.6rem !important;
            opacity: 0.8 !important;
            filter: none !important;
        }

        .product-modal-close-btn:hover {
            background-color: rgba(0, 0, 0, 0.4) !important;
            opacity: 1 !important;
        }




        #productList .product-card-display {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            /* Subtle border */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            /* Full width inside its centered container */
            max-width: 350px;
        
        }

        #productList .product-card-display:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        #productList .product-image-wrapper {
            width: 100%;
            max-height: 250px;
            /* Control image height */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e9ecef;
            /* Placeholder bg if image is transparent */
        }

        #productList .product-image {
            width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: contain;
            /* 'cover' or 'contain' based on your images */
        }

        #productList .product-details {
            background-color: #ffffff;
            width: 100%;
        }

        #productList .product-name {
            color: #212529;
            font-size: 1.3rem;
        }

        #productList .product-price {
            font-size: 1.1rem;
        }


        /* Buy Now Button Styling */
        .product-modal-buy-btn {
            background: linear-gradient(135deg, #f47656, #e70c7a);

            border: none;
            color: white;
            padding: 0.8rem 1.5rem;

            font-size: 1.1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-modal-buy-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15) !important;
            background: linear-gradient(135deg, #e70c7a, #f47656);

            color: white;
        }

        .product-modal-buy-btn:disabled {
            background: #adb5bd;

            cursor: not-allowed;
        }

        .product-modal-buy-btn .spinner-border {
            width: 1.2rem;
            height: 1.2rem;
            margin-right: 0.5rem;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 576px) {
            .modal-dialog.modal-lg {
                margin: 0.5rem;
                /* Less margin on small screens */
            }

            .product-modal-content {
                border-radius: 1rem !important;
            }

            .product-modal-icon {
                font-size: 2.5rem;
            }

            .modal-title.product-modal-title {
                font-size: 1.5rem;
                /* Smaller title on mobile */
            }

            .product-modal-close-btn {
                top: 1rem;
                right: 1rem;
                padding: 0.5rem !important;
            }

            #productList .product-card-display {
                max-width: 100%;
            }

            .product-modal-buy-btn {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
        }

        /* == End of Deposit Page Styles - V4 == */
    </style>
      <?php
    include 'config.php';
    $level1 = $conn->query("SELECT minimum_amount, maximum_amount FROM levels WHERE id = 1")->fetch_assoc();
    $level1_min = $level1['minimum_amount'];
    $level1_max = $level1['maximum_amount'];
    ?>
    <script>
        window.level1Min = <?php echo floatval($level1_min); ?>;
        window.level1Max = <?php echo floatval($level1_max); ?>;
    </script>

    
 
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
                                <h1>Deposit</h1>
                                <p>Anim pariatur cliche reprehenderit</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->
            <section aria-label="section" class="deposit-section-v4 py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card  deposit-card-v4 shadow-xl border-0 rounded-xl overflow-hidden">
                                <div class="deposit-card-header-shape"></div>
                                <div class="card-body p-4 p-md-5 position-relative">
                                    <div class="deposit-icon-v4 text-center mb-4">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                    <h2 class="text-center mb-4 deposit-title-v4">Secure Deposit</h2>
                                    
                                    <form method="POST" action="payment.php" class="deposit-form-v4">
                                        <input type="hidden" name="claim_bonus" value="<?php echo $claim_bonus; ?>">
                                        <div class="mb-4 position-relative">
                                            <label for="amount"
                                                class="form-label deposit-form-label-v4 fw-medium mb-2">Amount
                                                (USD)</label>
                                            <div class="input-group input-group-lg deposit-input-wrapper-v4">

                                                <span class="input-group-text deposit-input-prefix-v4"><i
                                                        class="fas fa-dollar-sign fa-sm"></i></span>
                                                <input type="number" step="0.01" min="1.00" name="amount" id="amount"
                                                    class="form-control deposit-form-control-v4" required
                                                    placeholder=" " />

                                                <label for="amount" class="deposit-placeholder-label mb-2">Enter
                                                    Amount</label>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-5">
                                            <button type="submit" name="submit" class="btn btn-deposit-v4 btn-lg">
                                                <span class="btn-text">Deposit Now</span>
                                                <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Footer Text -->
                                <div class="card-footer deposit-footer-v4 text-center text-muted py-3">
                                    <i class="fas fa-lock fa-xs me-1"></i> Secure transaction. Funds credited instantly.
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
    <!-- Success/Error Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div id="modalHeader"
                    class="modal-header text-white d-flex align-items-center justify-content-between rounded-top-4 px-4 py-3"
                    style="background: linear-gradient(135deg, #0cace7, #f47656)">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-4" id="modalIcon" style="color: #fff"></i>
                        <h5 class="modal-title fw-bold fs-4 mb-0" id="statusModalLabel">
                            Deposit Successful
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-4 px-5">
                    <p class="fs-5 fw-semibold mb-0 text-dark" id="modalMessage">
                        <!-- Message goes here -->
                    </p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn text-white px-4 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal"
                        style="background: #f47656">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content product-modal-content shadow-xl border-0 overflow-hidden">
                <div class="product-modal-header-band"></div>
                <div class="modal-header border-0 position-relative justify-content-center pt-4 pb-2">
                    <div class="text-center">
                        <i class="bi bi-tag-fill product-modal-icon mb-2"></i>
                        <h5 class="modal-title product-modal-title fw-bold fs-3 mb-0" id="productModalLabel">
                            Special Offer
                        </h5>
                    </div>
                </div>
                <div class="modal-body product-modal-body text-center py-4 px-sm-4"> 
                    <div id="productList" class="d-flex flex-column align-items-center gap-3">
                        <div class="product-card-display shadow-sm rounded-4 overflow-hidden">
                            <div class="product-image-wrapper">
                                <img src="images/product/placeholder.jpg" class="img-fluid product-image"
                                    alt="Product Name">
                            </div>
                            <div class="product-details p-3">
                                <h4 class="product-name fw-semibold mb-1">Amazing Gadget Pro</h4>
                                <p class="product-price text-primary fs-5 mb-0 fw-bold">$0.00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer product-modal-footer border-0 justify-content-center pb-4 pt-2 px-sm-4">
                    <button id="buyNowButton" type="button"
                        class="btn btn-lg product-modal-buy-btn fw-semibold shadow-sm w-100" disabled>
                        <i class="bi bi-cart-plus-fill me-2"></i> Buy Now
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
        document.addEventListener("DOMContentLoaded", function () {
            const successMessage = new URLSearchParams(window.location.search).get("success");
            const showErrorModal = new URLSearchParams(window.location.search).get("error"); // For general errors
            const showProductModalFlag = new URLSearchParams(window.location.search).get("showProductModal");

            const statusModalEl = document.getElementById("statusModal");
            const statusModalInstance = new bootstrap.Modal(statusModalEl);
            const modalMessage = document.getElementById("modalMessage");
            const modalTitle = document.getElementById("statusModalLabel");
            const modalHeader = document.getElementById("modalHeader");
            const modalIcon = document.getElementById("modalIcon");

            if (successMessage) {
                console.log("Success message detected:", successMessage);
                modalMessage.textContent = successMessage;
                modalTitle.textContent = "Deposit Successful";
                modalIcon.className = "bi bi-check-circle-fill fs-4"; // Green check
                modalHeader.style.background = "linear-gradient(135deg, #0cace7, #63d471)"; // Success gradient
                statusModalInstance.show();

                if (showProductModalFlag === "1") {
                    console.log("showProductModal flag detected:", showProductModalFlag);
                    // Hide status modal then show product modal
                    statusModalEl.addEventListener('hidden.bs.modal', function onHidden() {
                        console.log("Status modal hidden, showing product modal...");
                        showProductModalWithProducts();
                        statusModalEl.removeEventListener('hidden.bs.modal', onHidden); // Clean up listener
                    }, { once: true });


                }
            } else if (showErrorModal) {
                console.log("Error message detected:", showErrorModal);
                modalMessage.textContent = showErrorModal;
                modalTitle.textContent = "Operation Failed";
                modalIcon.className = "bi bi-x-circle-fill fs-4";
                modalHeader.style.background = "linear-gradient(135deg, #dc3545, #f47656)";
                statusModalInstance.show();
            }


            function showProductModalWithProducts() {
                console.log("Initializing product modal (focused on product)...");

                const productModalEl = document.getElementById("productModal");
                if (!productModalEl) {
                    console.error("Product modal element not found!");
                    return;
                }
                const productModalInstance = new bootstrap.Modal(productModalEl);
                const productList = document.getElementById("productList");
                const buyNowButton = document.getElementById("buyNowButton");

                // Generate random price for each product within Level 1 min/max
                function getRandomPrice() {
                    return Math.floor(Math.random() * (window.level1Max - window.level1Min + 1)) + window.level1Min;
                }

                // Make sure 'products' array is defined and populated
                // Example:
                const products = [
                    { name: "Luxury Watch", price: getRandomPrice(), img: "images/product/watch1.jpg" },
                    { name: "Couple Luxury Watch", price: getRandomPrice(), img: "images/product/watch2.png" },
                    { name: "Classic Watch", price: getRandomPrice(), img: "images/product/watch3.jpg" },
                    { name: "Heart Necklace", price: getRandomPrice(), img: "images/product/heart_necklace.jpg" },
                    { name: "Ring", price: getRandomPrice(), img: "images/product/ring.jpg" },
                    { name: "Necklace Set", price: getRandomPrice(), img: "images/product/necklace_set.jpg" },
                ];

                if (!products || products.length === 0) {
                    console.error("Products array is empty or not defined!");
                    productList.innerHTML = "<p class='text-danger'>No product available at the moment.</p>";
                    if (buyNowButton) buyNowButton.disabled = true;
                    productModalInstance.show();
                    return;
                }

                const randomProduct = products[Math.floor(Math.random() * products.length)];
                console.log("Random product for display:", randomProduct);

                productList.innerHTML = ""; // Clear existing products

                // Price display logic
                const displayPrice = `$${randomProduct.price.toFixed(2)}`; // Hamesha $X.XX format mein

                const productCardHTML = `
        <div class="product-card-display shadow-sm rounded-4 overflow-hidden w-100" style="max-width: 320px;"> <!-- Max width for the card -->
            <div class="product-image-wrapper">
                <img src="${randomProduct.img}" class="img-fluid product-image" alt="${randomProduct.name}" 
                     onerror="this.onerror=null; this.src='images/product/placeholder.jpg';"> 
            </div>
            <div class="product-details p-3 text-center">
                <h4 class="product-name fw-semibold mb-1 fs-5">${randomProduct.name}</h4> 
                <p class="product-price text-primary fs-4 mb-0 fw-bold">${displayPrice}</p> 
            </div>
        </div>
    `;
                productList.innerHTML = productCardHTML;

                if (buyNowButton) {
                    buyNowButton.disabled = false;
                    buyNowButton.dataset.selectedProduct = JSON.stringify(randomProduct);
                    // Button text already "Buy Now" in HTML, so no need to change here unless dynamic
                    console.log("Product modal displayed, Buy Now button enabled with product:", randomProduct);
                } else {
                    console.error("showProductModalWithProducts: buyNowButton DOM mein nahi mila!");
                }

                productModalInstance.show();
            }
            // Attach event listener to the "Buy Now" button
            const buyNowButton = document.getElementById("buyNowButton");
            if (buyNowButton) {
                buyNowButton.addEventListener("click", handleBuyNowClick);
            }

            function handleBuyNowClick() {
                const selectedProductString = buyNowButton.dataset.selectedProduct;
                if (!selectedProductString) {
                    alert("Please select a product first.");
                    return;
                }

                const selectedProduct = JSON.parse(selectedProductString);
                buyNowButton.disabled = true;
                buyNowButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;


                console.log("Attempting to buy:", selectedProduct);

                fetch("save_product.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        product_name: selectedProduct.name,
                        product_price: selectedProduct.price,
                    }),
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().catch(() => { throw new Error(`HTTP error! status: ${response.status}`) });
                        }
                        return response.json();
                    })
                    .then(data => {
                        buyNowButton.disabled = false;
                        buyNowButton.innerHTML = 'Buy Now';

                        if (data.success) {
                            alert(`You have successfully claimed: ${selectedProduct.name}!`);

                            const productModalInstance = bootstrap.Modal.getInstance(document.getElementById("productModal"));
                            if (productModalInstance) {
                                productModalInstance.hide();
                            }
                            window.history.replaceState({}, document.title, window.location.pathname);
                        } else {
                            alert(`Failed to claim product: ${data.error || "Please try again."}`);
                        }
                    })
                    .catch(error => {
                        buyNowButton.disabled = false;
                        buyNowButton.innerHTML = 'Buy Now';
                        console.error("Error during product purchase:", error);
                        alert("An error occurred while trying to claim the product. Please check console and try again.");
                    });
            }
        });
  
        // Random price between level 1 min and max
        const randomPrice = Math.floor(Math.random() * (window.level1Max - window.level1Min + 1)) + window.level1Min;
        console.log("Random price for deposit:", randomPrice);
    </script>
</body>

</html>