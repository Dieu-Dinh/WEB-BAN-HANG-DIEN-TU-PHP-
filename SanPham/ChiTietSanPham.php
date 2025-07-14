<?php
session_start();
// Hàm kiểm tra user đã đăng nhập chưa
function isUserLoggedIn() {
    return isset($_SESSION["user"]) && !empty($_SESSION["user"]["id"]);
}
?>

<?php
$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$id = (int)($_GET['id_san_pham'] ?? 0);

$sql = "SELECT sp.*, ha.url_hinh_anh, MIN(btsp.gia_ban) AS gia_thap_nhat
        FROM san_pham sp
        LEFT JOIN hinh_anh_san_pham ha ON sp.id_san_pham = ha.id_san_pham AND ha.la_anh_dai_dien = TRUE
        LEFT JOIN bien_the_san_pham btsp ON sp.id_san_pham = btsp.id_san_pham
        WHERE sp.id_san_pham = $id
        GROUP BY sp.id_san_pham";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Lấy biến thể
$variants = [];
$result_variant = $conn->query("SELECT * FROM bien_the_san_pham WHERE id_san_pham = $id");
while ($row = $result_variant->fetch_assoc()) {
    $variants[] = $row;
}

// Lấy đánh giá
$reviews = [];
$result_reviews = $conn->query("SELECT * FROM danh_gia_san_pham WHERE id_san_pham = $id ORDER BY ngay_danh_gia DESC");
while ($r = $result_reviews->fetch_assoc()) {
    $reviews[] = $r;
}

$conn->close();


// Xử lý video link YouTube
$videoLink = $product['video_gioi_thieu'] ?? '';
$embedLink = '';
if (strpos($videoLink, 'watch?v=') !== false) {
    $embedLink = str_replace('watch?v=', 'embed/', $videoLink);
} elseif (strpos($videoLink, 'youtu.be/') !== false) {
    $videoId = substr($videoLink, strrpos($videoLink, '/') + 1);
    $embedLink = 'https://www.youtube.com/embed/' . $videoId;
} elseif (strpos($videoLink, 'embed/') !== false) {
    $embedLink = $videoLink;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['ten_san_pham'] ?? 'Chi tiết sản phẩm') ?></title>
    <link rel="stylesheet" href="../index/index.css">
    <link rel="stylesheet" href="ChiTietSanPham.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
    $(".product-detail-wrapper img").hover(
        function() { $(this).css("transform", "scale(1.02)"); },
        function() { $(this).css("transform", "scale(1)"); }
    );

    // Toggle toàn bộ thông tin trong .product-info-block khi click vào h3
    $(".product-info-block h3").css("cursor", "pointer").click(function() {
        $(this).siblings("p").slideToggle(); // tất cả thẻ <p> bên cạnh h3 sẽ được ẩn/hiện
    });

    // Toggle danh sách biến thể
    $(".variant-section h3").css("cursor", "pointer").click(function() {
        $(this).next("ul").slideToggle();
    });

    // Cuộn đến phần đánh giá khi click
    $(".review-section h3").click(function() {
        $('html, body').animate({
            scrollTop: $(".review-section").offset().top - 100
        }, 600);
    });
});
    </script>
