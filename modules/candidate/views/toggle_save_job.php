<?php
header("Content-Type: application/json");

require_once getCurrentPath() . '/core/includes/connect.php';
require_once getCurrentPath() . '/core/config/config.php';
require_once getCurrentPath() . '/core/includes/session.php';

var_dump($_SESSION);
exit;

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập."]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$post_id = $_POST['post_id'];

// Lấy candidate_id
$stmt = $conn->prepare("SELECT id FROM candidate_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);

if ($stmt->rowCount() == 0) {
    echo json_encode(["status" => "error", "message" => "Bạn chưa có hồ sơ ứng viên!"]);
    exit;
}

$candidate_id = $stmt->fetch()['id'];

// Kiểm tra tồn tại
$check = $conn->prepare("
    SELECT id FROM saved_jobs 
    WHERE candidate_id = ? AND post_id = ?
");
$check->execute([$candidate_id, $post_id]);

if ($check->rowCount() > 0) {
    // Đã lưu → BỎ LƯU
    $delete = $conn->prepare("DELETE FROM saved_jobs WHERE candidate_id = ? AND post_id = ?");
    $delete->execute([$candidate_id, $post_id]);

    echo json_encode([
        "status" => "success",
        "action" => "unsaved",
        "message" => "Đã bỏ lưu công việc!"
    ]);
    exit;
}

// Chưa lưu → LƯU
$insert = $conn->prepare("
    INSERT INTO saved_jobs (candidate_id, post_id, saved_at) 
    VALUES (?, ?, ?)
");
$insert->execute([$candidate_id, $post_id, time()]);

echo json_encode([
    "status" => "success",
    "action" => "saved",
    "message" => "Đã lưu công việc!"
]);
