<html lang="vi">
<head>
    <meta charset="UTF-8" />
     <title>Fox Tech - Chính sách bảo mật</title>
    <link rel="stylesheet" href="chinhsachbaomat.css" />
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


    <!-- Privacy Policy Content -->
    <div id="fox-body">
        <h1>Chính sách bảo mật</h1>
        <p>Fox Tech cam kết bảo vệ thông tin cá nhân của khách hàng. Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin của bạn khi truy cập website.</p>
        <h2>1. Thu thập thông tin</h2>
        <p>Chúng tôi có thể thu thập các thông tin như: họ tên, email, số điện thoại, địa chỉ khi bạn đăng ký tài khoản hoặc mua hàng.</p>
        <h2>2. Mục đích sử dụng thông tin</h2>
        <ul>
            <li>Xử lý đơn hàng và cung cấp dịch vụ</li>
            <li>Liên hệ hỗ trợ khách hàng</li>
            <li>Gửi thông tin khuyến mãi, cập nhật sản phẩm mới (nếu bạn đồng ý)</li>
        </ul>
        <h2>3. Bảo mật thông tin</h2>
        <p>Chúng tôi áp dụng các biện pháp bảo mật để bảo vệ thông tin cá nhân khỏi truy cập trái phép, mất mát hoặc tiết lộ.</p>
        <h2>4. Chia sẻ thông tin</h2>
        <p>Fox Tech không chia sẻ thông tin cá nhân của bạn cho bên thứ ba, trừ khi có yêu cầu từ cơ quan pháp luật hoặc được sự đồng ý của bạn.</p>
        <h2>5. Quyền của khách hàng</h2>
        <p>Bạn có quyền kiểm tra, cập nhật hoặc yêu cầu xóa thông tin cá nhân của mình bất cứ lúc nào bằng cách liên hệ với chúng tôi.</p>
        <h2>6. Liên hệ</h2>
        <p>Nếu có thắc mắc về chính sách bảo mật, vui lòng liên hệ: <b>support@foxtech.vn</b> hoặc hotline <b>0123 456 789</b>.</p>
    </div>

    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789 | Email: support@foxtech.vn</p>
        <p>
            <a href="../index/index.php">Trang chủ</a> | 
            <a href="../SanPham/SanPham.php">Sản phẩm</a> | 
            <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> | 
            <a href="#">Chính sách bảo mật</a> |
            <a href="../LienHe/Lienhe.html">Liên hệ</a>
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