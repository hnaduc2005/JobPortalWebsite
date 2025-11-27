<?php include   'JobPortalWebsite/core/templates.candidate_header.php'; ?>
<!-- Nội Dung Trang hồ sơ của tôi -->
<section class="My_portfolio">
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
                                <li><a href=""><i class="fa-solid fa-circle"></i> Nhà tuyển dụng xem hồ sơ</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="./account_manage.php"><i class="fa-solid fa-circle"></i> Quản lý tài khoản</a></li>
                </ul>
            </aside>

            <div class="content-main">
                <div class="my-inform">
                    <div class="title">
                        <h2>Hồ Sơ Của Tôi</h2>
                    </div>
                    <div class="desc">
                        <div class="desc-wrapper">
                            <div class="item-1">
                                <div class="item-1-1">
                                    <div class="image">
                                        <img src="https://vieclam24h.vn/img/avatar.jpg" alt="avartar">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>

                                    <div class="information-person">
                                        <h3 class="name" id="display-name">Trong Suu</h3>

                                        <!-- Địa chỉ -->
                                        <p class="addres color open-modal" data-type="address" id="display-address">
                                            Thêm địa chỉ hiện tại
                                        </p>
                                    </div>
                                </div>

                                <div class="item-1-2">
                                    <div class="icon-fix open-modal">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </div>

                                    <div class="wrapper">
                                        <div class="email">
                                            <div class="email-inform"><i class="fa-regular fa-envelope"></i></div>
                                            <span id="display-email">abc@gmail.com</span>
                                        </div>
                                    </div>

                                    <!-- Số điện thoại -->
                                    <div class="phone">
                                        <div class="in4-form"><i class="fa-solid fa-mobile"></i>
                                            <span id="display-phone">123456789</span>
                                        </div>
                                        <div class="authen-phone"><i class="fa-solid fa-circle-check"></i></div>
                                    </div>

                                    <!-- Giới tính -->
                                    <div class="sex">
                                        <i class="fa-solid fa-user"></i>
                                        <span class="color open-modal" data-type="gender" id="display-gender">
                                            Thêm giới tính
                                        </span>
                                    </div>

                                    <!-- Ngày sinh -->
                                    <div class="birthday">
                                        <i class="fa-solid fa-cake-candles"></i>
                                        <span class="color open-modal" data-type="birthday" id="display-birthday">
                                            Thêm ngày sinh
                                        </span>
                                    </div>

                                    <!-- Hôn nhân -->
                                    <div class="marriage">
                                        <i class="fa-solid fa-ring"></i>
                                        <span class="color open-modal" data-type="marriage" id="display-marriage">
                                            Thêm tình trạng hôn nhân
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="desc-wrapper">
                            <div class="item-2">
                                <div class="title-cv">CV của tôi</div>

                                <div class="upload-cv">
                                    <!-- file ẩn -->
                                    <input type="file" id="cvInput" accept=".pdf,.doc,.docx" style="display:none;">

                                    <!-- nút bấm -->
                                    <div class="select-up" id="uploadBtn">
                                        <button type="button">
                                            <i class="fa-solid fa-upload"></i>
                                            <span>Tải lên cv có sẵn</span>
                                        </button>
                                    </div>

                                    <p class="desc">
                                        Hỗ trợ định dạng: doc, docx, pdf, tối đa 5MB
                                    </p>

                                    <!-- hiển thị tên file -->
                                    <p id="cvFileName" style="margin-top:10px; font-weight:600;"></p>
                                </div>
                            </div>
                        </div>


                        <div class="desc-wrapper">
                            <div class="item-3">
                                <div class="title-jobfind">
                                    <h2>Tiêu chí tìm việc</h2>
                                    <div class="icon open-popup"><i class="fa-regular fa-pen-to-square"></i></div>
                                </div>
                                <div class="hr"></div>

                                <div class="content">
                                    <div class="left">
                                        <div class="flex flex-1">
                                            <p class="title">Vị trí công việc</p>
                                            <p class="blue-content color open-popup">Thêm vị trí công việc</p>
                                        </div>
                                        <div class="flex flex-2">
                                            <p class="title">Ngành nghề</p>
                                            <p class="blue-content color open-popup">Thêm ngành nghề</p>
                                        </div>
                                        <div class="flex flex-3">
                                            <p class="title">Địa điểm tìm việc</p>
                                            <p class="blue-content color open-popup">Thêm địa điểm</p>
                                        </div>
                                    </div>

                                    <div class="right">
                                        <div class="flex flex-4">
                                            <p class="title">Mức lương mong muốn</p>
                                            <p class="blue-content color open-popup">Thêm mức lương</p>
                                        </div>
                                        <div class="flex flex-5">
                                            <p class="title">Hình thức làm việc</p>
                                            <p class="blue-content color open-popup">Thêm hình thức làm việc</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="desc-wrapper">
                            <div class="item-4">
                                <div class="title-jobfind">
                                    <h2>Thông tin chung</h2>
                                    <div class="icon"><i class="fa-regular fa-pen-to-square"></i></div>
                                </div>
                                <div class="hr"></div>
                                <div class="content">
                                    <div class="left">
                                        <div class="flex flex-1">
                                            <p class="title">Số năm kinh nghiệm</p>
                                            <p id="text-kinhnghiem" class="blue-content">Thêm số năm kinh nghiệm</p>
                                        </div>

                                        <div class="flex flex-2">
                                            <p class="title">Cấp bậc hiện tại</p>
                                            <p id="text-capbac" class="blue-content">Thêm cấp bậc hiện tại</p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="flex flex-3">
                                            <p class="title">Trình độ học vấn cao nhất</p>
                                            <p id="text-hocvan" class="blue-content">Thêm trình độ học vấn</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="desc-wrapper">
                            <div class="item-5">
                                <div class="title-jobfind open-work-exp-popup">
                                    <h2>Kinh nghiệm làm việc</h2>
                                    <div class="icon open-work-exp-popup"><i class="fa-regular fa-pen-to-square"></i></div>
                                </div>
                                <div class="hr"></div>
                                <div class="content" id="work-exp-display">
                                    <p>Giúp nhà tuyển dụng hiểu về kinh nghiệm làm việc của bạn</p>
                                </div>
                            </div>
                        </div>


                        <div class="desc-wrapper">
                            <div class="item-5" id="hocvan-box">
                                <div class="title-jobfind">
                                    <h2>Học vấn</h2>

                                    <!-- Nút mở popup Học vấn -->
                                    <button class="edit-btn" data-target="#edu-overlay">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                </div>

                                <div class="hr"></div>

                                <div class="content">
                                    <p id="hocvan-text">Giúp nhà tuyển dụng biết được trình độ học vấn của bạn</p>
                                </div>
                            </div>
                        </div>




                        <div class="desc-wrapper">
                            <div class="item-6" id="skill-box">
                                <div class="title-jobfind">
                                    <h2>Kỹ năng</h2>

                                    <!-- nút mở popup (dùng cả button và icon để tương thích) -->
                                    <button class="skill-edit-btn" data-target="#skill-overlay" aria-label="Chỉnh sửa kỹ năng">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                </div>

                                <div class="hr"></div>

                                <div class="content" id="skill-content">
                                    <p> Nổi bật hơn trong mắt nhà tuyển dụng với các kĩ năng quan trọng</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="Job_inform">
                    <div class="flex1">
                        <div class="title">
                            <h2>Việc làm gợi ý cho bạn</h2>
                        </div>
                        <div class="desc">
                            <a href="#" class="job-card">
                                <div class="inner">
                                    <div class="major"><span>Trưởng phòng hành chính nhân sự</span> <i class="fa-regular fa-heart"></i></div>
                                </div>
                                <div class="inner-two">
                                    <div class="image">
                                        <img src="https://cdn1.vieclam24h.vn/images/employer_avatar/2025/11/14/165043568763_176311347227.w-128.h-128.jpeg?v=220513%22" alt="Nguyen Cuong">
                                    </div>
                                    <div class="introduce">
                                        <h3>Công Ty Cổ Phần Nguyên Cường</h3>
                                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span>15 - 30 triệu</span></div>
                                        <div class="position">
                                            <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                        </div>
                                    </div>
                                    <div class="position">
                                        <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                    </div>
                                </div>
                                <div class="hr"></div>
                                <div class="coundown">
                                    <div class="space"></div>
                                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count">Còn 26 ngày</span></div>
                                </div>
                            </a>

                            <a href="#" class="job-card">
                                <div class="inner">
                                    <div class="major"><span>Trưởng phòng hành chính nhân sự</span> <i class="fa-regular fa-heart"></i></div>
                                </div>
                                <div class="inner-two">
                                    <div class="image">
                                        <img src="https://cdn1.vieclam24h.vn/tvn/asset/home/img/employer/5f73fc2e535c9_1601436718.w-128.h-128.jpg?v=220513" alt="Nguyen Cuong">
                                    </div>
                                    <div class="introduce">
                                        <h3>Công Ty Cổ Phần Nguyên Cường</h3>
                                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span>15 - 30 triệu</span></div>
                                        <div class="position">
                                            <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr"></div>
                                <div class="coundown">
                                    <div class="space"></div>
                                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count">Còn 26 ngày</span></div>
                                </div>
                            </a>

                            <a href="#" class="job-card">
                                <div class="inner">
                                    <div class="major"><span>Trưởng phòng hành chính nhân sự</span> <i class="fa-regular fa-heart"></i></div>
                                </div>
                                <div class="inner-two">
                                    <div class="image">
                                        <img src="https://cdn1.vieclam24h.vn/images/employer_avatar/2025/09/17/logo-prosper_175809727945.w-128.h-128.jpg?v=220513" alt="Nguyen Cuong">
                                    </div>
                                    <div class="introduce">
                                        <h3>Công Ty Cổ Phần Nguyên Cường</h3>
                                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span>15 - 30 triệu</span></div>
                                        <div class="position">
                                            <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr"></div>
                                <div class="coundown">
                                    <div class="space"></div>
                                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count">Còn 26 ngày</span></div>
                                </div>
                            </a>

                            <a href="#" class="job-card">
                                <div class="inner">
                                    <div class="major"><span>Trưởng phòng hành chính nhân sự</span> <i class="fa-regular fa-heart"></i></div>
                                </div>
                                <div class="inner-two">
                                    <div class="image">
                                        <img src="https://cdn1.vieclam24h.vn/images/employer_avatar/2025/05/29/11_174850107744.w-128.h-128.png?v=220513" alt="Nguyen Cuong">
                                    </div>
                                    <div class="introduce">
                                        <h3>Công Ty Cổ Phần Nguyên Cường</h3>
                                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span>15 - 30 triệu</span></div>
                                        <div class="position">
                                            <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr"></div>
                                <div class="coundown">
                                    <div class="space"></div>
                                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count">Còn 26 ngày</span></div>
                                </div>
                            </a>

                            <a href="#" class="job-card">
                                <div class="inner">
                                    <div class="major"><span>Trưởng phòng hành chính nhân sự</span> <i class="fa-regular fa-heart"></i></div>
                                </div>
                                <div class="inner-two">
                                    <div class="image">
                                        <img src="https://cdn1.vieclam24h.vn/images/old_employer_avatar/images/85b93ba31977b5085e6d6b5790b2f529_1546485884_logo_kizuna_2018_08.w-96.h-96.png" alt="Nguyen Cuong">
                                    </div>
                                    <div class="introduce">
                                        <h3>Công Ty Cổ Phần Nguyên Cường</h3>
                                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span>15 - 30 triệu</span></div>
                                        <div class="position">
                                            <i class="fa-thin fa-location-dot"></i> <span>TP.HCM</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr"></div>
                                <div class="coundown">
                                    <div class="space"></div>
                                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count">Còn 26 ngày</span></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Modal popup thông tin cá nhân-->
