## Cấu trúc dự án

1.	Core : Đặt các thành phần dùng chung
- Core/config : Chứa các file cấu hình ( database, hằng số, …)
- Core/includes : Chứa các file dùng chung ( functions, sessions, …)
- Core/templates : Chứa layout chung ( header, footer, navbar…) -> employer và candidate có thể dùng chung header, footer
2.	Assets : Chứa các tài nguyên tĩnh kp PHP
3.	Modules: Phần chính
- Admin, candidate, employer: Phần riêng cho từng đối tượng
    • Models: xử lí tương tác với database ( chỉ trả về dữ liệu, không xử lí logic hiển thị )
    • Views: phần giao diện HTML + PHP
    • Controllers: xử lí trung gian ( nhận dữ liệu từ models và xử lí logic để hiển thị lên views)
- Auth: Login/logout
4.	Routes : Định nghĩa và quản lí đường dẫn 


## Sử dụng git/github

Cấu hình cơ bản: 
git config --global user.name "..."
git config --global user.email "..."

git config --global --list -> Xem cấu hình

1. Clone về máy: git clone https://github.com/hnaduc2005/JobPortalWebsite ( chỉ 1 lần )
2. Lấy code mới nhất: 
- git checkout main
- git pull origin main
3. Tạo branch ( theo chức năng )
- git checkout -b feature/<-tên chức năng->
VD: git checkout -b feature/update_candidate
4. Lưu thay đổi
- git status -> kiểm tra file thay đổi
- git add . -> thêm file tất cả file vào danh sách chờ (git add <-đường dẫn file-> -> thêm file tuỳ chọn -> VD: git add admin/views/index.php)
- git commit -m "<-ghi chú->"  -> tạo commit (VD: git commit -m "update admin page")
5. Đẩy nhánh lên github
- git push origin feature/<-tên nhánh->
VD: git push origin feature/update_candidate
6. Tạo pull request
- Vào github -> mở repo
- Chọn "Pull request" -> có thông báo "Compare & Pull Request" -> Chọn "Compare & Pull Request"  (Hoặc copy đường dẫn ở terminal khi push thành công)
- Nhập mô tả 
- Chọn nhánh merge vào ( mặc định là main )
- Nhấn "Create Pull Request"
7. Xoá nhánh (tuỳ chọn)
C1: Ấn delete branch trên github sau khi gửi pull request -> Xóa nhánh trên GitHub
C2: git push origin --delete feature/<-tên nhánh->  -> Xóa nhánh trên GitHub

git branch -d feature/<-tên nhánh->     -> Xóa nhánh ở local (sau khi code đã được merge vào main)

! Pull code từ github về máy trước khi xoá ở local
- Chuyển nhánh từ nhánh hiện tại về main: git switch main
- Pull code từ github về máy : git pull origin main 
- Xoá

## Database
1. Bảng User:
- id : int, auto inc, primary key
- email : varchar(100), not null, unique -> duy nhất
- password : varchar(100), not null
- fullname : varchar(150), null -> khi tạo có thể null
- phone : varchar(15), null
- role : char(1) ->  0: admin, 1: candidate, 2: employer
- active_token : varchar(100) -> token được gửi về mail để kích hoạt tài khoản
- status : char(1) -> trạng thái tài khoản (0: bị khoá/chưa kích hoạt, 1: đã kích hoạt)
- is_verified : boolean -> xác nhận email hợp lệ (true/false)
- last_login : datetime -> thời gian đăng nhập cuối cùng
- created_at : datetime -> thời gian tạo
- updated_at : datetime -> cập nhật gần nhất

