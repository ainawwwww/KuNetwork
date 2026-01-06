<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - KU Network</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            height: 100vh;
            overflow: hidden; /* Scroll rokne ke liye */
        }

        /* COMING SOON OVERLAY CSS */
        #coming-soon-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cs-header {
            background-color: #f47656; /* Header Color */
            padding: 20px;
            text-align: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .cs-content {
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .logo {
            max-width: 150px; /* Logo size adjust karne ke liye */
            margin-bottom: 20px;
        }

        .cs-title {
            color: #f47656;
            font-size: 60px;
            font-weight: 800;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .timer-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .time-box {
            background-color: #f47656;
            color: white;
            padding: 20px;
            border-radius: 8px;
            width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .time-val {
            font-size: 40px;
            font-weight: bold;
            line-height: 1;
        }

        .time-label {
            font-size: 14px;
            margin-top: 5px;
        }

        .cs-footer {
            background-color: #f47656;
            color: white;
            text-align: center;
            padding: 15px;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .cs-title { font-size: 35px; }
            .time-box { width: 80px; height: 80px; padding: 10px; }
            .time-val { font-size: 24px; }
        }
    </style>
</head>
<body>

    <div id="coming-soon-overlay">
        <div class="cs-header">
            <i class="fas fa-play-circle"></i> KU Network
        </div>
        
        <div class="cs-content">
            <img alt="Logo" id="logo-k" class="logo" src="ku_networks2/images/logo-k.png" />
            
            <h1 class="cs-title">COMING SOON</h1>
            
            <div class="timer-container">
                <div class="time-box">
                    <span id="days" class="time-val">00</span>
                    <span class="time-label">Days</span>
                </div>
                <div class="time-box">
                    <span id="hours" class="time-val">00</span>
                    <span class="time-label">Hours</span>
                </div>
                <div class="time-box">
                    <span id="minutes" class="time-val">00</span>
                    <span class="time-label">Minutes</span>
                </div>
                <div class="time-box">
                    <span id="seconds" class="time-val">00</span>
                    <span class="time-label">Seconds</span>
                </div>
            </div>
        </div>

        <div class="cs-footer">
            &copy; 2025 KU Network. All Rights Reserved.
        </div>
    </div>

    <script>
        // ==========================================
        // DATE YAHAN SET KAREIN
        // Format: "Month Day, Year Time"
        // Example: "Dec 31, 2025 24:00:00"
        // ==========================================
        var countDownDate = new Date("Feb 15, 2026 24:00:00").getTime(); 

        var x = setInterval(function() {

          // Get today's date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Output result in elements
          if(document.getElementById("days")) {
              document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
              document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
              document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
              document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
          }

          // JAB TIMER KHATAM HO JAYE
          if (distance < 0) {
            clearInterval(x);
            // Yahan aap change kar sakte hain ke timer khatam hone par kya dikhana hai
            document.querySelector(".cs-title").innerHTML = "WE ARE LIVE!";
            document.querySelector(".timer-container").style.display = "none";
          }
        }, 1000);
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - KU Network</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            height: 100vh;
            overflow: hidden; /* Scroll rokne ke liye */
        }

        /* COMING SOON OVERLAY CSS */
        #coming-soon-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cs-header {
            background-color: #f47656; /* Header Color */
            padding: 20px;
            text-align: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .cs-content {
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .logo {
            max-width: 150px; /* Logo size adjust karne ke liye */
            margin-bottom: 20px;
        }

        .cs-title {
            color: #f47656;
            font-size: 60px;
            font-weight: 800;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .timer-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .time-box {
            background-color: #f47656;
            color: white;
            padding: 20px;
            border-radius: 8px;
            width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .time-val {
            font-size: 40px;
            font-weight: bold;
            line-height: 1;
        }

        .time-label {
            font-size: 14px;
            margin-top: 5px;
        }

        .cs-footer {
            background-color: #f47656;
            color: white;
            text-align: center;
            padding: 15px;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .cs-title { font-size: 35px; }
            .time-box { width: 80px; height: 80px; padding: 10px; }
            .time-val { font-size: 24px; }
        }
    </style>
</head>
<body>

    <div id="coming-soon-overlay">
        <div class="cs-header">
            <i class="fas fa-play-circle"></i> KU Network
        </div>
        
        <div class="cs-content">
            <img alt="Logo" id="logo-k" class="logo" src="ku_networks2/images/logo-k.png" />
            
            <h1 class="cs-title">COMING SOON</h1>
            
            <div class="timer-container">
                <div class="time-box">
                    <span id="days" class="time-val">00</span>
                    <span class="time-label">Days</span>
                </div>
                <div class="time-box">
                    <span id="hours" class="time-val">00</span>
                    <span class="time-label">Hours</span>
                </div>
                <div class="time-box">
                    <span id="minutes" class="time-val">00</span>
                    <span class="time-label">Minutes</span>
                </div>
                <div class="time-box">
                    <span id="seconds" class="time-val">00</span>
                    <span class="time-label">Seconds</span>
                </div>
            </div>
        </div>

        <div class="cs-footer">
            &copy; 2025 KU Network. All Rights Reserved.
        </div>
    </div>

    <script>
        // ==========================================
        // DATE YAHAN SET KAREIN
        // Format: "Month Day, Year Time"
        // Example: "Dec 31, 2025 24:00:00"
        // ==========================================
        var countDownDate = new Date("Feb 15, 2026 24:00:00").getTime(); 

        var x = setInterval(function() {

          // Get today's date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Output result in elements
          if(document.getElementById("days")) {
              document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
              document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
              document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
              document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
          }

          // JAB TIMER KHATAM HO JAYE
          if (distance < 0) {
            clearInterval(x);
            // Yahan aap change kar sakte hain ke timer khatam hone par kya dikhana hai
            document.querySelector(".cs-title").innerHTML = "WE ARE LIVE!";
            document.querySelector(".timer-container").style.display = "none";
          }
        }, 1000);
    </script>
</body>
>>>>>>> a4e7c1c (Initial commit)
</html>