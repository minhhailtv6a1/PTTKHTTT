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
include "./connectDatabase/DB_account.php";
$dbInvoice = new DB_import_invoice();
$data = $dbInvoice->getAllInvoice();
$data = isset($_GET['date1']) ? $dbInvoice->getInvoiceByDate($_GET['date1'], $_GET['date2']) : $data;
// echoJS("alert('$data[0][\'id\']');");
?>

<!-- PHÂN TRANG -->
<?php
// Thiết lập số trang hiện tại và số lượng dữ liệu trên mỗi trang
$currentPage = isset($_GET['current']) ? intval($_GET['current']) : 1; // Lấy trang hiện tại từ URL, mặc định là 1
$perPage = isset($_GET['totalPage']) ? intval($_GET['totalPage']) : 5; // Số lượng sách hiển thị trên mỗi trang

// Tổng số sách
$totalItems = count($data); // Tổng số phần tử trong mảng sách

// Tính tổng số trang
$totalPage = ceil($totalItems / $perPage);

// Xác định chỉ số bắt đầu và kết thúc trong mảng dựa trên trang hiện tại
$startIndex = ($currentPage - 1) * $perPage;

?>

<input type="text" id="hidden-current" hidden value=<?php echo $_GET['current'] ?? htmlspecialchars("1") ?>>
<input type="text" id="hidden-new_product_id" hidden value=<?php echo $_GET['product_id'] ?? "_" ?>>
<input type="text" id="hidden-new_detail_id" hidden value=<?php echo $_GET['new_detail_id'] ?? "_" ?>>
<input type="text" id="hidden-delete_detail_id" hidden>
<input type="text" id="hidden-date1" hidden value=<?php echo $_GET['date1'] ?? htmlspecialchars("") ?>>
<input type="text" id="hidden-date2" hidden value=<?php echo $_GET['date2'] ?? htmlspecialchars("") ?>>

