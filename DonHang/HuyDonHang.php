<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../Login/Login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"])) {
    $order_id = (int)$_POST["order_id"];
    $user_id = (int)$_SESSION["user"]["id"];

    $conn = new mysqli("localhost", "root", "", "webbh");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Chuẩn bị truy vấn an toàn
    $stmt = $conn->prepare("UPDATE orders SET status = 'Đã hủy' WHERE id = ? AND user_id = ? AND status = 'Chờ xử lý'");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: DonHangCuaToi.php");
exit();
