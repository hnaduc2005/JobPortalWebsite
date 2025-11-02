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

