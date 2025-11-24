<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/Candidate/homePage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Header</title>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="inner-wrapper">
                <div class="inner-left">
                    <div class="inner_logo">
                        <a href="#"><img src="../../assets/images/logo1.png" alt="logo"></a>
                    </div>
                    <div class="inner_drop_down">
                        <div class="inner inner-one">
                            <span class="title">Việc làm <i class="fa-solid fa-caret-down"></i></span>

                            <ul class="submenu-main">
                                <li><i class="fa-solid fa-magnifying-glass"></i> Tìm việc làm</li>

                                <li class="has-child">
                                    <div class="parent-item">
                                        <div class="click">
                                            <span><i class="fa-solid fa-suitcase"></i> Quản lý việc làm</span>
                                            <i class="fa-solid fa-chevron-down arrow"></i>
                                        </div>
                                        <ul class="submenu-listchild">
                                            <li><a href="/JobPortalWebsite/modules/candidate/applied-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã ứng tuyển</a></li>
                                            <li><a href="/JobPortalWebsite/modules/candidate/applied-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã lưu</a></li>
                                            <li><a href="/JobPortalWebsite/modules/candidate/wait-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm chờ ứng tuyển</a></li>
                                            <li><a href="#"><i class="fa-solid fa-circle"></i> Nhà tuyển dụng xem hồ sơ</a></li>
                                        </ul>
                                    </div>
                                </li>
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
                    <div class="inner-item">
                        <div class=" item item-one">
                            <button><i class="fa-regular fa-bell"></i></button>
                        </div>

                        <div class="item item-two">
                            <button>
                                <img src="../../assets/images/reference_logo.png" alt="personal logo">
                                <span>Suu</span>
                                <i class="fa-solid fa-caret-down"></i>
                            </button>

                            <ul class="submenu-main">
                                <li><i class="fa-solid fa-magnifying-glass"></i> Hồ sơ của tôi</li>

                                <li class="has-child">
                                    <div class="parent-item">
                                        <div class="click">
                                            <span><i class="fa-solid fa-suitcase"></i> Quản lý việc làm</span>
                                            <i class="fa-solid fa-chevron-down arrow"></i>
                                        </div>
                                        <ul class="submenu-listchild">
                                            <li><a href="/JobPortalWebsite/modules/candidate/applied-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã ứng tuyển</a></li>
                                            <li><a href="/JobPortalWebsite/modules/candidate/applied-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm đã lưu</a></li>
                                            <li><a href="/JobPortalWebsite/modules/candidate/wait-job_page.php"><i class="fa-solid fa-circle"></i> Việc làm chờ ứng tuyển</a></li>
                                            <li><a href="#"><i class="fa-solid fa-circle"></i> Nhà tuyển dụng xem hồ sơ</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="/JobPortalWebsite/modules/candidate/account_manage.php"><i class="fa-solid fa-circle"></i> Quản lý tài khoản</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="inner-translate">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARMAAAC3CAMAAAAGjUrGAAAAkFBMVEXaJR3//wDZGB7pfxLZIB3YAB7++ADZHR7//ADZFh7ZAB3ytwzpjRT+9gD2zAn++gDbKxzmeRX75QHePBr98gPiZBjupBDeQhrcNBzfThrpgxL64Qf52gbvqxDkdBf41AbtlQ/iVBf0wwvxsgz77AfqiRLgUxnlbRXkXhXxvA7tnhD0xwvrlhHkYRXhTxj30Ahp1qubAAAEq0lEQVR4nO3da3eCOBAGYIKDMYIXRASteKVe2/X//7tVWlQQLLicRcL7fO3lwJxMSIaJKgoAAAAAAAAAAAAAAAAAAAAAAAAA3LhlX8DbIcd0qOyLeDPcntu87It4M9xgBmISQe6KrVwkzz0aN1lnjJjcExPG2ESUfRlvReueY9LVyr6Mt6KyC7Xsy3gnohHEpIHkuWmfgpic2mVfyPsgaxjEZGjhyRMSn70gJr1PJE9IdNmPLmLyi5YfvzH5WCJ5fgij+RuTpoGB8qOvs5DeL/ti3gNtpteYTDdIngs+YTcTFAwu+uu7mKyRPMql6ji4i8kAFcgzvmX3tkgeRdHWkZisUTA47/9akZgw7AMVvouGhO2QPJoei4mO5On7sZj4ZV9R6dRtJxaTzrbuJUixZ3H7mu8DyZk+xGRa82qbaAwfYjJs1Dt5yHsICWNerccJOd2EmHRrvefhs2ZCTJqzWi/bDgkhYexQ9mWViKx5YkzmNX7y8GViSOTc85CaibZIiclCy/YPKjScaNmYNDIw43udkG9m+fNJo0qvg1yvl3K3RWqtqzTvEJmPa/aiTRtUoZCcqZukFWqR9E3lJmKuGK2/b+x1i37lQnImNsmrjyLMl6JaeRNSXS9eMCpGZ21VdutM6mTw9x3m5n/yag6SH2K3KjwkR7vihTjuHopdqvQObhUn1wjSrl1IRRjstCrnTYjTqqiptnlUKju5RpFiFDPV+l+KDIMkQGQn1RjzOtnyhES5TLVpZYHsRtWfXKNI+04rDGTj21JMrlHqeP/6U7m1GksyuUZx9+vVoTL8siTLmxBx+7Vd4dSuWKUkD9VZ/x2BB7ojZd6EqG3mXaoMGhUtC2Qn/jnmCkl3V/EdXxZc8R57CdIMvb7UeRMiJXMBe2pKtXJ9gpR4X18avS4hUWiZeZxU6aXWfxI5iPFcbY5p9LO/+vFqckwjehDjuboc0+B25pAwVpMPinnoJn+mJp3m7aQetjS9WhzTSG1OSrasQ/Jo+bbGtTjjRPkKS34NHjyqme9lT2cm/yZQ5HnqXOjS1wrIytvUJf8xDdVMKZ4M9bQfmLInj5qy1xkslbR37Z7kMSHnlHTbvX2bK7ytJ67mTpLvefgs6akzMH4aj5LbmjqyH9NIOohxvG70+HdSW5PcxzTISkidhXMbB9wyEpJH6g/HpM3DDfvbSMMecfsxf6T+oBgRT53W3oovyYSrx9+1H2RetmmxMTBIajwiZRH/NYn3gWRF73VuJ7/2FPG2JomXsiLSrNTynLScEI4XadaX+CPKtPuVqv/sqAnR7L6k8CFt8vDd3YKt+zC5Rgn3eBsqHWmrbWJ0vcuhJ/66S2qPrkOlNZI1efrXBdvUzNJ4xGfXtqaTpO++bqsxfZwtFfi1rWnwLWfyXBdsmQZJgCj8oDI5l21kBfu71nyc5/aEcwomITm/EIDbfjC55uzq5O7oUoDzpXxJSkYwuSp57+23rcmQcZy4+3MGvNTVqTo6Y3sJv5+Hxs3mq+dfuWJ0ehJ+m4Y6+UjZ8WUhdtOJfKVqbqbu+LJQLVPKSbbEvwYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAID/wb8V1zyo7eS2wAAAAABJRU5ErkJggg==" alt="flag">
                    </div>
                </div>
            </div>
    </header>
    <main>