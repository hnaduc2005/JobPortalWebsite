<?php
checkAccessToken();
if (isPost()) {
    $filter = filterData();
    $errors = [];

    // ============================
    // Validate Email
    // ============================
    // Đổi tên biến và thông báo lỗi để chỉ tập trung vào Email
    $email = trim($filter['email'] ?? ''); 
    if (empty($email)) {
        $errors['email']['required'] = 'Địa chỉ Email bắt buộc phải nhập.'; 
    } else {
        // Có thể thêm kiểm tra định dạng email cơ bản (tùy chọn)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             $errors['email']['invalid'] = 'Địa chỉ Email không hợp lệ.'; 
        }
        // Giữ kiểm tra chiều dài (tối thiểu 5 ký tự là hợp lý cho email)
        if (strlen($email) < 5) { 
            $errors['email']['length'] = 'Email phải có ít nhất 5 ký tự.';
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
    }

    // ============================
    // Nếu không có lỗi validate -> kiểm tra DB
    // ============================
    if (empty($errors)) {
        try {
            // !!! Truy vấn CHỈ tìm kiếm theo email
            $stmt = $conn->prepare('
                SELECT id, email, password, fullname, role, status, is_verified 
                FROM user 
                WHERE email = :email 
                LIMIT 1
            ');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && !empty($user['password'])) {
                
                // Kiểm tra trạng thái tài khoản (nên làm trước kiểm tra mật khẩu)
                if ($user['status'] == '0') {
                    $errors['general'] = 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.';
                } 
                // Có thể thêm kiểm tra is_verified ở đây nếu cần thiết

                if (empty($errors['general'])) {
                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        // Đăng nhập thành công
                        session_regenerate_id(true);
                        
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                            'email' => $user['email'], 
                            'fullname' => $user['fullname'] ?? '',
                            'role' => $user['role'] ?? ''
                        ];

                        // Cập nhật last_login
                        try {
                            $upd = $conn->prepare('UPDATE user SET last_login = NOW() WHERE id = :id');
                            $upd->execute([':id' => $user['id']]);
                        } catch (Exception $x) {
                            // Không block login nếu update thất bại
                        }

                        // Flash thành công và redirect
                        setSessionFlash('msg', 'Đăng nhập thành công!');
                        setSessionFlash('msg_type', 'success');
                        // Tùy chọn: Điều hướng dựa trên role
                        if ($user['role'] === '1') {
                            header('Location: ' . BASE_URL . '/?module=candidate&action=homepage');
                        } 
                        exit;
                    } else {
                        $errors['general'] = 'Email hoặc mật khẩu không đúng.';
                    }
                }
            } else {
                $errors['general'] = 'Email hoặc mật khẩu không đúng.';
            }
        } catch (PDOException $ex) {
            error_log("Login DB Error: " . $ex->getMessage());
            $errors['general'] = 'Có lỗi hệ thống. Vui lòng thử lại sau.';
        }
    }

    if ($checkStatus) { // Giả định mật khẩu đã được password_verify() xác minh
    
    // --- BƯỚC 1: THIẾT LẬP SESSION THÔNG THƯỜNG ---
        session_regenerate_id(true); // Tăng cường bảo mật session
        setSession('user', [
            'id' => $checkEmail['id'],
            'email' => $checkEmail['email'], 
            'fullname' => $checkEmail['fullname'] ?? '',
            'role' => $checkEmail['role'] ?? ''
        ]);
        
        // (Tùy chọn) Cập nhật last_login
        global $conn;
        $upd = $conn->prepare('UPDATE user SET last_login = NOW() WHERE id = :id');
        $upd->execute([':id' => $checkEmail['id']]);


        // --- BƯỚC 2: TẠO VÀ LƯU TOKEN NẾU CHỌN "NHỚ TÔI" ---
        
        // Giả định bạn có biến này từ form (ví dụ: $data['remember_me'])
        $rememberMe = !empty($data['remember_me']); 

        if ($rememberMe) {
            // 1. Tạo token theo phương pháp trong hình ảnh (SHA1)
            $token = sha1(uniqid() . time()); 

            // 2. Chuẩn bị dữ liệu DB
            $dataToken = [
                'user_id'    => $checkEmail['id'],   
                'token'      => $token, // Lưu token thô (theo yêu cầu của bạn)
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m:d H:i:s')
            ];

            // 3. Thực hiện INSERT vào bảng token_login (Sử dụng PDO trực tiếp)
            try {
                $stmtToken = $conn->prepare('
                    INSERT INTO token_login (user_id, token, created_at, updated_at) 
                    VALUES (:user_id, :token, :created_at, :updated_at)
                ');
                $stmtToken->execute($dataToken);

                // 4. Thiết lập Cookie cho trình duyệt (Hết hạn sau 30 ngày)
                $expiryTime = time() + (30 * 24 * 3600); // 30 ngày
                
                setcookie('remember_me', $token, [
                    'expires' => $expiryTime,
                    'path' => '/',
                    'httponly' => true, // BẮT BUỘC: Bảo vệ khỏi XSS
                    // 'secure' => true, // Chỉ bật nếu website chạy trên HTTPS
                    'samesite' => 'Lax'
                ]);

            } catch (PDOException $e) {
                error_log("Token DB Error: " . $e->getMessage());
                // Lỗi DB khi lưu token -> Log lỗi nhưng vẫn cho đăng nhập
            }
        }
        
        // --- BƯỚC 3: PHẢN HỒI VÀ CHUYỂN HƯỚNG ---
        setSessionFlash('msg', 'Đăng nhập thành công.');
        setSessionFlash('msg_type', 'success');
        // header('Location: ' . BASE_URL); 
        // exit();

    } else {
        // Mật khẩu không đúng
        setSessionFlash('msg', 'Vui lòng kiểm tra lại mật khẩu.');
        setSessionFlash('msg_type', 'danger');
    }
// ... (Chuyển hướng hoặc hiển thị lỗi) ...

    // Nếu có lỗi -> lưu flash và redirect
    if (!empty($errors)) {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
        
        $redirectTo = $_SERVER['REQUEST_URI'] ?? (BASE_URL . '/?module=admin&action=login');
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
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href=" <?php echo BASE_URL ?>  /assets/css/Candidate/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Ứng viên đăng nhập</h1>
            <p>Đăng nhập để tiếp tục</p>
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

        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
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
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu"
                    autocomplete="current-password" class="<?php echo !empty($errors['password']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['password'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['password'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="forgot-password">
                <a href="<?php echo BASE_URL; ?>/?module=candidate&action=forgot">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>

        <div class="divider">
            <span>hoặc</span>
        </div>

        <div class="register-link">
            <p>Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>/?module=candidate&action=register">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</body>

</html>