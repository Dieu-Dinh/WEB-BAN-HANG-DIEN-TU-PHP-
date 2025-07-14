<?php
session_start();

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

$sql = "SELECT 
            sp.id_san_pham, 
            sp.ten_san_pham, 
            sp.mo_ta, 
            sp.bao_hanh, 
            ha.url_hinh_anh,
            MIN(btsp.gia_ban) AS gia_ban
        FROM san_pham sp
        LEFT JOIN hinh_anh_san_pham ha ON sp.id_san_pham = ha.id_san_pham AND ha.la_anh_dai_dien = TRUE
        LEFT JOIN bien_the_san_pham btsp ON sp.id_san_pham = btsp.id_san_pham
        GROUP BY sp.id_san_pham
        ORDER BY sp.ten_san_pham";

$result = $conn->query($sql);
$groups = [];

while ($row = $result->fetch_assoc()) {
    $ten = $row['ten_san_pham'];
    if (stripos($ten, 'Túi') !== false) {
        $groups['Túi chống sốc'][] = $row;
    } elseif (stripos($ten, 'Balo') !== false) {
        $groups['Balo laptop'][] = $row;
    } elseif (stripos($ten, 'Quạt') !== false) {
        $groups['Quạt mini'][] = $row;
    } elseif (stripos($ten, 'Máy in') !== false) {
        $groups['Máy in'][] = $row;
    } elseif (stripos($ten, 'iPhone') !== false || stripos($ten, 'Samsung') !== false || stripos($ten, 'OPPO') !== false || stripos($ten, 'Xiaomi') !== false) {
        $groups['Điện thoại'][] = $row;
    } elseif (stripos($ten, 'Máy hủy') !== false) {
        $groups['Máy hủy tài liệu'][] = $row;
    } elseif (stripos($ten, 'PlayStation') !== false || stripos($ten, 'Nintendo') !== false || stripos($ten, 'Xbox') !== false) {
        $groups['Máy chơi game'][] = $row;
    } else {
        $groups['Các sản phẩm nổi bật'][] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm - Fox Tech</title>
    <link rel="stylesheet" href="../index/index.css">
    <link rel="stylesheet" href="SanPham.css">
    <script src="../jquery-3.7.1.min.js"></script>
    <style>
        #filter-bar {
            margin: 20px 30px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }
        #category-select {
            padding: 6px 12px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .category-title {
            font-size: 20px;
            font-weight: bold;
            color: #004a80;
            margin: 40px 0 20px 10px;
        }

    </style>
</head>
<body>
<div id="fox">
    <div id="fox-header">
        <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand">
    </div>

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

    <div id="products">
        <h2>Sản phẩm</h2>
        <div id="filter-bar">
            <label for="category-select">Lọc theo danh mục:</label>
            <select id="category-select">
                <option value="all">Tất cả</option>
                <?php foreach (array_keys($groups) as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php foreach ($groups as $category => $items): ?>
            <div class="category-group" data-category="<?= htmlspecialchars($category) ?>">
                <div class="category-title"><?= htmlspecialchars($category) ?></div>
                <div class="product-list">
                    <?php foreach ($items as $item): ?>
                        <div class="product-card">
                            <a href="ChiTietSanPham.php?id_san_pham=<?= $item['id_san_pham'] ?>" class="product-link">
                                <div class="product-image">
                                    <?php if (!empty($item['url_hinh_anh'])): ?>
                                        <img src="<?= htmlspecialchars($item['url_hinh_anh']) ?>" alt="<?= htmlspecialchars($item['ten_san_pham']) ?>">
                                    <?php else: ?>
                                        <img src="https://placehold.co/250x150?text=No+Image" alt="Không có ảnh">
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($item['ten_san_pham']) ?></h3>
                                    <p class="desc"><?= htmlspecialchars($item['mo_ta']) ?></p>
                                    <p class="price">Giá từ: <strong><?= number_format($item['gia_ban'], 0, ',', '.') ?>₫</strong></p>
                                    <p class="warranty">Bảo hành: <?= htmlspecialchars($item['bao_hanh']) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

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
    </div>
</div>




<script>
$(document).ready(function () {
    const $groups = $('.category-group');
    const $products = $('.product-card');

    $groups.hide();
    $('.category-group[data-category="Các sản phẩm nổi bật"]').show().find('.product-card').each(function (j) {
        $(this).delay(j * 100).queue(function (next) {
            $(this).addClass('show');
            next();
        });
    });

    $('#category-select').on('change', function () {
        let selected = $(this).val();
        $(this).prop('disabled', true);
        $('.product-card').removeClass('show');

        if (selected === 'all') {
            $groups.hide().each(function (i) {
                $(this).delay(i * 300).fadeIn(400, function () {
                    $(this).find('.product-card').each(function (j) {
                        $(this).delay(j * 100).queue(function (next) {
                            $(this).addClass('show');
                            next();
                        });
                    });
                });
            });
        } else {
            $groups.fadeOut(200);
            $groups.filter('[data-category="' + selected + '"]').delay(250).fadeIn(300, function () {
                $(this).find('.product-card').each(function (j) {
                    $(this).delay(j * 100).queue(function (next) {
                        $(this).addClass('show');
                        next();
                    });
                });
            });
        }

        setTimeout(() => {
            $('#category-select').prop('disabled', false);
        }, 1500);
    });
});

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
