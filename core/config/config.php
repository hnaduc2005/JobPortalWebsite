<?php
// --- Cấu hình database ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_portal');
define('DB_USER', 'root');
define('DB_PASS', '');

// --- Thiết lập chung ---
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start();

// --- Kiểm tra truy cập hợp lệ ---
const _accessToken = true;

echo _accessToken;