<html lang="vi">
<head>
    <meta charset="UTF-8" />
  <title>Liên hệ chúng tôi</title>
  <link rel="stylesheet" href="Lienhe.css" />
</head>
<body>
<div id="fox">
    <!-- Header -->
    <div id="fox-header">
        <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand" />
    </div>

    <!-- Navigation -->
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


    <!-- Contact -->
    <div class="contact-container">
        <h1>Liên hệ với chúng tôi</h1>
        <p>Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng để lại thông tin tại đây. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
        
        <form id="contactForm">
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="message">Nội dung:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Gửi</button>
            <p class="success-message" id="successMsg" style="display: none; color: green;">Đã gửi thành công!</p>
        </form>
    </div>

        <!-- Google Map -->
        <div style="margin-top: 30px;">
            <h2>Địa chỉ trên bản đồ</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.123456789012!2d106.12345678901234!3d10.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752abcde123456%3A0x123456789abcdef0!2sFox%20Tech%20Store!5e0!3m2!1sen!2svi!4v1612345678901" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789 | Email: support@foxtech.vn</p>
        <p>
            <a href="../index/index.html">Trang chủ</a> |
            <a href="../SanPham/SanPham.php">Sản phẩm</a> |
            <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> |
            <a href="../ChinhSachBaoMat/ChinhSachBaoMat.html">Chính sách bảo mật</a> |
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
    document.getElementById("contactForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const message = document.getElementById("message").value.trim();

        if (name === "" || email === "" || message === "") {
            alert("Vui lòng điền đầy đủ thông tin.");
            return;
        }

        document.getElementById("successMsg").style.display = "block";
        this.reset();
    });
</script>
</body>
</html>
