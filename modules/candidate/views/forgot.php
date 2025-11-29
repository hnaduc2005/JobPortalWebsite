<?php
if (isPost()) {
    $filter = filterData();
    $errors = [];

    // Validate Email
    $email = trim($filter['email'] ?? '');
    if (empty($email)) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email']['isEmail'] = 'Email không đúng định dạng';
        }
    }

    // Nếu không có lỗi validate -> kiểm tra DB
    if (empty($errors)) {
        try {
            // Kiểm tra email có tồn tại trong hệ thống
            $stmt = $conn->prepare('SELECT id, email, fullname FROM user WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Tạo token reset password (random + unique)
                $resetToken = bin2hex(random_bytes(32)); // Token 64 ký tự
                $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token hết hạn sau 1 giờ

                // Lưu token vào database (sử dụng cột active_token)
                $stmt = $conn->prepare('
                    UPDATE user 
                    SET active_token = :token, 
                        updated_at = NOW() 
                    WHERE id = :id
                ');
                $stmt->execute([
                    ':token' => $resetToken,
                    ':id' => $user['id']
                ]);

                // Tạo link reset password
                $resetLink = BASE_URL . '/?module=candidate&action=reset&token=' . $resetToken;

                // Gửi email
                $to = $user['email'];
                $subject = 'Yêu cầu đặt lại mật khẩu - JobPortal'; 
                
                $message = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #451DA0; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                        .button { display: inline-block; padding: 12px 30px; background: #451DA0; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                        .footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Đặt lại mật khẩu</h2>
                        </div>
                        <div class="content">
                            <p>Xin chào <strong>' . htmlspecialchars($user['fullname']) . '</strong>,</p>
                            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                            <p>Vui lòng nhấp vào nút bên dưới để đặt lại mật khẩu:</p>
                            <p style="text-align: center;">
                                <a href="' . $resetLink . '" class="button" style="color: white">Đặt lại mật khẩu</a>
                            </p>
                            <p>Hoặc copy link sau vào trình duyệt:</p>
                            <p style="background: #fff; padding: 10px; border: 1px solid #ddd; word-break: break-all;">
                                ' . $resetLink . '
                            </p>
                            <p><strong>Lưu ý:</strong> Link này chỉ có hiệu lực trong vòng <strong>1 giờ</strong>.</p>
                            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                        </div>
                        <div class="footer">
                            <p>&copy; ' . date('Y') . ' JobPortal. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: ' . 'JobPortal' . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>' . "\r\n";

                // Gửi email
                if (sendMail($to, $subject, $message)) {
                    setSessionFlash('msg', 'Đã gửi link đặt lại mật khẩu đến email của bạn. Vui lòng kiểm tra hộp thư.');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Có lỗi khi gửi email. Vui lòng thử lại sau.');
                    setSessionFlash('msg_type', 'danger');
                }

                header('Location: ' . BASE_URL . '/?module=candidate&action=forgot');
                exit;
            } else {
                $errors['email']['notFound'] = 'Email không tồn tại trong hệ thống';
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
        
        $redirectTo = $_SERVER['REQUEST_URI'] ?? (BASE_URL . '/?module=candidate&action=forgot');
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
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://timviec.io.vn/JobPortalWebsite/assets/css/Candidate/loginforgot.css">
</head>

<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <div class="icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1>Quên mật khẩu?</h1>
            <p>Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu</p>
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
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email của bạn"
                    value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                    class="<?php echo !empty($errors['email']) ? 'error' : ''; ?>">
                <?php if (!empty($errors['email'])): ?>
                <span class="error-message">
                    <?php echo htmlspecialchars(reset($errors['email'])); ?>
                </span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Gửi link đặt lại mật khẩu</button>
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