<?php 
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php'; 

const _ACTION = 'My_portfolio';

// Lấy action
$action = $_GET['action'] ?? _ACTION;

// Tạo đường dẫn view
$viewPath = __DIR__ . '/views/' . $action . '.php';

// Nếu view tồn tại → load
if (file_exists($viewPath)) {

    // include header
    require_once __DIR__ . '/../../core/templates/header.php';

    // include view
    require_once $viewPath;

    // include footer
    require_once __DIR__ . '/../../core/templates/footer.php';
} 
else {
    require_once __DIR__ . '/../../errors/404.php';
}
