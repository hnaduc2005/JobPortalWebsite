<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_GET['action'] ?? '';

// KHÔNG kiểm tra login khi đang ở trang login
if ($action !== 'login') {

    if (empty($_SESSION['user'])) {
        header("Location: " . BASE_URL . "?module=admin&action=login");
        exit;
    }

    if ($_SESSION['user']['role'] != 0) {
        header("Location: " . BASE_URL);
        exit;
    }
}