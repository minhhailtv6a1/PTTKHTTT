<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="./js/import_invoice.js"></script>
<link rel="stylesheet" href="./css/import_invoice.css">
<?php
function echoJS($mess)
{
    echo "<script>$mess</script>";
}
?>
<?php
include "./connectDatabase/DB_import_invoice.php";
include "./connectDatabase/DB_product.php";
include "./connectDatabase/DB_provider.php";
$dbInvoice = new DB_import_invoice();
$data = $dbInvoice->getAllInvoice();
// echoJS("alert('$data[0][\'id\']');");
?>

<!-- PHÂN TRANG -->
<?php
// Thiết lập số trang hiện tại và số lượng dữ liệu trên mỗi trang
$currentPage = isset($_GET['current']) ? intval($_GET['current']) : 1; // Lấy trang hiện tại từ URL, mặc định là 1
$perPage = isset($_GET['totalPage']) ? intval($_GET['totalPage']) : 2; // Số lượng sách hiển thị trên mỗi trang

// Tổng số sách
$totalItems = count($data); // Tổng số phần tử trong mảng sách

// Tính tổng số trang
$totalPage = ceil($totalItems / $perPage);

// Xác định chỉ số bắt đầu và kết thúc trong mảng dựa trên trang hiện tại
$startIndex = ($currentPage - 1) * $perPage;

?>

<input type="text" id="hidden-current" value=<?php echo $_GET['current'] ?? "_" ?> hidden>
<input type="text" id="hidden-new_product_id" value=<?php echo $_GET['new_product_id'] ?? "_" ?> hidden>
<input type="text" id="hidden-new_detail_id" value=<?php echo $_GET['new_detail_id'] ?? "_" ?> hidden>
<input type="text" id="hidden-delete_detail_id" hidden>

