<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["id"])) {
    echo "Lỗi: Không tìm thấy thông tin user trong session.";
    exit();
}

$user_id = (int)$_SESSION["user"]["id"];
$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) {
    die("Lỗi kết nối CSDL: " . $conn->connect_error);
}

// Gán các biến từ POST ở đây
$ten_san_pham = trim($_POST["ten_san_pham"] ?? '');
$product_id   = (int)($_POST["product_id"] ?? 0);
$id_bien_the  = (int)($_POST["id_bien_the"] ?? 0);
$quantity     = max(1, (int)($_POST["quantity"] ?? 1));

$user_id = (int)$_SESSION["user"]["id"];
$ten_san_pham = trim($_POST["ten_san_pham"] ?? '');
$product_id   = (int)($_POST["product_id"] ?? 0);
$id_bien_the   = (int)($_POST["id_bien_the"] ?? 0); // có thể = 0 nếu không có biến thể
$quantity     = max(1, (int)($_POST["quantity"] ?? 1));

$success = false;
$message = "";

// Trường hợp có biến thể (id_bien_the > 0)
if ($product_id > 0 && $quantity > 0) {
    if ($id_bien_the > 0) {
        $stmt = $conn->prepare("SELECT gia_ban, so_luong_ton_kho FROM bien_the_san_pham WHERE id_bien_the = ?");
        $stmt->bind_param("i", $id_bien_the);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($variant = $result->fetch_assoc()) {
            if ($quantity > $variant["so_luong_ton_kho"]) {
                $message = "Số lượng vượt quá tồn kho!";
            } else {
                $price = $variant["gia_ban"];
                $total_price = $price * $quantity;

                // Lấy tên SP
                $sqlProduct = "SELECT ten_san_pham FROM san_pham WHERE id_san_pham = ?";
                $stmtProduct = $conn->prepare($sqlProduct);
                $stmtProduct->bind_param("i", $product_id);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                $product = $resultProduct->fetch_assoc();
                $ten_san_pham = $product["ten_san_pham"];

                // Lấy giá từ biến thể
                $gia = 0;
                if ($id_bien_the != 0) {
                    $sqlVariant = "SELECT gia_ban FROM bien_the_san_pham WHERE id_bien_the = ?";
                    $stmtVariant = $conn->prepare($sqlVariant);
                    $stmtVariant->bind_param("i", $id_bien_the);
                    $stmtVariant->execute();
                    $resultVariant = $stmtVariant->get_result();
                    if ($resultVariant->num_rows > 0) {
                        $variant = $resultVariant->fetch_assoc();
                        $gia_ban = $variant["gia_ban"];
                    }
                }

                


                // Ghi đơn hàng
                $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, id_bien_the, ten_san_pham, quantity, gia_ban, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, 'Chờ xử lý', NOW())");
                $stmt->bind_param("iiisid", $user_id, $product_id, $id_bien_the, $ten_san_pham, $quantity, $total_price);
                $stmt->execute();

                // Trừ kho
                $stmt = $conn->prepare("UPDATE bien_the_san_pham SET so_luong_ton_kho = so_luong_ton_kho - ? WHERE id_bien_the = ?");
                $stmt->bind_param("ii", $quantity, $id_bien_the);
                $stmt->execute();

                $success = true;
                $message = "Đặt hàng thành công!";
            }
        } else {
            $message = "Không tìm thấy biến thể sản phẩm!";
        }
    } else {
        // Trường hợp không có biến thể
        $gia_ban = (float)($_POST["gia_ban"] ?? 0);
        $total_price = $gia_ban * $quantity;

        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, id_bien_the, ten_san_pham, quantity, gia_ban, status, created_at)
                                VALUES (?, ?, NULL, ?, ?, ?, 'Chờ xử lý', NOW())");
        $stmt->bind_param("iisid", $user_id, $product_id, $ten_san_pham, $quantity, $total_price);
        if ($stmt->execute()) {
            $success = true;
            $message = "Đặt hàng thành công!";
        } else {
            $message = "Lỗi ghi đơn hàng: " . $stmt->error;
        }
    }
} else {
    $message = "Dữ liệu đặt hàng không hợp lệ.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt hàng - Fox Tech</title>
    <link rel="stylesheet" href="../index/index.css">
    <style>
        .confirm-box {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 40px;
            text-align: center;
        }
        .confirm-box h2 {
            color: <?= $success ? "'#007acc'" : "'#e53935'" ?>;
            margin-bottom: 20px;
        }
        .confirm-box p {
            font-size: 16px;
            margin-bottom: 25px;
        }
        .confirm-box .btn {
            padding: 10px 25px;
            background-color: #007acc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .confirm-box .btn:hover {
            background-color: #005fa3;
        }
    </style>
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
            <li><a href="../User/ThongTinCaNhan.php">Tài khoản</a></li>
            <li><a href="DonHangCuaToi.php">Đơn hàng</a></li>
            <li><a href="../Login/logout.php">Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Xác nhận -->
    <div class="confirm-box">
        <h2><?= $success ? "🎉 Đặt hàng thành công" : "⚠️ Đặt hàng thất bại" ?></h2>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="../SanPham/SanPham.php" class="btn">← Quay lại sản phẩm</a>
        <?php if ($success): ?>
            <a href="DonHangCuaToi.php" class="btn" style="margin-left: 10px;">Xem đơn hàng</a>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
    </div>
</div>
</body>
</html>
