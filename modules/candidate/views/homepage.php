<?php include './core/templates/header.php'; ?>

<!-- BEGIN HEADER -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="#" class="logo-link">
            <div class="logo">TìmViệcLàm</div>
            <div class="slogan">NHANH HƠN,DỄ DÀNG HƠN</div>
        </a>
    </div>

    <ul class="nav-menu">
        <li class="nav-item has-dropdown">
            <a href="#">Việc làm <span class="arrow">▼</span></a>
        </li>
        <li class="nav-item has-dropdown">
            <a href="#">Công cụ <span class="arrow">▼</span></a>
        </li>
        <li class="nav-item">
            <a href="#">Cẩm nang nghề nghiệp</a>
        </li>
    </ul>

    <div class="navbar-right">
        <div class="user-area">
            <div class="user-text">Người tìm việc</div>
            <a href="#" class="auth-link">Đăng ký/Đăng nhập</a>
        </div>
        <div class="employer-area">
            <div class="employer-text">DÀNH CHO</div>
            <a href="#" class="recruiter-link">
                Nhà Tuyển Dụng
            </a>
        </div>
        <div class="language-select">
            <img src="https://flagcdn.com/w20/us.png" alt="English" class="flag-icon">
        </div>
    </div>
</nav>
<!-- END HEADER -->

<!-- SEARCH AND BANNER -->
    <div class="search-section">
    <div class="search-bar-container">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Nhập vị trí muốn ứng tuyển">
        </div>
        
        <div class="search-dropdown-group">
            <span class="dropdown-icon">📖</span>
            <select>
                <option>Tất cả nghề nghiệp</option>
                </select>
            <span class="arrow-down"><i class="ti ti-angle-down"></i></span>
        </div>

        <div class="search-dropdown-group">
            <span class="dropdown-icon">📍</span>
            <select>
                <option>Tất cả tỉnh thành</option>
                </select>
            <span class="arrow-down"><i class="ti ti-angle-down"></i></span>
        </div>

        <button class="search-button">
            <span class="button-icon"><i class="ti ti-search"></i></span> Tìm việc
        </button>
    </div>

    <div class="quick-links">
        <a href="#" class="quick-link primary">
            <span class="link-icon">⚡</span> Việc đi làm ngay <span class="badge">HOT</span>
        </a>
        <a href="#" class="quick-link secondary">
            <span class="link-icon">📄</span> Việc không cần CV <span class="badge">HOT</span>
        </a>
    </div>

    <div class="feature-banner-container">
        <div class="feature-banner">
            <div class="banner-content">
                <p>Mô Phỏng Banner</p>
                <h3>Làm DEV không kiệt sức</h3>
                <p class="powered-by">Powered by TimViecLam</p>
                </div>
        </div>
    </div>
</div>
<!-- END SEARCH AND BANNER -->

