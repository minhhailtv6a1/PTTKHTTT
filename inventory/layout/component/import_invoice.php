<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<div class="product container-content no-filter">
    <div class="head-content">
        <div class="heading">Phiếu nhập</div>
    </div>

    <div class="mid-content">
        <div style="display:flex; flex-direction: row; justify-content: space-between">
            <div class="addition-btn">
                <button class="btn-add">Thêm phiếu nhập</button>
            </div>
            <input class="input" type="text" id="search-product" name="search-product" placeholder="Tìm kiếm phiếu nhập">
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nhà cung cấp</th>
                        <th>Ngày</th>
                        <th>Thành tiền</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>101</td>
                        <td>Công ty TNHH ABC</td>
                        <td>10/03/2025</td>
                        <td>1.400.000đ</td>
                        <td>
                            <div class="flex-row-space-evenly">
                                <button class="btn-option">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn-option wrong">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                <button class="btn-option success">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>101</td>
                        <td>Công ty TNHH XYZ</td>
                        <td>10/03/2025</td>
                        <td>1.400.000đ</td>
                        <td>
                            <div class="flex-row-space-evenly">
                                <button class="btn-option">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn-option wrong">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                <button class="btn-option success" onclick="showDetail()">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="fakebg">
    <div>
        <div><button class="btn-option" type="button" onclick="closeFakeBG(this)">x</button></div>
        <div class="revise"></div>
        <div class="delete"></div>
        <div class="detail">
            <div id="import_form" class="table" style="background-color: #fff;">
                <h1>Phiếu nhập kho</h1>
                <div class="form-info">
                    <div>Ngày: 03/03/2025</div>
                    <div>Mã phiếu: 123456</div>
                    <div>Nhà cung cấp: Công ty ABC</div>
                </div>
                <table class="table-detail">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên hàng</th>
                            <th>Đơn vị</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Sản phẩm A</td>
                            <td>Cái</td>
                            <td>100</td>
                            <td>10.000</td>
                            <td>1.000.000</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Sản phẩm B</td>
                            <td>Hộp</td>
                            <td>50</td>
                            <td>20.000</td>
                            <td>1.000.000</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Sản phẩm C</td>
                            <td>Kg</td>
                            <td>30</td>
                            <td>30.000</td>
                            <td>900.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button id="download_pdf" class="btn-success">Download PDF</button>
        </div>
    </div>
</div>

<!-- Dialog ẩn mặc định -->
<dialog id="addInvoiceDialog">
    <h2>Thêm Phiếu Nhập</h2>
    <form id="invoiceForm">
        <label for="supplierName">Tên Nhà cung cấp:</label>
        <input type="text" id="supplierName" name="supplierName" required>

        <label for="date">Ngày:</label>
        <input type="date" id="date" name="date" required>

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" required>

        <label for="amount">Thành tiền:</label>
        <input type="number" id="amount" name="amount" required>

        <div class="dialog-buttons">
            <button type="button" onclick="closeDialog()">Hủy</button>
            <button type="submit">Lưu</button>
        </div>
    </form>
</dialog>


<script>
    function checkFormFilter() {
        let gia1 = document.getElementById("price1");
        let gia2 = document.getElementById("price2");


        if (gia1.value.trim() === "" && gia2.value.trim() === "") return true;

        // Kiểm tra giá trị rỗng
        if (gia1.value.trim() === "" && gia2.value.trim() !== "") {
            showFailedAlert("Nhập giá sai. Hãy nhập lại!");
            gia1.focus();
            return false;
        }

        if (gia1.value.trim() !== "" && gia2.value.trim() === "") {
            showFailedAlert("Nhập giá sai. Hãy nhập lại!");
            gia2.focus();
            return false;
        }

        // Kiểm tra định dạng giá tiền (regex)
        let pattern = /^\d+$/;
        if (!pattern.test(gia1.value.trim())) {
            showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
            gia1.focus();
            return false;
        }

        if (!pattern.test(gia2.value.trim())) {
            showFailedAlert("Sai định dạng giá tiền. Hãy nhập lại!");
            gia2.focus();
            return false;
        }

        let num1 = parseInt(gia1.value.trim(), 10); // Sử dụng parseInt của JavaScript
        let num2 = parseInt(gia2.value.trim(), 10);
        console.log(num1 + num2);

        if (num1 > num2) {
            showFailedAlert("Nhập sai khoảng giá. Hãy nhập lại!");
            gia2.focus();
            return false;
        }

        // Mọi thứ hợp lệ
        // showSuccessfulAlert("Xác nhận");
        return true;
    }

    function showDetail() {
        let table = document.getElementsByClassName("fakebg")[0];
        table.style.display = "block";
    }

    function closeFakeBG(element) {
        let table = document.getElementsByClassName("fakebg")[0];
        table.style.display = "none";
    }
