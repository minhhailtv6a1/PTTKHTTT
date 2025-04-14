<div id="customFailedAlert" class="custom-failed-alert">
    <!-- Nội dung thông báo sẽ được điền vào đây -->
</div>
<div id="customSuccessfulAlert" class="custom-successful-alert">
    <!-- Nội dung thông báo sẽ được điền vào đây -->
</div>

<style>
    /*-------------------customalert--------------------*/
    .custom-failed-alert {
        visibility: hidden;
        /* Ẩn thông báo mặc định */
        position: fixed;
        top: -80px;
        /* Bắt đầu ngoài khung nhìn */
        left: 50%;
        transform: translateX(-50%);
        padding: 20px;
        background-color: #f44336;
        /* Màu nền đỏ */
        color: white;
        /* Màu chữ trắng */
        border-radius: 5px;
        /* Bo góc */
        z-index: 1000;
        /* Đảm bảo thông báo nằm trên cùng */
        transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out,
            visibility 0.5s;
        opacity: 0;
        /* Bắt đầu với độ mờ */
        font-size: 20px;
    }

    .custom-successful-alert {
        visibility: hidden;
        /* Ẩn thông báo mặc định */
        position: fixed;
        top: -80px;
        /* Bắt đầu ngoài khung nhìn */
        left: 50%;
        transform: translateX(-50%);
        padding: 20px;
        background-color: #45a049;
        /* Màu nền đỏ */
        color: white;
        /* Màu chữ trắng */
        border-radius: 5px;
        /* Bo góc */
        z-index: 1000;
        /* Đảm bảo thông báo nằm trên cùng */
        transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out,
            visibility 0.5s;
        opacity: 0;
        /* Bắt đầu với độ mờ */
        font-size: 20px;
    }

    .custom-failed-alert.show,
    .custom-successful-alert.show {
        visibility: visible;
        /* Hiển thị thông báo khi cần */
        transform: translate(-50%, 100px);
        /* Trượt vào màn hình */
        opacity: 1;
        /* Hiển thị thông báo */
    }

    .custom-failed-alert.hide,
    .custom-successful-alert.hide {
        transform: translate(-50%, -50px);
        /* Trượt ra khỏi màn hình */
        opacity: 0;
        /* Ẩn thông báo */
        visibility: hidden;
        /* Ẩn thông báo sau khi chuyển đổi kết thúc */
    }
</style>

<script>
    function showFailedAlert(message) {
        const alertBox = document.createElement("div");
        alertBox.classList.add("custom-failed-alert");
        alertBox.innerHTML = message;

        document.body.appendChild(alertBox);
        requestAnimationFrame(() => {
            alertBox.classList.add("show");
        });

        setTimeout(() => {
            alertBox.classList.remove("show");
            alertBox.classList.add("hide");
            alertBox.addEventListener("transitionend", () => {
                alertBox.remove();
            });
        }, 3000); // Thời gian thông báo hiển thị (3000ms = 3 giây)
    }

    function showSuccessfulAlert(message) {
        const alertBox = document.createElement("div");
        alertBox.classList.add("custom-successful-alert");
        alertBox.innerHTML = message;

        document.body.appendChild(alertBox);
        requestAnimationFrame(() => {
            alertBox.classList.add("show");
        });

        setTimeout(() => {
            alertBox.classList.remove("show");
            alertBox.classList.add("hide");
            alertBox.addEventListener("transitionend", () => {
                alertBox.remove();
            });
        }, 3000); // Thời gian thông báo hiển thị (3000ms = 3 giây)
    }
</script>