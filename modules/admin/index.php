<?php 
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php'; 
require_once __DIR__ . '/../../core/includes/session.php';
require_once __DIR__ . '/../../core/includes/functions.php';

// ===========================
// Router xử lý action
// ===========================
const _ACTION = 'dashboard';

// Lấy action từ URL
$action = $_GET['action'] ?? _ACTION;

// Đường dẫn view
$viewPath = __DIR__ . '/views/' . $action . '.php';

// Load view nếu tồn tại
if (file_exists($viewPath)) {
    require_once $viewPath;
} 
else {
    require_once __DIR__ . '/../errors/404.php';
}