<div class="product container-content no-filter">
    <div class="head-content">
        <div class="heading">Phiếu xuất</div>
    </div>

    <div class="mid-content">
        <div style="display:flex; flex-direction: row; justify-content: space-between; align-items: center; height: fit-content;">
            <div class="addition-btn">
                <!-- <button class="btn-add">Thêm phiếu xuất</button> -->
                <a href='index.php?page=import_invoice&current=<?php echo $currentPage ?>&option=add_new' class='btn-add'>
                    Thêm phiếu xuất
                </a>
            </div>

            <div class="filter-invoice">
                <span>Lọc theo ngày:</span>
                <input type="date" name="date1" id="date1">
                <span>-</span>
                <input type="date" name="date2" id="date2">
                <button class="btn-filter btn-add" type="button" name="btn-filter" onclick="filterByDate()">
                    <i class="fa-solid fa-filter"></i>
                    <span>Lọc</span>
                </button>
            </div>
        </div>

        <div class="table">
            <div class="filter-result"></div>
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
                    // Nếu có date thì lọc
                    $new_data = isset($_GET['date1']) ? $dbInvoice->getInvoiceInRangeByDate($startIndex, $perPage, $_GET['date1'], $_GET['date2']) : $new_data;
                    if (is_array($new_data) && !empty($new_data)) {
                        foreach ($new_data as $key => $row) {

                            echo "
                            <tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['provider_name'] . "</td>
                                <td>" . formatDatePHP($row['importDate']) . "</td>
                                <td>" . number_format($row['total'], 0, ',', '.') . "đ</td>
                                <td>
                                <div class='flex-row-space-evenly'>  
                                <!-- 
                                    <a href='index.php?page=import_invoice&current=" . $currentPage . "&invoice_id=" . $row['id'] . "&option=update' class='btn-option warning-text' style='background-color: #fff2cf;''>  
                                        <i class='fa-regular fa-pen-to-square'></i>     
                                    </a> 
                                -->
                                    <a href='index.php?page=import_invoice&current=" . $currentPage . "&invoice_id=" . $row['id'] . "&option=delete' class='btn-option wrong' style='  background-color: #ffebeb !important;
  color: #fd6d6d !important;'>  
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
                        echo "<tr><td colspan=5 style=\"color:#eb6532\">Không có phiếu xuất nào</td></tr>";
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

            $date1 = $_GET['date1'] ?? ""; // Sử dụng null coalescing operator để gán giá trị mặc định nếu $_GET['date1'] không tồn tại
            $date2 = $_GET['date2'] ?? ""; // Tương tự cho $_GET['date2']

            $url = "index.php?page=import_invoice&current=$i";
            if (isset($_GET['date1'])) {
                $url .= "&date1=" . urlencode($_GET['date1']) . "&date2=" . urlencode($_GET['date2']);
            }

            echo "<a href=\"" . htmlspecialchars($url) . "\" class='page-link $activeClass'>" . $i . "</a>";
        }
        echo "</div>";
        ?>
    </div>
</div>
<!--  -->
<div class="fakebg1"></div>


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
    // echoJS("alert('" . $data1[0]['emp_id'] . "')");
    $dbAccount = new DB_account();
    $dataAccount = $dbAccount->getIdUser($data1[0]['emp_id']);

    // var_dump($dataAccount); // Kiểm tra giá trị của $dataAccount

    if ($dataAccount) {
        $empName = $dataAccount['name']; // Truy cập trực tiếp $dataAccount['name'] vì nó là một mảng duy nhất
        // echoJS("alert('" . $empName . "')");
    } else {
        // echoJS("alert('Không tìm thấy người dùng với ID: " . $data1[0]['emp_id'] . "')");
        // Xử lý trường hợp không tìm thấy người dùng (ví dụ: gán một giá trị mặc định cho $empName)
        $empName = "Không xác định";
    }
    $flag = 0;
    $html_code = '
     <div>
        <div class="detail">
          <div class="detail123">
          <div><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this)\">x</button></div>
          <div id="import_form" class="table-container-detail">
            <h1>Phiếu nhập kho</h1>
            <div class="form-info">
              <div>Ngày: ' . formatDatePHP($data1[0]['importDate']) . '</div>
              <div>Mã phiếu: ' . $data1[0]['id'] . '</div>
              <div>Nhà cung cấp: ' . $data1[0]['provider_name'] . '</div>
              <div>Nhân viên lập phiếu: ' . $empName . '</div>
            </div>
                <div style="display: flex; flex-direction: row; justify-content: center; width: 100%;">
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
            </div>
            <div><button id="download_pdf" class="btn-success" onclick="exportPDF()">Tải phiếu nhập</button></div>
          </div>

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
function formatDatePHP($inputDate)
{
    if (empty($inputDate)) {
        return ""; // Trả về chuỗi rỗng nếu input rỗng
    }

    $date = DateTime::createFromFormat('Y-m-d', $inputDate);
    if ($date) {
        return $date->format('d/m/Y');
    } else {
        return "Định dạng không hợp lệ"; // Hoặc bạn có thể xử lý lỗi khác
    }
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


function html_add_new()
{
    $dbProduct = new DB_product();
    $dbInvoice = new DB_import_invoice();
    $dbProvider = new DB_provider();
    $dataProduct = $dbProduct->getAllProductForInvoice();
    $dataProvider = $dbProvider->getAllProvider();
    // $dataDetailInvoice = $dbInvoice->getDetailInvoice($_GET['invoice_id']);
    $line_provider = '';
    foreach ($dataProvider as $key => $row_provider) {
        $line_provider .= '
            <option>' . $row_provider['name'] . '</option>
            ';
    }
    $html_code = '
        <div class="update-import-invoice">
        <div class="header">
            <i class="fas fa-file-alt header-icon"></i>
            <!-- Chỉnh lại id -->
            <div class="header-title">Thêm phiếu nhập</div>
            <div style="width: 100%"><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this), cancelAdd()\">x</button></div>
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
                        <tr id="product_' . $row['id'] . '">
                            <td>' . $row['id'] . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>
                                <img
                                    src="' . $img['img1'] . '"
                                    alt="" />
                            </td>
                            <td class="td-add" id="addToInvoice-' . $row['id'] . '" onclick="addProduct(this)">
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
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_add">
                    ';
    $line = '';
    $product_id = $_GET['product_id'] ?? "_";
    $dbProduct = new DB_product();
    if ($product_id != "_") {
        $parts = explode("_", $product_id);
        for ($i = 1; $i < count($parts) - 1; $i++) {
            // Đọc thông tin sản phẩm
            $dataProduct = $dbProduct->getProductByID("" . $parts[$i] . "");
            $loai12 = $dataProduct[0]['type_id'] == 1 || $dataProduct[0]['type_id'] == 2 ? "class=\"active\"" : "hidden";
            $loai3 = $dataProduct[0]['type_id'] == 3 ? "class=\"active\"" : "hidden";
            $loai45 = $dataProduct[0]['type_id'] == 4 || $dataProduct[0]['type_id'] == 5 ? "class=\"active\"" : "hidden";
            $loai6 = $dataProduct[0]['type_id'] == 6 ? "class=\"active\"" : "hidden";

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
                $size_array[$row_size] = "";
            }
            $line .= '
                <tr id="detail_id_' . $i . '">
                            <td>' . $i . '</td>
                            <td>' . $dataProduct[0]['name'] . '</td>
                            <td hidden>' . $dataProduct[0]['id'] . '</td>
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
                                        onclick="updateQuantity(this,' . $i . '), formatCurrency1(\'format_update_price_' . $i . '\', \'update_price_' . $i . '\', \'num' . $i . '\', \'thanhTien_' . $i . '\')"
"
                                        name="-">
                                        -
                                    </button>
                                    <input
                                        type="number"
                                        id="num' . $i . '"
                                        class="quantity-input"
                                        oninput="formatCurrency1(\'format_update_price_' . $i . '\', \'update_price_' . $i . '\', \'num' . $i . '\', \'thanhTien_' . $i . '\')"
                                        value="10" />
                                    <button
                                        class="quantity-button btn-increase"
                                        onclick="updateQuantity(this,' . $i . '), formatCurrency1(\'format_update_price_' . $i . '\', \'update_price_' . $i . '\', \'num' . $i . '\', \'thanhTien_' . $i . '\')"
"
                                        name="+">
                                        +
                                    </button>
                                </div>
                            </td>
                            <td>
                                <input
                                    type="text"
                                    id="format_update_price_' . $i . '"
                                    name="format_update_price_' . $i . '"
                                    required
                                    oninput="formatCurrency(this, \'update_price_' . $i . '\', \'num' . $i . '\', \'thanhTien_' . $i . '\')"
                                    value="0" />
                                <input
                                    type="text"
                                    id="update_price_' . $i . '"
                                    name="update_price_' . $i . '"
                                    required
                                    value="0"
                                    hidden />
                            </td>
                            <td align="center">
                                <span align="center" id="thanhTien_' . $i . '">0đ</span>
                                <span class="sumPrice" id="hiddenthanhTien_' . $i . '" hidden>0đ</span>
                            </td>
                            <td><button class="remove-button" onclick="deleteDetail(' . $dataProduct[0]['id'] . ', ' . $i . ')">×</button></td>
                        </tr>
            ';
        }
        $html_code .= $line;
    }

    $html_code .= '
                            </tbody>
                        </table>
                    </div>
                    <div class="btn-total">
                        <div class="total">
                            Tổng phiếu nhập: &nbsp; <span id="totalPrice">0đ</span> 
                            <span id="totalPriceHidden" hidden>0đ</span>
                        </div>
                        <div class="btn">
                            <button type="button" onclick="closeFakeBG1(this), cancelAdd()">Hủy</button>
                            <button id="btn-update-invoice" type="button" onclick="updateProduct(), closeFakeBG1(this)">Xác nhận </button> 
                        </div>
                    </div>
                </div>
            </div>
                    ';

    return $html_code;
}

