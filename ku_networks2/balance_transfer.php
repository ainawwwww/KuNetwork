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
        :root {

            --user-blue: #0cace7;
            --user-orange: #f47656;
            --primary-blue-dark: #0a8db8;
            --accent-orange-dark: #e06a4b;
            --text-on-primary: #FFFFFF;
            --text-on-accent: #FFFFFF;
            --bg-main: #F7FAFC;

            --bg-card: #FFFFFF;

            --bg-card-alt: #EDF2F7;
            --border-color: #E2E8F0;
            --border-color-light: #F1F5F9;
            --text-heading: #1a202c;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --text-muted: #718096;


            --border-radius-sm: 0.25rem;

            --border-radius-md: 0.375rem;

            --border-radius-lg: 0.5rem;

            --border-radius-xl: 0.75rem;


            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04);

            --font-family-sans-serif: 'Inter', sans-serif;
        }




        .transfer-block {
            background-color: var(--bg-card);

            border-radius: var(--border-radius-lg) !important;

            box-shadow: var(--shadow-sm);

            overflow: hidden;

        }

        .transfer-block-header {
            color: var(--text-on-primary) !important;
            border-top-left-radius: var(--border-radius-lg) !important;
            border-top-right-radius: var(--border-radius-lg) !important;
            padding: 0.9rem 1.25rem;

        }

        .transfer-block-header h2 {
            font-size: 1.15rem !important;

            font-weight: 600 !important;
            margin-bottom: 0 !important;
        }

        .transfer-block-header i {
            opacity: 0.9;
            font-size: 0.9em;

        }

        .header-blue {
            background-color: var(--user-blue) !important;

        }

        .header-orange {
            background-color: var(--user-orange) !important;

        }

        .transfer-block-body {
            padding: 1.5rem;

        }

        .transfer-form-label {
            color: var(--text-secondary);
            font-size: 0.875rem !important;

            font-weight: 500 !important;

            margin-bottom: 0.4rem !important;
        }

        .transfer-input,
        #receiver_select.transfer-input {

            background-color: #F0F3F7;

            border: 1px solid #DDE2E9;

            border-radius: var(--border-radius-md) !important;

            padding: 0.65rem 0.9rem;

            font-size: 0.95rem;
            color: var(--text-primary);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: auto;

        }

        .transfer-input::placeholder {
            color: #909EAB;
            /* Lighter placeholder */
        }

        .transfer-input:focus,
        #receiver_select.transfer-input:focus {
            border-color: var(--user-blue);

            box-shadow: 0 0 0 0.2rem rgba(12, 172, 231, 0.20);

            background-color: #FFFFFF;

            color: var(--text-primary);
        }


        .input-group .transfer-input#receiver_input {

            border-right: none;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group .transfer-input#receiver_input:focus {
            z-index: 3;

        }

        .btn-find-receiver-flat {
            background-color: var(--user-orange) !important;

            color: var(--text-on-accent) !important;
            border: 1px solid var(--user-orange) !important;
            border-left: none;
            border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0 !important;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .btn-find-receiver-flat:hover {
            background-color: var(--accent-orange-dark) !important;

            border-color: var(--accent-orange-dark) !important;
        }


        .receiver-dropdown-area {
            min-height: calc(1.5em + (0.65rem * 2) + 2px);

            background-color: #F0F3F7;

            border: 1px solid #DDE2E9;
            border-radius: var(--border-radius-md) !important;
            padding: 0.65rem 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;

            color: #909EAB;

        }





        .transfer-input-prefix-flat {
            background-color: #F0F3F7;

            border: 1px solid #DDE2E9;
            border-right: none;
            color: #6c757d;

            border-radius: var(--border-radius-md) 0 0 var(--border-radius-md) !important;
            padding: 0.65rem 0.85rem;
            font-size: 0.95rem;
        }

        .input-group .transfer-input#amount {
            border-left: none;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .transfer-code-input {
            font-size: 1rem;

            letter-spacing: 1.5px;

            font-family: 'Menlo', 'Monaco', 'Courier New', monospace;

        }

        /* Main Action Buttons */
        .btn-submit-transfer-flat {
            color: var(--text-on-primary) !important;
            border: none;
            border-radius: var(--border-radius-md) !important;

            padding: 0.8rem 1.5rem;

            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }

        .btn-submit-transfer-flat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .btn-submit-transfer-flat i {
            font-size: 0.9em;
            vertical-align: middle;

        }

        /* Specific Button Colors */
        .btn-submit-transfer-flat.btn-blue {
            background-color: var(--user-blue) !important;

        }

        .btn-submit-transfer-flat.btn-blue:hover {
            background-color: var(--primary-blue-dark) !important;

        }

        .btn-submit-transfer-flat.btn-orange {
            background-color: var(--user-orange) !important;

        }

        .btn-submit-transfer-flat.btn-orange:hover {
            background-color: var(--accent-orange-dark) !important;

        }

        #receiver_dropdown select {
            width: 100%;
            padding: 10px 12px;

            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 16px;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
        }

        #receiver_dropdown select:focus {
            border-color: #e06a4b;
            outline: none;
            background-color: #fff;
        }



        /* == End of Balance Transfer Page Styles == */
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
                                <h1>Balance Transfer And Accept</h1>
                                <p>Anim pariatur cliche reprehenderit</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->
            <section aria-label="section" class="balance-transfer-page py-5">
                <div class="container">
                    <div class="row justify-content-center g-4"> <!-- Added g-4 for gutter -->

                        <!-- First Block (Add Balance Transfer) -->
                        <div class="col-lg-6 col-md-10 mb-4 mb-lg-0">
                            <div class="transfer-block rounded-4 shadow-sm overflow-hidden">
                                <!-- Header Block -->
                                <div class="transfer-block-header header-blue text-white text-center py-3">
                                    <h2 class="mb-0 fs-5 fw-semibold"><i class="fas fa-paper-plane fa-xs me-2"></i>Add
                                        Balance Transfer</h2>
                                </div>

                                <!-- Form Area -->
                                <div class="transfer-block-body p-4">
                                    <form method="POST" id="transferForm" action="add_balance_transfer.php">
                                        <!-- Recipient Username or Email -->
                                        <div class="mb-3">
                                            <label for="receiver_input"
                                                class="form-label transfer-form-label small">Recipient Username or
                                                Email</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control transfer-input"
                                                    id="receiver_input" name="receiver_input"
                                                    placeholder="e.g., user@example.com" required>
                                                <button type="button" class="btn btn-find-receiver-flat"
                                                    onclick="findReceiver()">Find</button>
                                            </div>
                                        </div>

                                        <!-- Receiver Dropdown -->
                                        <div id="receiver_container" class="mb-3">
                                            <label for="receiver_select"
                                                class="form-label transfer-form-label small">Choose Receiver</label>
                                            <div id="receiver_dropdown" class="receiver-dropdown-area">

                                            </div>

                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <label for="amount" class="form-label transfer-form-label small">Amount
                                                (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text transfer-input-prefix-flat">$</span>
                                                <input type="number" class="form-control transfer-input" step="0.01"
                                                    id="amount" name="amount" placeholder="0.00" required>
                                            </div>
                                        </div>
                                        <!-- Transfer Code Display -->
                                        <div id="transferCodeContainer" style="display:none; margin-top:15px;">
                                            <label for="transferCodeInput"
                                                class="form-label transfer-form-label small">Transfer Code</label>
                                            <div class="input-group">
                                                <input type="text" id="transferCodeInput"
                                                    class="form-control transfer-input transfer-code-input" readonly>
                                                <button type="button" class="btn btn-find-receiver-flat"
                                                    onclick="copyTransferCode()">Copy</button>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-submit-transfer-flat btn-blue">
                                                <i class="fas fa-cogs fa-xs me-2"></i>Generate Transfer Code
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Second Block (Accept Balance Transfer) -->
                        <div class="col-lg-6 col-md-10">
                            <div class="transfer-block rounded-4 shadow-sm overflow-hidden">
                                <!-- Header Block -->
                                <div class="transfer-block-header header-orange text-white text-center py-3">
                                    <h2 class="mb-0 fs-5 fw-semibold"><i class="fas fa-handshake fa-xs me-2"></i>Accept
                                        Balance Transfer</h2>
                                </div>

                                <!-- Form Area -->
                                <div class="transfer-block-body p-4">
                                    <form method="POST" action="accept_transfer.php">
                                        <!-- Transfer Code -->
                                        <div class="mb-4"> <!-- More margin for code input -->
                                            <label for="code" class="form-label transfer-form-label small">Enter
                                                Transfer Code</label>
                                            <input type="text"
                                                class="form-control transfer-input transfer-code-input text-center"
                                                id="code" name="code" placeholder="XXXX-XXXX-XXXX" required>
                                        </div>

                                        <!-- Accept Transfer Button -->
                                        <div class="d-grid mt-5"> <!-- More margin for button -->
                                            <button type="submit" class="btn btn-submit-transfer-flat btn-orange">
                                                <i class="fas fa-check-circle fa-xs me-2"></i>Accept Transfer
                                            </button>
                                        </div>
                                    </form>
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


    <!-- Modal Structure -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Ensures the modal is vertically centered -->
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #0cace7, #f47656);">
                    <!-- Gradient in Header -->
                    <h5 class="modal-title text-white" id="messageModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessageBody">
                    <!-- Success or error messages will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background: #f47656; color: white;"
                        data-bs-dismiss="modal">Close</button> <!-- Button with orange color -->
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
        function findReceiver() {
            const input = document.querySelector('input[name="receiver_input"]').value;
            fetch(`find_receiver.php?query=${encodeURIComponent(input)}`)
                .then(res => res.json())
                .then(data => {
                    const dropdown = document.getElementById("receiver_dropdown");
                    if (data.length > 0) {
                        let selectHTML = '<select name="receiver_id" required>';
                        data.forEach(user => {
                            selectHTML += `<option value="${user.id}">${user.name} (${user.user_id})</option>`;
                        });
                        selectHTML += '</select><br><br>';
                        dropdown.innerHTML = selectHTML;
                    } else {
                        dropdown.innerHTML = "<span style='color:red;font-weight:bold;'>This user is not a valid referral receiver or does not exist in any referral team.</span>";
                    }
                });
        }

        document.getElementById("transferForm").addEventListener("submit", function (e) {
            const receiverSelect = document.querySelector('select[name="receiver_id"]');
            if (!receiverSelect) {
                alert("Please select a receiver from the dropdown after searching.");
                e.preventDefault();
            }
        });

        function showTransferCode(code) {
            document.getElementById('transferCodeInput').value = code;
            document.getElementById('transferCodeContainer').style.display = 'block';
        }

        function copyTransferCode() {
            const input = document.getElementById('transferCodeInput');
            input.select();
            input.setSelectionRange(0, 99999); 
            document.execCommand("copy");
            showModal("Transfer code copied!");
        }

        // Modal show function
        function showModal(message) {
            document.getElementById('modalMessageBody').innerHTML = message;
            const modal = new bootstrap.Modal(document.getElementById('messageModal'));
            modal.show();
        }


        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage && successMessage.includes("Share this code with the recipient:")) {

            const codeMatch = successMessage.match(/code with the recipient: ([A-Z0-9]+)/i);
            if (codeMatch && codeMatch[1]) {
                showTransferCode(codeMatch[1]);
            }

        } else if (successMessage) {
            showModal(successMessage);
        }

        if (errorMessage) {
            showModal(errorMessage);
        }

        if (successMessage || errorMessage) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }



    </script>

</body>

</html>