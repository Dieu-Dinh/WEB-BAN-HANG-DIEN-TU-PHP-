<?php
session_start();
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"], $_POST["password"])) {
    $conn = new mysqli("localhost", "root", "", "webbh");
    if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Truy vấn người dùng theo username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // ✅ So sánh đúng cách bằng password_verify
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = [
                "id" => $user["id"],
                "username" => $user["username"]
            ];
    
            // Chuyển hướng nếu là admin
            if (strpos(strtolower($user["username"]), "admin") !== false) {
                header("Location: ../admin/admin.php");
            } else {
                header("Location: ../index/index.php");
            }
            exit();
        } else {
            $loginError = "❌ Sai mật khẩu.";
        }
    } else {
        $loginError = "❌ Tài khoản không tồn tại.";
    }
    

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="Login.css">
    <script src="Login.js" defer></script>
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
                <li><a href="../Gioithieu/Gioithieu.html">Giới thiệu</a></li>
                <li><a href="../chinhsachbaomat/chinhsachbaomat.html">Chính sách bảo mật</a></li>
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

        <!-- About -->
        <div id="about">
            <h2>Về chúng tôi</h2>
            <p>Fox Tech cung cấp sản phẩm công nghệ đa dạng: laptop, điện thoại, thiết bị thông minh...</p>
        </div>

        <!-- Modal đăng ký -->
    <div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="registerClose">&times;</span>
        <h2>Đăng Ký</h2>
        <form method="POST" action="register.php" onsubmit="return validateRegister()">
            <input type="text" name="username" id="registerUsername" placeholder="Tên đăng nhập" required />
            <input type="password" name="password" id="registerPassword" placeholder="Mật khẩu" required />
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required />
            <input type="text" name="phone" id="registerPhone" placeholder="Số điện thoại" required />
            <input type="text" name="address" id="registerAddress" placeholder="Địa chỉ" required />
            <input type="text" name="email" id="registerEmail" placeholder="Email" required />
            <button type="submit" class="btn" id="registerSubmit">Đăng Ký</button>
        </form>
    </div>
</div>

        <!-- Đăng nhập -->
        <div class="login-section">
            <?php if (!isset($_SESSION["user"])): ?>
                <h2>Tài khoản đăng nhập</h2>
                <form method="POST">
                    <label for="username">Tên đăng nhập:</label><br />
                    <input type="text" id="inline-username" name="username" placeholder="Nhập tên đăng nhập" required /><br />

                    <label for="password">Mật khẩu:</label><br />
                    <input type="password" id="inline-password" name="password" placeholder="Nhập mật khẩu" required /><br />

                    <p><button type="submit" class="btn">Đăng nhập</button></p>
                    <p><button type="button" class="btn" id="registerLink">Đăng ký tài khoản</button></p>
                    <?php if ($loginError): ?>
                        <p style="color:red;"><?= $loginError ?></p>
                    <?php endif; ?>
                </form>

            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div id="fox-footer">
            <p>© 2025 Fox Tech. All rights reserved.</p>
            <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789 | Email: support@foxtech.vn</p>
            <p>
            <a href="../index/index.php">Trang chủ</a> | 
            <a href="../SanPham/SanPham.php">Sản phẩm</a> | 
            <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> | 
            <a href="../chinhsachbaomat/chinhsachbaomat.html">Chính sách bảo mật</a> |
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
