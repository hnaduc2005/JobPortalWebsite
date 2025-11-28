<?php
// user_list.php
// Toàn bộ file quản lý danh sách user + search + phân trang (window 3 trang)
// Giữ các hàm trợ giúp: checkAccessToken(), filterData(), isGet(), getOne(), getAll()

checkAccessToken();
require_once __DIR__ . "/header.php";
require_once __DIR__ . "/sidebar.php";

// Lấy filter từ request (giữ nguyên hàm filterData() nếu bạn đã có)
$filter = filterData();
$whereString = '';
$group = '';
$keyword = '';

// Chỉ xử lý khi request GET
if (isGet()) {
    if (isset($filter['keyword'])) {
        $keyword = trim($filter['keyword']);
    }

    if (isset($filter['group'])) {
        $group = $filter['group'];
    }

    // Build WHERE string an toàn tối thiểu (sử dụng addslashes nếu bạn không dùng prepared statements ở layer DB)
    // Nếu lớp DB của bạn đã hỗ trợ binding thì nên cập nhật getOne/getAll để dùng binding.
    if (!empty($keyword)) {
        $kw = addslashes($keyword);
        if (strpos($whereString, 'WHERE') === false) {
            $whereString .= ' WHERE ';
        } else {
            $whereString .= ' AND ';
        }
        $whereString .= "(fullname LIKE '%$kw%' OR email LIKE '%$kw%')";
    }

    if ($group !== '') {
        // ép kiểu an toàn
        $groupVal = (int)$group;
        if (strpos($whereString, 'WHERE') === false) {
            $whereString .= ' WHERE ';
        } else {
            $whereString .= ' AND ';
        }
        $whereString .= "role = '$groupVal'";
    }
}

// ----- PHÂN TRANG -----
$limit = 5; // số bản ghi mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// Tổng số bản ghi
$totalUserRow = getOne("SELECT COUNT(*) AS total FROM user $whereString");
$totalUser = isset($totalUserRow['total']) ? (int)$totalUserRow['total'] : 0;
$totalPage = ($totalUser > 0) ? (int)ceil($totalUser / $limit) : 1;
if ($totalPage < 1) $totalPage = 1;

// Lấy danh sách user có phân trang
$getUserDetails = getAll("
    SELECT user.id, user.fullname, user.email, user.role 
    FROM user 
    $whereString 
    ORDER BY user.role 
    LIMIT $start, $limit
");

// Role map
$roles = [
    0 => 'admin',
    1 => 'candidate',
    2 => 'employer'
];

?>

<div class="container" style="margin-top: 25px;">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
    <div class="alert alert-success">Đã xoá người dùng thành công!</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_id'): ?>
    <div class="alert alert-danger">ID không hợp lệ!</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'not_found'): ?>
    <div class="alert alert-warning">Người dùng không tồn tại!</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'delete_failed'): ?>
    <div class="alert alert-danger">Không thể xoá người dùng!</div>
    <?php endif; ?>
    <div class="container-fluid">
        <a href="?module=admin&action=add_user" class="btn btn-success mb-3">
            <i class="fa-solid fa-plus"></i> Thêm mới tài khoản
        </a>

        <form action="" method="get" class="mb-3">
            <input type="hidden" name="module" value="admin">
            <input type="hidden" name="action" value="user_list">

            <div class="row">
                <div class="col-3">
                    <select class="form-select form-control" name="group">
                        <option value="">Nhóm người dùng</option>
                        <?php foreach ($roles as $value => $label): ?>
                        <option value="<?php echo $value; ?>"
                            <?php if ($group !== '' && (int)$group == $value) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-7">
                    <input type="text" class="form-control"
                        value="<?php echo (!empty($keyword)) ? htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        name="keyword" placeholder="Nhập thông tin tìm kiếm...">
                </div>

                <div class="col-2">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>TÊN</th>
                    <th>EMAIL</th>
                    <th>PHÂN QUYỀN</th>
                    <th>NHÓM</th>
                    <th>XOÁ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($getUserDetails)): ?>
                <?php foreach ($getUserDetails as $key => $item): ?>
                <tr>
                    <th><?php echo $start + $key + 1; ?></th>
                    <td><?php echo htmlspecialchars($item['fullname'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['email'], ENT_QUOTES, 'UTF-8'); ?></td>

                    <td>
                        <a href="?module=admin&action=permission&id=<?php echo (int)$item['id']; ?>"
                            class="btn btn-primary">Phân quyền</a>
                    </td>

                    <td>
                        <?php
                                    $r = isset($item['role']) ? (int)$item['role'] : null;
                                    echo isset($roles[$r]) ? htmlspecialchars($roles[$r], ENT_QUOTES, 'UTF-8') : 'N/A';
                                ?>
                    </td>

                    <td>
                        <a href="?module=admin&action=delete&id=<?php echo (int)$item['id']; ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xoá người dùng này?')"
                            class="btn btn-danger">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6">Không có dữ liệu</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- PHÂN TRANG -->
        <?php
        // Escape params cho URL (hiển thị link)
        $group_enc = urlencode($group);
        $keyword_enc = urlencode($keyword);

        // Window hiển thị số trang (3)
        $visibleCount = 3;
        if ($totalPage <= $visibleCount) {
            $startPage = 1;
            $endPage = $totalPage;
        } else {
            $half = floor($visibleCount / 2);
            $startPage = $page - $half;
            $endPage = $page + $half;

            if ($startPage < 1) {
                $startPage = 1;
                $endPage = $visibleCount;
            }

            if ($endPage > $totalPage) {
                $endPage = $totalPage;
                $startPage = $totalPage - $visibleCount + 1;
                if ($startPage < 1) $startPage = 1;
            }
        }
        ?>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">

                <!-- Prev -->
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link"
                        href="?module=admin&action=user_list&page=<?php echo max(1, $page - 1); ?>&group=<?php echo $group_enc; ?>&keyword=<?php echo $keyword_enc; ?>">&laquo;</a>
                </li>

                <!-- Link tới trang 1 nếu window bắt đầu sau 1 -->
                <?php if ($startPage > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?module=admin&action=user_list&page=1&group=<?php echo $group_enc; ?>&keyword=<?php echo $keyword_enc; ?>">1</a>
                </li>
                <?php if ($startPage > 2): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Page numbers -->
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link"
                        href="?module=admin&action=user_list&page=<?php echo $i; ?>&group=<?php echo $group_enc; ?>&keyword=<?php echo $keyword_enc; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>

                <!-- Link tới trang cuối nếu window chưa tới cuối -->
                <?php if ($endPage < $totalPage): ?>
                <?php if ($endPage < $totalPage - 1): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?module=admin&action=user_list&page=<?php echo $totalPage; ?>&group=<?php echo $group_enc; ?>&keyword=<?php echo $keyword_enc; ?>">
                        <?php echo $totalPage; ?>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Next -->
                <li class="page-item <?php if ($page >= $totalPage) echo 'disabled'; ?>">
                    <a class="page-link"
                        href="?module=admin&action=user_list&page=<?php echo min($totalPage, $page + 1); ?>&group=<?php echo $group_enc; ?>&keyword=<?php echo $keyword_enc; ?>">&raquo;</a>
                </li>

            </ul>
        </nav>

    </div>
</div>

<?php
require_once __DIR__ . "/footer.php";
?>