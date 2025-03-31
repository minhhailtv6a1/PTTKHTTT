<div id="customFailedAlert" class="custom-failed-alert">
    <!-- Nội dung thông báo sẽ được điền vào đây -->
</div>
<div id="customSuccessfulAlert" class="custom-successful-alert">
    <!-- Nội dung thông báo sẽ được điền vào đây -->
</div>

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