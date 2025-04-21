<?php
include "./connectDatabase/DB_import_invoice.php";
include "./connectDatabase/DB_product.php";
include "./connectDatabase/DB_provider.php";
include "./connectDatabase/DB_account.php";
// global $dbProduct;
$dbProduct = new DB_product();
// global $dbProvider;
$dbProvider = new DB_provider();
$dbImInvoice = new DB_import_invoice();
$dbAccount = new DB_account();

?>
<div class="dashboard container-content">
    <div class="sumarize">
        <div class="sumarize-content">
            <i class="fa-solid fa-box-open primary-text"></i>
            <div class="information">
                <span>Sản phẩm</span>
                <div class="quantity">
                    <?php
                    // global $dbProduct;
                    $n = $dbProduct->getAllProductForInvoice()->num_rows;
                    echo $n;
                    ?>
                </div>
            </div>
        </div>
        <div class="sumarize-content">
            <i class="fa-solid fa-handshake warning-text"></i>
            <div class="information">
                <span>Nhà cung cấp</span>
                <div class="quantity">
                    <?php
                    echo count($dbProvider->getAllProvider());
                    ?>
                </div>
            </div>
        </div>
        <div class="sumarize-content">
            <i class="fa-solid fa-file-invoice success-text"></i>
            <div class="information">
                <span>Phiếu nhập kho
                    <!-- <span style="font-size: 13px; white-space: nowrap">(Tháng này)</span></span> -->
                    <div class="quantity">
                        <?php
                        echo count($dbImInvoice->getAllInvoice());
                        ?>
                    </div>
            </div>
        </div>
        <div class="sumarize-content">
            <!-- <i class="fa-solid fa-file-export danger-text"></i> -->
            <i class="fa-solid fa-users danger-text"></i>
            <div class="information">
                <span>Nhân viên kho
                    <!-- <span style="font-size: 13px; white-space: nowrap">(Tháng này)</span></span> -->
                    <div class="quantity">
                        <?php
                        echo count($dbAccount->getAllInventoryStaff());
                        ?>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="img-home">
    <img src="./assets/img/img_home/new_up_coming.jpg" alt="" class="img1">
    <img src="./assets/img/img_home/shirt_collection.jpg" alt="" class="img2">
    <img src="./assets/img/img_home/shoes_collection.png" alt="" class="img3">
</div>


<style>
    .img-home {
        width: 100%;
        height: auto;
        /* background-color: red; */
        /* margin-left: 230px; */
        margin-top: 20px;
        padding: 0;
        position: relative;
    }

    .img-home img {
        width: 81%;
        /* height: 70vh; */
        object-fit: contain;
        margin: 0;
        position: absolute;
        top: 0;
        left: 230px;
        opacity: 0;
        /* Ẩn tất cả ảnh ban đầu */
        transition: opacity 1s ease-in-out;
        border-radius: 10px;
        /* Hiệu ứng mờ dần */
        /* box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3); */
    }

    .img-home img.active {
        opacity: 1;
        /* Hiển thị ảnh hiện tại */
    }

    @media screen and (max-width: 1100px) {
        .img-home img {
            left: 35px;
            width: calc(100% - 68px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageContainer = document.querySelector('.img-home');
        const images = imageContainer.querySelectorAll('img');
        let currentIndex = 0;
        const intervalTime = 3000; // 3 giây

        // Hiển thị ảnh đầu tiên
        images[currentIndex].classList.add('active');

        function nextImage() {
            // Ẩn ảnh hiện tại
            images[currentIndex].classList.remove('active');
            // Cập nhật index
            currentIndex = (currentIndex + 1) % images.length;
            // Hiển thị ảnh tiếp theo
            images[currentIndex].classList.add('active');
            // alert(1);
        }

        // Thiết lập bộ hẹn giờ
        setInterval(nextImage, intervalTime);
    });
</script>