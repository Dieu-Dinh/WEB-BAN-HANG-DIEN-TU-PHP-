// Mở modal đăng ký khi nhấn nút
document.getElementById("registerLink").onclick = function () {
    document.getElementById("registerModal").style.display = "block";
};

// Đóng modal khi nhấn vào dấu ×
document.getElementById("registerClose").onclick = function () {
    document.getElementById("registerModal").style.display = "none";
};

// Đóng modal nếu click ra ngoài nội dung modal
window.onclick = function (event) {
    const modal = document.getElementById("registerModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
