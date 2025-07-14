<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION["user"])) {
    header("Location: ../Login/Login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy và xử lý dữ liệu từ form
$idSanPham = isset($_POST['id_san_pham']) ? (int)$_POST['id_san_pham'] : 0;
$diem = isset($_POST['diem_danh_gia']) ? (int)$_POST['diem_danh_gia'] : 0;
$noiDung = isset($_POST['noi_dung_binh_luan']) ? trim($_POST['noi_dung_binh_luan']) : '';
$tenNguoiDung = $_SESSION["user"]["username"];
$ngay = date("Y-m-d");

// Kiểm tra dữ liệu
if ($idSanPham > 0 && $diem >= 1 && $diem <= 5 && !empty($noiDung)) {
    // Escape dữ liệu
    $noiDungEscaped = $conn->real_escape_string($noiDung);
    $tenNguoiDungEscaped = $conn->real_escape_string($tenNguoiDung);

    // Thêm vào bảng đánh giá (chưa duyệt)
    $sql = "INSERT INTO danh_gia_san_pham (id_san_pham, ten_nguoi_dung, diem_danh_gia, noi_dung_binh_luan, ngay_danh_gia)
            VALUES ($idSanPham, '$tenNguoiDungEscaped', $diem, '$noiDungEscaped', '$ngay')";
    $conn->query($sql);
}

// Đóng kết nối
$conn->close();

// Quay lại trang chi tiết sản phẩm kèm anchor đến phần đánh giá
header("Location: ../SanPham/ChiTietSanPham.php?id_san_pham=$idSanPham#review");
exit();
