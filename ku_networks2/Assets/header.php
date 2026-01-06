<link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="KU Network" name="description" />
<meta content="" name="keywords" />
<meta content="" name="author" />

<link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
<link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
<link href="css/animate.css" rel="stylesheet" type="text/css" />
<link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
<link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
<link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
<link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
<link href="css/coloring.css" rel="stylesheet" type="text/css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom_account.css" rel="stylesheet" type="text/css" />

<?php
// Config file
require_once 'config.php';

// Active Page Helper Function
function isActive($pageName) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $pageName) ? 'active' : '';
}
?>

<style>
    :root {
        --primary-color: var(--accent-orange, #f47656); 
        --sidebar-bg: #ffffff;
        --sidebar-width: 280px;
        --header-height: 80px;
        --text-color: #555;
        --active-bg: rgba(244, 118, 86, 0.1);
    }

    body { background-color: #f4f6f9; }

    /* Layout */
    @media (min-width: 992px) {
        #wrapper { margin-left: var(--sidebar-width); transition: margin-left 0.3s ease; }
        footer { width: 100%; margin-left: 0; }
    }

    /* Sidebar */
    .dashboard-sidebar {
        position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
        background: var(--sidebar-bg); border-right: 1px solid #e7e7e7; z-index: 1050;
        padding-bottom: 30px; box-shadow: 4px 0 24px rgba(0,0,0,0.02); transition: all 0.3s ease;
        overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--primary-color) transparent;
    }
    .dashboard-sidebar::-webkit-scrollbar { width: 5px; }
    .dashboard-sidebar::-webkit-scrollbar-thumb { background-color: var(--accent-orange); border-radius: 20px; }
    .dashboard-sidebar:hover::-webkit-scrollbar-thumb { background-color: var(--primary-color); }

    /* Brand */
    .sidebar-brand {
        height: 100px; display: flex; align-items: center; justify-content: center;
        border-bottom: 1px solid #f0f0f0; margin-bottom: 20px; background: #fff;
    }
    .sidebar-brand img { max-height: 65px; width: auto; transition: transform 0.3s; }
    .sidebar-brand:hover img { transform: scale(1.05); }

    /* Menu */
    .sidebar-menu { list-style: none; padding: 0 15px; margin: 0; }
    .menu-header {
        font-size: 11px; text-transform: uppercase; letter-spacing: 1.2px;
        color: #999; font-weight: 700; margin: 25px 0 10px 15px; opacity: 0.8;
    }
    .sidebar-menu li { margin-bottom: 8px; }
    .sidebar-menu a {
        display: flex; align-items: center; padding: 14px 20px; color: var(--text-color);
        text-decoration: none; font-size: 15px; border-radius: 12px; font-weight: 500;
        transition: all 0.2s ease-in-out; position: relative;
    }
    .sidebar-menu a i {
        font-size: 18px; width: 35px; color: #adb5bd; transition: color 0.2s;
        display: flex; align-items: center;
    }
    .sidebar-menu a:hover { background-color: #fff5f2; color: var(--primary-color); }
    .sidebar-menu a:hover i { color: var(--primary-color); }
    .sidebar-menu a.active {
        background: linear-gradient(90deg, var(--active-bg) 0%, rgba(255,255,255,0) 100%);
        color: var(--primary-color); font-weight: 700;
        border-left: 4px solid var(--primary-color); border-radius: 4px 12px 12px 4px;
    }
    .sidebar-menu a.active i { color: var(--primary-color); }

    /* Header */
    .dashboard-header {
        position: sticky; top: 0; width: 100%; height: var(--header-height);
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
        border-bottom: 1px solid #e7e7e7; z-index: 1040;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .header-left { display: flex; align-items: center; }
    .mobile-toggle { display: none; font-size: 24px; margin-right: 20px; cursor: pointer; color: #333; padding: 5px; }
    .search-bar { position: relative; }
    .search-bar input {
        background: #f8f9fa; border: 1px solid #eee; padding: 12px 20px 12px 45px;
        border-radius: 50px; width: 280px; font-size: 14px; color: #555; transition: all 0.3s;
    }
    .search-bar input:focus {
        border-color: var(--primary-color); background: #fff; outline: none;
        box-shadow: 0 0 0 3px rgba(244, 118, 86, 0.1);
    }
    .search-bar i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #aaa; }

    /* Wallet Button */
    .btn-connect-wallet {
        background: var(--primary-color); color: #fff !important; padding: 10px 30px;
        border-radius: 50px; font-weight: 600; font-size: 14px;
        box-shadow: 0 4px 15px rgba(244, 118, 86, 0.3); display: flex; align-items: center;
        gap: 10px; text-decoration: none; transition: all 0.3s; border: 1px solid transparent;
    }
    .btn-connect-wallet:hover {
        background: #fff; color: var(--primary-color) !important;
        border-color: var(--primary-color); transform: translateY(-2px);
    }

    /* Mobile */
    @media (max-width: 991px) {
        .dashboard-sidebar { transform: translateX(-100%); }
        .dashboard-sidebar.show { transform: translateX(0); }
        .mobile-toggle { display: block; }
        .search-bar { display: none; }
        #wrapper { margin-left: 0; }
        .sidebar-brand img { max-height: 50px; }
    }
</style>

<aside class="dashboard-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="index.php">
            <img src="images/logo-k.png" alt="KU Network">
        </a>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-header">Main</li>
        <li>
            <a href="index.php" class="<?php echo isActive('index.php'); ?>">
                <i class="fa fa-home"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="anouncement.php" class="<?php echo isActive('anouncement.php'); ?>">
                <i class="fa fa-bullhorn"></i> <span>Announcements</span>
            </a>
        </li>

        <li class="menu-header">My Earnings & Status</li>
        <li>
            <a href="account.php" class="<?php echo isActive('account.php'); ?>">
                <i class="fa fa-user-circle"></i> <span>My Profile</span>
            </a>
        </li>
        <li>
            <a href="income_streams.php" class="<?php echo isActive('income_streams.php'); ?>">
                <i class="fas fa-chart-line"></i> <span>Income Breakdown</span>
            </a>
        </li>
        <li>
            <a href="points_withdrawal.php" class="<?php echo isActive('points_withdrawal.php'); ?>">
                <i class="fas fa-hand-holding-dollar"></i> <span>Points Withdrawal</span>
            </a>
        </li>
        <li>
            <a href="status_circles.php" class="<?php echo isActive('status_circles.php'); ?>">
                <i class="fas fa-circle-notch"></i> <span>Rank Status</span>
            </a>
        </li>
        <li>
            <a href="team_levels.php" class="<?php echo isActive('team_levels.php'); ?>">
                <i class="fas fa-users"></i> <span>Team & Levels</span>
            </a>
        </li>
        <li>
            <a href="level_program.php" class="<?php echo isActive('level_program.php'); ?>">
                <i class="fas fa-layer-group"></i> <span>Level Progress</span>
            </a>
        </li>
        <li>
            <a href="monthly_bonus.php" class="<?php echo isActive('monthly_bonus.php'); ?>">
                <i class="fas fa-money-bill-wave"></i> <span>Monthly Salary</span>
            </a>
        </li>

        <li class="menu-header">Wallet & Finance</li>
        <li>
            <a href="wallet.php" class="<?php echo isActive('wallet.php'); ?>">
                <i class="fa fa-wallet"></i> <span>My Wallet</span>
            </a>
        </li>
        <li>
            <a href="deposit.php" class="<?php echo isActive('deposit.php'); ?>">
                <i class="fa fa-arrow-circle-down"></i> <span>Deposit Funds</span>
            </a>
        </li>
        <li>
            <a href="withdrawinterface.php" class="<?php echo isActive('withdrawinterface.php'); ?>">
                <i class="fa fa-arrow-circle-up"></i> <span>Withdraw Funds</span>
            </a>
        </li>
        <li>
            <a href="balance_transfer.php" class="<?php echo isActive('balance_transfer.php'); ?>">
                <i class="fa fa-exchange-alt"></i> <span>Transfer Balance</span>
            </a>
        </li>

        <li class="menu-header">Team Network</li>
        <li>
            <a href="teamdetail.php" class="<?php echo isActive('teamdetail.php'); ?>">
                <i class="fa fa-network-wired"></i> <span>Tree View</span>
            </a>
        </li>
        <li>
            <a href="referalcode.php" class="<?php echo isActive('referalcode.php'); ?>">
                <i class="fa fa-link"></i> <span>My Referral Link</span>
            </a>
        </li>
        <li>
            <a href="claimbonus.php" class="<?php echo isActive('claimbonus.php'); ?>">
                <i class="fa fa-gift"></i> <span>Daily Bonus</span>
            </a>
        </li>

        <li class="menu-header">Settings</li>
        <li>
            <a href="gallery.php" class="<?php echo isActive('gallery.php'); ?>">
                <i class="fa fa-images"></i> <span>Gallery</span>
            </a>
        </li>
        <li>
            <a href="contact.php" class="<?php echo isActive('contact.php'); ?>">
                <i class="fa fa-headset"></i> <span>Support</span>
            </a>
        </li>
        <li style="margin-top: 30px; margin-bottom: 30px; padding: 0 15px;">
            <a href="logout.php" style="background: #fff0f0; color: #dc3545; justify-content: center;">
                <i class="fa fa-sign-out-alt" style="color: #dc3545; margin-right: 10px;"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>

<header class="dashboard-header">
    <div class="header-left">
        <div class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </div>
        <div class="search-bar">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
    </div>

    <div class="header-right">
        <a href="wallet.php" class="btn-connect-wallet">
            <i class="fa fa-wallet"></i>
            <span>Connect Wallet</span>
        </a>
    </div>
</header>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        var sidebar = document.getElementById('sidebar');
        var toggleBtn = document.querySelector('.mobile-toggle');
        
        if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
</script>