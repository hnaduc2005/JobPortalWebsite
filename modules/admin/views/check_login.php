<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đang ở trang login thì không check
$current_action = $_GET['action'] ?? '';

if ($current_action !== 'login') {

    // Check login
    if (empty($_SESSION['user'])) {
        setSessionFlash('msg', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        setSessionFlash('msg_type', 'danger');
        header("Location: " . BASE_URL . "?module=admin&action=login");
        exit;
    }

    // Check role
    if ($_SESSION['user']['role'] !== 0) {
        setSessionFlash('msg', 'Bạn không có quyền vào admin.');
        setSessionFlash('msg_type', 'danger');
        header("Location: " . BASE_URL);
        exit;
    }
}