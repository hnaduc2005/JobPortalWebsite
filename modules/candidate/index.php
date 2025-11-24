<?php 
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php'; 

const _ACTION = 'homepage';

// Lấy action
$action = $_GET['action'] ?? _ACTION;

// Tạo đường dẫn view
$viewPath = __DIR__ . '/views/' . $action . '.php';

// Nếu view tồn tại → load
if (file_exists($viewPath)) {
<<<<<<< HEAD
    require_once $viewPath;
=======
    require_once getCurrentPath() . '/core/templates/header.php';
    require_once $viewPath;
    require_once getCurrentPath() . '/core/templates/footer.php';
>>>>>>> 4066b6e3936180ad2662152c4456146470735f6c
} 
// Nếu view không tồn tại → 404
else {
    require_once __DIR__ . '/../errors/404.php';
}