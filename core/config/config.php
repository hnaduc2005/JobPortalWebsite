<?php
// --- Cấu hình database ---
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'timvieci_job_portal');
define('DB_USER', 'hnaduc2005');
define('DB_PASS', 'hnaduc2005@');
define('DB_DRIVER', 'mysql');


// --- Thiết lập chung ---
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start();

// --- Kiểm tra truy cập hợp lệ ---
const _accessToken = true;

// --- Thiết lập host ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST']; 
$folder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';

define('BASE_URL', $protocol . $domain . $folder);

// --- Thiết lập path ---

define('_PATH_URL', __DIR__);