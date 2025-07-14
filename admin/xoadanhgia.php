<?php
session_start();

// Kiểm tra nếu chưa đăng nhập hoặc không phải admin thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION["user"]) || stripos($_SESSION["user"]["username"], "admin") === false) {
    header("Location: ../Login/Login.php");
    exit();
}

// Kiểm tra nếu có gửi id_danh_gia qua POST
if (isset($_POST["id_danh_gia"])) {
    // Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "webbh");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $id = (int)$_POST["id_danh_gia"];

    // Xóa đánh giá
    $conn->query("DELETE FROM danh_gia_san_pham WHERE id_danh_gia = $id");

    $conn->close();

    // Quay về trang quản lý đánh giá
    header("Location: quanlydanhgia.php");
    exit();
} else {
    // Nếu không có id gửi lên
    header("Location: quanlydanhgia.php");
    exit();
}
?>
