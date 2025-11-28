<?php 
checkAccessToken();

if (isPost()) {
    $filter = filterData();
    $errors = [];

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
    }

    // ============================
    // Validate Password
    // ============================
    $password = trim($filter['password'] ?? '');

    if (empty($password)) {
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    } else {
        if (strlen($password) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
        }
    }

    // ============================
    // Nếu không có lỗi validate -> kiểm tra DB
    // ============================
    if (empty($errors)) {
        try {
            // Ở đây giả định bảng users có cột password chứa password đã hash bằng password().
            $stmt = $conn->prepare('SELECT id, email, password, fullname, role FROM user WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && !empty($user['password'])) {
                // So sánh trực tiếp với password từ DB
                if ($password === $user['password']) {
                    // Đăng nhập thành công
                    session_regenerate_id(true);
            
                    // Lưu user vào session (không lưu password)
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'fullname' => $user['fullname'] ?? '',
                        'role' => $user['role'] ?? ''
                    ];
            
                    // (Tùy chọn) cập nhật last_login
                    try {
                        $upd = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = :id');
                        $upd->execute([':id' => $user['id']]);
                    } catch (Exception $x) {
                        // Không block login nếu update thất bại
                    }
            
                    // Flash thành công và redirect
                    setSessionFlash('msg', 'Đăng nhập thành công. Chuyển đến trang quản trị...');
                    setSessionFlash('msg_type', 'success'); 
                    header('Location: ' . BASE_URL . '/?module=admin&action=dashboard');
                    exit;
                } else {
                    $errors['general'] = 'Email hoặc mật khẩu không đúng.';
                }
            } else {
                $errors['general'] = 'Email hoặc mật khẩu không đúng.';
            }
            
        } catch (PDOException $ex) {
            // Log lỗi vào file/logging system nếu có; hiển thị thông báo chung cho user
            $errors['general'] = 'Có lỗi hệ thống. Vui lòng thử lại sau.';
        }
    }

    // Nếu có lỗi (validate/DB) -> lưu flash và redirect về trang login (để hiển thị lỗi)
    if (!empty($errors)) {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);

        // Redirect về chính trang hiện tại (GET) để hiển thị flash
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Đăng nhập hệ thống</title>
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">

                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="<?php echo BASE_URL; ?>/assets/images/login.webp" class="img-fluid" alt="Login Image">
                </div>

                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                    <?php 
                    if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    }
                    ?>

                    <form method="POST" action="">
                        <div class="mb-4">
                            <h2 class="fw-normal mb-0">Đăng nhập hệ thống</h2>
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-3">
                            <input type="email" name="email" class="form-control form-control-lg"
                                placeholder="Vui lòng nhập email"
                                value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                        </div>

                        <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger mb-3">
                            <?php foreach ($errors['email'] as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <input type="password" name="password" class="form-control form-control-lg"
                                placeholder="Vui lòng nhập mật khẩu">
                        </div>

                        <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger mb-3">
                            <?php foreach ($errors['password'] as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($errors['general'])): ?>
                        <div class="text-danger mb-3">
                            <?php echo htmlspecialchars($errors['general']); ?>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="<?php echo BASE_URL;?>/?module=admin&action=forgot" class="text-body">Quên mật
                                khẩu?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Đăng nhập
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
    .h-custom {
        height: calc(100% - 73px);
    }

    @media (max-width: 450px) {
        .h-custom {
            height: 100%;
        }
    }
    </style>
</body>

</html>