<!-- JOB LIST ONE -->
<div class="job-listing-section">
    <h2 class="section-title">
        <span class="fire-icon">🔥</span> Việc làm tuyển gấp
    </h2>

    <div class="filter-bar">
        
        <div class="filter-dropdown-container">
            
            <div class="filter-trigger" id="filter-trigger">
                <span class="icon"><i class="ti ti-filter"></i></span>
                <span>Lọc theo: Địa điểm</span> 
                <span class="arrow-down-small"><i class="ti ti-angle-down"></i></span>
            </div>
            
            <div class="filter-panel">
                <div class="filter-options-nav" id="filter-nav-tabs">
                    <div class="filter-tab active">Địa điểm</div>
                    <div class="filter-tab">Mức lương</div>
                    <div class="filter-tab">Kinh nghiệm</div>
                    <div class="filter-tab">Ngành nghề</div>
                </div>
            </div>
        </div>
        
        <div class="sub-filter-display">
            
            <button class="sub-filter-option active all-filter">Tất cả</button>
            
            <div class="sub-filter-options-wrapper">
                
                <div class="sub-filter-group active" id="sub-location">
                    <button class="sub-filter-option">TP.HCM</button>
                    <button class="sub-filter-option">An Giang</button>
                    <button class="sub-filter-option">Bà Rịa - Vũng Tàu</button>
                    <button class="sub-filter-option">Bạc Liêu</button>
                    <button class="sub-filter-option">Bến Tre</button>
                    <button class="sub-filter-option">Hà Nội</button>
                </div>
            </div>

            <button class="sub-filter-option next-arrow"><i class="ti ti-angle-right"></i></button>
        </div>
    </div>

    <div class="job-cards-grid">
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Kế Toán Trưởng, Kinh Nghiệm 3 Năm (Gói...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Chi Nhánh Thuận Việt - Công ty TNHH Trường...</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 15 - 25 Triệu</span>
                <span class="detail-item location">📍 Tiền Giang</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 12 ngày</span>
            </div>
        </div>

        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">[Đi Làm Ngay] Tài Xế Lái Xe - Bằng C, Xe Tải...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Hyung Dk Vietnam</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 12 - 16 Triệu</span>
                <span class="detail-item location">📍 Đồng Nai</span>
            </div>
            <div class="card-footer">
                <span class="tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 6 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Lập Trình Phay Tiện CNC</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH MTP Precision Tech</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 12 - 16 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 27 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Hỗ Trợ Kinh Doanh Đi Làm Ngay</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Đầu tư Nội công nghệ Y Tế Giàu...</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 Thỏa thuận</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 18 ngày</span>
            </div>
        </div>

        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Văn Phòng - Biết Tiếng Anh</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Gold Century Garment Vina</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 10 - 15 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 8 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Kinh Doanh Lương Upto 50Tr /...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Hóa Kiến Nhân</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 10 - 50 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 5 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Phiên Dịch Viên Tiếng Trung</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH WeWork Vietnam</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 14 - 20 Triệu</span>
                <span class="detail-item location">📍 Bà Rịa - Vũng Tàu</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 22 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Phục Vụ (Làm Việc Cả Sinh Viên)...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Viet Nam Saliveriya</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 9 - 9 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="days-ago">⏰ Còn 26 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Trưởng Nhóm Kinh Doanh (Dự Án Nội Thất)...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Chi Nhánh Công Ty Cổ Phần Kiến Trúc Xây...</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 15 - 30 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag hot">HOT</span>
                <span class="days-ago">⏰ Còn 18 ngày</span>
            </div>
        </div>
    </div>
    
    <div class="pagination">
        <button class="prev-page"><i class="ti ti-angle-left"></i></button>
        <span class="page-info">1 / 36</span>
        <button class="next-page active"><i class="ti ti-angle-right"></i></button>
    </div>

</div>
<!-- END JOB LIST ONE -->

<!-- JOB LIST TWO -->
<div class="job-listing-section immediate-jobs-section">
    <h2 class="section-title">
        <span class="fire-icon immediate-icon">🔴</span> Việc đi làm ngay 
        <a href="#" class="view-all-link">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </h2>

    <div class="filter-bar">
        
        <div class="filter-dropdown-container">
            <div class="filter-trigger">
                <span class="icon"><i class="ti ti-filter"></i></span>
                <span>Lọc theo: Địa điểm</span> 
                <span class="arrow-down-small"><i class="ti ti-angle-down"></i></span>
            </div>
            
            <div class="filter-panel">
                <div class="filter-options-nav">
                    <div class="filter-tab active">Địa điểm</div>
                    <div class="filter-tab">Mức lương</div>
                    <div class="filter-tab">Kinh nghiệm</div>
                </div>
            </div>
        </div>
        
        <div class="sub-filter-display">
            
            <button class="sub-filter-option active all-filter">Tất cả</button>
            
            <div class="sub-filter-options-wrapper">
                
                <div class="sub-filter-group active" id="immediate-location">
                    <button class="sub-filter-option">TP.HCM</button>
                    <button class="sub-filter-option">An Giang</button>
                    <button class="sub-filter-option">Bà Rịa - Vũng Tàu</button>
                    <button class="sub-filter-option">Bạc Liêu</button>
                    <button class="sub-filter-option">Bến Tre</button>
                </div>
            </div>

            <button class="sub-filter-option next-arrow"><i class="ti ti-angle-right"></i></button>
        </div>
    </div>

    <div class="job-cards-grid">
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Đóng Gói Hái Trái Cây - Đi...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty Cổ Phần Trái Cây Hoàng Thọ</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 9 - 12 Triệu</span>
                <span class="detail-item location">📍 Bình Dương, TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 6 ngày</span>
            </div>
        </div>

        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Chăm Sóc Khách Hàng - Đi...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công ty TNHH MTV Nguyễn Gia Hoàng Thịnh</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 7 - 12 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 6 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Bếp Cam Gà Hội An De Foito...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Hộ Kinh Doanh Sunwah Pearl</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 8 - 14 Triệu</span>
                <span class="detail-item location">📍 Hà Nội</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 5 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Mcdonald's Khu Vực QL.Q5| Nhân Viên...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công ty Cổ phần Good Day Hospitality...</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 3 - 5 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 5 ngày</span>
            </div>
        </div>

        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Nữ Phục Vụ Quán Bar (Thu...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Hộ Kinh Doanh Một Thoáng Sài Gòn (Saigon...</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 20 - 30 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 5 ngày</span>
            </div>
        </div>
        
        <div class="job-card">
            <div class="card-header">
                <a href="#" class="job-title">Nhân Viên Lái Xe Du Lịch - Bình Chán...</a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="./assets/imgs/Chelsea.png" alt="Logo" class="company-logo">
                <span class="company-name">Công Ty TNHH Nội Thất Tân Phú</span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 10 - 12 Triệu</span>
                <span class="detail-item location">📍 TP.HCM</span>
            </div>
            <div class="card-footer">
                <span class="tag secondary-tag">Không cần CV</span>
                <span class="days-ago">⏰ Còn 5 ngày</span>
            </div>
        </div>
    </div>
    
    <div class="pagination">
        <button class="prev-page"><i class="ti ti-angle-left"></i></button>
        <span class="page-info">1 / 30</span>
        <button class="next-page active"><i class="ti ti-angle-right"></i></button>
    </div>
</div>
<!-- END JOB LIST TWO -->

<!-- FEATURED COMPANIES  -->
<div class="featured-companies-section">
    <h2 class="section-title featured-title">
        <span class="trophy-icon">🏆</span> Công ty nổi bật
        <a href="#" class="view-all-link">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </h2>

    <div class="company-cards-grid">
        
        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo GSC" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 42 vị trí đang tuyển
            </div>
        </div>

        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo Adsota" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 1 vị trí đang tuyển
            </div>
        </div>

        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo Adayroi" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 9 vị trí đang tuyển
            </div>
        </div>

        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo VTB" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 2 vị trí đang tuyển
            </div>
        </div>

        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo Eurowindow" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 9 vị trí đang tuyển
            </div>
        </div>

        <div class="company-card">
            <div class="logo-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Logo Bamboo" class="company-logo-img">
            </div>
            <div class="job-count">
                <span class="icon"><i class="ti ti-briefcase"></i></span> 4 vị trí đang tuyển
            </div>
        </div>
        
    </div>
</div>
<!-- END FEATURED COMPANIES -->

<!-- CẨM NANG NGHỀ NGHIỆP -->
<div class="career-handbook-section">
    <h2 class="handbook-title">Cẩm nang nghề nghiệp</h2>

    <div class="handbook-articles-grid">
        
        <div class="article-card">
            <div class="article-image-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Sự kiện HR Nexus" class="article-image">
            </div>
            <h3 class="article-title">HR NEXUS #2: Xây dựng đội ngũ hiệu suất cao trong kỷ nguyên AI</h3>
            <p class="article-summary">Tiếp nối chuỗi sự kiện nhân sự - networking của Vieclam24h dành cho cộng đồng HR cấp cao, chương trình HR NEXUS #2 - "Xây dựng đội ngũ hiệu...</p>
        </div>

        <div class="article-card">
            <div class="article-image-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Công việc lý tưởng cho nữ" class="article-image">
            </div>
            <h3 class="article-title">10 công việc lý tưởng giúp trả lời câu hỏi con gái nên học ngành gì</h3>
            <p class="article-summary">Con gái nên học ngành gì? Con gái khi chọn nghề nghiệp phù hợp nên lưu ý điều gì? Việc làm phù hợp cho phái nữ gồm các ngành nào?</p>
        </div>

        <div class="article-card">
            <div class="article-image-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Top 8 nghề nghiệp lương cao" class="article-image">
            </div>
            <h3 class="article-title">Nên học nghề gì khi không học Đại học? Top 8 nghề nghiệp lương cao hiện nay</h3>
            <p class="article-summary">Việc học nghề đã trở thành một trong những giải pháp an toàn để bạn có một công việc ổn định cho bản thân. Vậy thực chất học nghề là gì?</p>
        </div>
    </div>
    
    <div class="view-more-container">
        <a href="#" class="view-more-link">Xem thêm cẩm nang nghề nghiệp</a>
    </div>
</div>
<!-- END CẨM NANG NGHỀ NGHIỆP -->
<div class="quick-links-footer-section">
    <div class="quick-links-container">
        
        <div class="footer-column">
            <h3 class="footer-title">Việc làm theo nghề nghiệp</h3>
            <ul class="footer-list">
                <li><a href="#">Hành chính - Thư ký</a></li>
                <li><a href="#">An ninh - Bảo vệ</a></li>
                <li><a href="#">Thiết kế - Sáng tạo nghệ thuật</a></li>
                <li><a href="#">Kiến trúc - Thiết kế nội ngoại thất</a></li>
                <li><a href="#">Khách sạn - Nhà hàng - Du lịch</a></li>
            </ul>
            <a href="#" class="footer-link">Xem tất cả ›</a>
        </div>

        <div class="footer-column">
            <h3 class="footer-title">Việc làm theo Khu vực</h3>
            <ul class="footer-list">
                <li><a href="#">Toàn quốc</a></li>
                <li><a href="#">Hà Nội</a></li>
                <li><a href="#">TP.HCM</a></li>
                <li><a href="#">An Giang</a></li>
                <li><a href="#">Bà Rịa - Vũng Tàu</a></li>
            </ul>
            <a href="#" class="footer-link">Xem tất cả ›</a>
        </div>

        <div class="footer-column">
            <h3 class="footer-title">Việc làm mới</h3>
            <ul class="footer-list">
                <li><a href="#">Chuyên Viên Kinh Doanh Dịch Vụ Cộng Thanh Toán</a></li>
                <li><a href="#">Chuyên Viên Quay Dựng Video (Video Editor)</a></li>
                <li><a href="#">Kỹ Sư Xây Dựng / Kỹ Sư Cơ Điện / Kỹ Sư M&E</a></li>
                <li><a href="#">Kiến Trúc Sư (Thu Nhập Lên Đến 25 Triệu/Tháng)</a></li>
                <li><a href="#">Nhân Viên Kế Toán</a></li>
            </ul>
        </div>

    </div>
</div>


<?php include './core/templates/footer.php'; ?>