2. Bảng Candidate_profiles:
- id : int, auto inc, primary key
- user_id : Users [ref, unique, required] -> khoá phụ tham chiếu đến bảng Users
- address : text, null
- links : text, null -> các liên kết đến bên ngoài (fb, ig, github, ...)
- avatar : varchar(200) -> đường dẫn đến ảnh
- date_of_birth : date
- gender : enum('male', 'female', 'other')
- education_level : varchar(100)
- major : varchar(100)
- experience_months : int -> kinh nghiệm nhập vào có thể là cả năm và tháng ( tuỳ form nhập ) -> dùng hàm chuyển về tháng để dễ so sánh
- expected_salary : bigint -> mức lương mong muốn
- preferred_location : varchar(150) -> địa điểm muốn làm việc
- skills : text -> kĩ năng ngăn cách bởi dẩu phẩy (nhập vào)
- cv_files : varchar(250) -> link upload cv
- created_at : datetime -> thời gian tạo 
- updated_at : datetime -> cập nhật gần nhất

3. Bảng Employer_profiles:
- id : int, auto inc, primary key
- user_id : Users [ref, unique, required] → liên kết 1-1 với bảng Users
- company_name : varchar(150) → tên công ty
- industry : text→ ngành nghề hoạt động
- description : text, null → mô tả/giới thiệu công ty
- address : text → địa chỉ trụ sở
- website : text, null → trang web công ty
- phone : char(15) → số điện thoại công ty
- logo : varchar(200) → đường dẫn logo công ty
- founded_year : year → năm thành lập
- tax_code : varchar(20) → mã số thuế
- contact_person : varchar(100) → người phụ trách tuyển dụng
- contact_email : varchar(100) → email liên hệ tuyển dụng
- size : varchar(50) → quy mô (VD: “50-100 nhân viên”)
- created_at : datetime → thời gian tạo
- updated_at : datetime → cập nhật gần nhất

4. Bảng Recruitment_posts:
- id : int, auto inc, primary key
- user_id : Users [ref, required] → nhà tuyển dụng đăng tin
- groups_id : Groups [ref, required] → nhóm ngành/lĩnh vực
- title : varchar(255) → tiêu đề bài đăng
- content : text → mô tả chi tiết công việc
- salary_min : bigInt → mức lương tối thiểu (VNĐ)
- salary_max : bigInt → mức lương tối đa
- recruitment_area : text → khu vực làm việc (TP.HCM, Hà Nội, …)
- experience_months_required : int → yêu cầu kinh nghiệm (tháng)
- level_required : varchar(50), null → cấp độ (Junior/Senior/…)
- employment_type : enum(full-time, part-time, remote) → hình thức làm việc 
- location : varchar(150) → địa điểm làm việc cụ thể
- benefits : text → phúc lợi
- requirements : text → yêu cầu kỹ năng, học vấn
- work_hours : varchar(100) → giờ làm việc
- deadline : datetime → hạn nộp
- status : char(1) → 0: chưa duyệt, 1: đã duyệt
- is_hot : boolean → tin nổi bật (true/false)
- quantity : int → số lượng tuyển
- created_at : datetime → ngày tạo
- updated_at : datetime → cập nhật

5. Bảng Groups:
- id : int, auto inc, primary key
- name : varchar(100), required → tên ngành nghề
- description : text → mô tả ngắn
- slug : varchar(100) → nhãn đường dẫn

6. Bảng Applications:
- id : int, auto inc, primary key
- candidate_id : Candidate_profiles [ref, required] → ứng viên
- post_id : Recruitment_posts [ref, required] → tin tuyển dụng
- cv_file : varchar(255) → CV đính kèm cho tin này (link download)
- cover_letter : text, null → thư xin việc
- status : enum('pending','reviewed','accepted','rejected') → trạng thái
- applied_at : datetime → thời điểm nộp
- updated_at : datetime → cập nhật trạng thái

7. Bảng Saved_jobs:
- id : int, auto inc, primary key
- candidate_id : Candidate_profiles [ref, required] → ứng viên
- post_id : Recruitment_posts [ref, required] → tin được lưu
- saved_at : datetime → thời điểm lưu

8. Bảng Notifications:
- id : int, auto inc, primary key
- user_id : Users [ref, required] → người nhận thông báo
- title : varchar(255) → tiêu đề
- message : text → nội dung
- is_read : boolean → đã đọc hay chưa
- created_at : datetime → thời điểm gửi

