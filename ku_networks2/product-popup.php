<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Pop-Up</title>
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container text-center mt-5">
        <button class="btn btn-lg" style="background-color: #0cace7; color: white;" onclick="showPopup()">Click Me for
            Surprise</button>
    </div>

    <div id="overlay" onclick="hidePopup()"></div>

    <div id="productPopup">
        <img id="popupImg" src="" alt="Product Image">
        <h5 id="popupHeading">You Won!</h5>
        <p id="popupMsg">Here’s a special product just for you!</p>
        <button onclick="hidePopup()">Claim Now</button>
    </div>

    <script>
        const productList = [
            {
                img: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80",
                heading: "Stylish Watch!",
                message: "A perfect timepiece to match your elegance."
            },
            {
                img: "https://img.drz.lazcdn.com/static/pk/p/c6ef6c2e4a5e397cca1e59e346b25a95.jpg_720x720q80.jpg_.webp",
                heading: "Luxury Jewelry!",
                message: "Add sparkle to your style with this jewel."
            },
            {
                img: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80",
                heading: "Elegant Watch!",
                message: "Timeless style, timeless quality."
            },
            {
                img: "https://img.drz.lazcdn.com/static/pk/p/6e8d28389c6bfd3a31610e922c242d71.jpg_720x720q80.jpg_.webp",
                heading: "Exclusive Ring!",
                message: "Crafted with love and luxury."
            },
            {
                img: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80",
                heading: "Designer Bracelet!",
                message: "Style that wraps around your wrist."
            }
        ];

        function showPopup() {
            const item = productList[Math.floor(Math.random() * productList.length)];
            document.getElementById("popupImg").src = item.img;
            document.getElementById("popupHeading").innerText = item.heading;
            document.getElementById("popupMsg").innerText = item.message;

            document.getElementById("overlay").style.display = "block";

            const popup = document.getElementById("productPopup");
            popup.classList.add("show");
            popup.style.display = "block";
        }

        function hidePopup() {
            const popup = document.getElementById("productPopup");
            popup.classList.remove("show");
            setTimeout(() => {
                popup.style.display = "none";
                document.getElementById("overlay").style.display = "none";
            }, 300); 
        }

    </script>



    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>