<?php if (isset($showWeeklyBonusPopup) && $showWeeklyBonusPopup): ?>
<div class="modal fade" id="weeklyBonusModal" tabindex="-1" aria-labelledby="weeklyBonusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="position-relative text-center" style="width: 350px; margin: 0 auto;">
                <img src="images/weekly_bonus.png" onerror="this.src='images/product/placeholder.jpg'" alt="Bonus" class="img-fluid w-100 mb-5" style="border-radius: 100px;" />
                
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="position-absolute top-50 start-50 translate-middle w-100 px-5 pt-5 text-center">
                    <h5 class="text-dark" id="weeklyBonusModalLabel">Claim Weekly Bonus</h5>
                    <p class="fw-bold text-dark">You are eligible to claim your weekly bonus!</p>
                    <p>To claim, you must deposit a minimum of $100 first.</p>
                    <div class="text-center mt-5 pt-4">
                        <a href="claimweekbonus.php" class="btn btn-primary mt-3">🎁 Claim Bonus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($modalData) && !empty($modalData)): ?>
<div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="position-relative text-center" style="width: 350px; margin: 0 auto;">
                <img src="<?php echo htmlspecialchars($modalData['image']); ?>" alt="Token" class="img-fluid w-100 mb-5" style="border-radius: 100px;" />
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="position-absolute top-50 start-50 translate-middle w-100 px-5 pt-5 text-center">
                    <h5 class="text-dark"><span><?php echo htmlspecialchars($modalData['title']); ?></span></h5>
                    <p class="fw-bold text-dark"><?php echo htmlspecialchars($modalData['message']); ?></p>
                    <div class="text-center mt-5 pt-4">
                        <p class="text-dark btn-claim-text mb-1">You earned points for your activity!</p>
                    </div>
                    <?php if (isset($userPoints) && $userPoints >= 100): ?>
                    <form method="post">
                        <input type="hidden" name="claim_bonus" value="1">
                        <button class="btn btn-success mt-3">🎁 Claim Bonus</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content product-modal-content shadow-xl border-0 overflow-hidden">
            <div class="product-modal-header-band"></div>
            <div class="modal-header border-0 position-relative justify-content-center pt-4 pb-2">
                <div class="text-center">
                    <i class="fas fa-gift product-modal-icon mb-2"></i>
                    <h5 class="modal-title fw-bold fs-3 mb-0" id="productModalLabel">
                        Weekly Bonus Reward
                    </h5>
                </div>
            </div>
            <div class="modal-body text-center py-4 px-sm-4"> 
                <div id="productList" class="d-flex flex-column align-items-center gap-3">
                    </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 pt-2 px-sm-4">
                <button id="buyNowButton" type="button" class="btn btn-lg product-modal-buy-btn fw-semibold shadow-sm w-100" disabled>
                    <i class="fas fa-cart-plus me-2"></i> Claim Reward
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Weekly Bonus Button Logic
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btnShowWeeklyBonus');
    if (btn) {
        btn.addEventListener('click', function() {
            var weeklyBonusModal = new bootstrap.Modal(document.getElementById('weeklyBonusModal'));
            weeklyBonusModal.show();
        });
    }

    // 2. Points Modal Logic (Show automatically if stageText exists)
    <?php if (isset($stageText) && !empty($stageText)): ?>
    var pointsModalEl = document.getElementById('pointsModal');
    if(pointsModalEl) {
        var pointsModal = new bootstrap.Modal(pointsModalEl);
        pointsModal.show();
    }
    <?php endif; ?>
});
</script>

<script>
// 3. Product Reward Logic (URL Flag Check)
document.addEventListener("DOMContentLoaded", function () {
    // Check URL for flags
    const urlParams = new URLSearchParams(window.location.search);
    const showProductModalFlag = urlParams.get("showProductModal");
    const successMsg = urlParams.get("success");
    const offerType = urlParams.get("offerType"); 

    // Level 1 Limits from PHP (passed via global JS vars in user_data_logic if needed, or defaults here)
    const lvl1Min = (typeof window.level1Min !== 'undefined') ? window.level1Min : 10;
    const lvl1Max = (typeof window.level1Max !== 'undefined') ? window.level1Max : 100;

    if (successMsg && showProductModalFlag === "1") {
        alert(successMsg); 
        showProductModalWithProducts();
    }

    function showProductModalWithProducts() {
        const productModalEl = document.getElementById("productModal");
        if (!productModalEl) return;

        // Title Change Logic
        const modalTitle = document.getElementById("productModalLabel");
        if (offerType === "limited" && modalTitle) {
            modalTitle.innerText = "Limited Time Offer For You";
            modalTitle.style.color = "#d9534f";
        }

        const productModalInstance = new bootstrap.Modal(productModalEl);
        const productList = document.getElementById("productList");
        const buyNowButton = document.getElementById("buyNowButton");

        function getRandomPrice() {
            return Math.floor(Math.random() * (lvl1Max - lvl1Min + 1)) + lvl1Min;
        }

        const products = [
            { name: "Luxury Watch", price: getRandomPrice(), img: "images/product/watch1.jpg" },
            { name: "Couple Luxury Watch", price: getRandomPrice(), img: "images/product/watch2.png" },
            { name: "Classic Watch", price: getRandomPrice(), img: "images/product/watch3.jpg" },
            { name: "Heart Necklace", price: getRandomPrice(), img: "images/product/heart_necklace.jpg" },
            { name: "Ring", price: getRandomPrice(), img: "images/product/ring.jpg" },
            { name: "Necklace Set", price: getRandomPrice(), img: "images/product/necklace_set.jpg" },
        ];

        if (products.length > 0) {
            const randomProduct = products[Math.floor(Math.random() * products.length)];
            const displayPrice = `$${randomProduct.price.toFixed(2)}`;

            productList.innerHTML = `
                <div class="product-card-display shadow-sm rounded-4 overflow-hidden w-100" style="max-width: 320px; margin: 0 auto;">
                    <div class="product-image-wrapper" style="background:#fff; padding:10px;">
                        <img src="${randomProduct.img}" class="img-fluid product-image" alt="${randomProduct.name}" 
                             onerror="this.onerror=null; this.src='images/product/placeholder.jpg';"> 
                    </div>
                    <div class="product-details p-3 text-center">
                        <h4 class="product-name fw-semibold mb-1 fs-5">${randomProduct.name}</h4> 
                        <p class="product-price text-primary fs-4 mb-0 fw-bold">${displayPrice}</p> 
                    </div>
                </div>`;
            
            if(buyNowButton) {
                buyNowButton.disabled = false;
                buyNowButton.innerHTML = '<i class="fas fa-cart-plus me-2"></i> Claim Reward';
                
                buyNowButton.onclick = function() {
                    buyNowButton.disabled = true;
                    buyNowButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

                    fetch("save_product.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            product_name: randomProduct.name,
                            product_price: randomProduct.price,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Congratulations! You claimed: ${randomProduct.name}`);
                            productModalInstance.hide();
                            
                            // Clean URL
                            const url = new URL(window.location);
                            url.searchParams.delete('showProductModal');
                            url.searchParams.delete('success');
                            url.searchParams.delete('offerType');
                            window.history.replaceState({}, '', url);
                        } else {
                            alert(`Error: ${data.error}`);
                            buyNowButton.disabled = false;
                            buyNowButton.innerHTML = 'Claim Reward';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Something went wrong.");
                        buyNowButton.disabled = false;
                        buyNowButton.innerHTML = 'Claim Reward';
                    });
                };
            }
        }
        productModalInstance.show();
    }
});
</script>