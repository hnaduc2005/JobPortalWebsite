<?php
// Xóa toàn bộ session của user
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Hủy toàn bộ session
session_unset();
session_destroy();

// Tạo session mới để đảm bảo flash message hoạt động
session_start();

// Thông báo
$_SESSION['msg'] = "Bạn đã đăng xuất thành công!";
$_SESSION['msg_type'] = "success";

// Điều hướng về trang login
header("Location: " . BASE_URL . "?module=admin&action=login");
exit;
?>