<div id="profileModal" class="modal">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Thông tin cá nhân</h2>
            <span class="close" id="closeProfile">&times;</span>
        </div>

        <div class="modal-body">

            <label>Họ và tên *</label>
            <input id="input-name" type="text" class="input">

            <label>Email *</label>
            <input id="input-email" type="email" class="input">

            <label>Số điện thoại *</label>
            <input id="input-phone" type="text" class="input" disabled>

            <label>Địa chỉ hiện tại *</label>
            <div class="row">
                <select id="tinh" class="input">
                    <option value="">Chọn tỉnh thành</option>
                </select>

                <select id="huyen" class="input">
                    <option value="">Chọn quận huyện</option>
                </select>
            </div>


            <label>Ngày sinh</label>
            <input id="input-birthday" type="date" class="input">

            <label>Giới tính</label>
            <div class="row gender-group">
                <button class="select-btn" data-value="Nữ">Nữ</button>
                <button class="select-btn" data-value="Nam">Nam</button>
            </div>

            <label>Tình trạng hôn nhân</label>
            <div class="row marry-group">
                <button class="select-btn" data-value="Độc thân">Độc thân</button>
                <button class="select-btn" data-value="Đã lập gia đình">Đã lập gia đình</button>
            </div>

        </div>

        <div class="modal-footer">
            <button class="cancel-btn" id="cancelProfile">Hủy</button>
            <button class="save-btn" id="saveProfile">Lưu thông tin</button>
        </div>
    </div>
