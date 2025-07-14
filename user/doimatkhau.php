<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../Login/Login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

$username = $_SESSION["user"]["username"];
$message = "";

if (isset($_POST["change"])) {
    $old = $_POST["old_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];

    $user = $conn->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc();

    if ($user && $user["password"] === $old) {
        if ($new === $confirm) {
            $conn->query("UPDATE users SET password = '$new' WHERE username = '$username'");
            $message = "<span style='color:green;'>Đổi mật khẩu thành công!</span>";
            header("refresh:2;url=../index/index.php");
        } else {
            $message = "❌ Mật khẩu mới không khớp.";
        }
    } else {
        $message = "❌ Mật khẩu cũ không chính xác.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đổi mật khẩu</title>
  <link rel="stylesheet" href="../index/index.css">
  <style>
    .password-change-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 8px 28px rgba(0,0,0,0.08);
    }
    .password-change-container h2 {
      text-align: center;
      color: #004a80;
      margin-bottom: 20px;
    }
    .password-change-container form label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #333;
    }
    .password-change-container form input {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    .password-change-container .btn-submit {
      margin-top: 25px;
      padding: 10px 25px;
      background-color: #007acc;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .password-change-container .btn-submit:hover {
      background-color: #005fa3;
    }
    .message {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>
<body>
<div id="fox">
  <div id="fox-header">
    <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand" />
  </div>

  <div id="fox-nav">
    <ul>
      <li><a href="../index/index.php">Trang chủ</a></li>
      <li><a href="../SanPham/SanPham.php">Sản phẩm</a></li>
      <li><a href="../User/ThongTinCaNhan.php">Thông tin cá nhân</a></li>
      <li><a href="../Login/logout.php">Đăng xuất</a></li>
    </ul>
  </div>

  <div class="password-change-container">
    <h2>🔐 Đổi mật khẩu</h2>
    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="old_password">Mật khẩu cũ:</label>
      <input type="password" name="old_password" required>

      <label for="new_password">Mật khẩu mới:</label>
      <input type="password" name="new_password" required>

      <label for="confirm_password">Nhập lại mật khẩu mới:</label>
      <input type="password" name="confirm_password" required>

      <button type="submit" name="change" class="btn-submit">Cập nhật</button>
    </form>
  </div>

  <div id="fox-footer">
    <p>© 2025 Fox Tech. All rights reserved.</p>
    <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789</p>
    <p>
      <a href="../index/index.php">Trang chủ</a> |
      <a href="../SanPham/SanPham.php">Sản phẩm</a> |
      <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> |
      <a href="../LienHe/LienHe.html">Liên hệ</a>
    </p>
  </div>
</div>
</body>
</html>
