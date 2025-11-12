<?php
// --- Cấu hình database ---
define('DB_HOST', '103.255.237.26:3306');
define('DB_NAME', 'timvieci_job_portal');
define('DB_USER', 'timvieci_hnaduc2005');
define('DB_PASS', 'VKGMJ;4@I{wM');
define('DB_DRIVER', 'mysql');


// --- Thiết lập chung ---
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start();

// --- Kiểm tra truy cập hợp lệ ---
const _accessToken = true;
function checkAccessToken() {
    if (!defined('_accessToken')) {
        die("Truy cập không hợp lệ!");
    }
}

// --- Thiết lập host ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST']; 
$folder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';

define('BASE_URL', $protocol . $domain . $folder);

// --- Thiết lập path ---

// define('_PATH_URL', __DIR__);
function getCurrentPath() {
    $trace = debug_backtrace();
    $caller = end($trace);
    return dirname($caller['file']);
}