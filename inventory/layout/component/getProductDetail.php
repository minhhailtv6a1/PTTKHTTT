<?php
include "./connectDatabase/DB_product.php"
?>
<div class="product-detail">
    <h1>Chi tiết sản phẩm</h1>
    <div class="content-detail">
        <div class="show-img">
            <div>
                <img class="main-img"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh1.png"
                    alt="hinhTo" />
            </div>
            <div class="total-img">
                <img class="active detail-img  photo1" onclick="showPhoto(this)"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh1.png"
                    alt="hinh1" />
                <img class="detail-img photo2" onclick="showPhoto(this)"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh2.png"
                    alt="hinh2" />
                <img class="detail-img photo3" onclick="showPhoto(this)"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh3.png"
                    alt="hinh3" />
                <img class="detail-img photo4" onclick="showPhoto(this)"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh4.png"
                    alt="hinh4" />
                <img class="detail-img photo5" onclick="showPhoto(this)"
                    src="assets/img/img_product/chiTietAnhSP/SP_1/anh5.png"
                    alt="hinh5" />
            </div>
        </div>
        <div class="show-info">
            <h2>Áo bóng đá CLB MU 23-24</h2>
            <div class="id_type">
                <div>Mã sản phẩm: <span>1</span></div>
                <div>Phân loại: <span>Quần áo CLB</span></div>
            </div>
            <div class="brand">Thương hiệu: <span>Nike</span></div>
            <div class="price">Giá sản phẩm: <span>1.000.000đ</span></div>
            <div class="size">
                <div>Size:</div>
                <div class="size-btn-container">
                    <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">S</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">M</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">L</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">XL</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">XXL</button type="button">
                </div>
            </div>
            <div class="stock size-all">Kho: <span>200</span></div>
            <div class="stock size-s" hidden>Kho: <span>50</span></div>
            <div class="stock size-m" hidden>Kho: <span>70</span></div>
            <div class="stock size-l" hidden>Kho: <span>30</span></div>
            <div class="stock size-xl" hidden>Kho: <span>20</span></div>
            <div class="stock size-xxl" hidden>Kho: <span>30</span></div>
        </div>
    </div>
</div>