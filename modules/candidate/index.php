<?php
// modules/candidate/index.php

// Load core (đường dẫn điều chỉnh theo dự án)
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/connect.php';
require_once __DIR__ . '/../../core/includes/database.php';

// default action
$defaultAction = 'homepage';

// Lấy action từ query (nếu có)
$action = $_GET['action'] ?? $defaultAction;

// Build path tới view
$viewFile = __DIR__ . '/views/' . basename($action) . '.php';

// Kiểm tra và include view
if (file_exists($viewFile)) {
    // nếu muốn include header/footer global:
    require_once __DIR__ . '/../../core/templates/header.php';
    require_once $viewFile;
    require_once __DIR__ . '/../../core/templates/footer.php';
} else {
    // 404 của module hoặc include chung
    http_response_code(404);
    require_once __DIR__ . '/../errors/404.php';
}
