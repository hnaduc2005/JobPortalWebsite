<?php
if (isPost()) {
    $filter = filterData();
    $errors = [];

    // ============================
    // Validate Fullname
    // ============================
    $fullname = trim($filter['fullname'] ?? '');
    if (empty($fullname)) {
        $errors['fullname']['required'] = 'Họ và tên bắt buộc phải nhập';
    } else {
        if (strlen($fullname) < 3) {
            $errors['fullname']['length'] = 'Họ và tên phải có ít nhất 3 ký tự';
        }
        if (strlen($fullname) > 150) {
            $errors['fullname']['max'] = 'Họ và tên không được vượt quá 150 ký tự';
        }
    }

    // ============================
    // Validate Email
    // ============================
    $email = trim($filter['email'] ?? '');
    if (empty($email)) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email']['isEmail'] = 'Email không đúng định dạng';
        }
        if (strlen($email) > 100) {
            $errors['email']['max'] = 'Email không được vượt quá 100 ký tự';
        }
    }

    // ============================
    // Validate Phone (Optional)
    // ============================
    $phone = trim($filter['phone'] ?? '');
    if (!empty($phone)) {
        if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            $errors['phone']['format'] = 'Số điện thoại phải có 10-11 chữ số';
        }
        if (strlen($phone) > 15) {
            $errors['phone']['max'] = 'Số điện thoại không được vượt quá 15 ký tự';
        }
    }

    // ============================
    // Validate Password
    // ============================
    $password = trim($filter['password'] ?? '');
    if (empty($password)) {
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
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
    // Nếu không có lỗi validate -> kiểm tra DB
    // ============================
    if (empty($errors)) {
        try {
            // Kiểm tra email đã tồn tại
            $stmt = $conn->prepare('SELECT id FROM user WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $errors['email']['exists'] = 'Email đã được sử dụng';
            }

            // Kiểm tra phone đã tồn tại (nếu có nhập)
            if (!empty($phone)) {
                $stmt = $conn->prepare('SELECT id FROM user WHERE phone = :phone LIMIT 1');
                $stmt->execute([':phone' => $phone]);
                if ($stmt->fetch()) {
                    $errors['phone']['exists'] = 'Số điện thoại đã được sử dụng';
                }
            }

            // Nếu không có lỗi trùng lặp -> insert user mới
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Tạo active token để xác thực email (nếu cần)
                $activeToken = md5(uniqid() . time());
                
                $stmt = $conn->prepare('
                    INSERT INTO user (
                        email, 
                        password, 
                        fullname, 
                        phone, 
                        role, 
                        active_token, 
                        status, 
                        is_verified, 
                        created_at, 
                        updated_at
                    ) 
                    VALUES (
                        :email, 
                        :password, 
                        :fullname, 
                        :phone, 
                        :role, 
                        :active_token, 
                        :status, 
                        :is_verified, 
                        NOW(), 
                        NOW()
                    )
                ');
                
                $result = $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':fullname' => $fullname,
                    ':phone' => !empty($phone) ? $phone : null,
                    ':role' => '1', // 1 = candidate (ứng viên)
                    ':active_token' => $activeToken,
                    ':status' => '1', // 1 = active
                    ':is_verified' => 0 // Chưa xác thực email
                ]);

                if ($result) {
                    // Đăng ký thành công
                    setSessionFlash('msg', 'Đăng ký thành công! Vui lòng đăng nhập.');
                    setSessionFlash('msg_type', 'success');
                    
                    // TODO: Gửi email xác thực tài khoản nếu cần
                    // sendVerificationEmail($email, $activeToken);
                    
                    header('Location: ' . BASE_URL . '/?module=candidate&action=login');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.';
                }
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
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
        
        $redirectTo = $_SERVER['REQUEST_URI'] ?? (BASE_URL . '/?module=candidate&action=register');
        header('Location: ' . $redirectTo);
        exit();
    }
}

// Lấy flash để hiển thị
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$old = getSessionFlash('oldData');
$errors = getSessionFlash('errors');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Ứng Viên</title>
    <link rel="stylesheet" href="https://timviec.io.vn/JobPortalWebsite/assets/css/Candidate/register.css">
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Đăng ký ứng viên</h1>
            <p>Tạo tài khoản mới để tìm việc làm</p>
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

        <form id="registerForm" method="POST" action="">
            <div class="form-group">
                <label for="fullname">Họ và tên <span class="required">*</span></label>
                <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên đầy đủ"
                    value="<?php echo htmlspecialchars($old['fullname'] ?? ''); ?>"
                    class="<?php echo !empty($errors['fullname']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['fullname'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['fullname'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" autocomplete="email"
                    value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                    class="<?php echo !empty($errors['email']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['email'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['email'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại <span class="optional">(Không bắt buộc)</span></label>
                <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại (10-11 số)"
                    value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"
                    class="<?php echo !empty($errors['phone']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['phone'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['phone'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu <span class="required">*</span></label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                    autocomplete="new-password" class="<?php echo !empty($errors['password']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['password'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['password'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu <span class="required">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu"
                    autocomplete="new-password"
                    class="<?php echo !empty($errors['confirm_password']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['confirm_password'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['confirm_password'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-register">Đăng ký</button>
        </form>

        <div class="divider">
            <span>hoặc</span>
        </div>

        <div class="login-link">
            <p>Đã có tài khoản? <a href="<?php echo BASE_URL; ?>/?module=candidate&action=login">Đăng nhập ngay</a></p>
        </div>
    </div>
</body>

</html>