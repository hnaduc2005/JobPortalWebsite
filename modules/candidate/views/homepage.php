<?php 
    require_once __DIR__ . '/../../../core/templates/candidate_header.php'; 

    if (!function_exists('calculate_days_remaining')) {
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
    }

    if (!function_exists('format_salary')) {
        /**
         * Định dạng mức lương từ giá trị bigint (VND) sang chuỗi hiển thị (Triệu).
         */
        function format_salary($min, $max) {
            // Chuyển đổi sang đơn vị triệu
            $min_trieu = $min / 1000000;
            $max_trieu = $max / 1000000;
            
            if ($min > 0 && $max > 0 && $min != $max) {
                return number_format($min_trieu, 0) . ' - ' . number_format($max_trieu, 0) . ' Triệu';
            } elseif ($min > 0) {
                return 'Từ ' . number_format($min_trieu, 0) . ' Triệu';
            }
            return 'Thỏa thuận';
        }
    }

    // ----------------------------------------------------
    // II. CÁC HÀM XỬ LÝ URL VÀ LỌC (FILTER/PAGINATION)
    // ----------------------------------------------------

    if (!function_exists('buildFilterLink')) {
        /**
         * Tạo link lọc (filtering link), bảo toàn các tham số URL cũ và áp dụng tham số mới.
         * Đồng thời, xóa tham số 'page' và 'ipage' để reset phân trang khi lọc mới.
         */
        function buildFilterLink($newParams) {
            $currentParams = $_GET; 
            $mergedParams = array_merge($currentParams, $newParams);

            // 1. Xóa các tham số rỗng/mặc định (khi click nút "Tất cả")
            foreach ($mergedParams as $key => $value) {
                if ($value === '' || $value === -1 || $value === 0) {
                    unset($mergedParams[$key]);
                }
            }
            
            // 2. Xóa tham số phân trang của cả hai khối
            unset($mergedParams['page']);
            unset($mergedParams['ipage']);

            return '?' . http_build_query($mergedParams);
        }
    }

    if (!function_exists('buildPaginationLink')) {
    /**
     * Tạo link phân trang, bảo toàn các tham số lọc và chỉ thay đổi tham số phân trang được chỉ định.
     * @param string $pageKeyName Tên tham số phân trang (ví dụ: 'page' hoặc 'ipage').
     * @param int $pageNumber Trang đích.
     * @param int $totalPages Tổng số trang.
     * @return string URL hoàn chỉnh bắt đầu bằng '?'.
     */
    function buildPaginationLink($pageKeyName, $pageNumber, $totalPages) {
        $params = $_GET; // Lấy tất cả tham số hiện tại (location, salary, page, ipage,...)
        
        // --- 1. XỬ LÝ THAM SỐ PHÂN TRANG KHÔNG LIÊN QUAN ---
        // Đảm bảo không có cả 'page' và 'ipage' bị đặt giá trị 1 không cần thiết.
        // Đây là điểm cốt yếu để loại bỏ sự phụ thuộc chéo.
        if ($pageKeyName === 'page') {
             // Nếu ta đang xử lý khối 1, ta xóa tham số của khối 2
             unset($params['ipage']);
        } elseif ($pageKeyName === 'ipage') {
             // Nếu ta đang xử lý khối 2, ta xóa tham số của khối 1
             unset($params['page']);
        }

        // --- 2. ĐẶT GIÁ TRỊ MỚI ---
        $params[$pageKeyName] = max(1, min($pageNumber, $totalPages));

        // 3. Nếu page là 1, xóa tham số phân trang để URL trông sạch hơn
        if ($pageNumber <= 1) {
            unset($params[$pageKeyName]);
        }
        
        return '?' . http_build_query($params);
        }
    }

    // --- KHAI BÁO CÁC BIẾN CẦN THIẾT ---
    $jobsPerPage = 9; // Số bài đăng cho Job List One
    $immediateJobsPerPage = 6; // Số bài đăng cho Job List Two
    $salaryRanges = [
        'Thỏa thuận' => [0, 0], '5-10' => [5000000, 10000000],
        '10-20' => [10000000, 20000000], '20-35' => [20000000, 35000000],
        '35+' => [35000000, 99999999]
    ];

    // ----------------------------------------------------------------------------------
    // LOGIC CHUNG: XÂY DỰNG ĐIỀU KIỆN LỌC (WHERE)
    // ----------------------------------------------------------------------------------

    // Lấy các tham số lọc từ URL
    $filterLocation = isset($_GET['location']) ? $_GET['location'] : '';
    $filterSalaryRange = isset($_GET['salary']) ? $_GET['salary'] : '';
    $filterExpMonths = isset($_GET['exp']) && is_numeric($_GET['exp']) ? (int)$_GET['exp'] : -1;
    $filterGroupId = isset($_GET['group_id']) && is_numeric($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

    $additionalConditions = "";
    // 1. Lọc theo Địa điểm
    if (!empty($filterLocation)) { $additionalConditions .= " AND rj.location = '" . $filterLocation . "'"; }
    // 2. Lọc theo Ngành nghề
    if ($filterGroupId > 0) { $additionalConditions .= " AND rj.groups_id = " . $filterGroupId; }
    // 3. Lọc theo Kinh nghiệm
    if ($filterExpMonths >= 0) { $additionalConditions .= " AND rj.experience_months_required = " . $filterExpMonths; }
    // 4. Lọc theo Mức lương
    if (!empty($filterSalaryRange) && isset($salaryRanges[$filterSalaryRange])) {
        list($min, $max) = $salaryRanges[$filterSalaryRange];
        if ($min == 0 && $max == 0 && $filterSalaryRange == 'Thỏa thuận') {
            $additionalConditions .= " AND (rj.salary_min = 0 OR rj.salary_max = 0)";
        } elseif ($filterSalaryRange == '35+') {
            $additionalConditions .= " AND rj.salary_max >= " . $min;
        } else {
            $additionalConditions .= " AND (rj.salary_min >= " . $min . " AND rj.salary_max <= " . $max . ")";
        }
    }

    // ----------------------------------------------------------------------------------
    // LẤY DỮ LIỆU CHO CÁC NÚT LỌC UI (Chỉ cần chạy 1 lần)
    // ----------------------------------------------------------------------------------

    $locationSql = "SELECT DISTINCT location FROM recruitment_posts WHERE status = '1' ORDER BY location ASC";
    $availableLocations = getAll($locationSql);

    $groupSql = "SELECT id, name FROM groups ORDER BY name ASC";
    $availableGroups = getAll($groupSql);

    $expSql = "SELECT DISTINCT experience_months_required AS months FROM recruitment_posts WHERE status = '1' ORDER BY months ASC";
    $availableExperiences = getAll($expSql);

    // ----------------------------------------------------------------------------------
    // LOGIC CHO JOB LIST ONE: "VIỆC LÀM TUYỂN GẤP" (is_hot = 1)
    // ----------------------------------------------------------------------------------

    // 1. Phân trang
    $countSql = "SELECT id FROM recruitment_posts WHERE is_hot = 1 AND status = '1'" . $additionalConditions;
    $totalJobs = getRows($countSql); 
    $totalPages = ceil($totalJobs / $jobsPerPage); 
    $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages)); 
    $offset = ($currentPage - 1) * $jobsPerPage;

    // 2. Truy vấn chính
    $sql = "
        SELECT rj.id, rj.title, rj.salary_min, rj.salary_max, rj.location, rj.deadline, rj.is_hot, ep.company_name, ep.logo
        FROM recruitment_posts rj
        JOIN employer_profiles ep ON rj.user_id = ep.user_id
        WHERE rj.is_hot = 1 AND rj.status = '1' " . $additionalConditions . " 
        ORDER BY rj.created_at DESC
        LIMIT $jobsPerPage OFFSET $offset 
    ";
    $jobs = getAll($sql);

    $prevLink = '#'; $nextLink = '#'; // Khai báo mặc định
    if ($totalPages > 1) { 
        $prevLink = buildPaginationLink('page', $currentPage - 1, $totalPages);
        $nextLink = buildPaginationLink('page', $currentPage + 1, $totalPages);
    }

    // ----------------------------------------------------------------------------------
    // LOGIC CHO JOB LIST TWO: "VIỆC ĐI LÀM NGAY" (employment_type = 'full-time')
    // ----------------------------------------------------------------------------------

    // 1. Phân trang
    $immediateCountSql = "SELECT id FROM recruitment_posts WHERE TRIM(LOWER(employment_type)) IN ('full-time', 'full time') AND status = '1'" . $additionalConditions;
    $totalImmediateJobs = getRows($immediateCountSql); 
    $totalImmediatePages = ceil($totalImmediateJobs / $immediateJobsPerPage); 

    $currentImmediatePage = isset($_GET['ipage']) && is_numeric($_GET['ipage']) ? (int)$_GET['ipage'] : 1; // Sử dụng tham số URL 'ipage'
    $currentImmediatePage = max(1, min($currentImmediatePage, $totalImmediatePages)); 
    $offsetImmediate = ($currentImmediatePage - 1) * $immediateJobsPerPage;

    // 2. Truy vấn chính
    // Trong khối Logic JOB LIST TWO (Nơi bạn định nghĩa $immediateSql)
    $immediateSql = "
        SELECT rj.id, rj.title, rj.salary_min, rj.salary_max, rj.location, rj.deadline, rj.employment_type, ep.company_name, ep.logo
        FROM recruitment_posts rj
        JOIN employer_profiles ep ON rj.user_id = ep.user_id
        WHERE TRIM(LOWER(rj.employment_type)) IN ('full-time', 'full time') AND rj.status = '1'" 
        . $additionalConditions . " 
        ORDER BY rj.created_at DESC
        LIMIT " . $immediateJobsPerPage . " OFFSET " . $offsetImmediate; // Nối biến chính xác
        
    // Thay vì: LIMIT $immediateJobsPerPage OFFSET $offsetImmediate
    // Hãy dùng: LIMIT " . $immediateJobsPerPage . " OFFSET " . $offsetImmediate;
    $immediateJobs = getAll($immediateSql);

    $prevImmediateLink = '#'; $nextImmediateLink = '#'; // Khai báo mặc định
    if ($totalImmediatePages > 1) { 
        $prevImmediateLink = buildPaginationLink('ipage', $currentImmediatePage - 1, $totalImmediatePages);
        $nextImmediateLink = buildPaginationLink('ipage', $currentImmediatePage + 1, $totalImmediatePages);
    }
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
                    <div class="filter-tab active">Địa điểm</div>
                    <div class="filter-tab">Mức lương</div>
                    <div class="filter-tab">Kinh nghiệm</div>
                    <div class="filter-tab">Ngành nghề</div>
                </div>
            </div>
        </div>

        <div class="sub-filter-display">

            <?php 
            // NÚT TẤT CẢ (ALL) - Job List ONE
            $isAnyFilterActive = !empty($filterLocation) || !empty($filterSalaryRange) || $filterExpMonths != -1 || $filterGroupId != 0;
            $isAllActive = !$isAnyFilterActive ? 'active' : '';
            $allLink = buildFilterLink(['location' => '', 'salary' => '', 'exp' => '', 'group_id' => '']);
            ?>
            <a href="<?php echo $allLink; ?>" class="sub-filter-option all-filter <?php echo $isAllActive; ?>">
                Tất cả
            </a>

            <div class="sub-filter-options-wrapper">

                <div class="sub-filter-group active" id="sub-location">
                    <?php 
                    foreach ($availableLocations as $loc) {
                        $locationName = htmlspecialchars($loc['location']);
                        $isActive = ($filterLocation === $locationName) ? 'active' : '';
                        $locationLink = buildFilterLink(['location' => $locationName, 'page' => 1]); 
                    ?>
                    <a href="<?php echo $locationLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo $locationName; ?></a>
                    <?php } ?>
                </div>

                <div class="sub-filter-group" id="sub-salary">
                    <?php 
                    foreach ($salaryRanges as $label => $range) {
                        $isActive = ($filterSalaryRange === $label) ? 'active' : '';
                        $salaryLink = buildFilterLink(['salary' => $label, 'page' => 1]);
                    ?>
                    <a href="<?php echo $salaryLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo htmlspecialchars($label); ?></a>
                    <?php } ?>
                </div>

                <div class="sub-filter-group" id="sub-experience">
                    <?php 
                    foreach ($availableExperiences as $exp) {
                        $expMonths = (int)$exp['months'];
                        $expLabel = ($expMonths == 0) ? 'Không yêu cầu' : ($expMonths / 12) . ' năm';
                        $isActive = ($filterExpMonths === $expMonths) ? 'active' : '';
                        $expLink = buildFilterLink(['exp' => $expMonths, 'page' => 1]);
                    ?>
                    <a href="<?php echo $expLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo $expLabel; ?></a>
                    <?php } ?>
                </div>

                <div class="sub-filter-group" id="sub-group">
                    <?php 
                    foreach ($availableGroups as $group) {
                        $groupId = (int)$group['id'];
                        $groupName = htmlspecialchars($group['name']);
                        $isActive = ($filterGroupId === $groupId) ? 'active' : '';
                        $groupLink = buildFilterLink(['group_id' => $groupId, 'page' => 1]);
                    ?>
                    <a href="<?php echo $groupLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo $groupName; ?></a>
                    <?php } ?>
                </div>

            </div>

            <button class="sub-filter-option next-arrow"><i class="ti ti-angle-right"></i></button>
        </div>
    </div>

    <div class="job-cards-grid">

        <?php 
        if (!empty($jobs)) {
            // Vòng lặp cho JOB LIST ONE
            foreach ($jobs as $job) { 
                
                $jobId = htmlspecialchars($job['id']);
                $jobTitle = htmlspecialchars($job['title']);
                $companyName = htmlspecialchars($job['company_name']);
                $location = htmlspecialchars($job['location']);
                
                $salaryRange = format_salary($job['salary_min'], $job['salary_max']);
                $daysRemaining = calculate_days_remaining($job['deadline']);

                $logoUrl = !empty($job['logo']) ? htmlspecialchars($job['logo']) : './assets/imgs/default.png';
                $tagHtml = ($job['is_hot'] == 1) ? '<span class="tag hot">HOT</span>' : '';
        ?>

        <div class="job-card">
            <div class="card-header">
                <a href="/job/detail?id=<?php echo $jobId; ?>" class="job-title"><?php echo $jobTitle; ?></a>
                <button class="favorite-btn" data-job-id="<?php echo $jobId; ?>"><i class="ti ti-heart"></i></button>
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
            } 
        } else {
            echo '<p style="grid-column: 1 / -1; text-align: center; padding: 20px;">Không có việc làm tuyển gấp nào đang hoạt động.</p>';
        }
        ?>

    </div>

    <div class="pagination">
        <?php if (isset($totalPages) && $totalPages > 1) { ?>
        <a href="<?php echo $prevLink; ?>" class="prev-page <?php echo ($currentPage <= 1) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-left"></i>
        </a>

        <span class="page-info"><?php echo $currentPage; ?> / <?php echo $totalPages; ?></span>

        <a href="<?php echo $nextLink; ?>"
            class="next-page <?php echo ($currentPage >= $totalPages) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-right"></i>
        </a>
        <?php } else {
            echo '<span class="page-info">1 / 1</span>';
        } ?>
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

            <?php 
            // NÚT TẤT CẢ (ALL) - Job List TWO
            $isImmediateFilterActive = !empty($filterLocation) || !empty($filterSalaryRange) || $filterExpMonths != -1;
            $isAllImmediateActive = !$isImmediateFilterActive ? 'active' : '';
            $allImmediateLink = buildFilterLink(['location' => '', 'salary' => '', 'exp' => '', 'group_id' => '', 'ipage' => 1]); 
            ?>
            <a href="<?php echo $allImmediateLink; ?>"
                class="sub-filter-option all-filter <?php echo $isAllImmediateActive; ?>">
                Tất cả
            </a>

            <div class="sub-filter-options-wrapper">

                <div class="sub-filter-group active" id="immediate-location">
                    <?php 
                    foreach ($availableLocations as $loc) {
                        $locationName = htmlspecialchars($loc['location']);
                        $isActive = ($filterLocation === $locationName) ? 'active' : '';
                        $locationLink = buildFilterLink(['location' => $locationName, 'ipage' => 1]); // Dùng ipage
                    ?>
                    <a href="<?php echo $locationLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo $locationName; ?></a>
                    <?php } ?>
                </div>

                <div class="sub-filter-group" id="sub-salary-imm">
                    <?php 
                    foreach ($salaryRanges as $label => $range) {
                        $isActive = ($filterSalaryRange === $label) ? 'active' : '';
                        $salaryLink = buildFilterLink(['salary' => $label, 'ipage' => 1]);
                    ?>
                    <a href="<?php echo $salaryLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo htmlspecialchars($label); ?></a>
                    <?php } ?>
                </div>

                <div class="sub-filter-group" id="sub-experience-imm">
                    <?php 
                    foreach ($availableExperiences as $exp) {
                        $expMonths = (int)$exp['months'];
                        $expLabel = ($expMonths == 0) ? 'Không yêu cầu' : ($expMonths / 12) . ' năm';
                        $isActive = ($filterExpMonths === $expMonths) ? 'active' : '';
                        $expLink = buildFilterLink(['exp' => $expMonths, 'ipage' => 1]);
                    ?>
                    <a href="<?php echo $expLink; ?>"
                        class="sub-filter-option <?php echo $isActive; ?>"><?php echo $expLabel; ?></a>
                    <?php } ?>
                </div>

            </div>

            <button class="sub-filter-option next-arrow"><i class="ti ti-angle-right"></i></button>
        </div>
    </div>

    <div class="job-cards-grid">

        <?php 
        if (!empty($immediateJobs)) {
            // Vòng lặp cho JOB LIST TWO
            foreach ($immediateJobs as $job) { 
                
                $jobId = htmlspecialchars($job['id']);
                $jobTitle = htmlspecialchars($job['title']);
                $companyName = htmlspecialchars($job['company_name']);
                $location = htmlspecialchars($job['location']);
                
                $salaryRange = format_salary($job['salary_min'], $job['salary_max']);
                $daysRemaining = calculate_days_remaining($job['deadline']);
                $logoUrl = !empty($job['logo']) ? htmlspecialchars($job['logo']) : './assets/imgs/default.png';

                // Thẻ tag phụ (Giả định: Không cần CV nếu không phải full-time)
                $secondaryTag = (isset($job['employment_type']) && $job['employment_type'] !== 'full-time') ? '<span class="tag secondary-tag">Không cần CV</span>' : '';
        ?>

        <div class="job-card">
            <div class="card-header">
                <a href="/job/detail?id=<?php echo $jobId; ?>" class="job-title"><?php echo $jobTitle; ?></a>
                <button class="favorite-btn" data-job-id="<?php echo $jobId; ?>"><i class="ti ti-heart"></i></button>
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
                <?php echo $secondaryTag; ?>
                <span class="days-ago">⏰ <?php echo $daysRemaining; ?></span>
            </div>
        </div>

        <?php
            } 
        } else {
            echo '<p style="grid-column: 1 / -1; text-align: center; padding: 20px;">Hiện không có việc làm đi làm ngay nào.</p>';
        }
        ?>

    </div>

    <div class="pagination">
        <?php 
        if (isset($totalImmediatePages) && $totalImmediatePages > 1) { 
        ?>
        <a href="<?php echo $prevImmediateLink; ?>"
            class="prev-page <?php echo ($currentImmediatePage <= 1) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-left"></i>
        </a>

        <span class="page-info"><?php echo $currentImmediatePage; ?> / <?php echo $totalImmediatePages; ?></span>

        <a href="<?php echo $nextImmediateLink; ?>"
            class="next-page <?php echo ($currentImmediatePage >= $totalImmediatePages) ? 'disabled' : 'active'; ?>">
            <i class="ti ti-angle-right"></i>
        </a>
        <?php 
        } else {
            echo '<span class="page-info">1 / 1</span>'; 
        }
        ?>
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
            <p class="article-summary">Tiếp nối chuỗi sự kiện nhân sự - networking của Vieclam24h dành cho cộng đồng HR
                cấp cao, chương trình HR NEXUS #2 - "Xây dựng đội ngũ hiệu...</p>
        </div>

        <div class="article-card">
            <div class="article-image-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Công việc lý tưởng cho nữ" class="article-image">
            </div>
            <h3 class="article-title">10 công việc lý tưởng giúp trả lời câu hỏi con gái nên học ngành gì</h3>
            <p class="article-summary">Con gái nên học ngành gì? Con gái khi chọn nghề nghiệp phù hợp nên lưu ý điều gì?
                Việc làm phù hợp cho phái nữ gồm các ngành nào?</p>
        </div>

        <div class="article-card">
            <div class="article-image-container">
                <img src="./assets/imgs/logo_timvieclam.jpg" alt="Top 8 nghề nghiệp lương cao" class="article-image">
            </div>
            <h3 class="article-title">Nên học nghề gì khi không học Đại học? Top 8 nghề nghiệp lương cao hiện nay</h3>
            <p class="article-summary">Việc học nghề đã trở thành một trong những giải pháp an toàn để bạn có một công
                việc ổn định cho bản thân. Vậy thực chất học nghề là gì?</p>
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
<script>
document.addEventListener("click", function (e) {
    let btn = e.target.closest(".favorite-btn");
    if (!btn) return; 

    let postId = btn.dataset.jobId;

    if (!postId) {
        console.error("Không tìm thấy job-id");
        return;
    }

    // Gửi AJAX bằng Fetch API (JS thuần)
    fetch("JobPortalWebsite/?module=candidate&action=toggle_save_job", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId
    })
    .then(response => response.json())
    .then(res => {
        if (res.action === "saved") {
            btn.classList.remove("fa-regular");
            btn.classList.add("fa-solid", "saved");
        }
        else if (res.action === "unsaved") {
            btn.classList.remove("fa-solid", "saved");
            btn.classList.add("fa-regular");
        }

        showToast(res.message);
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi hệ thống! Không thể lưu hoặc bỏ lưu.");
    });
});

function showToast(message) {
    const toast = document.createElement("div");
    toast.className = "custom-toast";
    toast.innerText = message;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => toast.classList.remove("show"), 2500);
    setTimeout(() => toast.remove(), 3000);
}
</script>
<?php require_once __DIR__ . '/../../../core/templates/candidate_footer.php'; ?>
