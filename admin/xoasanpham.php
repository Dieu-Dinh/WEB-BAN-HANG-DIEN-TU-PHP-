<?php
session_start();
if (!isset($_SESSION["user"]) || stripos($_SESSION["user"]["username"], "admin") === false) {
    header("Location: ../Login/Login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: quanlysanpham.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

$id = (int)$_GET["id"];

// Xóa hình ảnh trước (nếu có)
$conn->query("DELETE FROM hinh_anh_san_pham WHERE id_san_pham = $id");

// Xóa các biến thể
$conn->query("DELETE FROM bien_the_san_pham WHERE id_san_pham = $id");

// Xóa sản phẩm chính
$conn->query("DELETE FROM san_pham WHERE id_san_pham = $id");

header("Location: quanlysanpham.php");
exit();
?>
