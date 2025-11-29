<?php
checkAccessToken();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ?module=admin&action=user_list");
    exit;
}

$id   = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$role = isset($_POST['role']) ? (int)$_POST['role'] : -1;

// Các role hợp lệ
$validRoles = [0, 1, 2];

if (!in_array($role, $validRoles)) {
    header("Location: ?module=admin&action=user_list&error=invalid_role");
    exit;
}

// Kiểm tra user tồn tại
$user = getOne("SELECT * FROM user WHERE id = $id");
if (!$user) {
    header("Location: ?module=admin&action=user_list&error=user_not_found");
    exit;
}

// Cập nhật quyền
$sql = "UPDATE user SET role = :role WHERE id = :id";
$stmt = $conn->prepare($sql);
$success = $stmt->execute([
    ':role' => $role,
    ':id'   => $id
]);


if ($success) {
    header("Location: ?module=admin&action=user_list&success=updated_permission");
    exit;
} else {
    header("Location: ?module=admin&action=user_list&error=update_failed");
    exit;
}
?>