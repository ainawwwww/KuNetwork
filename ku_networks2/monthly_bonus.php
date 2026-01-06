<?php
require 'user_data_logic.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Monthly Bonus</title>
   <link rel="stylesheet" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" href="custom_dashboard.css">
    <link rel="stylesheet" href="custom_account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <!-- CSS Files -->
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php include 'Assets/header.php'; ?>
        <div class="container py-5">
           
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0 mt-4" id="monthlyBonusCard">
                        <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #28a745, #218838);">
                            <span class="fs-5 fw-bold"><i class="fas fa-money-bill-wave me-2"></i> Monthly Salary Bonus</span>
                        </div>
                        <div class="card-body text-center p-5">
                            
                            <div id="loadingState">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="mt-2 text-muted">Checking eligibility...</p>
                            </div>

                            <div id="bonusContent" style="display:none;">
                                <h5 id="statusTitle" class="text-success fw-bold mb-3"></h5>
                                <p id="bonusStats" class="text-muted small mb-4"></p>
                                <h2 id="bonusAmountDisplay" class="fw-bold text-dark mb-4 display-4"></h2>
                                
                                <button id="btnClaimMonthly" class="btn btn-success w-100 py-3 fw-bold shadow fs-5" disabled>
                                    <i class="fas fa-gift me-2"></i> Check Status
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'Assets/footer.php'; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingDiv = document.getElementById('loadingState');
        const contentDiv = document.getElementById('bonusContent');
        const btnClaim = document.getElementById('btnClaimMonthly');
        const amountDisplay = document.getElementById('bonusAmountDisplay');
        const statsDisplay = document.getElementById('bonusStats');
        const statusTitle = document.getElementById('statusTitle');

        function checkSalaryBonus() {
            fetch('salary_logic.php?action=check&_=' + new Date().getTime())
                .then(response => response.json())
                .then(data => {
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';

                    if (data.status === 'wait') {
                        btnClaim.disabled = true;
                        btnClaim.className = 'btn btn-secondary w-100 py-3 fw-bold';
                        let mins = Math.ceil(data.remaining_seconds / 60);
                        btnClaim.innerHTML = `<i class="fas fa-clock me-2"></i> Wait ${mins} Mins`;
                        statusTitle.textContent = "Already Claimed";
                        amountDisplay.textContent = 'Claimed';
                        statsDisplay.textContent = data.message;
                    } 
                    else if (data.status === 'eligible') {
                        btnClaim.disabled = false;
                        btnClaim.className = 'btn btn-success w-100 py-3 fw-bold shadow pulse-animation';
                        btnClaim.innerHTML = '<i class="fas fa-gift me-2"></i> Claim Salary Now';
                        statusTitle.textContent = "You are Eligible!";
                        amountDisplay.textContent = '$ ' + parseFloat(data.amount).toFixed(2);
                        statsDisplay.textContent = data.stats;
                    } 
                    else {
                        // Not eligible
                        btnClaim.disabled = true;
                        btnClaim.className = 'btn btn-outline-secondary w-100 py-3';
                        btnClaim.innerHTML = 'Not Eligible Yet';
                        statusTitle.textContent = "Keep Working!";
                        amountDisplay.textContent = '$ 0.00';
                        statsDisplay.textContent = data.stats || "Requirements not met.";
                    }
                })
                .catch(err => {
                    console.error(err);
                    loadingDiv.innerHTML = '<p class="text-danger">Connection Error</p>';
                });
        }

        btnClaim.addEventListener('click', function() {
            if(!confirm('Claim Monthly Salary?')) return;
            
            btnClaim.disabled = true;
            btnClaim.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            fetch('salary_logic.php?action=claim&_=' + new Date().getTime())
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    checkSalaryBonus(); // Refresh state
                })
                .catch(err => {
                    alert('Error processing claim');
                    checkSalaryBonus();
                });
        });

        // Init
        checkSalaryBonus();
        setInterval(checkSalaryBonus, 30000); // Poll every 30s
    });
    </script>
    <style>
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
    </style>
</body>
</html>