<?php require_once getCurrentPath() . '/core/templates/candidate_header.php'; ?>

<?php 
    // Hàm trợ giúp để tính số ngày còn lại
    function calculate_days_remaining($deadline) {
        try {
            $now = new DateTime();
            $expiry = new DateTime($deadline);
        } catch (Exception $e) {
            return 'Không rõ';
        }
        
        if ($expiry > $now) {
            $interval = $now->diff($expiry);
            return 'Còn ' . $interval->days . ' ngày';
        }
        return 'Đã hết hạn';
    }
    // Hàm trợ giúp để định dạng lương
        function format_salary($min, $max) {
            $min_trieu = $min / 1000000;
            $max_trieu = $max / 1000000;
            
            if ($min > 0 && $max > 0 && $min != $max) {
                return number_format($min_trieu, 0) . ' - ' . number_format($max_trieu, 0) . ' Triệu';
            } elseif ($min > 0) {
                return 'Từ ' . number_format($min_trieu, 0) . ' Triệu';
            }
            return 'Thỏa thuận';
        }

        $salaryRanges = [
            'Thỏa thuận' => [0, 0], '5-10' => [5000000, 10000000],
            '10-20' => [10000000, 20000000], '20-35' => [20000000, 35000000],
            '35+' => [35000000, 99999999]
        ];


        // Cấu hình phân trang
        $jobsPerPage = 9; // Số bài đăng mỗi trang

        // 1. Tính tổng số bài đăng (dùng hàm getRows() của bạn)
        $countSql = "SELECT id FROM recruitment_posts WHERE is_hot = 1 AND status = '1'";
        $totalJobs = getRows($countSql); // Ví dụ: $totalJobs = 10

        // 2. Tính tổng số trang
        $totalPages = ceil($totalJobs / $jobsPerPage); // ceil(10 / 9) = 2

        // 3. Xác định trang hiện tại (lấy từ URL, mặc định là 1)
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        // Đảm bảo trang hiện tại không vượt quá giới hạn
        $currentPage = max(1, min($currentPage, $totalPages)); 

        // 4. Tính OFFSET cho SQL
        $offset = ($currentPage - 1) * $jobsPerPage;

        function buildPaginationLink($pageNumber, $totalPages) {
            // Lấy tất cả các tham số hiện tại từ URL
            $params = $_GET;

            // 1. Đặt giá trị page mới
            $params['page'] = max(1, min($pageNumber, $totalPages));

            // 2. Nếu page là 1, xóa tham số 'page' để URL trông sạch hơn
            if ($pageNumber <= 1) {
                unset($params['page']);
            }
            
            // 3. Dùng http_build_query để tạo chuỗi tham số hoàn chỉnh
            // Ví dụ: ?module=candidate&action=homepage&page=2
            return '?' . http_build_query($params);
        }

        if ($totalPages > 1) { // Chỉ hiển thị nếu có nhiều hơn 1 trang
            
            // Tạo link cho trang trước và trang sau
            $prevLink = buildPaginationLink($currentPage - 1, $totalPages);
            $nextLink = buildPaginationLink($currentPage + 1, $totalPages);
        }
        //Lệnh sql 
        $sql = "
            SELECT 
                rj.id, 
                rj.title, 
                rj.salary_min, 
                rj.salary_max, 
                rj.location, 
                rj.deadline, 
                rj.is_hot,
                ep.company_name, 
                ep.logo
            FROM recruitment_posts rj
            JOIN employer_profiles ep ON rj.user_id = ep.user_id
            WHERE rj.is_hot = 1  
            AND rj.status = '1' 
            ORDER BY rj.created_at DESC
            LIMIT $jobsPerPage OFFSET $offset;  -- Dùng biến động
        ";
    $jobs = getAll($sql);
?>
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
                    <div class="filter-tab">Địa điểm</div>
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
        
        <?php 
        if (!empty($jobs)) {
            // Vòng lặp để tạo các thẻ công việc (job-card) từ dữ liệu DB
            foreach ($jobs as $job) { 
                
                // Lấy và làm sạch các giá trị
                $jobId = htmlspecialchars($job['id']);
                $jobTitle = htmlspecialchars($job['title']);
                $companyName = htmlspecialchars($job['company_name']);
                $location = htmlspecialchars($job['location']);
                
                $salaryRange = format_salary($job['salary_min'], $job['salary_max']);
                $daysRemaining = calculate_days_remaining($job['deadline']);

                $logoUrl = !empty($job['logo']) ? htmlspecialchars($job['logo']) : './assets/imgs/default.png';

                $tagHtml = '';
                if ($job['is_hot'] == 1) { 
                    $tagHtml = '<span class="tag hot">HOT</span>';
                }
        ?>
        
        <div class="job-card">
            <div class="card-header">
                <a href="/job/detail?id=<?php echo $jobId; ?>" class="job-title"><?php echo $jobTitle; ?></a>
                <button class="favorite-btn"><i class="ti ti-heart"></i></button>
            </div>
            <div class="company-info">
                <img src="<?php echo $logoUrl; ?>" alt="Logo Công ty" class="company-logo">
                <span class="company-name"><?php echo $companyName; ?></span>
            </div>
            <div class="job-details">
                <span class="detail-item salary">💰 <?php echo $salaryRange; ?></span>
                <span class="detail-item location">📍 <?php echo $location; ?></span>
            </div>
            <div class="card-footer">
                <?php echo $tagHtml; ?>
                <span class="days-ago">⏰ <?php echo $daysRemaining; ?></span>
            </div>
        </div>
        
        <?php
            } // Kết thúc vòng lặp foreach
        } else {
            echo '<p style="grid-column: 1 / -1; text-align: center; padding: 20px;">Không có việc làm tuyển gấp nào đang hoạt động.</p>';
        }
        ?>

    </div>
    
    <div class="pagination">
        <a href="<?php echo ($currentPage > 1) ? $prevLink : 'javascript:void(0)'; ?>"
        class="prev-page <?php echo ($currentPage <= 1) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-left"></i>
        </a>
        
        <span class="page-info"><?php echo $currentPage; ?> / <?php echo $totalPages; ?></span>
        
        <a href="<?php echo ($currentPage < $totalPages) ? $nextLink : 'javascript:void(0)'; ?>"
        class="next-page <?php echo ($currentPage >= $totalPages) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-right"></i>
        </a>
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

<?php require_once getCurrentPath() . '/core/templates/candidate_footer.php'; ?>
