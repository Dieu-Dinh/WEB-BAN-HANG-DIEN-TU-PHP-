<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fox Tech - Giới Thiệu</title>
    <link rel="stylesheet" href="Gioithieu.css">
</head>
<body>
<div id="fox">
    <!-- Header -->
    <div id="fox-header">
        <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand" />
    </div>

    <!-- Navigation -->
   <div id="fox-nav">
    <ul>
        <li><a href="../index/index.php">Trang chủ</a></li>
        <li><a href="../SanPham/SanPham.php">Sản phẩm</a></li>
        <li><a href="../Gioithieu/Gioithieu.php">Giới thiệu</a></li>
        <li><a href="../chinhsachbaomat/chinhsachbaomat.php">Chính sách bảo mật</a></li>
        <li><a href="../LienHe/Lienhe.php">Liên hệ</a></li>

        <?php 
        session_start();
        if (!isset($_SESSION["user"])): ?>
            <!-- Chưa đăng nhập -->
            <li><a href="../Login/Login.php">Đăng nhập</a></li>
        <?php else: ?>
            <!-- Đã đăng nhập -->
            <?php $username = htmlspecialchars($_SESSION["user"]["username"]); ?>
            <li class="user-dropdown">
                <a href="#" id="user-toggle"><?= $username ?> ⮟</a>
                <ul class="dropdown-menu" style="display: none;">
                    <li><a href="../User/ThongTinCaNhan.php">Thông tin cá nhân</a></li>
                    <li><a href="../DonHang/DonHangCuaToi.php">Đơn hàng của tôi</a></li>
                    <li><a href="../Login/logout.php">Đăng xuất</a></li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</div>

    <!-- Hero Section -->
    <div class="intro-hero">
        <h1>Về Fox Tech</h1>
        <p>Chúng tôi là <span class="highlight">đơn vị tiên phong</span> trong lĩnh vực thương mại điện tử đồ công nghệ, 
        mang đến cho khách hàng những sản phẩm chất lượng cao với dịch vụ tận tâm nhất.</p>
    </div>

    <div class="main-content">
        <!-- Về chúng tôi -->
        <div class="section about-section">
            <h2>Câu chuyện của chúng tôi</h2>
            <div class="about-content">
                <div class="about-text">
                    <p><strong>Fox Tech</strong> sinh ra với tầm nhìn trở thành nền tảng thương mại điện tử hàng đầu Việt Nam trong lĩnh vực công nghệ.</p>
                    
                    <p>Với đội ngũ chuyên gia giàu kinh nghiệm và am hiểu sâu sắc về công nghệ, Fox Tech cam kết mang đến cho khách hàng những sản phẩm chính hãng, chất lượng cao từ các thương hiệu uy tín trên thế giới.</p>
                    
                    <p>Chúng tôi hiểu rằng công nghệ không chỉ là sản phẩm, mà còn là cầu nối giúp con người kết nối, sáng tạo và phát triển. Vì vậy, sứ mệnh của chúng tôi là democratize technology - làm cho công nghệ trở nên dễ tiếp cận với mọi người.</p>
                </div>
                <div class="about-stats">
                    <div class="stat-card">
                        <span class="stat-number">50K+</span>
                        <div class="stat-label">Khách hàng tin tưởng</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">5000+</span>
                        <div class="stat-label">Sản phẩm đa dạng</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">99%</span>
                        <div class="stat-label">Khách hàng hài lòng</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">Hỗ trợ khách hàng</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch sử phát triển -->
        <div class="section history-section">
            <h2>Hành trình phát triển</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2019</div>
                        <div class="timeline-event">Thành lập Fox Tech với 5 nhân viên đầu tiên. Bắt đầu kinh doanh laptop và linh kiện máy tính qua website.</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2020</div>
                        <div class="timeline-event">Mở rộng sang điện thoại thông minh và thiết bị wearable. Đạt 10,000 khách hàng và doanh thu 50 tỷ đồng.</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2021</div>
                        <div class="timeline-event">Ra mắt ứng dụng mobile Fox Tech App. Thiết lập hệ thống kho bãi và logistics hiện đại tại TP.HCM.</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2022</div>
                        <div class="timeline-event">Mở rộng ra Hà Nội và Đà Nẵng. Trở thành đại lý chính thức của Apple, Samsung, Xiaomi tại Việt Nam.</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-event">Đạt mốc 50,000 khách hàng và doanh thu 500 tỷ đồng. Nhận giải thưởng "Thương mại điện tử xuất sắc".</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-event">Triển khai AI Shopping Assistant và hệ thống giao hàng drone. Mục tiêu mở rộng toàn Đông Nam Á.</div>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
            </div>
        </div>

        <!-- Tầm nhìn & Sứ mệnh -->
        <div class="section vision-mission">
            <h2>Tầm nhìn & Sứ mệnh</h2>
            <div class="vm-grid">
                <div class="vm-card">
                    <h3>🎯 Tầm nhìn</h3>
                    <p>Trở thành nền tảng thương mại điện tử công nghệ hàng đầu Đông Nam Á vào năm 2030, với hệ sinh thái hoàn chỉnh từ bán lẻ, dịch vụ hậu mãi đến giải pháp công nghệ cho doanh nghiệp.</p>
                </div>
                <div class="vm-card">
                    <h3>🚀 Sứ mệnh</h3>
                    <p>Democratize Technology - Làm cho công nghệ trở nên dễ tiếp cận, giúp mọi người nâng cao chất lượng cuộc sống thông qua những sản phẩm công nghệ chất lượng cao với giá cả hợp lý.</p>
                </div>
            </div>
        </div>

        <!-- Giá trị cốt lõi -->
        <div class="section values-section">
            <h2>Giá trị cốt lõi</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🎯</div>
                    <div class="value-title">Chất lượng</div>
                    <div class="value-description">Cam kết chỉ cung cấp sản phẩm chính hãng, chất lượng từ các thương hiệu uy tín hàng đầu thế giới.</div>
                </div>
                <div class="value-card">
                    <div class="value-icon">💎</div>
                    <div class="value-title">Uy tín</div>
                    <div class="value-description">Xây dựng niềm tin thông qua sự minh bạch, trung thực trong mọi giao dịch với khách hàng.</div>
                </div>
                <div class="value-card">
                    <div class="value-icon">🚀</div>
                    <div class="value-title">Đổi mới</div>
                    <div class="value-description">Không ngừng cải tiến công nghệ và dịch vụ để mang lại trải nghiệm tốt nhất cho khách hàng.</div>
                </div>
                <div class="value-card">
                    <div class="value-icon">❤️</div>
                    <div class="value-title">Tận tâm</div>
                    <div class="value-description">Đặt khách hàng làm trung tâm, luôn lắng nghe và hỗ trợ tận tình trong mọi tình huống.</div>
                </div>
            </div>
        </div>

        <!-- Đội ngũ lãnh đạo -->
        <div class="section team-section">
            <h2>Đội ngũ lãnh đạo</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-avatar">NK</div>
                    <div class="member-name">Nguyễn Đức Khánh</div>
                    <div class="member-position">CEO & Founder</div>
                    <div class="member-description">Với hơn 15 năm kinh nghiệm trong lĩnh vực công nghệ và thương mại điện tử. Tốt nghiệp MBA từ ĐH Stanford và từng làm việc tại các tập đoàn công nghệ lớn.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">LA</div>
                    <div class="member-name">Huỳnh Ngọc Lan Anh</div>
                    <div class="member-position">CTO</div>
                    <div class="member-description">Chuyên gia công nghệ với 12 năm kinh nghiệm phát triển hệ thống e-commerce. Tốt nghiệp Thạc sĩ Khoa học Máy tính từ ĐH Bách khoa Hà Nội.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">DD</div>
                    <div class="member-name">Điểu Đinh</div>
                    <div class="member-position">CMO</div>
                    <div class="member-description">Chuyên gia marketing số với 10 năm kinh nghiệm trong lĩnh vực thương mại điện tử. Đã xây dựng thành công nhiều thương hiệu công nghệ tại Việt Nam.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">LH</div>
                    <div class="member-name">Lê Nhật Hải</div>
                    <div class="member-position">Thành Viên</div>
                    <div class="member-description">Chuyên viên phát triển sản phẩm với kinh nghiệm trong việc nghiên cứu thị trường và phân tích xu hướng công nghệ.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">LK</div>
                    <div class="member-name">Lê Trung Kiên</div>
                    <div class="member-position">Thành Viên</div>
                    <div class="member-description">Chuyên gia về logistics và chuỗi cung ứng, đảm bảo việc giao hàng nhanh chóng và chất lượng dịch vụ khách hàng.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Công Nghệ, Quận 1, TP.HCM | Hotline: 0123 456 789 | Email: info@foxtech.vn</p>
        <p>
            <a href="../index/index.php">Trang chủ</a> | 
            <a href="../SanPham/SanPham.php">Sản phẩm</a> | 
            <a href="#">Giới thiệu</a> | 
            <a href="../chinhsachbaomat/chinhsachbaomat.html">Chính sách bảo mật</a> |
            <a href="../LienHe/LienHe.html">Liên hệ</a>
        </p>
        <p style="margin-top: 20px;">
            <strong>Theo dõi chúng tôi:</strong>
            <a href="#">Facebook</a> | 
            <a href="#">Instagram</a> | 
            <a href="#">LinkedIn</a> | 
            <a href="#">YouTube</a>
        </p>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("user-toggle");
    const menu = document.querySelector(".dropdown-menu");

    if (toggle && menu) {
        toggle.addEventListener("click", function(e) {
            e.preventDefault();
            menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
        });

        // Đóng menu khi click ra ngoài
        document.addEventListener("click", function(e) {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    }
});
</script>

</body>
</html>