</script>

<?php include "layout/alert/alert.php" ?>

<style>
    * {
        font-family: Arial, sans-serif;
    }

    h1 {
        text-align: center;
        color: #4CAF50;
        margin: 15px;
    }

    .form-info {
        width: 80%;
        margin: 20px auto;
        text-align: left;
    }

    .form-info div {
        margin-bottom: 10px;
        font-size: 16px;
    }

    .table-detail {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        /* overflow-x: scroll; */
    }

    .table-detail th,
    .table-detail td {
        border: 1px solid #ddd;
        padding: 8px;
        white-space: nowrap;
    }

    .table-detail th {
        background-color: #f2f2f2;
        color: black;
        text-align: center;
    }

    .table-detail td {
        text-align: center;
    }

    .table-detail tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table-detail tr:hover {
        background-color: #e2e2e2;
    }

    .table .table-detail {
        width: 80%;
    }

    .detail button {
        text-align: left;
        margin: 20px;
    }

    @media screen and (max-width: 500px) {
        .table table {
            max-width: 400px;
        }
    }
</style>


<script>
    var specialElementHandlers = {
        ".no-export": function(element, renderer) {
            return true;
        },
    };

    async function exportPDF() {
        const {
            jsPDF
        } = window.jspdf;

        // Create a new jsPDF instance
        const doc = new jsPDF('p', 'pt', 'a4');

        // Source element to be converted to PDF
        const element = document.getElementById('import_form'); // Wrap your table and additional elements in a container

        // Options for html2pdf
        const opt = {
            margin: 10,
            filename: 'phieu_nhap_kho.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'pt',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        // New Promise-based usage of html2pdf
        html2pdf().from(element).set(opt).save();
    }

    let btn = document.getElementById("download_pdf");
    btn.addEventListener("click", exportPDF);
</script>

<script>
    // Lấy phần tử dialog
    const addInvoiceDialog = document.getElementById("addInvoiceDialog");
    const addInvoiceButton = document.querySelector(".btn-add");

    // Khi nhấn nút "Thêm phiếu nhập", hiển thị dialog
    addInvoiceButton.addEventListener("click", () => {
        addInvoiceDialog.showModal();
    });

    // Đóng dialog khi nhấn nút "Hủy"
    function closeDialog() {
        addInvoiceDialog.close();
    }

    // Xử lý sự kiện khi form trong dialog được submit
    document.getElementById("invoiceForm").addEventListener("submit", function(e) {
        e.preventDefault(); // Ngăn chặn reload trang

        // Lấy dữ liệu từ form
        const supplierName = document.getElementById("supplierName").value;
        const date = document.getElementById("date").value;
        const address = document.getElementById("address").value;
        const amount = document.getElementById("amount").value;

        // In ra console hoặc thực hiện logic lưu
        console.log({
            supplierName,
            date,
            address,
            amount
        });

        // Sau khi lưu, đóng dialog
        closeDialog();

        // Reset form
        this.reset();
    });
</script>

<style>
    dialog {
        width: 30%;
        border: none;
        border-radius: 8px;
        padding: 20px 40px 20px 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        /* text-align: left; */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        /* background-color: #fff; */
        /* padding-right: 5px; */
    }

    dialog h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #4CAF50;
    }

    dialog form label {
        display: block;
        margin-top: 10px;
    }

    dialog form input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        /* margin-right: 5px; */
        /* text-align: center; */
    }

    .dialog-buttons {
        margin-top: 20px;
        text-align: right;
    }

    .dialog-buttons button {
        margin-left: 10px;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .dialog-buttons button[type="button"] {
        background-color: #f44336;
        color: #fff;
    }

    .dialog-buttons button[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
    }

    @media (max-width: 900px) {
        dialog {
            width: 40%;
        }
    }

    @media (max-width: 600px) {
        dialog {
            width: 50%;
        }
    }
</style>