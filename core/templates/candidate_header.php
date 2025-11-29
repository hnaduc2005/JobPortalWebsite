<?php
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/includes/session.php';

//XỬ LÝ LOGIC KIỂM TRA ĐĂNG NHẬP
// Lấy thông tin người dùng từ Session
$currentUser = getSession('user');
// Kiểm tra nếu đã đăng nhập VÀ role là Candidate ('1')
$isLoggedIn = ($currentUser !== false && !empty($currentUser) && ($currentUser['role'] === '1'));

$displayName = '';
$userRole = null;

if ($isLoggedIn) {
    // Ưu tiên hiển thị fullname
    $displayName = htmlspecialchars($currentUser['fullname'] ?? $currentUser['email']);
    if (empty(trim($displayName))) {
        $displayName = htmlspecialchars($currentUser['email']);
    }
    // Giới hạn độ dài tên hiển thị
    if (mb_strlen($displayName) > 15) {
        $displayName = mb_substr($displayName, 0, 15) . '...';
    }
}

// --- BƯỚC 3: ĐỊNH NGHĨA CÁC URL VÀ VAI TRÒ ---
$homeUrl = BASE_URL;
$loginUrl = BASE_URL . '?module=candidate&action=login';
$employerLoginUrl = 'https://employer.timviec.io.vn/';
$logoutUrl = BASE_URL . '?module=candidate&action=logout';
$isCandidate = $isLoggedIn; // Chỉ cần dùng $isLoggedIn
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/Candidate/homePage.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/themify-icons@1.0.1-alpha.3/themify-icons.min.css">
    <title>Header</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="inner-wrapper">
                <div class="inner-left">
                    <div class="inner_logo">
                        <a href="<?php echo $homeUrl; ?>"><img src="./assets/images/logo1.png" alt="logo"></a>
                    </div>
                    <div class="inner_drop_down">
                        <div class="inner inner-one">
                            <span class="title">Việc làm <i class="fa-solid fa-caret-down"></i></span>
                            <ul class="submenu-main">
                                <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i> Tìm việc làm</a></li>

                                <?php if ($isLoggedIn): ?>
                                <li class="has-child">
                                    <div class="parent-item">
                                        <div class="click">
                                            <span><i class="fa-solid fa-suitcase"></i> Quản lý việc làm</span>
                                            <i class="fa-solid fa-chevron-down arrow"></i>
                                        </div>
                                        <ul class="submenu-listchild">
                                            <li><a
                                                    href="<?php echo BASE_URL; ?>/?module=candidate&action=applied-job_page"><i
                                                        class="fa-solid fa-circle"></i> Việc làm đã ứng tuyển</a></li>
                                            <li><a
                                                    href="<?php echo BASE_URL; ?>/?module=candidate&action=saved-job_page"><i
                                                        class="fa-solid fa-circle"></i> Việc làm đã lưu</a></li>
                                            <li><a
                                                    href="<?php echo BASE_URL; ?>/?module=candidate&action=wait-job_page"><i
                                                        class="fa-solid fa-circle"></i> Việc làm chờ ứng tuyển</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="inner inner-two">
                            <span class="title">Công cụ <i class="fa-solid fa-caret-down"></i></span>
                            <ul class="submenu-main">
                                <li><i class="fa-regular fa-face-smile"></i> <span>Trắc nghiệm tính cách</span></li>
                                <li><i class="fa-solid fa-calculator"></i> <span>Tính Lương Gross sang Net</span></li>
                                <li><i class="fa-solid fa-wand-magic-sparkles"></i> <span>Tạo CV</span></li>
                            </ul>
                        </div>
                        <a href="#" class="inner inner-three" style="color:white">Cẩm nang nghề nghiệp</a>
                    </div>
                </div>

                <div class="inner-right">

                    <?php if ($isLoggedIn): ?>
                    <div class="inner-item">
                        <div class="item item-one">
                            <button><i class="fa-regular fa-bell"></i></button>
                        </div>

                        <div class="item item-two">
                            <button>
                                <img src="./assets/images/reference_logo.png" alt="personal logo">
                                <span><?php echo $displayName; ?></span> <i class="fa-solid fa-caret-down"></i>
                            </button>

                            <ul class="submenu-main">
                                <li><a href="<?php echo BASE_URL; ?>/modules/candidate/profile.php"><i
                                            class="fa-solid fa-user"></i> Hồ sơ của tôi</a></li>

                                <li class="has-child">
                                    <div class="parent-item">
                                        <div class="click">
                                            <span><i class="fa-solid fa-suitcase"></i> Quản lý việc làm</span>
                                            <i class="fa-solid fa-chevron-down arrow"></i>
                                        </div>
                                        <ul class="submenu-listchild">
                                            <li><a
                                                    href="<?php echo BASE_URL; ?>/modules/candidate/applied-job_page.php"><i
                                                        class="fa-solid fa-circle"></i> Việc làm đã ứng tuyển</a></li>
                                            <li><a
                                                    href="<?php echo BASE_URL; ?>/modules/candidate/applied-job_page.php"><i
                                                        class="fa-solid fa-circle"></i> Việc làm đã lưu</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/modules/candidate/wait-job_page.php"><i
                                                        class="fa-solid fa-circle"></i> Việc làm chờ ứng tuyển</a></li>
                                            <li><a href="#"><i class="fa-solid fa-circle"></i> Nhà tuyển dụng xem hồ
                                                    sơ</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="<?php echo BASE_URL; ?>/modules/candidate/account_manage.php"><i
                                            class="fa-solid fa-gear"></i> Quản lý tài khoản</a></li>

                                <li><a href="<?php echo $logoutUrl; ?>" id="logoutButton"><i
                                            class="fa-solid fa-sign-out-alt"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>

                    <?php else: ?>
                    <div class="inner-item">
                        <div class="text-white-1">
                            <span class="text-inform">Người Tìm Việc</span>
                            <a href="<?php echo $loginUrl; ?>" class="text-register" style="color: #fff">Đăng ký/Đăng
                                nhập</a>
                        </div>

                        <div class="text-white-2">
                            <div class="icon"><i class="fa-solid fa-suitcase"></i></div>
                            <div class="employer">
                                <span class="text-inform">Dành cho</span>
                                <a href="<?php echo $employerLoginUrl; ?>" class="text-register" style="color: #fff">Nhà
                                    Tuyển Dụng</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="inner-translate">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Flag_of_the_United_States.svg/1280px-Flag_of_the_United_States.svg.png"
                            alt="flag">
                    </div>
                </div>
            </div>
    </header>
    <main>