</head>
<body>
<div id="fox">
    <!-- Header -->
    <div id="fox-header">
        <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand" />
    </div>

    <!-- Nav -->
    <div id="fox-nav">
    <ul>
        <li><a href="../index/index.php">Trang chủ</a></li>
        <li><a href="../SanPham/SanPham.php">Sản phẩm</a></li>
        <li><a href="../Gioithieu/Gioithieu.html">Giới thiệu</a></li>
        <li><a href="../ChinhSachBaoMat/ChinhSachBaoMat.html">Chính sách bảo mật</a></li>
        <li><a href="../LienHe/LienHe.html">Liên hệ</a></li>

        <?php if (!isset($_SESSION["user"])): ?>
            <li><a href="../Login/Login.php">Đăng nhập</a></li>
        <?php else: ?>
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


    <!-- Chi tiết sản phẩm -->
    <?php if ($product): ?>
        <div class="product-detail-wrapper">
  <h1><?= htmlspecialchars($product['ten_san_pham']) ?></h1>

  <div class="product-detail-layout">
    <div class="product-image-block">
      <img src="<?= htmlspecialchars($product['url_hinh_anh'] ?: 'https://placehold.co/600x350') ?>" alt="Ảnh sản phẩm">
    </div>

    <div class="product-info-block">
      <h3>Thông tin sản phẩm</h3>
      <p><strong>Giá từ:</strong> <?= number_format($product['gia_thap_nhat'], 0, ',', '.') ?>₫</p>
      <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($product['mo_ta'])) ?></p>
      <p><strong>Thông số kỹ thuật:</strong><br><?= nl2br(htmlspecialchars($product['thong_so_ky_thuat'])) ?></p>
      <p><strong>Khuyến mãi:</strong> <?= htmlspecialchars($product['chuong_trinh_khuyen_mai'] ?: "Không có") ?></p>
      <p><strong>Bảo hành:</strong> <?= htmlspecialchars($product['bao_hanh']) ?></p>

      <div class="variant-section">
        <h4>Biến thể sản phẩm</h4>
        <ul>
          <?php foreach ($variants as $v): ?>
            <li>
              Màu: <?= htmlspecialchars($v['mau_sac']) ?> |
              Cấu hình: <?= htmlspecialchars($v['cau_hinh']) ?: "Mặc định" ?> |
              Giá: <?= number_format($v['gia_ban'], 0, ',', '.') ?>₫ |
              Kho: <?= $v['so_luong_ton_kho'] ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <form method="POST" action="../DonHang/DatHang.php" class="order-form">
    <input type="hidden" name="ten_san_pham" value="<?= htmlspecialchars($product['ten_san_pham']) ?>">
    <input type="hidden" name="product_id" value="<?= $product['id_san_pham'] ?>">

    <?php if (count($variants) > 0): ?>
        <div class="variant-wrapper">
            <label for="id_bien_the">Chọn biến thể:</label>
            <select name="id_bien_the" id="id_bien_the" required>
                <option value="" disabled selected>-- Chọn biến thể --</option>
                <?php foreach ($variants as $v): ?>
                    <option value="<?= $v['id_bien_the'] ?>">
                        <?= htmlspecialchars($v['mau_sac']) ?> - Kho: <?= $v['so_luong_ton_kho'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="id_bien_the" value="0">
        <input type="hidden" name="gia_ban" value="<?= $product['gia_thap_nhat'] ?>">
    <?php endif; ?>

    <div class="quantity-wrapper">
        <label for="quantity">Số lượng:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" required>
    </div>
    <?php if (!isUserLoggedIn()): ?>
        <button type="button" class="btn-order" id="btnShowLoginMsg">🛒 Đặt hàng ngay</button>
    <?php else: ?>
        <button type="submit" class="btn-order">🛒 Đặt hàng ngay</button>
    <?php endif; ?>
</form>
<!-- Popup thông báo đăng nhập -->
<div id="loginMsgBox" style="display:none;position:fixed;top:30%;left:50%;transform:translate(-50%,-50%);background:#fff;border:2px solid #007acc;padding:30px 40px;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,0.15);z-index:9999;text-align:center;">
    <p style="font-size:18px;color:#e53935;margin-bottom:20px;">
        Vui lòng <a href="../Login/Login.php" style="color:#007acc;text-decoration:underline;">đăng nhập</a> để mua hàng.
    </p>
    <button onclick="document.getElementById('loginMsgBox').style.display='none'"
            style="padding:8px 20px;border:none;background:#007acc;color:#fff;border-radius:5px;cursor:pointer;">
        Đóng
    </button>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var btnShowLoginMsg = document.getElementById("btnShowLoginMsg");
    if (btnShowLoginMsg) {
        btnShowLoginMsg.addEventListener("click", function(e) {
            document.getElementById("loginMsgBox").style.display = "block";
        });
    }
});
</script>
    </div>
  </div>


        <!-- Video -->
        <?php if (!empty($embedLink)): ?>
        <div class="video-section">
            <h3>Video giới thiệu</h3>
            <iframe src="<?= htmlspecialchars($embedLink) ?>" frameborder="0" allowfullscreen></iframe>
        </div>
        <?php endif; ?>

        <div class="review-section">
    <h3>🗣️ Đánh giá & Bình luận</h3>

    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $rv): ?>
            <div class="review-box">
                <div class="review-header">
                    <span class="review-user"><?= htmlspecialchars($rv['ten_nguoi_dung']) ?></span>
                    <span class="review-rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $rv['diem_danh_gia'] ? '★' : '☆' ?>
                        <?php endfor; ?>
                        (<?= $rv['diem_danh_gia'] ?>/5)
                    </span>
                    <span class="review-date"><?= date("d/m/Y", strtotime($rv['ngay_danh_gia'])) ?></span>
                </div>
                <div class="review-content"><?= nl2br(htmlspecialchars($rv['noi_dung_binh_luan'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="margin: 10px 0; color: #666;">Chưa có đánh giá nào cho sản phẩm này.</p>
    <?php endif; ?>

    <!-- ===== Gửi đánh giá ===== -->
    <?php if (isset($_SESSION["user"])): ?>
    <div class="review-form">
        <h3>Gửi đánh giá của bạn</h3>
        <form method="POST" action="../DonHang/XuLyDanhGia.php">
            <input type="hidden" name="id_san_pham" value="<?= $product['id_san_pham'] ?>">

            <label for="diem_danh_gia">Chọn số sao:</label>
            <select name="diem_danh_gia" id="diem_danh_gia" required>
                <option value="">-- Chọn số sao --</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?> sao</option>
                <?php endfor; ?>
            </select>

            <label for="noi_dung_binh_luan">Nội dung bình luận:</label>
            <textarea name="noi_dung_binh_luan" rows="3" placeholder="Nhập đánh giá của bạn..." required></textarea>

            <button type="submit" class="btn-order">📩 Gửi đánh giá</button>
        </form>
    </div>
    <?php else: ?>
        <p style="text-align:center; margin-top:20px;">Bạn cần <a href="../Login/Login.php">đăng nhập</a> để gửi đánh giá.</p>
    <?php endif; ?>
</div>
        <button class="btn-back" onclick="window.location.href='SanPham.php'">← Quay lại danh sách sản phẩm</button>

    </div>
    <?php else: ?>
        <p style="text-align:center; margin: 50px 0; font-size: 18px;">Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
    </div>
    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789 | Email: support@foxtech.vn</p>
        <p>
            <a href="../index/index.php">Trang chủ</a> |
            <a href="SanPham.php">Sản phẩm</a> |
            <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> |
            <a href="../ChinhSachBaoMat/ChinhSachBaoMat.html">Chính sách bảo mật</a> |
            <a href="../LienHe/LienHe.html">Liên hệ</a>
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
