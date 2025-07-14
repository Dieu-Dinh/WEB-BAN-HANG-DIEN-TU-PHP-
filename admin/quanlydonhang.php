<?php
session_start();
if (!isset($_SESSION["user"]) || strpos(strtolower($_SESSION["user"]["username"]), "admin") === false) {
    header("Location: ../Login/Login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "webbh");

// Cập nhật trạng thái nếu admin gửi form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"], $_POST["new_status"])) {
    $order_id = (int)$_POST["order_id"];
    $new_status = $_POST["new_status"];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
}

// Lấy tất cả đơn hàng nhóm theo người dùng
$sql = "SELECT orders.*, users.username, sp.ten_san_pham, bt.mau_sac 
        FROM orders 
        JOIN users ON orders.user_id = users.id
        JOIN san_pham sp ON orders.product_id = sp.id_san_pham
        JOIN bien_the_san_pham bt ON orders.id_bien_the = bt.id_bien_the
        ORDER BY users.username, orders.created_at DESC";
$result = $conn->query($sql);

// Nhóm đơn hàng theo user
$orders_by_user = [];
while ($row = $result->fetch_assoc()) {
    $orders_by_user[$row["username"]][] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../index/index.css">
    <style>
        .admin-container {
            max-width: 1100px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        h3.user-title {
            background: #004a80;
            color: white;
            padding: 10px;
            border-radius: 6px;
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        .action-form select {
            padding: 6px;
            border-radius: 6px;
        }
        .action-form button {
            background-color: #007acc;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .action-form button:hover {
            background-color: #005fa3;
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
            <li><a href="admin.php">Trang Chủ</a></li>
            <li><a href="quanlysanpham.php">Quản Lý Sản Phẩm</a></li>
            <li><a href="quanlydonHang.php">Quản lý Đơn Hàng</a></li>
            <li><a href="quanlynguoidung.php">Quản lý Người Dùng</a></li>
            <li><a href="quanlythongke.php">Thống Kê</a></li>\
            <li><a href="quanlydanhgia.php">Quản lý Đánh Giá</a></li>
            <li><a href="../Login/logout.php">Đăng Xuất</a></li>
        </ul>
    </div>

    <div class="admin-container">
        <h2>Danh sách đơn hàng theo người dùng</h2>
        <?php foreach ($orders_by_user as $username => $orders): ?>
            <h3 class="user-title">👤 <?= htmlspecialchars($username) ?></h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Màu</th>
                    <th>Số lượng</th>
                    <th>Tổng giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order["id"] ?></td>
                        <td><?= htmlspecialchars($order["ten_san_pham"]) ?></td>
                        <td><?= htmlspecialchars($order["mau_sac"]) ?></td>
                        <td><?= $order["quantity"] ?></td>
                        <td><?= number_format($order["gia_ban"], 0, ',', '.') ?>₫</td>
                        <td><?= $order["status"] ?></td>
                        <td>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="order_id" value="<?= $order["id"] ?>">
                                <select name="new_status" required>
                                    <?php
                                    $statuses = ["Chờ xử lý", "Đang giao", "Đã giao", "Đã hủy"];
                                    foreach ($statuses as $status) {
                                        $selected = ($order["status"] === $status) ? "selected" : "";
                                        echo "<option value=\"$status\" $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit">Cập nhật</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    </div>

    <div id="fox-footer">
        <p>© 2025 Fox Tech. All rights reserved.</p>
    </div>
</div>
</body>
</html>
