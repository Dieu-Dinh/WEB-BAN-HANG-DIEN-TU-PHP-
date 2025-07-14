<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../Login/Login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

$user_id = (int)$_SESSION["user"]["id"];

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="../index/index.css">
    <style>
        .order-container {
            max-width: 960px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }
        .order-container h2 {
            color: #004a80;
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 15px;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        .btn-cancel, .btn-review {
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-cancel {
            background-color: #e53935;
            color: white;
        }
        .btn-review {
            background-color: #4caf50;
            color: white;
        }
        .user-dropdown {
            position: relative;
        }
        .user-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #004a80;
            border: 1px solid #007acc;
            min-width: 180px;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            z-index: 999;
        }
        .user-dropdown .dropdown-menu li a {
            display: block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #005fa3;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div id="fox">
    <!-- Header -->
    <div id="fox-header">
        <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand">
    </div>

    <!-- Navigation -->
    <div id="fox-nav">
        <ul>
            <li><a href="../index/index.php">Trang chủ</a></li>
            <li><a href="../SanPham/SanPham.php">Sản phẩm</a></li>
            <li><a href="../Gioithieu/Gioithieu.html">Giới thiệu</a></li>
            <li><a href="../ChinhSachBaoMat/ChinhSachBaoMat.php">Chính sách bảo mật</a></li>
            <li><a href="../LienHe/LienHe.php">Liên hệ</a></li>
            <?php if (!isset($_SESSION["user"])): ?>
                <li><a href="../Login/Login.php">Đăng nhập</a></li>
            <?php else: ?>
                <?php $username = htmlspecialchars($_SESSION["user"]["username"]); ?>
                <li class="user-dropdown">
                    <a href="#" id="user-toggle"><?= $username ?> ⮟</a>
                    <ul class="dropdown-menu">
                        <li><a href="../User/ThongTinCaNhan.php">Thông tin cá nhân</a></li>
                        <li><a href="../DonHang/DonHangCuaToi.php">Đơn hàng của tôi</a></li>
                        <li><a href="../Login/logout.php">Đăng xuất</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Content -->
    <div class="order-container">
        <h2>Đơn hàng của tôi</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><?= htmlspecialchars($row["ten_san_pham"]) ?><?= $row["id_bien_the"] ? ' - ' . htmlspecialchars($row["id_bien_the"]) : '' ?></td>
                        <td><?= $row["quantity"] ?></td>
                        <td><?= number_format($row["gia_ban"], 0, ',', '.') ?>₫</td>
                        <td><?= $row["created_at"] ?></td>
                        <td><?= $row["status"] ?></td>
                        <td>
                            <?php if ($row["status"] === "Chờ xử lý"): ?>
                                <form method="POST" action="HuyDonHang.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row["id"] ?>">
                                    <button class="btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">❌ Hủy</button>
                                </form>
                            <?php elseif ($row["status"] === "Đã giao"): ?>
                                <a href="../SanPham/ChiTietSanPham.php?id_san_pham=<?= $row["product_id"] ?>#review" class="btn-review">⭐ Đánh giá</a>
                            <?php else: ?>
                                <span style="color: #888;">Đang xử lý</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
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
