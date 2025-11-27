<?php
global $conn; 
checkAccessToken();

// --- LOGIC TỰ ĐỘNG ĐĂNG NHẬP (REMEMBER ME) ---
function checkRememberMe() {
    global $conn; 
    
    // Nếu user đã đăng nhập bằng session, không cần làm gì nữa
    if (getSession('user')) {
        return;
    }

    // Kiểm tra xem có cookie 'remember_me' tồn tại không
    if (isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
        
        try {
            //cột 'token' lưu chuỗi SHA1 thô
            $stmt = $conn->prepare('
                SELECT u.id, u.email, u.fullname, u.role
                FROM user u 
                JOIN token_login t ON u.id = t.user_id
                WHERE t.token = :token 
                LIMIT 1
            ');
            $stmt->execute([':token' => $token]);
            $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tokenData) {
                // Khôi phục Session và gia hạn token/cookie 
                session_regenerate_id(true); 
                
                setSession('user', [
                    'id' => $tokenData['id'],
                    'email' => $tokenData['email'], 
                    'fullname' => $tokenData['fullname'] ?? '',
                    'role' => $tokenData['role'] ?? ''
                ]);
                
                //Cập nhật last_login 
                $upd = $conn->prepare('UPDATE user SET last_login = NOW() WHERE id = :id');
                $upd->execute([':id' => $tokenData['id']]);
                
            } else {
                // Token không hợp lệ: Xóa cookie để buộc đăng nhập lại
                setcookie('remember_me', '', time() - 3600, '/');
            }

        } catch (PDOException $e) {
            error_log("Token Check DB Error: " . $e->getMessage());
        }
    }
}

//Gọi hàm kiểm tra Token ngay sau khi config được load
checkRememberMe(); 

// --- CÁC HÀM SESSION CƠ BẢN ---
// set session
function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }
    return false;
}

// get session
function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION ?? [];
    }
    if (isset($_SESSION[$key])) {
        //Trả về giá trị của khóa 1 lần
        return $_SESSION[$key];
    }
    return false;
}

// Xoá session
function removeSession($key = '')
{
    if (empty($key)) {
        $_SESSION = []; 
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        return true;
    }
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
        return true;
    }
    return false;
}

// set session flash
function setSessionFlash($key, $value)
{
    $key = $key . 'Flash';
    $rel = setSession($key, $value);
    return $rel;
}

// get session flash
function getSessionFlash($key)
{
    $key = $key . 'Flash';
    $rel = getSession($key);
    if ($rel !== false) { 
        removeSession($key);
    }
    return $rel;
}
