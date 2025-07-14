<?php
session_start();
if (!isset($_SESSION["user"]) || stripos($_SESSION["user"]["username"], "admin") === false) {
    header("Location: ../Login/Login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// Tổng sản phẩm
$totalProduct = $conn->query("SELECT COUNT(*) FROM san_pham")->fetch_row()[0];

// Tổng đơn hàng
$totalOrder = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];

// Tổng doanh thu
$totalRevenue = $conn->query("SELECT SUM(gia_ban) FROM orders WHERE status = 'Đã giao'")->fetch_row()[0] ?? 0;

// Doanh thu theo tháng (12 tháng gần nhất)
$monthlyRevenue = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(gia_ban) AS revenue
    FROM orders
    WHERE status = 'Đã giao'
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
")->fetch_all(MYSQLI_ASSOC);
$monthlyRevenue = array_reverse($monthlyRevenue); // Đảo ngược lại tăng dần
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thống kê - Fox Tech</title>
  <link rel="stylesheet" href="../index/index.css">
  <style>
    .admin-container {
      max-width: 960px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }
    .stat-box {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }
    .stat {
      background: #f3f8ff;
      padding: 20px;
      border-radius: 10px;
      width: 30%;
      text-align: center;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .stat h3 {
      margin-bottom: 10px;
      color: #004a80;
    }
    canvas {
      max-width: 100%;
      height: auto;
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
    <h2>📊 Thống kê hệ thống</h2>

    <div class="stat-box">
      <div class="stat">
        <h3>Tổng sản phẩm</h3>
        <p><?= $totalProduct ?></p>
      </div>
      <div class="stat">
        <h3>Tổng đơn hàng</h3>
        <p><?= $totalOrder ?></p>
      </div>
      <div class="stat">
        <h3>Tổng doanh thu</h3>
        <p><?= number_format($totalRevenue, 0, ',', '.') ?>₫</p>
      </div>
    </div>

    <h3>📈 Doanh thu theo tháng</h3>
    <canvas id="revenueChart"></canvas>
  </div>

  <div id="fox-footer">
    <p>© 2025 Fox Tech. All rights reserved.</p>
  </div>
</div>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_column($monthlyRevenue, 'month')) ?>,
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      data: <?= json_encode(array_column($monthlyRevenue, 'revenue')) ?>,
      backgroundColor: '#007acc'
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: value => value.toLocaleString() + ' ₫'
        }
      }
    }
  }
});
</script>
</body>
</html>
