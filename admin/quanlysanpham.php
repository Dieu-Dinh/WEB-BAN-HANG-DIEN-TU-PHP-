<?php
session_start();
if (!isset($_SESSION["user"]) || stripos($_SESSION["user"]["username"], "admin") === false) {
    header("Location: ../Login/Login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "webbh");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// ===== Thêm sản phẩm =====
if (isset($_POST["add"])) {
  $ten = $conn->real_escape_string($_POST["ten_san_pham"]);
  $mo_ta = $conn->real_escape_string($_POST["mo_ta"]);
  $hinh = $conn->real_escape_string($_POST["hinh_anh"]);
  $bao_hanh = $conn->real_escape_string($_POST["bao_hanh"]);
  $video = $conn->real_escape_string($_POST["video_gioi_thieu"]);

  // Thêm sản phẩm chính
  $conn->query("INSERT INTO san_pham (ten_san_pham, mo_ta, bao_hanh, video_gioi_thieu)
                VALUES ('$ten', '$mo_ta', '$bao_hanh', '$video')");
  $idMoi = $conn->insert_id;

  // Thêm ảnh đại diện
  $conn->query("INSERT INTO hinh_anh_san_pham (id_san_pham, url_hinh_anh, la_anh_dai_dien)
                VALUES ($idMoi, '$hinh', 1)");

  // Thêm biến thể
  $mau_sacs = $_POST["mau_sac"];
  $so_luongs = $_POST["so_luong_ton_kho"];
  $gia_bans = $_POST["gia_ban"];

  for ($i = 0; $i < count($mau_sacs); $i++) {
      $mau = $conn->real_escape_string($mau_sacs[$i]);
      $sl = (int)$so_luongs[$i];
      $gia = (float)$gia_bans[$i];

      $conn->query("INSERT INTO bien_the_san_pham (id_san_pham, mau_sac, so_luong_ton_kho, gia_ban)
                    VALUES ($idMoi, '$mau', $sl, $gia)");
  }

  header("Location: quanlysanpham.php");
  exit();
}

// ===== Cập nhật sản phẩm và biến thể =====
if (isset($_POST["update"])) {
    $id = (int)$_POST["id_sua"];
    $ten = $conn->real_escape_string($_POST["ten_san_pham"]);
    $mo_ta = $conn->real_escape_string($_POST["mo_ta"]);

    $conn->query("UPDATE san_pham SET ten_san_pham='$ten', mo_ta='$mo_ta' WHERE id_san_pham = $id");

    // Cập nhật từng biến thể
    $ids = $_POST["id_bien_the"];
    $maus = $_POST["mau_sac"];
    $soluongs = $_POST["so_luong_ton_kho"];
    $gias = $_POST["gia_ban"];

    for ($i = 0; $i < count($ids); $i++) {
        $id_bien_the = (int)$ids[$i];
        $mau = $conn->real_escape_string($maus[$i]);
        $sl = (int)$soluongs[$i];
        $gia = (float)$gias[$i];
        $conn->query("UPDATE bien_the_san_pham SET mau_sac='$mau', so_luong_ton_kho=$sl, gia_ban=$gia 
                      WHERE id_bien_the=$id_bien_the");
    }

    header("Location: quanlysanpham.php");
    exit();
}

// ===== Dữ liệu cho form sửa sản phẩm =====
$productEdit = null;
$variantsEdit = [];
if (isset($_GET["id_sua"])) {
    $idSua = (int)$_GET["id_sua"];
    $productEdit = $conn->query("SELECT * FROM san_pham WHERE id_san_pham = $idSua")->fetch_assoc();
    $variantsEdit = $conn->query("SELECT * FROM bien_the_san_pham WHERE id_san_pham = $idSua")->fetch_all(MYSQLI_ASSOC);
}

// ===== Danh sách sản phẩm + biến thể =====
$products = $conn->query("
    SELECT sp.id_san_pham AS id, sp.ten_san_pham AS name, sp.mo_ta AS description,
           bt.id_bien_the, bt.mau_sac, bt.so_luong_ton_kho, bt.gia_ban
    FROM san_pham sp
    LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham
    ORDER BY sp.id_san_pham, bt.id_bien_the
");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý sản phẩm Admin - Fox Tech</title>
  <link rel="stylesheet" href="admin.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(function () {
      $(".toggle-form").click(() => $(".form-section").slideToggle());
      $(".btn-edit-trigger").click(() => {
        $('html, body').animate({ scrollTop: $("#form-update").offset().top - 80 }, 600);
      });
    });
  </script>
</head>
<body>
<div id="fox">
  <div id="fox-header">
    <img src="../Hinh/Foxbrand.png" alt="Fox Tech Brand" />
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
    <h2>🌐 Quản lý sản phẩm</h2>
    <a href="admin.php" class="btn" style="margin-bottom: 20px; display: inline-block;">← Quay lại trang Admin</a></br>
    
    <button class="toggle-form" style="margin-bottom: 20px;">➕ Thêm sản phẩm mới</button>

    <!-- Form Thêm -->
<div class="form-section" style="display: none;">
  <form method="POST" class="product-form">
    <label>Tên sản phẩm</label>
    <input type="text" name="ten_san_pham" required>

    <label>Mô tả</label>
    <textarea name="mo_ta" rows="3" required></textarea>

    <label>URL hình ảnh (ảnh đại diện)</label>
    <input type="url" name="hinh_anh" required>

    <label>Bảo hành</label>
    <input type="text" name="bao_hanh">

    <label>Video giới thiệu (YouTube)</label>
    <input type="url" name="video_gioi_thieu">

    <h4>Biến thể sản phẩm</h4>
    <div id="variant-container">
      <div class="variant-row">
        <input type="text" name="mau_sac[]" placeholder="Màu sắc" required>
        <input type="number" name="so_luong_ton_kho[]" placeholder="Số lượng" required>
        <input type="number" name="gia_ban[]" placeholder="Giá bán" required>
        <button type="button" onclick="removeVariant(this)" class="remove-btn">❌</button>
      </div>
    </div>
    <button type="button" onclick="addVariant()">➕ Thêm biến thể</button>

    <br><br>
    <button type="submit" name="add">Thêm sản phẩm</button>
  </form>
</div>

<script>
function addVariant() {
  const container = document.getElementById("variant-container");
  const div = document.createElement("div");
  div.className = "variant-row";
  div.innerHTML = `
    <input type="text" name="mau_sac[]" placeholder="Màu sắc" required>
    <input type="number" name="so_luong_ton_kho[]" placeholder="Số lượng" required>
    <input type="number" name="gia_ban[]" placeholder="Giá bán" required>
    <button type="button" onclick="removeVariant(this)" class="remove-btn">❌</button>
  `;
  container.appendChild(div);
}

function removeVariant(btn) {
  btn.parentElement.remove();
}
</script>

    <?php if ($productEdit): ?>
<div id="form-update" style="margin-top: 40px;">
  <h3>✏️ Cập nhật sản phẩm</h3>
  <form method="POST">
    <input type="hidden" name="id_sua" value="<?= $productEdit["id_san_pham"] ?>">
    <label>Tên sản phẩm</label>
    <input type="text" name="ten_san_pham" value="<?= htmlspecialchars($productEdit["ten_san_pham"]) ?>" required>

    <label>Mô tả</label>
    <textarea name="mo_ta" rows="3" required><?= htmlspecialchars($productEdit["mo_ta"]) ?></textarea>

    <h4>Biến thể:</h4>
    <?php foreach ($variantsEdit as $v): ?>
      <div class="variant-block" style="margin-bottom:10px; border:1px solid #ccc; padding:10px; border-radius:6px;">
        <input type="hidden" name="id_bien_the[]" value="<?= $v['id_bien_the'] ?>">
        <label>Màu sắc:</label>
        <input type="text" name="mau_sac[]" value="<?= htmlspecialchars($v['mau_sac']) ?>" required>
        <label>Số lượng:</label>
        <input type="number" name="so_luong_ton_kho[]" value="<?= $v['so_luong_ton_kho'] ?>" required>
        <label>Giá bán:</label>
        <input type="number" name="gia_ban[]" value="<?= $v['gia_ban'] ?>" required>
      </div>
    <?php endforeach; ?>

    <button type="submit" name="update">Lưu cập nhật</button>
  </form>
</div>
<?php endif; ?>


    <h3 style="margin-top: 40px;">📦 Danh sách sản phẩm</h3>
    <table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Tên sản phẩm</th>
      <th>Mô tả</th>
      <th>Biến thể</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $lastId = null;
    $variantRows = [];

    while ($row = $products->fetch_assoc()):
        if ($lastId !== $row["id"]) {
            // In sản phẩm trước đó nếu có
            if ($lastId !== null) {
                echo "<td><table class='inner-table' style='width:100%; border:none;'>";
                echo "<thead><tr><th style='border:none;'>Màu</th><th style='border:none;'>Số lượng</th><th style='border:none;'>Giá</th></tr></thead><tbody>";
                foreach ($variantRows as $v) {
                    echo "<tr>
                            <td style='border:none;'>".htmlspecialchars($v["mau_sac"] ?: "-")."</td>
                            <td style='border:none;'>".$v["so_luong_ton_kho"]."</td>
                            <td style='border:none;'>".number_format($v["gia_ban"], 0, ',', '.')."₫</td>
                          </tr>";
                }
                echo "</tbody></table></td>
                      <td class='action-col'>
                          <a href='quanlysanpham.php?id_sua=$lastId' class='btn-edit'>Sửa SP</a>
                          <a href='deleteproduct.php?id=$lastId' class='btn-delete' onclick='return confirm(\"Xóa sản phẩm này?\")'>Xóa SP</a>
                      </td>
                    </tr>";
            }

            // Bắt đầu dòng sản phẩm mới
            echo "<tr>
                    <td>{$row["id"]}</td>
                    <td>".htmlspecialchars($row["name"])."</td>
                    <td>".nl2br(htmlspecialchars(substr($row["description"], 0, 100)))."</td>";
            $variantRows = [];
            $lastId = $row["id"];
        }

        // Thu thập biến thể cho sản phẩm hiện tại
        $variantRows[] = $row;
    endwhile;

    // In dòng cuối cùng
    if ($lastId !== null) {
        echo "<td><table class='inner-table' style='width:100%; border:none;'>";
        echo "<thead><tr><th style='border:none;'>Màu</th><th style='border:none;'>Số lượng</th><th style='border:none;'>Giá</th></tr></thead><tbody>";
        foreach ($variantRows as $v) {
            echo "<tr>
                    <td style='border:none;'>".htmlspecialchars($v["mau_sac"] ?: "-")."</td>
                    <td style='border:none;'>".$v["so_luong_ton_kho"]."</td>
                    <td style='border:none;'>".number_format($v["gia_ban"], 0, ',', '.')."₫</td>
                  </tr>";
        }
        echo "</tbody></table></td>
              <td class='action-col'>
                  <a href='quanlysanpham.php?id_sua=$lastId' class='btn-edit'>Sửa SP</a>
                  <a href='xoasanpham.php?id=$lastId' class='btn-delete' onclick='return confirm(\"Xóa sản phẩm này?\")'>Xóa SP</a>
              </td>
            </tr>";
    }
    ?>
  </tbody>
</table>
  </div>

  <div id="fox-footer">
    <p>© 2025 Fox Tech. All rights reserved.</p>
    <p>Địa chỉ: 123 Đường Công Nghệ, TP.HCM | Hotline: 0123 456 789</p>
    <p>
      <a href="../index/index.html">Trang chủ</a> |
      <a href="SanPham.php">Sản phẩm</a> |
      <a href="../Gioithieu/Gioithieu.html">Giới thiệu</a> |
      <a href="../ChinhSachBaoMat/ChinhSachBaoMat.html">Chính sách</a> |
      <a href="../LienHe/LienHe.html">Liên hệ</a>
    </p>
  </div>
</div>
</body>
</html>
