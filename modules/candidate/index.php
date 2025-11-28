<?php 
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php'; 
require_once __DIR__ . '/../../core/includes/session.php';

//Email
require_once __DIR__ . '/../../core/includes/mailer/Exception.php';
require_once __DIR__ . '/../../core/includes/mailer/PHPMailer.php';
require_once __DIR__ . '/../../core/includes/mailer/SMTP.php';


require_once __DIR__ . '/../../core/includes/functions.php';

const _ACTION = 'homepage';

// Lấy action
$action = $_GET['action'] ?? _ACTION;

// Tạo đường dẫn view
$viewPath = __DIR__ . '/views/' . $action . '.php';

// Nếu view tồn tại → load
if (file_exists($viewPath)) {
    require_once $viewPath;
} 
else {
    require_once __DIR__ . '/../../errors/404.php'; 
}
