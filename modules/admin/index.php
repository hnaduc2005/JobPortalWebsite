<?php 
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php'; 
require_once __DIR__ . '/../../core/includes/session.php';
require_once __DIR__ . '/../../core/includes/functions.php';

const _ACTION = 'dashboard';

// Lấy action
$action = $_GET['action'] ?? _ACTION;

// Tạo đường dẫn view
$viewPath = __DIR__ . '/views/' . $action . '.php';

// Nếu view tồn tại → load
if (file_exists($viewPath)) {
    require_once $viewPath;
} 
// Nếu view không tồn tại → 404
else {
    require_once __DIR__ . '/../errors/404.php';
}