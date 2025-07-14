<?php
session_start();
$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $raw_password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);
    $address  = trim($_POST["address"]);

    // Kiểm tra mật khẩu nhập lại
    if ($raw_password !== $confirm_password) {
        echo "Mật khẩu nhập lại không khớp.";
        exit();
    }

    // Mã hóa sau khi kiểm tra khớp
    $password = password_hash($raw_password, PASSWORD_DEFAULT);

    // Kiểm tra username đã tồn tại
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo "Tên đăng nhập đã tồn tại.";
        exit();
    }

    // Đăng ký
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $email, $phone, $address);

    if ($stmt->execute()) {
        $_SESSION["user"] = ["username" => $username];
        header("Location: ../index/index.php");
        exit();
    } else {
        echo "Lỗi đăng ký: " . $stmt->error;
    }
}
?>