?>

<!-- Themmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm -->
<?php
$option = isset($_GET['option']) ? $_GET['option'] : "";
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $option == 'add_new') {
    $html_code = html_add_new();
    // echoJS("alert('hello')");

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

<!-- LỌC THEO NGÀYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['date1'])) {
    $dateString1 = $_GET['date1'];
    $dateString2 = $_GET['date2'];
    // $date1 = DateTime::createFromFormat('Y-m-d', $dateString1);
    // $date2 = DateTime::createFromFormat('Y-m-d', $dateString2);

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
    function formatCurrency1(input_id, hiddenFieldId, quantityID, totalID) {
        let e = document.getElementById(input_id);
        formatCurrency(e, hiddenFieldId, quantityID, totalID);
    }

    function formatCurrency(input, hiddenFieldId, quantityID, totalID) {
        // Lấy giá trị gốc không định dạng
        let rawValue = input.value.replace(/\D/g, ""); // Loại bỏ mọi ký tự không phải số

        // Định dạng lại giá trị cho người dùng
        let formattedValue = new Intl.NumberFormat("vi-VN").format(rawValue);
        input.value = formattedValue; // Hiển thị giá trị đã định dạng trong ô nhập liệu

        // Gán giá trị không định dạng vào ô ẩn
        document.getElementById(hiddenFieldId).value = rawValue;

        let soLuong = document.getElementById(quantityID);
        let thanhTien = Number.parseFloat(rawValue) * Number.parseInt(soLuong.value);
        thanhTien = isNaN(thanhTien) ? 0 : thanhTien;
        // alert(totalID);
        let thanhTienElement = document.getElementById(totalID);
        let hiddenthanhTienElement = document.getElementById("hidden" + totalID);
        thanhTienElement.innerHTML = new Intl.NumberFormat("vi-VN").format(thanhTien) + 'đ';
        hiddenthanhTienElement.innerHTML = thanhTien;
        sumPrice();
    }

    function sumPrice() {
        let e = document.getElementsByClassName("sumPrice");
        // alert(e.value);
        let rs = document.getElementById("totalPrice");
        let hiddenRS = document.getElementById("totalPriceHidden");
        var sum = 0
        // Array.from(ePrice).length;
        Array.from(e).forEach((e1) => {
            // alert(e1.value);
            var price = isNaN(Number.parseFloat(e1.innerHTML)) ? 0 : Number.parseFloat(e1.innerHTML);
            sum += price;
        });
        // alert(sum);
        if (isNaN(sum)) sum = 0
        rs.innerHTML = new Intl.NumberFormat("vi-VN").format(sum) + "đ";
        hiddenRS.innerHTML = sum;
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
    function addProduct(element) {
        // alert(element.parentNode.id);
        var product_id = element.parentNode.id.split('_')[1];
        let url_product_id;
        if (document.getElementById("hidden-new_product_id").value == "")
            url_product_id = document.getElementById("hidden-new_product_id").value += `_${product_id}_`;
        else
            url_product_id = document.getElementById("hidden-new_product_id").value += `${product_id}_`;

        // alert(document.getElementById("hidden-new_product_id").value);
        const params = new URLSearchParams(window.location.search);
        params.set("product_id", url_product_id);
        window.location.href = `index.php?${params.toString()}`;
        return;
    }

    function showAdditionalProduct() {}


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

    function deleteDetail(product_id, detail_id) {
        let list_products = document.getElementById("hidden-new_product_id");
        // alert("Giá trị ban đầu: " + list_products.value);

        // Tách chuỗi thành mảng, bỏ phần rỗng
        let array = list_products.value.split("_").filter(item => item !== "");

        // Xóa phần tử theo index (vị trí)
        if (detail_id >= 0 && detail_id - 1 < array.length) {
            array.splice(detail_id - 1, 1); // xóa 1 phần tử tại vị trí detail_id
        }

        // Nếu mảng còn phần tử, ghép lại thành chuỗi với dấu _
        let newString = array.length > 0 ? "_" + array.join("_") + "_" : "";

        // alert("Sau khi xóa: " + newString);

        // Cập nhật lại input (nếu cần)
        list_products.value = newString;

        const params = new URLSearchParams(window.location.search);
        params.set("product_id", newString);
        window.location.href = `index.php?${params.toString()}`;


        // alert("hello");
        // let tr = document.getElementById("detail_id_" + id);
        // tr.style.display = 'none';
        // var delete_detail = document.getElementById("hidden-delete_detail_id");
        // delete_detail.value += '_';
        // delete_detail.value += id;
        // alert(delete_detail.value);
        // alert(product_id);
        // alert(detail_id);
        // const params = new URLSearchParams(window.location.search);
        // var new_detail = document.getElementById("hidden-new_detail_id");
        // new_detail.value += '_';
        // params.set("status", "cancelUpdate");
        // params.set("new_detail_id", new_detail.value);
        // window.location.href = `index.php?${params.toString()}`;
    }

    function updateInvoice() {
        var delete_detail = document.getElementById("hidden-delete_detail_id");
        const params = new URLSearchParams(window.location.search);
        params.set("delete_detail_id", delete_detail.value);
        params.set("status", "update");
        window.location.href = `index.php?${params.toString()}`;
    }

    function cancelAdd() {
        // const params = new URLSearchParams(window.location.search);
        // params.delete('option');
        // return;
        let e = document.getElementById("hidden-current");
        window.location.href = `index.php?page=import_invoice&current=` + e.value;
    }
</script>

<script>
    function updateProduct() {
        var table = document.getElementById('table_add');
        var rows = table.getElementsByTagName('tr');
        var importDetails = [];
        const now = new Date(); // Lấy đối tượng Date hiện tại
        const formattedDateTime = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')} `; // Định dạng theo YYYY-MM-DD HH:mm:ss (ví dụ)

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            // Lấy dữ liệu từ các cột trong mỗi dòng
            var product_id = row.getElementsByTagName('td')[2].textContent; // Ví dụ: lấy tên sản phẩm
            var sizeSelect = row.querySelector('select[class^="active"]');
            var size = sizeSelect ? sizeSelect.value : '';
            var quantityInput = row.querySelector('input[id^="num"]');
            var quantity = quantityInput ? parseInt(quantityInput.value) : 0;
            var priceInput = row.querySelector(`input[name="update_price_${i+1}"]`);
            var price = priceInput ? parseFloat(priceInput.value) : 0;

            importDetails.push({
                productID: product_id, // Hoặc productId nếu có
                size: size,
                quantity: quantity,
                price: price,
            });
        }
        var sendData = {
            provider: document.getElementById("infoProvider").value,
            // date: formattedDateTime,
            // provider: providerValue,
            importDetails: importDetails,
            total: document.getElementById("totalPriceHidden").innerHTML,
        };
        // Gửi dữ liệu importDetails đến server bằng AJAX
        sendDataToServer(sendData);

        showSuccessfulAlert("Đã thêm phiếu nhập thành công!");
        var current = document.getElementById("hidden-current");
        setTimeout(function() {
            window.location.href = `index.php?page=import_invoice&current=${current.value}`;
        }, 1000); // Đợi 2 giây trước khi chuyển hướng
    }

    function sendDataToServer(data) {
        console.log("Dữ liệu chuẩn bị gửi đi:", data); // Thêm dòng này

        var xhr = new XMLHttpRequest();
        var url = "./process/process_import.php";

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Phản hồi từ server:", xhr.responseText); // Xem phản hồi từ PHP
                // Xử lý phản hồi từ server (nếu cần)
            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                console.error("Lỗi request:", xhr.status, xhr.statusText);
            }
        };

        var jsonData = JSON.stringify(data);
        xhr.send(jsonData);
    }
