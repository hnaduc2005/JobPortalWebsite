<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// BƯỚC 1: INCLUDE CÁC FILE CẦN THIẾT
// Đảm bảo đường dẫn require_once là chính xác
require_once __DIR__ . '/../../../core/config/config.php';
require_once __DIR__ . '/../../../core/includes/session.php';
global $conn;

// Khởi tạo mảng phản hồi mặc định
$response = ['success' => false, 'message' => 'Lỗi hệ thống không xác định.'];

// --- 1. XỬ LÝ LOGIC ĐĂNG XUẤT ---

if (getSession('user')) {
    
    // XÓA TOKEN ĐĂNG NHẬP DÀI HẠN (REMEMBER ME)
    if (isset($_COOKIE['remember_me'])) {
        $tokenToDelete = $_COOKIE['remember_me'];
        
        // Chỉ xóa nếu kết nối DB sẵn sàng và token không rỗng
        if (isset($conn) && $conn && !empty($tokenToDelete)) { 
            try {
                // Xóa token khỏi bảng token_login
                $stmt = $conn->prepare('DELETE FROM token_login WHERE token = :token');
                $stmt->execute([':token' => $tokenToDelete]);
            } catch (PDOException $e) {
                // Log lỗi DB nhưng không làm hỏng phản hồi JSON
                error_log("Logout Token DB Error: " . $e->getMessage());
            }
        }
        // Luôn xóa cookie trên client
        setcookie('remember_me', '', time() - 3600, '/'); 
    }

    // XÓA SESSION CHÍNH
    removeSession();
    
    // Thiết lập phản hồi thành công
    $response = ['success' => true, 'message' => 'Đăng xuất thành công. Trang sẽ được làm mới.'];
    
} else {
    // Nếu chưa đăng nhập
    $response = ['success' => false, 'message' => 'Bạn chưa đăng nhập.'];
    http_response_code(401); // Gửi Status 401 cho trường hợp Unauthorized
}

// --- 2. TRẢ VỀ PHẢN HỒI JSON SẠCH SẼ ---

// BƯỚC QUAN TRỌNG NHẤT ĐỂ SỬA LỖI JSON
// 1. Vô hiệu hóa/Xóa tất cả các output trước đó khỏi bộ đệm.
// Lệnh này đảm bảo mọi Warning/Notice/khoảng trắng đều bị xóa trước khi gửi JSON.
if (ob_get_length()) {
    ob_end_clean(); 
}

// 2. Thiết lập header báo hiệu nội dung JSON
header('Content-Type: application/json');

// 3. TRẢ VỀ JSON
echo json_encode($response);
exit();
