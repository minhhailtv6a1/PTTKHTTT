<style>
    body {
        font-family: sans-serif;
        margin: 20px;
    }

    .detail-import-invoice .header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .detail-import-invoice .header-icon {
        font-size: 1.5em;
        margin-right: 10px;
    }

    .detail-import-invoice .header-title {
        font-size: 1.2em;
        font-weight: bold;
    }

    .detail-import-invoice .search-container {
        display: flex;
        margin-bottom: 15px;
    }

    .detail-import-invoice .search-input {
        flex-grow: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .detail-import-invoice .search-button {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px 12px;
        margin-left: 5px;
        cursor: pointer;
    }

    .detail-import-invoice .table-container {
        width: calc(70% - 15px);
        border: 1px solid #ccc;
        border-radius: 4px;
        /* overflow: hidden; */
        max-width: 985px;
        overflow-x: scroll;
        display: block;
        overflow-y: scroll;
        max-height: 413px;
    }

    .detail-import-invoice .table-container h2 {
        text-align: center;
    }

    .detail-import-invoice .table-container th {
        /* white-space: nowrap; */
        position: sticky;
        top: 0;
        /* Đặt tiêu đề dính vào mép trên cùng */
        z-index: 1;
        /* Đảm bảo tiêu đề nằm trên nội dung cuộn */
    }

    .detail-import-invoice .table-container h2 {
        /* white-space: nowrap; */
        position: sticky;
        top: 0;
        /* Đặt tiêu đề dính vào mép trên cùng */
        left: 0;
        z-index: 1;
        /* Đảm bảo tiêu đề nằm trên nội dung cuộn */
    }

    .detail-import-invoice .total {
        display: flex;
        flex-direction: row;
        justify-content: end;
        padding: 20px 20px 0 0;
        width: 100%;
        font-weight: bold;
    }

    .detail-import-invoice table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ccc;
    }

    .detail-import-invoice th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #eee;
        white-space: nowrap;
    }

    .detail-import-invoice th {
        background-color: #f7f7f7;
        font-weight: bold;
    }

    .detail-import-invoice tr:last-child td {
        border-bottom: none;
    }

    .detail-import-invoice .quantity-controls {
        display: flex;
        align-items: center;
    }

    .detail-import-invoice .quantity-button {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
        padding: 5px 8px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .detail-import-invoice .quantity-input {
        width: 40px;
        padding: 5px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 0 5px;
    }

    .detail-import-invoice .remove-button {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        font-size: 1.2em;
        margin-left: 10px;
        transition: all 0.3s ease;
        padding: 2px 7px 0px;
    }

    .detail-import-invoice .remove-button:hover {
        background-color: #ffe1e1;
    }

    /* Thêm CSS để căn giữa input */
    .detail-import-invoice .quantity-controls input[type="number"] {
        text-align: center;
        /* Căn giữa nội dung */
    }

    .detail-import-invoice .quantity-input {
        /* ... các thuộc tính CSS khác ... */
        -moz-appearance: textfield;
        /* Cho Firefox */
    }

    /* Ẩn các nút tăng giảm mặc định của input number cho các trình duyệt khác */
    .detail-import-invoice .quantity-input::-webkit-outer-spin-button,
    .detail-import-invoice .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .detail-import-invoice .list-product {
        width: calc(30% - 10px);
        border: 1px solid #ccc;
        max-height: 413px;
        overflow-y: scroll;
    }

    .detail-import-invoice .list-product th {
        /* white-space: nowrap; */
        position: sticky;
        top: 0;
        /* Đặt tiêu đề dính vào mép trên cùng */
        z-index: 1;
        /* Đảm bảo tiêu đề nằm trên nội dung cuộn */
    }

    .detail-import-invoice .list-product .td-add {
        text-align: center;
    }

    .detail-import-invoice .list-product .td-add i {
        border: 1px solid;
        padding: 4px 5px;
        border-radius: 100%;
        transition: all 0.3s ease;
    }

    .detail-import-invoice .list-product .td-add i:hover {
        /* border: 1px solid; */
        /* padding: 2px 3px; */
        /* border-radius: 100%; */
        background-color: #000000;
        color: #ffffff;
        cursor: pointer;
    }

    .detail-import-invoice .list-product img {
        height: 50px;
        width: 50px;
    }

    .detail-import-invoice .content-detail {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
        border: 1px solid #ccc;
        padding: 2%;
    }

    .detail-import-invoice .general-info {
        width: 100%;
        margin: 0 0 20px 0;
        font-size: 18px;
    }

    .detail-import-invoice .table-container select {
        width: 5 0px;
        padding: 5px 0;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 0 5px;
        cursor: pointer;
    }

    .detail-import-invoice .table-container option {
        text-align: center;
        /* cursor: pointer; */
    }

    .detail-import-invoice input[type="text"] {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 0 5px;
        font-size: 16px;
        width: calc(30% - 150px);
        min-width: 220px;
    }

    .detail-import-invoice td input[type="text"] {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 0 5px;
        font-size: 14px;
        width: calc(30% - 150px);
        min-width: 100px;
    }

    .detail-import-invoice .showList {
        display: none;
    }

    @media screen and (max-width: 1180px) {
        .detail-import-invoice .list-product {
            width: calc(35%);
        }

        .detail-import-invoice .table-container {
            width: calc(65% - 15px);
        }
    }

    @media screen and (max-width: 1070px) {
        .detail-import-invoice .list-product {
            width: 350px;
            display: none;
        }

        .detail-import-invoice .showList {
            display: block;
        }

        .detail-import-invoice .table-container {
            width: calc(100% - 10px);
            overflow-x: scroll;
        }

        .has-list-product {
            position: relative;
        }

        .list-product {
            position: absolute;
            top: 160px;
            left: 40px;
            background-color: #fff;
            z-index: 100;
            /* display: none; */
        }
    }
</style>



<div class="detail-import-invoice">
    <div class="header">
        <i class="fas fa-file-alt header-icon"></i>
        <!-- Chỉnh lại id -->
        <div class="header-title">Chỉnh sửa phiếu nhập #30000654</div>
    </div>

    <div class="content-detail">
        <div class="general-info">
            <!-- <form action=""></form> -->
            <label for="infoProvider">Nhà cung cấp:</label>
            <input type="text" name="infoProvider" id="infoProvider" />
        </div>
        <div class="showList">
            <button type="button" name="btn-show" id="btn-show">
                Danh sách sản phẩm
            </button>
        </div>
        <div class="list-product">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Ảnh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_1/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_14/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_2/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_3/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_5/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Giày bóng đá mới</td>
                        <td>
                            <img
                                src="assets/img/img_product/chiTietAnhSP/SP_8/anh1.png"
                                alt="" />
                        </td>
                        <td class="td-add"><i class="fa-solid fa-plus"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h2>Chi tiết phiếu nhập</h2>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng yêu cầu</th>
                        <th>Giá nhập</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            ÁO THUN TAY NGẮN IN THÊU. REGULAR CROP<br />
                            <!-- <small>10F21TSSW014 | Be | M</small> -->
                        </td>
                        <td>
                            <!-- Size cua loai 1 2 -->
                            <select name="size1" id="size1">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>

                            <!-- Size cua loai 3 -->
                            <select name="size3" id="size3" hidden>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                            </select>

                            <!-- Size cua loai 4 5 -->
                            <select name="size4" id="size4" hidden>
                                <option value="XS">38</option>
                                <option value="S">39</option>
                                <option value="M">40</option>
                                <option value="L">41</option>
                                <option value="L">42</option>
                            </select>

                            <!-- Size cua loai 6 -->
                            <select name="size6" id="size6" hidden>
                                <option value="XS">28</option>
                                <option value="S">29</option>
                                <option value="M">30</option>
                                <option value="L">31</option>
                                <option value="L">32</option>
                                <option value="L">33</option>
                                <option value="L">34</option>
                            </select>
                        </td>
                        <td>
                            <div class="quantity-controls">
                                <button
                                    class="quantity-button btn-decrease"
                                    onclick="updateQuantity(this,1)"
                                    name="-">
                                    -
                                </button>
                                <input
                                    type="number"
                                    id="num1"
                                    class="quantity-input"
                                    value="11" />
                                <button
                                    class="quantity-button btn-increase"
                                    onclick="updateQuantity(this,1)"
                                    name="+">
                                    +
                                </button>
                            </div>
                        </td>
                        <td>
                            <input
                                type="text"
                                id="format_update_price"
                                name="format_update_price"
                                required
                                oninput="formatCurrency(this, 'update_price')"
                                value="0" />
                            <input
                                type="text"
                                id="update_price"
                                name="update_price"
                                value="0"
                                hidden />
                        </td>
                        <td><button class="remove-button">×</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            Áo polo nam A00987654<br />
                            <!-- <small>A00987654 | Cam | S</small> -->
                        </td>
                        <td>8935049510910</td>
                        <td>
                            <div class="quantity-controls">
                                <button class="quantity-button">-</button>
                                <!-- Thêm id vào class của input -->
                                <input type="number" class="quantity-input" value="40" />
                                <button class="quantity-button">+</button>
                            </div>
                        </td>
                        <td></td>
                        <td><button class="remove-button">×</button></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            ÁO THUN TAY NGẮN, RÃ THÂN TRƯỚC. REGULAR<br />
                            <!-- <small>10S21TSS006R1 | Cam | S</small> -->
                        </td>
                        <td>8935049510220</td>
                        <td>
                            <div class="quantity-controls">
                                <button class="quantity-button">-</button>
                                <input type="number" class="quantity-input" value="28" />
                                <button class="quantity-button">+</button>
                            </div>
                        </td>
                        <td></td>
                        <td><button class="remove-button">×</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="total">
            Tổng phiếu nhập: &nbsp; <span>10.000.000đ</span>
        </div>
    </div>
</div>


<script>
    function handleResize() {
        // Lấy chiều rộng hiện tại của cửa sổ
        const windowWidth = window.innerWidth;

        if (windowWidth >= 1070) {
            document.querySelector(".list-product").style.display = "block";
            document.addEventListener("click", function() {
                listProductDiv.style.display = "block";
            });
        } else {
            document.querySelector(".list-product").style.display = "none";
            // Đóng dropdown khi click ra ngoài nút và dropdown
            document.addEventListener("click", function() {
                listProductDiv.style.display = "none";
            });
        }
    }

    // Gắn trình xử lý sự kiện resize vào đối tượng window
    window.addEventListener("resize", handleResize);

    // Gọi hàm handleResize một lần khi trang được tải để có kích thước ban đầu
    handleResize();
</script>

<script>
    //   alert(document.querySelector(".list-product").style);
    function showDropDownMenu(element) {
        event.stopPropagation(); // Ngăn chặn sự kiện lan rộng

        // Lấy menu liên kết
        let dd_menu = element.getElementsByClassName("list-product")[0];

        // Chuyển đổi trạng thái hiển thị
        dd_menu.style.display =
            dd_menu.style.display === "block" ? "none" : "block";
    }

    // Đảm bảo khi click bên trong menu lọc, menu không bị đóng
    document.querySelectorAll(".list-product").forEach((menu) => {
        menu.addEventListener("click", function(event) {
            event.stopPropagation(); // Giữ trạng thái menu
        });
    });

    // Lấy nút "Danh sách sản phẩm"
    const showListButton = document.getElementById("btn-show");
    // Lấy phần tử "list-product"
    const listProductDiv = document.querySelector(".list-product");

    // Thêm sự kiện click cho nút "Danh sách sản phẩm"
    showListButton.addEventListener("click", function(event) {
        event.stopPropagation(); // Ngăn chặn sự kiện lan rộng

        // Chuyển đổi trạng thái hiển thị của "list-product"
        listProductDiv.style.display =
            listProductDiv.style.display === "block" ? "none" : "block";
    });

    // Ngăn dropdown bị đóng khi click vào bên trong dropdown
    listProductDiv.addEventListener("click", function(event) {
        event.stopPropagation();
    });
</script>

<script>
    function formatCurrency(input, hiddenFieldId) {
        // Lấy giá trị gốc không định dạng
        let rawValue = input.value.replace(/\D/g, ""); // Loại bỏ mọi ký tự không phải số

        // Định dạng lại giá trị cho người dùng
        let formattedValue = new Intl.NumberFormat("vi-VN").format(rawValue);
        input.value = formattedValue; // Hiển thị giá trị đã định dạng trong ô nhập liệu

        // Gán giá trị không định dạng vào ô ẩn
        document.getElementById(hiddenFieldId).value = rawValue;
    }
</script>

<script>
    function updateQuantity(element, num) {
        // Thêm id sản phẩm
        let number = document.getElementById("num" + num);
        // alert(number.value);
        // alert(element.name);
        if (element.name == "+") {
            number.value = 1 + Number.parseInt(number.value);
        } else {
            number.value = Number.parseInt(number.value) - 1;
        }
    }
</script>