<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_GET['action'] ?? '';

// KHÔNG kiểm tra login khi đang ở trang login
if ($action !== 'login') {

    if (empty($_SESSION['user'])) {
        setSessionFlash('msg', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        setSessionFlash('msg_type', 'danger');
        header("Location: " . BASE_URL . "?module=admin&action=login");
        exit;
    }

    if ($_SESSION['user']['role'] != 0) {
        setSessionFlash('msg', 'Bạn không có quyền truy cập trang quản trị.');
        setSessionFlash('msg_type', 'danger');
        header("Location: " . BASE_URL);
        exit;
    }
}