<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Pop-Up</title>
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Trigger Button -->
    <div class="container mt-5 text-center">
        <button class="btn btn-primary" id="showPopupBtn">Show Token Pop-Up</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tokenModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">

                <div class="position-relative text-center" style="width: 350px;">

                    <!-- Background Image -->
                    <img src="images/wallet/Token.png" alt="Token" class="img-fluid w-100 mb-5 "
                        style="border-radius: 100px;">

                    <!-- Close Button -->
                    <button type="button" class="btn-close position-absolute text-dark top-0 end-0 m-2"
                        data-bs-dismiss="modal"></button>

                    <!-- Overlay Content -->
                    <!-- Overlay Content -->
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
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        const tokenModal = new bootstrap.Modal(document.getElementById('tokenModal'));

        // Show after 10 seconds
        setTimeout(() => {
            tokenModal.show();
        }, 1000);

        // Optional: Trigger manually via button
        document.getElementById('showPopupBtn')?.addEventListener('click', () => {
            tokenModal.show();
        });
    </script>
</body>

</html>