</div>

<!-- Modal popup Tiêu chí làm việc -->
<!-- ======= POPUP TIÊU CHÍ ======= -->
<div class="popup-overlay" id="popup-tieuchi" style="display:none;">
    <div class="popup-box">
        <div class="popup-header">
            <h2>Tiêu chí tìm việc</h2>
            <span id="tieuchi-close-x" class="popup-close">&times;</span>
        </div>

        <div class="popup-body">
            <label>Vị trí mong muốn*</label>
            <input id="tc-vitri" type="text" placeholder="Nhập vị trí muốn ứng tuyển">

            <label>Ngành nghề làm việc*</label>
            <select id="tc-nganh">
                <option value="">Chọn ngành nghề</option>
                <option value="IT">IT</option>
                <option value="Kế toán">Kế toán</option>
                <option value="Marketing">Marketing</option>
            </select>

            <label>Địa điểm làm việc*</label>
            <select id="tc-diadiem">
                <option value="">Chọn địa điểm</option>
                <option value="Hà Nội">Hà Nội</option>
                <option value="TP HCM">TP HCM</option>
                <option value="Đà Nẵng">Đà Nẵng</option>
            </select>

            <label>Mức lương mong muốn*</label>
            <div class="salary-row">
                <input id="tc-luong-min" type="number" placeholder="Tối thiểu">
                <span>Triệu</span>
                <input id="tc-luong-max" type="number" placeholder="Tối đa">
                <span>Triệu</span>
            </div>

            <label>Hình thức làm việc</label>
            <select id="tc-hinhthuc">
                <option value="">Chọn hình thức</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Remote">Remote</option>
            </select>
        </div>

        <div class="popup-footer">
            <button id="tieuchi-cancel" class="popup-btn-cancel">Hủy</button>
            <button id="tieuchi-save" class="popup-btn-save">Lưu thông tin</button>
        </div>
    </div>
