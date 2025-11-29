<?php 
    ini_set('display_errors', 0); // Quan trọng nhất để ngăn HTML lỗi xuất hiện
    error_reporting(0);

    require_once getCurrentPath() . '/core/templates/candidate_header.php';

    // 1. Lấy dữ liệu người dùng từ Session
    $userData = getSession('user'); 

    // 2. Kiểm tra trạng thái đăng nhập (Nếu chưa đăng nhập thì chuyển hướng)
    if (!$userData || !isset($userData['id'])) {
        // Thay thế bằng URL đăng nhập thực tế của bạn
        header('Location: ' . BASE_URL . '/?module=candidate&action=login'); 
        exit();
    }

    // 3. Khai báo các biến động
    $currentUserId = $userData['id'];
    $currentUserName = $userData['fullname'] ?? $userData['email'] ?? 'Người dùng'; 
    $savedJobs = [];
    $suggestedJobs = [];

    // II. LOGIC TRUY VẤN VIỆC LÀM ĐÃ LƯU

    // 1. Lấy Candidate ID từ User ID
    $candidateProfileSql = "SELECT id FROM candidate_profiles WHERE user_id = {$currentUserId}";
    $candidateProfile = getOne($candidateProfileSql); 
    $candidateId = $candidateProfile['id'] ?? 0;
    $savedPostIds = ['0']; // Mảng để lưu ID các bài đã lưu (để loại trừ ở phần gợi ý)

    if ($candidateId > 0) {
        // 2. Truy vấn chi tiết các bài đăng đã lưu (sử dụng LEFT JOIN để kiểm tra trạng thái lưu)
        $savedJobsSql = "
            SELECT 
                rj.id, rj.title, rj.salary_min, rj.salary_max, rj.deadline, rj.location,
                ep.company_name, ep.logo
            FROM saved_jobs sj
            JOIN recruitment_posts rj ON sj.post_id = rj.id
            JOIN employer_profiles ep ON rj.user_id = ep.user_id
            WHERE sj.candidate_id = {$candidateId} AND rj.status = '1'
            ORDER BY sj.saved_at DESC
        ";
        $savedJobs = getAll($savedJobsSql);
        
        // Lấy danh sách ID đã lưu để loại trừ khỏi phần gợi ý
        $savedPostIds = array_map(fn($job) => $job['id'], $savedJobs);
        $excludeIds = empty($savedPostIds) ? '0' : implode(',', $savedPostIds);

    } else {
        $excludeIds = '0';
    }

    // III. LOGIC TRUY VẤN VIỆC LÀM GỢI Ý

    $suggestedLimit = 5; 
    $suggestedJobsSql = "
        SELECT 
            rj.id, rj.title, rj.salary_min, rj.salary_max, rj.deadline, rj.location,
            ep.company_name, ep.logo
        FROM recruitment_posts rj
        JOIN employer_profiles ep ON rj.user_id = ep.user_id
        WHERE rj.status = '1' AND rj.id NOT IN ({$excludeIds})
        ORDER BY rj.created_at DESC
        LIMIT {$suggestedLimit}
    ";
    $suggestedJobs = getAll($suggestedJobsSql);
?>

<!--  Nội dung trang savejob -->
<div class="container ">
    <div class="inner-wrapper">
        <aside class="sidebar">
            <div class="username"><?php echo $currentUserName; ?></div>

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

        <div class="content">
            <div class="content-one">
                <div class="intro">Xin Chào, <span><?php echo $currentUserName; ?></span></div>
            </div>

            <div class="content-two">
            <div class="title">
                <span class="title-one">Việc làm đã lưu</span>
            </div>

            <?php if (empty($savedJobs)) { ?>
                <div class="display-inform">
                    <div class="status-inform">Bạn chưa có việc làm đã lưu</div>
                    <div class="desc-inform">
                        <img src="/JobPortalWebsite/assets/images/undraw_empty-street_3ogh.svg" alt="empty">
                    </div>
                </div>
            <?php } else { ?>
                <div class="job-list-saved">
                    <?php 
                    foreach ($savedJobs as $job) { 
                        $jobId = htmlspecialchars($job['id']);
                        $jobTitle = htmlspecialchars($job['title']);
                        $companyName = htmlspecialchars($job['company_name']);
                        $salaryRange = format_salary($job['salary_min'], $job['salary_max']);
                        $daysRemaining = calculate_days_remaining($job['deadline']);
                        $location = htmlspecialchars($job['location']);
                        $logoUrl = !empty($job['logo']) ? htmlspecialchars($job['logo']) : 'URL_DEFAULT'; // Tùy chỉnh URL mặc định
                    ?>
                        <div class="job-card-saved">
                            <div class="card-left">
                                <a href="/job/detail?id=<?php echo $jobId; ?>"><?php echo $jobTitle; ?></a>
                                <div class="company-name"><?php echo $companyName; ?></div>
                            </div>
                            <div class="card-right">
                                <span class="salary"><?php echo $salaryRange; ?></span>
                                <span class="location"><?php echo $location; ?></span>
                                <i class="fa-solid fa-heart favorite-btn saved" data-job-id="<?php echo $jobId; ?>"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    <div class="content-three">
    <div class="wrapper">
        <div class="title"><i class="fa-regular fa-lightbulb"></i> <span>Việc làm gợi ý</span></div>
    </div>

    <div class="job-grid">
        <?php 
        if (!empty($suggestedJobs)) {
            foreach ($suggestedJobs as $job) {
                $jobId = htmlspecialchars($job['id']);
                $jobTitle = htmlspecialchars($job['title']);
                $companyName = htmlspecialchars($job['company_name']);
                $salaryRange = format_salary($job['salary_min'], $job['salary_max']);
                $daysRemaining = calculate_days_remaining($job['deadline']);
                $location = htmlspecialchars($job['location']);
                $logoUrl = !empty($job['logo']) ? htmlspecialchars($job['logo']) : 'URL_DEFAULT';
        ?>
            <a href="/job/detail?id=<?php echo $jobId; ?>" class="job-card">
                <div class="inner">
                    <div class="major"><span><?php echo $jobTitle; ?></span> 
                        <i class="fa-regular fa-heart favorite-btn" data-job-id="<?php echo $jobId; ?>"></i>
                    </div>
                </div>
                <div class="inner-two">
                    <div class="image">
                        <img src="<?php echo $logoUrl; ?>" alt="<?php echo $companyName; ?>">
                    </div>
                    <div class="introduce">
                        <h3><?php echo $companyName; ?></h3>
                        <div class="salary"><i class="fa-solid fa-dollar-sign"></i> <span><?php echo $salaryRange; ?></span></div>
                        <div class="position">
                            <i class="fa-thin fa-location-dot"></i> <span><?php echo $location; ?></span>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="coundown">
                    <div class="space"></div>
                    <div class="time"><i class="fa-regular fa-clock"></i> <span class="tim_count"><?php echo $daysRemaining; ?></span></div>
                </div>
            </a>
        <?php 
            }
        } else {
            echo '<p>Hiện không có gợi ý việc làm nào.</p>';
        }
        ?>
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
    fetch("/modules/candidate/views/toggle_save_job.php", {
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
<?php require_once getCurrentPath() . '/core/templates/candidate_footer.php'; ?>
