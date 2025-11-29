<?php
// Lấy token từ URL
$token = trim($_GET['token'] ?? '');

// Kiểm tra token có hợp lệ không
$tokenValid = false;
$userData = null;

if (!empty($token)) {
    try {
        // Tìm user có token này
        $stmt = $conn->prepare('
            SELECT id, email, fullname, active_token 
            FROM user 
            WHERE active_token = :token 
            LIMIT 1
        ');
        $stmt->execute([':token' => $token]);
        $userData = $stmt->fetch();

        if ($userData) {
            $tokenValid = true;
        }
    } catch (PDOException $ex) {
        error_log($ex->getMessage());
    }
}

// Nếu token không hợp lệ -> redirect về forgot
if (!$tokenValid) {
    setSessionFlash('msg', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
    setSessionFlash('msg_type', 'danger');
    header('Location: ' . BASE_URL . '/?module=candidate&action=forgot');
    exit;
}

// Xử lý form submit
if (isPost()) {
    $filter = filterData();
    $errors = [];

    // ============================
    // Validate Password
    // ============================
    $password = trim($filter['password'] ?? '');
    if (empty($password)) {
        $errors['password']['required'] = 'Mật khẩu mới bắt buộc phải nhập';
    } else {
        if (strlen($password) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        if (strlen($password) > 100) {
            $errors['password']['max'] = 'Mật khẩu không được vượt quá 100 ký tự';
        }
    }

    // ============================
    // Validate Confirm Password
    // ============================
    $confirm_password = trim($filter['confirm_password'] ?? '');
    if (empty($confirm_password)) {
        $errors['confirm_password']['required'] = 'Xác nhận mật khẩu bắt buộc phải nhập';
    } else {
        if ($confirm_password !== $password) {
            $errors['confirm_password']['match'] = 'Mật khẩu xác nhận không khớp';
        }
    }

    // ============================
    // Nếu không có lỗi validate -> cập nhật password
    // ============================
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Cập nhật password và xóa token
            $stmt = $conn->prepare('
                UPDATE user 
                SET password = :password, 
                    active_token = :empty_token,
                    updated_at = NOW() 
                WHERE id = :id
            ');
            
            $result = $stmt->execute([
                ':password' => $hashedPassword,
                ':empty_token' => '', // Xóa token sau khi đã dùng
                ':id' => $userData['id']
            ]);

            if ($result) {
                // Đặt lại mật khẩu thành công
                setSessionFlash('msg', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
                setSessionFlash('msg_type', 'success');
                header('Location: ' . BASE_URL . '/?module=candidate&action=login');
                exit;
            } else {
                $errors['general'] = 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại.';
            }
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            $errors['general'] = 'Có lỗi hệ thống. Vui lòng thử lại sau.';
        }
    }

    // Nếu có lỗi -> lưu flash và redirect
    if (!empty($errors)) {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('errors', $errors);
        
        header('Location: ' . BASE_URL . '/?module=candidate&action=reset&token=' . $token);
        exit();
    }
}

// Lấy flash để hiển thị
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <link rel="stylesheet" href="https://timviec.io.vn/JobPortalWebsite/assets/css/Candidate/reset.css">
</head>

<body>
    <div class="reset-container">
        <div class="reset-header">
            <div class="icon">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1>Đặt lại mật khẩu</h1>
            <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
        </div>

        <div class="user-info">
            Đặt lại mật khẩu cho: <span><?php echo htmlspecialchars($userData['email']); ?></span>
        </div>

        <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($msg_type); ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($errors['general']); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                    class="<?php echo !empty($errors['password']) ? 'error' : ''; ?>" autocomplete="new-password">
                <?php if (!empty($errors['password'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['password'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới"
                    class="<?php echo !empty($errors['confirm_password']) ? 'error' : ''; ?>"
                    autocomplete="new-password">
                <?php if (!empty($errors['confirm_password'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['confirm_password'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Đặt lại mật khẩu</button>
        </form>

        <div class="back-to-login">
            <a href="<?php echo BASE_URL; ?>/?module=candidate&action=login">
                <i class="fa-solid fa-arrow-left"></i> Quay lại đăng nhập
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>