</div>


<!-- popup thông tin chung -->
<!-- ===== POPUP THÔNG TIN CHUNG ===== -->
<<!-- Nút mở popup -->

    <!-- Popup Thông tin chung - Độc lập -->
    <div id="ttc-overlay" class="popup-overlay" style="display:none;">
        <div class="ttc-box">
            <div class="ttc-header">
                <h2>Thông tin chung</h2>
                <span id="ttc-close-x" class="ttc-close-btn">&times;</span>
            </div>

            <div class="ttc-body">

                <label class="ttc-label">Kinh nghiệm làm việc</label>
                <div class="ttc-radio-group">
                    <label><input type="radio" name="ttc-exp" value="none" checked> Chưa có kinh nghiệm</label>
                    <label><input type="radio" name="ttc-exp" value="have"> Đã có kinh nghiệm</label>
                </div>

                <div id="ttc-sonam-wrap" class="ttc-input-group ttc-hide">
                    <label>Số năm kinh nghiệm *</label>
                    <select id="ttc-sonam">
                        <option value="">Chọn số năm kinh nghiệm</option>
                        <option value="1 năm">1 năm</option>
                        <option value="2 năm">2 năm</option>
                        <option value="3 năm">3 năm</option>
                        <option value="4 năm">4 năm</option>
                        <option value="5 năm">5 năm</option>
                    </select>
                </div>

                <div class="ttc-input-group">
                    <label>Cấp bậc hiện tại</label>
                    <select id="ttc-capbac">
                        <option value="">Chọn cấp bậc hiện tại</option>
                        <option value="Nhân viên">Nhân viên</option>
                        <option value="Trưởng nhóm">Trưởng nhóm</option>
                        <option value="Quản lý">Quản lý</option>
                    </select>
                </div>

                <div class="ttc-input-group">
                    <label>Trình độ học vấn cao nhất</label>
                    <select id="ttc-hocvan">
                        <option value="">Chọn trình độ học vấn</option>
                        <option value="Cao đẳng">Cao đẳng</option>
                        <option value="Đại học">Đại học</option>
                        <option value="Thạc sĩ">Thạc sĩ</option>
                    </select>
                </div>

            </div>

            <div class="ttc-footer">
                <button id="ttc-btn-cancel" class="ttc-btn ttc-btn-cancel">Hủy</button>
                <button id="ttc-btn-save" class="ttc-btn ttc-btn-save">Lưu thông tin</button>
            </div>
        </div>
    </div>


    <!-- ===== POPUP KINH NGHIỆM LÀM VIỆC ===== -->
    <div id="popupWorkExp" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Kinh nghiệm làm việc</h2>
                <span class="popup-close" id="closeWorkExp">&times;</span>
            </div>

            <div class="popup-body">
                <label>Tên công ty*</label>
                <input type="text" id="company">

                <label>Vị trí công việc*</label>
                <input type="text" id="position">

                <div class="checkbox-row">
                    <input type="checkbox" id="currentJob">
                    <!-- <label for="currentJob">Tôi đang làm việc ở đây</label> -->
                </div>

                <div class="time-row">
                    <div>
                        <label>Thời gian bắt đầu*</label>
                        <input type="month" id="startTime">
                    </div>
                    <div>
                        <label>Thời gian kết thúc*</label>
                        <input type="month" id="endTime">
                    </div>
                </div>

                <label>Mô tả trách nhiệm công việc*</label>
                <textarea id="description" rows="4"></textarea>
            </div>

            <div class="popup-footer">
                <button id="cancelWorkExp" class="btn-cancel">Hủy</button>
                <button id="saveWorkExp" class="btn-save">Lưu thông tin</button>
            </div>
        </div>
    </div>


    <!-- Popup học vấn -->
    <div class="modal-overlay" id="modalEdu">
        <div class="modal-container">
            <h2>Thêm học vấn</h2>

            <div class="modal-form">

                <input id="edu-truong" type="text" placeholder="Nhập tên trường">

                <div class="form-row">
                    <div class="col">
                        <input id="edu-start" type="text" placeholder="Bắt đầu (vd: 2020)">
                    </div>
                    <div class="col">
                        <input id="edu-end" type="text" placeholder="Kết thúc (vd: 2024)">
                    </div>
                </div>

                <input id="edu-nganh" type="text" placeholder="Nhập chuyên ngành">

                <select id="edu-bangcap">
                    <option>Chọn bằng cấp</option>
                    <option value="Trung học phổ thông">Trung học phổ thông</option>
                    <option value="Trung cấp">Trung cấp</option>
                    <option value="Cao đẳng">Cao đẳng</option>
                    <option value="Đại học">Đại học</option>
                    <option value="Cử nhân">Cử nhân</option>
                    <option value="Kỹ sư">Kỹ sư</option>
                    <option value="Thạc sĩ">Thạc sĩ</option>
                    <option value="Tiến sĩ">Tiến sĩ</option>
                    <option value="Chứng chỉ nghề">Chứng chỉ nghề</option>
                    <option value="Khác">Khác</option>
                </select>

                <textarea id="edu-mota" placeholder="Mô tả chi tiết..."></textarea>

                <div class="modal-buttons">
                    <button id="edu-btn-cancel" class="btn-cancel">Hủy</button>
                    <button id="edu-btn-save" class="btn-save">Lưu thông tin</button>
                </div>

            </div>
        </div>
    </div>

    <!-- popup kĩ năng -->
    <!-- POPUP KỸ NĂNG (skill) -->
    <div id="skill-overlay" class="skill-overlay" style="display:none;">
        <div class="skill-popup">
            <div class="skill-header">
                <h2>Kỹ năng</h2>
                <button class="skill-close" id="skill-close-x" aria-label="Đóng">&times;</button>
            </div>

            <div class="skill-body">
                <label for="skill-input" class="skill-label">Kỹ năng <span class="skill-counter" id="skill-counter">0/20</span></label>

                <div class="skill-input-row">
                    <input id="skill-input" class="skill-input" type="text" maxlength="20" placeholder="Nhập kỹ năng (tối đa 20 ký tự)">
                    <button id="skill-add-btn" class="skill-add-btn" type="button">Thêm</button>
                </div>

                <div id="skill-error" class="skill-error" aria-live="polite" style="display:none;">Vui lòng chọn ít nhất 1 kỹ năng.</div>

                <div id="skill-chips" class="skill-chips" aria-live="polite"></div>
            </div>

            <div class="skill-footer">
                <button id="skill-cancel" class="skill-btn skill-btn-cancel">Hủy</button>
                <button id="skill-save" class="skill-btn skill-btn-save">Lưu thông tin</button>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../../core/templates/candidate_footer.php'; ?>