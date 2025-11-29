<?php
checkAccessToken();
require_once __DIR__ . "/check_login.php";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/sidebar.php';

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ?module=admin&action=user_list&error=invalid_id");
    exit;
}

$id = (int)$_GET['id'];

// Lấy thông tin user
$user = getOne("SELECT * FROM user WHERE id = $id");
if (!$user) {
    header("Location: ?module=admin&action=user_list&error=user_not_found");
    exit;
}

// Các loại role bạn muốn dùng
$roles = [
    0 => 'Admin',
    1 => 'Candidate',
    2 => 'Employer'
];
?>

<div class="container mt-4">
    <h3>Phân quyền người dùng</h3>

    <form method="POST" action="?module=admin&action=permission_process">

        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Quyền truy cập</label>
            <select name="role" class="form-select">
                <?php foreach ($roles as $value => $label): ?>
                <option value="<?php echo $value; ?>" <?php echo ($user['role'] == $value) ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="?module=admin&action=user_list" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>