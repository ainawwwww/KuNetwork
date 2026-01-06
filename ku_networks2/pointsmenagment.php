<?php
// Safe bootstrap: ensure session and logged-in user
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Forbidden');
}

require 'config.php';

$modalMessage = ""; // modal میں دکھانے کے لیے میسج
$stageText = "";    // modal میں Stage یا پوائنٹس کا text

if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $currentDay = strtolower(date("l"));
    $todayDate = gmdate("Y-m-d");
    $currentMonth = gmdate("m");
    $currentYear = gmdate("Y");

    $checkQuery = "SELECT * FROM `user_login_times` WHERE user_id = $uid";
    $result = mysqli_query($conn, $checkQuery);

    // Flags for earned points
    $gotDailyPoint = false;
    $gotWeeklyPoint = false;
    $gotMonthlyPoint = false;

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);

        $daytimeArray = json_decode($userData['daytime'], true);
        if (!is_array($daytimeArray)) {
            $daytimeArray = array_fill_keys(
                ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'], ""
            );
        }

        $loginDates = json_decode($userData['login_dates'], true);
        if (!is_array($loginDates)) {
            $loginDates = [];
        }

        if (!in_array($todayDate, $loginDates)) {
            $loginDates[] = $todayDate;
        }

        $dailyPoints = (int)$userData['daily_points'];
        $weeklyPoints = (int)$userData['weekly_points'];
        $monthlyPoints = (int)$userData['monthly_points'];

        if (!isset($daytimeArray[$currentDay]) || $daytimeArray[$currentDay] != 1) {
            $dailyPoints += 1;
            $daytimeArray[$currentDay] = 1;
            $gotDailyPoint = true;
        }

        $weeklyPointsGiven = isset($userData['weekly_points_given']) ? (int)$userData['weekly_points_given'] : 0;

        if (count(array_filter($daytimeArray)) == 7 && !$weeklyPointsGiven) {
            $weeklyPoints += 5;
            $daytimeArray = array_fill_keys(array_keys($daytimeArray), "");
            $weeklyPointsGiven = 1;
            $gotWeeklyPoint = true;
        } elseif (count(array_filter($daytimeArray)) < 7) {
            $weeklyPointsGiven = 0;
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$currentMonth, (int)$currentYear);
        $monthDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $monthDates[] = gmdate("Y-m-d", strtotime($currentYear . "-" . $currentMonth . "-" . $d . " UTC"));
        }

        $hasLoggedAllDays = count(array_intersect($monthDates, $loginDates)) === $daysInMonth;

        if ($hasLoggedAllDays) {
            $monthlyPoints += 10;
            $loginDates = [];
            $gotMonthlyPoint = true;
        }

        // === SAFE UPDATE USING PREPARED STATEMENT ===
        $updateSql = "UPDATE user_login_times
            SET daytime = ?, daily_points = ?, weekly_points = ?, monthly_points = ?, login_dates = ?, current_month = ?, weekly_points_given = ?, last_login_at = UTC_TIMESTAMP()
            WHERE user_id = ?";

        $stmt = $conn->prepare($updateSql);
        if ($stmt) {
            $daytimeJson = json_encode($daytimeArray);
            $loginDatesJson = json_encode($loginDates);
            $stmt->bind_param(
                "siiisiii",
                $daytimeJson,
                $dailyPoints,
                $weeklyPoints,
                $monthlyPoints,
                $loginDatesJson,
                $currentMonth,
                $weeklyPointsGiven,
                $uid
            );
            $stmt->execute();
            $stmt->close();
        }

    } else {
        // New user
        $daytimeArray = array_fill_keys(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'], "");
        $daytimeArray[$currentDay] = 1;
        $loginDates = [$todayDate];

        $dailyPoints = 1;
        $weeklyPoints = 0;
        $monthlyPoints = 0;

        $insertQuery = "INSERT INTO `user_login_times` 
        (`user_id`, `loginTimes`, `daytime`, `daily_points`, `weekly_points`, `monthly_points`, `status`, `current_month`, `login_dates`, `weekly_points_given`, `last_login_at`)
        VALUES ($uid, 1, '" . mysqli_real_escape_string($conn, json_encode($daytimeArray)) . "', $dailyPoints, $weeklyPoints, $monthlyPoints, 'active', '" . mysqli_real_escape_string($conn, $currentMonth) . "', '" . mysqli_real_escape_string($conn, json_encode($loginDates)) . "', 0, UTC_TIMESTAMP())";

        if (mysqli_query($conn, $insertQuery)) {
            $gotDailyPoint = true;
        }
    }

    // Set modal message priority
    if ($gotMonthlyPoint) {
        $modalMessage = "🎊 Monthly goal complete! +10 points";
    } elseif ($gotWeeklyPoint) {
        $modalMessage = "🎉 Weekly complete! +5 points";
    } elseif ($gotDailyPoint) {
        $modalMessage = "✅ Daily point added!";
    } else {
        $modalMessage = "Already logged in today. No new point.";
    }

    $stageTextArr = [];
    $totalPoints = 0;

    if ($dailyPoints > 0) {
        $stageTextArr[] = "Daily Points: $dailyPoints";
        $totalPoints += $dailyPoints;
    }
    if ($weeklyPoints > 0) {
        $stageTextArr[] = "Weekly Points: $weeklyPoints";
        $totalPoints += $weeklyPoints;
    }
    if ($monthlyPoints > 0) {
        $stageTextArr[] = "Monthly Points: $monthlyPoints";
        $totalPoints += $monthlyPoints;
    }

    if (empty($stageTextArr)) {
        $stageText = "No points earned yet.";
    } else {
        $stageText = implode(", ", $stageTextArr);
    }

    // === NO AUTO CLAIM HERE ===

    $modalData = [
        'title' => 'Congratulations!',
        'message' => $stageText,
        'info' => $modalMessage,
        'image' => 'images/wallet/Token.png'
    ];
}
?>