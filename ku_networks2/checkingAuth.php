<?php
// START SESSION FIRST
session_start();

// Redirect if session not set
if (!isset($_SESSION['user_id'])) {
    header("Location: loginInterface.php?error=" . urlencode("Please log in to view your account."));
    
    exit();
}

$user_id = $_SESSION['user_id'];  

// CONSTANTS (define once)
if (!defined('PROFILE_IMAGE_UPLOAD_DIR')) {
    define('PROFILE_IMAGE_UPLOAD_DIR', 'images/uploads/profile_images/');
}
if (!defined('PROFILE_IMAGE_UPLOAD_DIR_ADMIN')) {
    define('PROFILE_IMAGE_UPLOAD_DIR_ADMIN', '/ku_networks_admin/images/uploads/profile_images/');
}
if (!defined('BASE_WEB_PATH_MAIN_PROJECT')) {
    define('BASE_WEB_PATH_MAIN_PROJECT', '/ku_networks/');
}
if (!defined('DEFAULT_AVATAR_FILENAME')) {
    define('DEFAULT_AVATAR_FILENAME', 'default.png');
}
if (!defined('ULTIMATE_DEFAULT_AVATAR_WEB_PATH')) {
    define('ULTIMATE_DEFAULT_AVATAR_WEB_PATH', BASE_WEB_PATH_MAIN_PROJECT . 'images/wallet/profile.jpg');
}

// Include DB
require 'config.php';
?>
