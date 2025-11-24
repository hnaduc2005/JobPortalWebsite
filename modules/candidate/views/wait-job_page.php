<?php include __DIR__ . '/../../core/templates/header.php'; ?>
<div class="container">
    <div class="inner-wrapper">
        <aside class="sidebar">
            <div class="username">Trong Suu</div>

            <ul class="submenu-main-content">
                <li><i class="fa-solid fa-magnifying-glass"></i> Hồ sơ của tôi</li>
                <li class="has-child-one">
                    <div class="parent-item">
                        <div class="clickd">
                            <span><i class="fa-solid fa-suitcase"></i> Quản lý việc làm</span>
                            <i class="fa-solid fa-chevron-down arrow"></i>
                        </div>
                        <ul class="submenu-listchild-contetnt">
                            <li><a href="./applied-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã ứng tuyển</a></li>
                            <li><a href="./saved-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã lưu</a></li>
                            <li><a href="./wait-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm chờ ứng tuyển</a></li>
                            <li><a href="#"><i class="fa-solid fa-circle"></i> Nhà tuyển dụng xem hồ sơ</a></li>
                        </ul>
                    </div>
                </li>
                <li><i class="fa-regular fa-circle-user"></i>Quản lý tài khoản</li>
            </ul>
        </aside>

        <div class="content">
            <div class="content-two">
                <div class="title">
                    <span class="title-one">Việc làm ứng tuyển</span>
                </div>
                <div class="display-inform">
                    <div class="status-inform">Bạn chưa có việc làm chờ ứng tuyển nào</div>
                    <div class="desc-inform">
                        <img src="/JobPortalWebsite/assets/images/nodata.svg" alt="empty">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php include __DIR__ . '/../../core/templates/footer.php'; ?>