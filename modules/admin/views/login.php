<?php 
checkAccessToken();

// Lấy flash message
$msg       = getSessionFlash('msg');
$msg_type  = getSessionFlash('msg_type');
$old       = getSessionFlash('oldData');
$errors    = getSessionFlash('errors') ?: [];

// =============================
// XỬ LÝ LOGIN
// =============================
if (isPost()) {

    $filter   = filterData();
    $email    = trim($filter['email'] ?? '');
    $password = trim($filter['password'] ?? '');

    $errors = [];

    // Validate email
    if ($email === '') {
        $errors['email'][] = 'Email bắt buộc phải nhập.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'][] = 'Email không đúng định dạng.';
    }

    // Validate password
    if ($password === '') {
        $errors['password'][] = 'Mật khẩu bắt buộc phải nhập.';
    }

    // Nếu không có lỗi validate → kiểm tra DB
    if (empty($errors)) {
        try {

            $stmt = $conn->prepare("
                SELECT id, email, password, fullname, role, status
                FROM user 
                WHERE email = :email 
                LIMIT 1
            ");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $errors['general'] = "Email hoặc mật khẩu không đúng.";
            } else {

                // Kiểm tra trạng thái tài khoản
                if ($user['status'] != '1') {
                    $errors['general'] = "Tài khoản đã bị vô hiệu hoá.";
                }

                // Kiểm tra role admin
                elseif ((int)$user['role'] !== 0) {
                    $errors['general'] = "Tài khoản không có quyền truy cập trang quản trị.";
                }

                // ⚠️ QUAN TRỌNG: Kiểm tra mật khẩu HASH
                elseif (!password_verify($password, $user['password'])) {
                    $errors['general'] = "Email hoặc mật khẩu không đúng.";
                }

                // Nếu không có lỗi → đăng nhập thành công
                else {

                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id'       => $user['id'],
                        'email'    => $user['email'],
                        'fullname' => $user['fullname'],
                        'role'     => $user['role']
                    ];

                    // Cập nhật last login
                    $u = $conn->prepare("UPDATE user SET last_login = NOW() WHERE id = :id");
                    $u->execute([':id' => $user['id']]);

                    setSessionFlash('msg', 'Đăng nhập thành công!');
                    setSessionFlash('msg_type', 'success');

                    header("Location: " . BASE_URL . "?module=admin&action=dashboard");
                    exit;
                }
            }

        } catch (PDOException $e) {
            $errors['general'] = "Lỗi hệ thống: " . $e->getMessage();
        }
    }

    // Nếu có lỗi → trả về form
    if (!empty($errors)) {
        setSessionFlash('msg', 'Vui lòng kiểm tra lại thông tin.');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('errors', $errors);
        setSessionFlash('oldData', $filter);

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

                    <?php if (!empty($msg)): ?>
                    <div class="alert alert-<?php echo $msg_type; ?>">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <h3 class="mb-4">Đăng nhập quản trị</h3>

                        <!-- Email -->
                        <div class="form-outline mb-3">
                            <input type="email" name="email" class="form-control form-control-lg"
                                placeholder="Nhập email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                        </div>
                        <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger mb-2">
                            <?php foreach ($errors['email'] as $e) echo "<div>$e</div>"; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Password -->
                        <div class="form-outline mb-3">
                            <input type="password" name="password" class="form-control form-control-lg"
                                placeholder="Nhập mật khẩu">
                        </div>
                        <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger mb-2">
                            <?php foreach ($errors['password'] as $e) echo "<div>$e</div>"; ?>
                        </div>
                        <?php endif; ?>

                        <!-- General error -->
                        <?php if (!empty($errors['general'])): ?>
                        <div class="text-danger mb-3">
                            <?php echo $errors['general']; ?>
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
                            Đăng nhập
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <style>
    .h-custom {
        height: calc(100% - 80px);
    }

    @media (max-width: 450px) {
        .h-custom {
            height: 100%;
        }
    }
    </style>

</body>

</html>