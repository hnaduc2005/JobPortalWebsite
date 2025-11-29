<?php
// add_user.php
// Thêm người dùng mới
checkAccessToken();
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/sidebar.php';

// Lưu thông báo lỗi / thành công
$errors = [];
$success = '';

// Kiểm tra biến $conn (PDO) tồn tại - file header.php của bạn nên tạo PDO và gán vào $conn
if (!isset($conn) || !($conn instanceof PDO)) {
    // Nếu bạn không dùng PDO, cập nhật phần sau để sử dụng lớp DB của bạn.
    $errors[] = 'Database connection not found. Vui lòng đảm bảo $conn là một PDO instance.';
}

// Role map giống bảng
$roles = [
    0 => 'admin',
    1 => 'candidate',
    2 => 'employer'
];

// Giá trị mặc định form
$form = [
    'email' => '',
    'password' => '',
    'fullname' => '',
    'phone' => '',
    'role' => '1',        // mặc định candidate
    'status' => '1'
];

// Xử lý submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy input và trim
    $form['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $form['password'] = isset($_POST['password']) ? $_POST['password'] : '';
    $form['fullname'] = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $form['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $form['role'] = isset($_POST['role']) ? (string)$_POST['role'] : '1';
    $form['status'] = isset($_POST['status']) ? (string)$_POST['status'] : '1';

    // Validate
    if ($form['email'] === '') {
        $errors[] = 'Email là bắt buộc.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }

    if ($form['password'] === '') {
        $errors[] = 'Mật khẩu là bắt buộc.';
    } elseif (strlen($form['password']) < 6) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    }

    // fullname/phone optional but trim length check
    if ($form['fullname'] !== '' && mb_strlen($form['fullname']) > 150) {
        $errors[] = 'Tên không được dài quá 150 ký tự.';
    }

    if ($form['phone'] !== '' && mb_strlen($form['phone']) > 15) {
        $errors[] = 'Số điện thoại không được dài quá 15 ký tự.';
    }

    // role kiểm tra có hợp lệ không
    $roleInt = (int)$form['role'];
    if (!array_key_exists($roleInt, $roles)) {
        $errors[] = 'Nhóm (role) không hợp lệ.';
    }

    // status và is_verified là char(1) / tinyint(1) - ép kiểu an toàn
    $statusVal = ($form['status'] === '1') ? '1' : '0';

    // Kiểm tra email đã tồn tại chưa
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare('SELECT COUNT(*) AS cnt FROM user WHERE email = :email');
            $stmt->execute([':email' => $form['email']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $exists = $row && isset($row['cnt']) && (int)$row['cnt'] > 0;
            if ($exists) {
                $errors[] = 'Email đã được sử dụng.';
            }
        } catch (PDOException $ex) {
            $errors[] = 'Lỗi kiểm tra email: ' . $ex->getMessage();
        }
    }

    // Nếu không có lỗi -> chèn
    if (empty($errors)) {
        $passwordHash = password_hash($form['password'], PASSWORD_DEFAULT);
        $now = date('Y-m-d H:i:s');

        try {
            $sql = "INSERT INTO user (email, password, fullname, phone, role, active_token, status, last_login, created_at, updated_at)
                    VALUES (:email, :password, :fullname, :phone, :role, :active_token, :status, :last_login, :created_at, :updated_at)";

            $stmt = $conn->prepare($sql);

            $params = [
                ':email' => $form['email'],
                ':password' => $passwordHash,
                ':fullname' => $form['fullname'] !== '' ? $form['fullname'] : null,
                ':phone' => $form['phone'] !== '' ? $form['phone'] : null,
                ':role' => (string)$roleInt,
                ':active_token' => null,      // tạo active_token nếu cần (hiện để NULL)
                ':status' => $statusVal,
                ':last_login' => null,
                ':created_at' => $now,
                ':updated_at' => null
            ];

            $res = $stmt->execute($params);
            if ($res) {
                // Redirect về danh sách với thông báo thành công
                header('Location: ?module=admin&action=user_list&msg=' . urlencode('Thêm người dùng thành công'));
                exit;
            } else {
                $errors[] = 'Không thể thêm người dùng. Vui lòng thử lại.';
            }
        } catch (PDOException $ex) {
            $errors[] = 'Lỗi khi thêm user: ' . $ex->getMessage();
        }
    }
}
?>

<div class="container" style="margin-top:25px;">
    <div class="container-fluid">
        <h3>Thêm người dùng mới</h3>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" action="" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input id="email" name="email" type="email" class="form-control"
                    value="<?php echo htmlspecialchars($form['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <input id="password" name="password" type="password" class="form-control" minlength="6" required>
                <div class="form-text">Mật khẩu tối thiểu 6 ký tự.</div>
            </div>

            <div class="mb-3">
                <label for="fullname" class="form-label">Họ và tên</label>
                <input id="fullname" name="fullname" type="text" class="form-control"
                    value="<?php echo htmlspecialchars($form['fullname'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input id="phone" name="phone" type="text" class="form-control"
                    value="<?php echo htmlspecialchars($form['phone'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Nhóm (Role)</label>
                    <select id="role" name="role" class="form-select">
                        <?php foreach ($roles as $k => $v): ?>
                        <option value="<?php echo (int)$k; ?>"
                            <?php if ((int)$form['role'] === (int)$k) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select id="status" name="status" class="form-select">
                        <option value="1" <?php if ($form['status'] === '1') echo 'selected'; ?>>Kích hoạt</option>
                        <option value="0" <?php if ($form['status'] === '0') echo 'selected'; ?>>Vô hiệu</option>
                    </select>
                </div>

            </div>

            <div class="mb-3">
                <button class="btn btn-success" type="submit">Lưu</button>
                <a class="btn btn-secondary" href="?module=admin&action=user_list">Huỷ</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>