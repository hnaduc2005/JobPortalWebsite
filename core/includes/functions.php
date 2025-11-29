<?php
// Kiểm tra phương thức post
function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Kiểm tra phương thức get
function isGet() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Hàm filterData
function filterData($method = '')
{
    $data = [];

    // Xác định nguồn dữ liệu
    if (strtolower($method) === 'post') {
        $data = $_POST;
    } elseif (strtolower($method) === 'get') {
        $data = $_GET;
    } else {
        // Nếu không chỉ định -> xác định theo request hiện tại
        if (isPost()) {
            $data = $_POST;
        } elseif (isGet()) {
            $data = $_GET;
        }
    }

    // Nếu rỗng -> trả về mảng trống
    if (empty($data)) {
        return [];
    }

    // Làm sạch dữ liệu (cả mảng)
    $cleanData = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $cleanData[$key] = array_map(function ($item) {
                return htmlspecialchars(trim(stripslashes($item)), ENT_QUOTES, 'UTF-8');
            }, $value);
        } else {
            $cleanData[$key] = htmlspecialchars(trim(stripslashes($value)), ENT_QUOTES, 'UTF-8');
        }
    }

    return $cleanData;
}

// thông báo error/success
function getMsg($msg, $type = 'success') {
    $bgClass = [
        'success' => 'bg-success text-white',
        'error'   => 'bg-danger text-white',
        'warning' => 'bg-warning text-dark',
        'info'    => 'bg-info text-dark'
    ];
    $class = isset($bgClass[$type]) ? $bgClass[$type] : $bgClass['info'];

    echo '
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast show '.$class.'" role="alert">
            <div class="toast-body">
                '.$msg.'
            </div>
        </div>
    </div>';
}

// thông báo từng lỗi
function errorMessage($errorsArr, $inputName) {
    if (!empty($errorsArr[$inputName])) {
        echo '<div class="error-message">'
            . htmlspecialchars($errorsArr[$inputName]) .
        '</div>';
    }
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Hàm gửi Mail

function sendMail($emailTo, $subject, $content) {

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'mail.timviec.io.vn';   
        $mail->SMTPAuth   = true;
        $mail->Username   = 'check_register@timviec.io.vn'; 
        $mail->Password   = 'Kw~tLXe7vLl)';        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     // SSL
        $mail->Port       = 465;                            

        //Recipients
        $mail->setFrom('check_register@timviec.io.vn', 'Job Portal');
        $mail->addAddress($emailTo);

        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;

        // Fix lỗi SSL (nếu host tự ký chứng chỉ)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        return $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}