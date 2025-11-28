<?php

checkAccessToken();
require_once __DIR__ . "/../../../core/includes/connect.php";
require_once __DIR__ . "/../../../core/includes/database.php";


// Lấy ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ID không hợp lệ
if ($id <= 0) {
    header("Location: ?module=admin&action=user_list&error=invalid_id");
    exit;
}

// Kiểm tra user có tồn tại
$user = getOne("SELECT id FROM user WHERE id = $id");

if (empty($user)) {
    header("Location: ?module=admin&action=user_list&error=not_found");
    exit;
}

// Xoá user
delete("user", "id = $id");

// Xoá thành công
header("Location: ?module=admin&action=user_list&success=deleted");
exit;