<div class="product container-content no-filter">
    <div class="head-content">
        <div class="heading">Phiếu nhập</div>
    </div>

    <div class="mid-content">
        <div style="display:flex; flex-direction: row; justify-content: space-between; align-items: center; height: fit-content;">
            <div class="addition-btn">
                <button class="btn-add">Thêm phiếu nhập</button>
            </div>

            <div class="filter-invoice">
                <span>Lọc theo ngày:</span>
                <input type="date" name="date1" id="date1">
                <span>-</span>
                <input type="date" name="date2" id="date2">
                <button class="btn-filter btn-add" type="button" name="btn-filter">
                    <i class="fa-solid fa-filter"></i>
                    <span>Lọc</span>
                </button>
            </div>
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
                    <?php
                    $new_data = $dbInvoice->getInvoiceInRange($startIndex, $perPage);
                    if (is_array($new_data) && !empty($new_data)) {
                        foreach ($new_data as $key => $row) {

                            echo "
                            <tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['provider_name'] . "</td>
                                <td>" . $row['importDate'] . "</td>
                                <td>" . number_format($row['total'], 0, ',', '.') . "đ</td>
                                <td>
                                    <div class='flex-row-space-evenly'>  
                                    <a href='index.php?page=import_invoice&current=" . $currentPage . "&invoice_id=" . $row['id'] . "&option=update' class='btn-option warning-text' style='background-color: #fff2cf;''>  
                                        <i class='fa-regular fa-pen-to-square'></i>     
                                    </a> 
                                    <a href='index.php?page=import_invoice&current=" . $currentPage . "&invoice_id=" . $row['id'] . "&option=delete' class='btn-option wrong'>  
                                        <i class='fa-regular fa-trash-can'></i>   
                                    </a> 
                                    <a href='index.php?page=import_invoice&current=" . $currentPage . "&invoice_id=" . $row['id'] . "&option=detail' class='btn-option success'>  
                                        <i class='fa-solid fa-ellipsis'></i>  
                                    </a> 
                                </div>  
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan=5 style=\"color:#eb6532\">Không có phiếu nhập nào</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        // Hiển thị nút phân trang
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPage; $i++) {
            $activeClass = ($i == $currentPage) ? 'active' : ''; // Thêm lớp 'active' nếu là trang hiện tại
            echo "<a href=\"index.php?page=import_invoice&current=$i\" class='page-link $activeClass'>" . $i . "</a>";
        }
        echo "</div>";
        ?>
    </div>
</div>

<div class="fakebg1"></div>

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


<!-- XU LY CAC NUT TUY CHINHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['invoice_id'])) {
    $id = intval($_GET['invoice_id']); // Get the value of 'id' from the URL
    $option = isset($_GET['option']) ? $_GET['option'] : "";
    if ($option == 'detail') {
        $data1 = $dbInvoice->getDetailInvoice($id);
        if (empty($data1)) {
            die("Không tìm thấy chi tiết phiếu nhập với ID: " . $id);
        }

        if (is_array($data1) && !empty($data1)) {
            $html_code = html_detail($data1);
        } else {
            echo "<script>alert(\"Không thấy thông tin\")</script>";
        }
    }

    if ($option == 'delete') {
        $html_code = html_delete();
    }

    if ($option == 'update') {
        /// Nếu update rồi thì update và đóng fakebg1
        $status = $_GET['status'] ?? "_";
        if ($status == "addProduct") {
            $invoice_id = $_GET['invoice_id'];
            $product_id = $_GET['new_product_id'];
            addProductToInvoice($invoice_id, $product_id);
            // updateProduct($dbProduct);
            // echo "
            //     <script>
            //         window.onload = function(){
            //             let table = document.getElementsByClassName(\"fakebg1\")[0];
            //         table.style.display = \"none\";
            //         }
            //     </script>
            //     ";
            // return;
        } else if ($status == "cancelUpdate") {
            rollBackBeforeUpdate();
            echo "
                <script>
                    window.onload = function(){
                        let table = document.getElementsByClassName(\"fakebg1\")[0];
                    table.style.display = \"none\";
                    }
                </script>
                ";
            return;
        }

        $html_code = html_update();
    }


    if ($option == "") {
        echo "
            <script>
                window.onload = function(){
                    let table = document.getElementsByClassName(\"fakebg1\")[0];
                table.style.display = \"none\";
                }
            </script>
            ";
        return;
    }

    /// Show fakebg1 panel 
    if ($option == "update") {
        echo "
        <script>
            window.onload = function(){
                let table = document.getElementsByClassName(\"fakebg1\")[0];
            table.innerHTML=
            `
                " . $html_code . "
            `;
            Object.assign(table.style, {
                backgroundColor: 'white',
                });
            showDetail();
            }
        </script>
    ";
    } else {
        echo "
        <script>
            window.onload = function(){
                let table = document.getElementsByClassName(\"fakebg1\")[0];
            table.innerHTML=
            `
                " . $html_code . "
            `;
            showDetail();
            }
        </script>
    ";
    }
}
/// Close fakebg1 panel 
else {
    echo "
        <script>
            window.onload = function(){
                let table = document.getElementsByClassName(\"fakebg1\")[0];
            table.style.display = \"none\";
            }
        </script>
        ";
}

function html_delete()
{
    $html_code = '
        <div class="invoice-delete">
            <div>
                <button class="close-detail" type="button" onclick="closeFakeBG1(this)">
                x
                </button>
            </div>
            <div class="content-delete">
                <p>
                Phiếu nhập này sẽ được xóa khỏi website, bạn chắc chắn muốn xóa phiếu nhập
                này?
                </p>
            </div>
            <div class="btn">
                <button type="button" onclick="closeFakeBG1(this)">Hủy</button>
                <a href=\'index.php?page=import_invoice&current=' . $_GET['current'] . '&invoice_id=' . $_GET['invoice_id'] . '&option=delete&isDeleted=true\'
                    <button id="btn-delete-invoice" type="button" onclick="closeFakeBG1(this)">Xóa phiếu nhập</button> 
                </a> 
            </div>
        </div>
        ';
    return $html_code;
}

function html_detail($data1)
{
    // $row = $data1;
    // echo $data1;

    $flag = 0;
    $html_code = '
     <div>
        <div class="detail">
          <div id="import_form" class="table" style="background-color: #fff">
          <div><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this)\">x</button></div>
            <h1>Phiếu nhập kho</h1>
            <div class="form-info">
              <div>Ngày: ' . $data1[0]['importDate'] . '</div>
              <div>Mã phiếu: ' . $data1[0]['id'] . '</div>
              <div>Nhà cung cấp: ' . $data1[0]['provider_name'] . '</div>
            </div>
            <table class="table-detail">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tên sản phẩm</th>
                  <th>Số lượng</th>
                  <th>Size</th>
                  <th>Đơn giá</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              
            
        ';
    foreach ($data1 as $key => $row) {
        // $flag_newProduct = isset($row['size']) ? 1 : 0;
        if ($flag == 0) {
            $flag = 1;

            $html_code .=
                '
                <tbody>
                <tr>
                  <td>' . $row['product_id'] . '</td>
                  <td>' . $row['product_name'] . '</td>
                  <td>' . $row['quantity'] . '</td>
                  <td>' . $row['size'] . '</td>
                  <td>' . number_format($row['price'], 0, ',', '.') . 'đ</td>
                  <td>' . number_format($row['price'] * $row['quantity'], 0, ',', '.') . 'đ</td>
                </tr>
                    ';
        } else {
            $html_code .= '
                <tr>
                  <td>' . $row['product_id'] . '</td>
                  <td>' . $row['product_name'] . '</td>
                  <td>' . $row['quantity'] . '</td>
                  <td>' . $row['size'] . '</td>
                  <td>' . number_format($row['price'], 0, ',', '.') . 'đ</td>
                  <td>' . number_format($row['price'] * $row['quantity'], 0, ',', '.') . 'đ</td>
                </tr>
                ';
        }
    }
    $html_code .= '
                </tbody>
            </table>
          </div>

          <button id="download_pdf" class="btn-success" onclick="exportPDF()">Download PDF</button>
        </div>
      </div>
        ';
    return $html_code;
}

function addProductToInvoice($invoice_id, $product_id)
{
    $dbProduct = new DB_product();
    $dbInvoice = new DB_import_invoice();
    $type_id = $dbProduct->getProductByID($product_id)[0]['type_id'];
    if ($type_id == 1 || $type_id == 2) $default_size = "S";
    if ($type_id == 3) $default_size = "XS";
    if ($type_id == 4 || $type_id == 5) $default_size = 38;
    if ($type_id == 6) $default_size = 28;
    $newDetail = $dbInvoice->addDetailInvoice($invoice_id, $product_id, $default_size, 0, 10);
    // echoJS("alert(\"" . $newDetail . "\")");
    echoJS("
            document.getElementById('hidden-new_detail_id').value += " . $newDetail . ";
    ");
}

function rollBackBeforeUpdate()
{
    $listIDString = $_GET['new_detail_id'];

    // Gọi hàm để cắt chuỗi thành mảng các số
    $numberArray = stringToNumberArray($listIDString);

    // In ra mảng kết quả để kiểm tra
    // print_r($numberArray);
    // foreach ($numberArray as $key => $num) {
    //     echoJS("alert('" . $num . "')");
    // }

    $dbInvoice = new DB_import_invoice();
    foreach ($numberArray as $key => $num) {
        $dbInvoice->deleteDetailInvoice($num);
    }

    // Bạn có thể thực hiện các thao tác khác với mảng số này ở đây
}
function stringToNumberArray(string $str, string $delimiter = '_'): array
{
    // Loại bỏ dấu phân cách ở đầu và cuối chuỗi nếu có
    $str = trim($str, $delimiter);

    // Tách chuỗi thành mảng các phần tử dựa trên dấu phân cách
    $parts = explode($delimiter, $str);

    $numbers = [];
    foreach ($parts as $part) {
        // Kiểm tra xem phần tử có phải là một chuỗi số không rỗng
        if ($part !== '' && ctype_digit($part)) {
            // Chuyển đổi phần tử thành số nguyên và thêm vào mảng kết quả
            $numbers[] = (int) $part;
        }
    }

    return $numbers;
}

function html_update()
{
    $dbProduct = new DB_product();
    $dbInvoice = new DB_import_invoice();
    $dbProvider = new DB_provider();
    $dataProduct = $dbProduct->getAllProductForInvoice();
    $dataProvider = $dbProvider->getAllProvider();
    $dataDetailInvoice = $dbInvoice->getDetailInvoice($_GET['invoice_id']);
    $line_provider = '';
    foreach ($dataProvider as $key => $row_provider) {
        if ($dataDetailInvoice[0]['provider_name'] == $row_provider['name'])
            $line_provider .= '
            <option selected>' . $row_provider['name'] . '</option>
            ';
        else {
            $line_provider .= '
            <option>' . $row_provider['name'] . '</option>
            ';
        }
    }
    $html_code = '
        <div class="update-import-invoice">
        <div class="header">
            <i class="fas fa-file-alt header-icon"></i>
            <!-- Chỉnh lại id -->
            <div class="header-title">Chỉnh sửa phiếu nhập #' . $_GET['invoice_id'] . '</div>
            <div style="width: 100%"><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this), cancelUpdate()\">x</button></div>
        </div>

        <div class="content-detail">
            <div class="general-info">
                <!-- <form action=""></form> -->
                <label for="infoProvider">Nhà cung cấp:</label>
                <select id="infoProvider" name="infoProvider">
                    ' . $line_provider . '
                </select>
            </div>
            <div class="showList has-drop-down-menu">
                <button type="button" name="btn-show" id="btn-show" onclick="showListProduct()">
                    Danh sách sản phẩm
                </button>
            </div>
            <div id="list-product" class="list-product">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sản phẩm</th>
                            <th>Ảnh</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
    $line = '';
    foreach ($dataProduct as $key => $row) {
        $img = $dbProduct->getImagesProduct($row['id']);
        if (empty($img)) {
            $img = [
                'img1' => "./assets/img/img_product/default_img.jpeg",
                'img2' => "./assets/img/img_product/default_img.jpeg",
                'img3' => "./assets/img/img_product/default_img.jpeg",
                'img4' => "./assets/img/img_product/default_img.jpeg",
                'img5' => "./assets/img/img_product/default_img.jpeg"
            ];
        }
        $line .= '
                        <tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>
                                <img
                                    src="' . $img['img1'] . '"
                                    alt="" />
                            </td>
                            <td class="td-add" id="addToInvoice-' . $row['id'] . '" onclick="addProductToInvoice(this)">
                                <i class="fa-solid fa-plus"></i>
                            </td>
                        </tr>
                        ';
    }
    $line .= '
                            </tbody>
                        </table>
                    </div>
                    ';

    $html_code .= $line;

    $html_code .= '
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
                    ';

    // $dataInvoice = $dbInvoice->getAllOriginalInvoice();
    $dataDetailInvoice = $dbInvoice->getDetailInvoice($_GET['invoice_id']);
    $line = "";
    for ($i = 0; $i < count($dataDetailInvoice); $i++) {
        $loai12 = $dataDetailInvoice[$i]['type_id'] == 1 || $dataDetailInvoice[$i]['type_id'] == 2 ? "" : "hidden";
        $loai3 = $dataDetailInvoice[$i]['type_id'] == 3 ? "" : "hidden";
        $loai45 = $dataDetailInvoice[$i]['type_id'] == 4 || $dataDetailInvoice[$i]['type_id'] == 5 ? "" : "hidden";
        $loai6 = $dataDetailInvoice[$i]['type_id'] == 6 ? "" : "hidden";

        $size = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '29', '30', '31', '32', '33', '34', '38', '39', '40', '41', '42'];
        $size_array = [
            "XS" => "",
            "S" => "",
            "M" => "",
            "L" => "",
            "XL" => "",
            "XXL" => "",
            "28" => "",
            "29" => "",
            "30" => "",
            "31" => "",
            "32" => "",
            "33" => "",
            "34" => "",
            "35" => "",
            "36" => "",
            "37" => "",
            "38" => "",
            "39" => "",
            "40" => "",
            "41" => "",
            "42" => "",
        ];
        foreach ($size as $key => $row_size) {
            if ($row_size == $dataDetailInvoice[$i]['size']) {
                $size_array[$row_size] = "selected";
            } else {
                $size_array[$row_size] = "";
            }
        }

        $line .= '
                        <tr id="detail_id_' . $dataDetailInvoice[$i]['detail_id'] . '">
                            <td>' . ($i + 1) . '</td>
                            <td>' . $dataDetailInvoice[$i]['product_name'] . '</td>
                            <td>
                                <!-- Size cua loai 1 2 -->
                                <select name="size1" id="size1" ' . $loai12 . '>
                                    <option value="S" ' . $size_array['S'] . '>S</option>
                                    <option value="M" ' . $size_array['M'] . '>M</option>
                                    <option value="L" ' . $size_array['L'] . '>L</option>
                                    <option value="XL" ' . $size_array['XL'] . '>XL</option>
                                    <option value="XXL" ' . $size_array['XXL'] . '>XXL</option>
                                </select>

                                <!-- Size cua loai 3 -->
                                <select name="size3" id="size3" ' . $loai3 . '>
                                    <option value="XS" ' . $size_array['XS'] . '>XS</option>
                                    <option value="S" ' . $size_array['S'] . '>S</option>
                                    <option value="M" ' . $size_array['M'] . '>M</option>
                                    <option value="L" ' . $size_array['L'] . '>L</option>
                                </select>

                                <!-- Size cua loai 4 5 -->
                                <select name="size4" id="size4" ' . $loai45 . '>
                                    <option value="38" ' . $size_array['38'] . '>38</option>
                                    <option value="39" ' . $size_array['39'] . '>39</option>
                                    <option value="40 ' . $size_array['40'] . '">40</option>
                                    <option value="41" ' . $size_array['41'] . '>41</option>
                                    <option value="42" ' . $size_array['42'] . '>42</option>
                                </select>

                                <!-- Size cua loai 6 -->
                                <select name="size6" id="size6" ' . $loai6 . '>
                                    <option value="28" ' . $size_array['28'] . '>28</option>
                                    <option value="29" ' . $size_array['29'] . '>29</option>
                                    <option value="30" ' . $size_array['30'] . '>30</option>
                                    <option value="31" ' . $size_array['31'] . '>31</option>
                                    <option value="32" ' . $size_array['32'] . '>32</option>
                                    <option value="33" ' . $size_array['33'] . '>33</option>
                                    <option value="34" ' . $size_array['34'] . '>34</option>
                                </select>
                            </td>
                            <td>
                                <div class="quantity-controls">
                                    <button
                                        class="quantity-button btn-decrease"
                                        onclick="updateQuantity(this,' . $dataDetailInvoice[$i]['detail_id'] . ')"
                                        name="-">
                                        -
                                    </button>
                                    <input
                                        type="number"
                                        id="num' . $dataDetailInvoice[$i]['detail_id'] . '"
                                        class="quantity-input"
                                        value="' . $dataDetailInvoice[$i]['quantity'] . '" />
                                    <button
                                        class="quantity-button btn-increase"
                                        onclick="updateQuantity(this,' . $dataDetailInvoice[$i]['detail_id'] . ')"
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
                                    oninput="formatCurrency(this, \'update_price\')"
                                    value="' . number_format($dataDetailInvoice[$i]['price'], 0, ',', '.') . '" />
                                <input
                                    type="text"
                                    id="update_price"
                                    name="update_price"
                                    value="0"
                                    hidden />
                            </td>
                            <td><button class="remove-button" onclick="deleteDetail(' . $dataDetailInvoice[$i]['detail_id'] . ')">×</button></td>
                        </tr>
                            ';
    }

    $html_code .= $line;

    $html_code .= '
                            </tbody>
                        </table>
                    </div>
                    <div class="btn-total">
                        <div class="total">
                            Tổng phiếu nhập: &nbsp; <span>' . number_format($dataDetailInvoice[0]['total'], 0, ',', '.')  . 'đ</span>
                        </div>
                        <div class="btn">
                            <button type="button" onclick="closeFakeBG1(this), cancelUpdate()">Hủy</button>
                            <button id="btn-update-invoice" type="button" onclick="updateProduct(), closeFakeBG1(this)">Xác nhận </button> 
                        </div>
                    </div>
                </div>
            </div>
                    ';

    return $html_code;
}

?>

<!-- XOAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['isDeleted'])) {
    $id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;
    echo "
    <script>
        alert(" . $id . ");
    </script>";
    $dbInvoice->deleteInvoice($id);
    echo "
        <script>
            window.onload = function() {
                // let table = document.getElementsByClassName(\"fakebg1\")[0];
                // table.style.display = \"none\";
                showSuccessfulAlert(`Xóa phiếu nhập thành công`);
                setTimeout(function() {
                    window.location.href = 'index.php?page=import_invoice&current=" . $_GET['current'] . "';
                }, 1000); // Đợi 2 giây trước khi chuyển hướng
            }
        </script>";
}
?>

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
    btn.addEventdataener("click", exportPDF);
</script>

<script>
    // Lấy phần tử dialog
    const addInvoiceDialog = document.getElementById("addInvoiceDialog");
    const addInvoiceButton = document.querySelector(".btn-add");

    // Khi nhấn nút "Thêm phiếu nhập", hiển thị dialog
    addInvoiceButton.addEventdataener("click", () => {
        addInvoiceDialog.showModal();
    });

    // Đóng dialog khi nhấn nút "Hủy"
    function closeDialog() {
        addInvoiceDialog.close();
    }

    // Xử lý sự kiện khi form trong dialog được submit
    document.getElementById("invoiceForm").addEventdataener("submit", function(e) {
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

<script>
    function handleResize() {
        // Lấy chiều rộng hiện tại của cửa sổ
        const windowWidth = window.innerWidth;

        const listProductDiv = document.querySelector(".list-product");

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
    // handleResize();
</script>

<script>
    function showListProduct() {
        // Lấy nút "Danh sách sản phẩm"
        const showListButton = document.getElementById("btn-show");
        // Lấy phần tử "list-product"
        const listProductDiv = document.querySelector(".list-product");


        // alert(listProductDiv.innerHTML);

        listProductDiv.style.display = "block";
        // listProductDiv.style.display === "block" ? "none" : "block";


        // Ngăn dropdown bị đóng khi click vào bên trong dropdown
        listProductDiv.addEventListener("click", function(event) {});
        event.stopPropagation();

        // Ngăn dropdown bị đóng khi click vào bên trong dropdown
        listProductDiv.addEventListener("click", function(event) {
            event.stopPropagation();
        });

        document.addEventListener("click", function() {
            listProductDiv.style.display = "none";
        });
    }
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
        // alert("num" + num);
        if (element.name == "+") {
            number.value = 1 + Number.parseInt(number.value);
        } else {
            number.value = Number.parseInt(number.value) - 1;
        }
    }
</script>

<script>
    function addProductToInvoice(element) {
        var s = element.id;
        var parts = s.split('-');
        var numberID = parts.pop();
        const current = document.getElementById("hidden-current");
        const params = new URLSearchParams(window.location.search);
        // var listProductID = document.getElementById("hidden-new_product_id");
        // listProductID.value += "_";
        // listProductID.value += numberID;
        var new_detail = document.getElementById("hidden-new_detail_id");
        new_detail.value += '_';
        params.set("page", "import_invoice");
        params.set("current", current.value);
        params.set("option", "update");
        params.set("status", "addProduct");
        params.set("new_product_id", numberID);
        params.set("new_detail_id", new_detail.value);
        // Cập nhật URL
        window.location.href = `index.php?${params.toString()}`;
    }

    function cancelUpdate() {
        // var listID = document.getElementById("hidden-new_detail_id");
        // var str = listID.value;
        // var parts = str.split('_');
        // var numbers = [];

        // for (var i = 0; i < parts.length; i++) {
        //     var part = parts[i];
        //     // Kiểm tra xem phần tử có phải là một số nguyên dương hay không
        //     if (/^\d+$/.test(part)) {
        //         numbers.push(parseInt(part, 10)); // Chuyển đổi thành số và thêm vào mảng
        //     }
        // }
        const params = new URLSearchParams(window.location.search);
        var new_detail = document.getElementById("hidden-new_detail_id");
        new_detail.value += '_';
        params.set("status", "cancelUpdate");
        params.set("new_detail_id", new_detail.value);
        window.location.href = `index.php?${params.toString()}`;
    }

    function deleteDetail($id) {
        // alert("hello");
        let tr = document.getElementById("detail_id_" + $id);
        tr.style.display = 'none';
        var delete_detail = document.getElementById("hidden-delete_detail_id");
        delete_detail.value += '_';
        delete_detail.value += $id
        alert(delete_detail.value);
    }

    function updateInvoice() {
        var delete_detail = document.getElementById("hidden-delete_detail_id");
        const params = new URLSearchParams(window.location.search);
        params.set("delete_detail_id", delete_detail.value);
        params.set("status", "update");
        window.location.href = `index.php?${params.toString()}`;
    }
</script>