</script>

<script>
    function filterByDate() {
        let dateInput1 = document.getElementById("date1");
        let dateInput2 = document.getElementById("date2");
        // alert(dateInput1.value);
        // alert(dateInput2.value);
        if (dateInput1.value == "" && dateInput2.value != "") {
            showFailedAlert("Bạn cần nhập đầy đủ thông tin!");
            dateInput1.focus();
            return;
        }

        if (dateInput2.value == "" && dateInput1.value != "") {
            showFailedAlert("Bạn cần nhập đầy đủ thông tin!");
            dateInput2.focus();
            return;
        }

        if (dateInput1.value != "" && dateInput2.value != "") {
            let date1 = new Date(dateInput1.value);
            let date2 = new Date(dateInput2.value);
            if (date1 > date2) {
                showFailedAlert("Khoảng thời gian không hợp lệ!");
                dateInput2.focus();
                return;
            }
            // alert(dateInput1.value);
            // alert(dateInput2.value);
            // const params = new URLSearchParams(window.location.search);
            // params.set("date1", delete_detail.value);
            // params.set("status", "update");
            let current = document.getElementById("hidden-current");
            window.location.href = `index.php?page=import_invoice&current=1&date1=${dateInput1.value}&date2=${dateInput2.value}`;
            return;
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // checkFormFilter();
        let date1 = document.getElementById("hidden-date1");
        let date2 = document.getElementById("hidden-date2");

        var innerText = "";

        if (date1.value != "") {
            innerText +=
                `
                    <div>Từ ngày: &nbsp;<span>${formatDate(date1.value)}</span></div>
                    <div>Đến ngày: &nbsp;<span>${formatDate(date2.value)}</span></div>
                `;
        }
        // alert(innerText);
        if (innerText == "") return;

        let filterResult = document.getElementsByClassName('filter-result')[0];
        filterResult.style.display = 'block';
        filterResult.innerHTML = `  
                <a href='index.php?page=import_invoice' class='btn-option success'>  
                    <div>${innerText}</div>   
                    <div><button type='button' class='btn'>x</button>  </div>
                </a>  
            `;

        // Gán sự kiện cho nút x  
        filterResult.querySelector('.btn').addEventListener('click', function() {
            filterResult.style.display = 'none';
        });
    });

    function formatDate(inputDate) {
        if (!inputDate) {
            return ""; // Trả về chuỗi rỗng nếu input rỗng
        }

        const parts = inputDate.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = parts[1];
            const day = parts[2];
            return `${day}/${month}/${year}`;
        } else {
            return "Định dạng không hợp lệ"; // Hoặc bạn có thể xử lý lỗi khác
        }
    }
</script>