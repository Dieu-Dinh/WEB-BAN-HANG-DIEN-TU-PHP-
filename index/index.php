<?php
session_start();
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// Lấy 6 sản phẩm nổi bật (hoặc điều kiện tùy chọn)
$sql = "SELECT 
            sp.id_san_pham,
            sp.ten_san_pham,
            sp.mo_ta,
            ha.url_hinh_anh,
            MIN(bt.gia_ban) AS gia_ban
        FROM san_pham sp
        LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham
        LEFT JOIN hinh_anh_san_pham ha ON sp.id_san_pham = ha.id_san_pham AND ha.la_anh_dai_dien = 1
        GROUP BY sp.id_san_pham
        ORDER BY sp.id_san_pham DESC
        LIMIT 6";
$result = $conn->query($sql);
?>

<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Fox Tech - Trang Chủ</title>
    <link rel="stylesheet" href="index.css" />
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

        <?php if (!isset($_SESSION["user"])): ?>
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
    <div id="fox-body">
        <h1>Chào mừng đến với Fox Tech</h1>
        <p>Sản phẩm công nghệ chất lượng cao, giá tốt nhất</p>
        <a href="#products" class="btn">Khám phá ngay</a>
    </div>

    <!-- About -->
    <div id="about">
        <h2>Về chúng tôi</h2>
        <p>Fox Tech cung cấp sản phẩm công nghệ đa dạng: laptop, điện thoại, thiết bị thông minh...</p>
    </div>

    <!-- News & Login -->
    <div class="two-columns">
        <!-- Tin tức -->
        <div class="news-section">
            <h2>Tin tức công nghệ</h2>
            <div class="news-list">
                <div class="news-item">
                    <h3>Ra mắt sản phẩm mới</h3>
                    <p>Fox Tech vừa giới thiệu mẫu laptop thế hệ mới với hiệu năng vượt trội...</p>
                    <a href="#" class="btn-detail">Đọc thêm</a>
                </div>
                <div class="news-item">
                    <h3>Khuyến mãi hè 2025</h3>
                    <p>Chương trình giảm giá cực sốc cho các sản phẩm công nghệ trong tháng 6...</p>
                    <a href="#" class="btn-detail">Đọc thêm</a>
                </div>
            </div>
        </div>

        
    </div>
    
    <!-- Hot Deal -->
    <div class="hot-deal">
        <h2>HOT DEAL</h2>
    </div>

    <!-- Product Section -->
    <div id="products">
    <h2>Sản phẩm nổi bật</h2>
    <div class="product-list">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($row['url_hinh_anh'] ?: 'https://placehold.co/260x150?text=No+Image') ?>" alt="<?= htmlspecialchars($row['ten_san_pham']) ?>" />
            <h3><?= htmlspecialchars($row['ten_san_pham']) ?></h3>
            <p><?= htmlspecialchars(substr($row['mo_ta'], 0, 80)) ?><?= strlen($row['mo_ta']) > 80 ? '...' : '' ?></p>
            <a href="../SanPham/ChiTietSanPham.php?id_san_pham=<?= $row['id_san_pham'] ?>" class="btn-detail">Xem chi tiết</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

    <!-- Dịch vụ -->
    <div id="services">
        <h2>Dịch vụ & Ưu đãi</h2>
        <ul>
            <li>Bảo hành 12 tháng</li>
            <li>Giao hàng nhanh trong 24h</li>
            <li>Hỗ trợ kỹ thuật 24/7</li>
        </ul>
    </div>

    <?php if (isset($_GET['updated'])) echo "<script>alert('Cập nhật thông tin thành công!');</script>"; ?>

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
    document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("user-toggle");
    const dropdownMenu = document.querySelector(".user-dropdown .dropdown-menu");

    if (toggleBtn && dropdownMenu) {
        toggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
            if (!e.target.closest(".user-dropdown")) {
                dropdownMenu.style.display = "none";
            }
        });
    }
});
</script>
</body>
</html>
