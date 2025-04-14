<link rel="stylesheet" href="./css/product.css">
<script src="./js/product.js"></script>
<!-- <script src="./connectDatabase/DB_product.php"></script> -->
<?php
include "./connectDatabase/DB_product.php";
$dbProduct = new DB_product();
// global $category_id;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : "";
$brand = isset($_GET['brand']) ? $_GET['brand'] : "";
$price1 = isset($_GET['price1']) ? $_GET['price1'] : "";
$price2 = isset($_GET['price2']) ? $_GET['price2'] : "";
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : "";
$data = $dbProduct->getAllProductDB($category_id, $brand, $price1, $price2);
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
// $endIndex = min($startIndex + $perPage, $totalItems);

?>

<input type="text" name="hidden-name" value="<?php echo htmlspecialchars($_GET['product_name'] ?? ''); ?>" class="hidden-name" hidden>
<input type="text" name="hidden-brand" value="<?php echo htmlspecialchars($_GET['brand'] ?? ''); ?>" class="hidden-brand" hidden>
<input type="text" name="hidden-category" value="<?php echo htmlspecialchars($_GET['category_id'] ?? ''); ?>" class="hidden-category" hidden>
<input type="text" name="hidden-price1" value="<?php echo htmlspecialchars($_GET['price1'] ?? ''); ?>" class="hidden-price1" hidden>
<input type="text" name="hidden-price2" value="<?php echo htmlspecialchars($_GET['price2'] ?? ''); ?>" class="hidden-price2" hidden>

<div class="product container-content">
    <div class="head-content">
        <div class="heading">Sản phẩm</div>
        <div class="addition-btn">
            <button class="btn-add">Thêm sản phẩm</button>
        </div>
    </div>

    <div class="mid-content">
        <div style="display:flex; flex-direction: row; justify-content: space-between">
            <div class="has-drop-down-menu filter" onclick="handleFilterMenu(this)">
                <button class="input" id="loc">
                    <i class="fa-solid fa-filter"></i>
                    <span>Lọc</span>
                </button>
                <form action="" method="$_GET">
                    <div class="drop-down-menu">
                        <div class="category filter-content">
                            <h3>Loại</h3>
                            <div>
                                <span>Quần áo CLB</span>
                                <span><input type="radio" name="raType" id="ra1" value=1></span>
                            </div>
                            <div>
                                <span>Quần áo đội tuyển quốc gia</span>
                                <span><input type="radio" name="raType" id="ra2" value=2></span>
                            </div>
                            <div>
                                <span>Quần áo bóng đá trẻ em</span>
                                <span><input type="radio" name="raType" id="ra3" value=3></span>
                            </div>

                            <div>
                                <span>Giày cỏ nhân tạo</span>
                                <span><input type="radio" name="raType" id="ra4" value=4></span>
                            </div>
                            <div>
                                <span>Giày cỏ tự nhiên</span>
                                <span><input type="radio" name="raType" id="ra5" value=5></span>
                            </div>
                            <div>
                                <span>Giày bóng đá trẻ em</span>
                                <span><input type="radio" name="raType" id="ra6" value=6></span>
                            </div>

                        </div>
                        <div class="brand filter-content">
                            <h3>Thương hiệu</h3>
                            <div>
                                <span>Nike</span>
                                <span><input type="radio" name="raBrand" id="raNike" value="Nike"></span>
                            </div>
                            <div>
                                <span>Adidas</span>
                                <span><input type="radio" name="raBrand" id="raAdidas" value="Adidas"></span>
                            </div>
                            <div>
                                <span>Puma</span>
                                <span><input type="radio" name="raBrand" id="raPuma" value="Puma"></span>
                            </div>
                        </div>
                        <div class="price">
                            <h3>Giá tiền</h3>
                            <input type="text" name="formattedPrice1" id="formattedPrice1" placeholder="Chọn giá tiền" oninput="formatCurrency(this, 'price1')">
                            <input type="text" name="price1" id="price1" hidden>
                            <input type="text" name="formattedPrice2" id="formattedPrice2" placeholder="Chọn giá tiền" oninput="formatCurrency(this, 'price2')">
                            <input type="text" name="price2" id="price2" hidden>
                        </div>
                        <input type="text" name="product" value="product" hidden>
                        <div class="confirm"><button class="btn-success" type="button" onclick="linkFormFilter()">Xác nhận</button>
                        </div>
                        <div class="confirm" hidden><button class="btn-success" type="reset">Xóa</button></div>
                    </div>
                </form>
            </div>
            <div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center;">
                <input class="input" type="text" id="search-product" name="search-product" placeholder="Tìm kiếm sản phẩm">
                <button style="margin-left: 5px; padding: 10px 8px; cursor: pointer; background-color: #eb6532; border: 0; border-radius: 5px;" type="button" onclick="searchProduct()"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>

        <div class="table">
            <div class="filter-result"></div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    $new_data = $dbProduct->getProductInRange($startIndex, $perPage, $category_id, $brand, $price1, $price2);
                    $category = "";
                    /// Lấy ra bảng loại
                    $result = $dbProduct->getTypeProduct($category_id);
                    if (isset($_GET['product_name'])) {
                        $new_data = $dbProduct->getProductByName($startIndex, $perPage, $_GET['product_name']);
                        $totalItems = count($dbProduct->getAllProductByName($_GET['product_name']));
                        $totalPage = ceil($totalItems / $perPage);
                        // printJS($totalItems);
                        // printJS($totalPage);
                    }
                    if (isset($_GET['category_id']) && count($result) > 0) {
                        $category = $dbProduct->getTypeProduct($category_id)[0]['name'];
                    }
                    if (is_array($new_data) && !empty($new_data)) {
                        foreach ($new_data as $key => $row) {
                            $img = $dbProduct->getImagesProduct($row['id']);
                            $img_src = isset($img['img1']) ? "./" . $img['img1'] : "./assets/img/img_product/default_img.jpeg";
                            if (empty($img)) {
                                $img = [
                                    'img1' => "./assets/img/img_product/default_img.jpeg",
                                    'img2' => "./assets/img/img_product/default_img.jpeg",
                                    'img3' => "./assets/img/img_product/default_img.jpeg",
                                    'img4' => "./assets/img/img_product/default_img.jpeg",
                                    'img5' => "./assets/img/img_product/default_img.jpeg"
                                ];
                            }
                            // $img = "./" . $img;

                            // Hiển thị ảnh (sử dụng ảnh đầu tiên nếu chỉ có 1 ảnh, hoặc lặp qua tất cả ảnh nếu có nhiều hơn)
                            $quantity_all = isset($row['quantity']) ? $row['quantity'] : 0;
                            // printJS($img_src);
                            echo "
                        <tr>  
                            <td>" . $row['id'] . "</td>  
                            <td><img src='" . $img_src . "' alt=''></td>  
                            <td>" . $row['name'] . "</td>  
                            <td>" . number_format($row['price'], 0, ',', '.') . "đ</td>  
                            <td>" . $quantity_all . "</td>  
                            <td>  
                                <div class='flex-row-space-evenly'>  
                                    <a href='index.php?page=product&current=" . $currentPage . "&category_id=$category_id&brand=$brand&price1=$price1&price2=$price2&product_id=" . $row['id'] . "&option=update' class='btn-option warning-text' style='background-color: #fff2cf;''>  
                                        <i class='fa-regular fa-pen-to-square'></i>     
                                    </a> 
                                    <a href='index.php?page=product&current=" . $currentPage . "&category_id=$category_id&brand=$brand&price1=$price1&price2=$price2&product_id=" . $row['id'] . "&option=delete' class='btn-option wrong'>  
                                        <i class='fa-regular fa-trash-can'></i>   
                                    </a> 
                                    <a href='index.php?page=product&current=" . $currentPage . "&category_id=$category_id&brand=$brand&price1=$price1&price2=$price2&product_id=" . $row['id'] . "&option=detail' class='btn-option success'>  
                                        <i class='fa-solid fa-ellipsis'></i>  
                                    </a> 
                                </div>  
                            </td>  
                        </tr>";
                        }
                    } else {
                        echo "<tr><td colspan=6 style=\"color:#eb6532\">Không có sản phẩm nào</td></tr>";
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
            if (isset($_GET['product_name'])) {
                echo "<a href=\"index.php?page=product&current=$i&product_name=$product_name\"";
            }
            echo "<a href=\"index.php?page=product&current=$i&category_id=$category_id&brand=$brand&price1=$price1&price2=$price2\" class='page-link $activeClass'>" . $i . "</a>";
        }
        echo "</div>";

        ?>
    </div>
</div>

<!-- XU LY CAC NUT TUY CHINHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
    $id = intval($_GET['product_id']); // Get the value of 'id' from the URL
    $option = isset($_GET['option']) ? $_GET['option'] : "";
    if ($option == 'detail') {
        $data1 = $dbProduct->getAllDetailProduct($id);
        if (empty($data1)) {
            die("Không tìm thấy chi tiết sản phẩm với ID: " . $id);
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
        if (isset($_GET['new_name'])) {
            updateProduct($dbProduct);
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
        $data1 = $dbProduct->getAllDetailProduct($id);
        if (empty($data1)) {
            die("Không tìm thấy chi tiết sản phẩm với ID: " . $id);
        }

        if (is_array($data1) && !empty($data1)) {
            $html_code = html_update($data1);
        } else {
            echo "<script>alert(\"Không thấy thông tin\")</script>";
        }
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
        <div class="product-delete">
            <div>
                <button class="close-detail" type="button" onclick="closeFakeBG1(this)">
                x
                </button>
            </div>
            <div class="content-delete">
                <p>
                Sản phẩm này sẽ được xóa khỏi website, bạn chắc chắn muốn xóa sản phẩm
                này?
                </p>
            </div>
            <div class="btn">
                <button type="button" onclick="closeFakeBG1(this)">Hủy</button>
                <a href=\'index.php?page=product&current=' . $_GET['current'] . '&product_id=' . $_GET['product_id'] . '&option=delete&isDeleted=true\'
                    <button id="btn-delete-product" type="button" onclick="closeFakeBG1(this)">Xóa sản phẩm</button> 
                </a> 
            </div>
        </div>
        ';
    return $html_code;
}

function html_detail($data1)
{
    $data = new DB_product();
    $row_tmp = $data->getProductByID($_GET['product_id']);
    $img = $data->getImagesProduct($_GET['product_id']);
    // $category_tmp = $row_tmp[0]['type_id'];
    /// Mới thêm sản phẩm mới
    $flag_newProduct = 0;
    if (empty($img)) {
        $img = [
            'img1' => "./assets/img/img_product/default_img.jpeg",
            'img2' => "./assets/img/img_product/default_img.jpeg",
            'img3' => "./assets/img/img_product/default_img.jpeg",
            'img4' => "./assets/img/img_product/default_img.jpeg",
            'img5' => "./assets/img/img_product/default_img.jpeg"
        ];
        // $data->initDetailProduct($_GET['product_id'], $_GET['ca']);
        // $data1 = $data->getAllDetailProduct($_GET['product_id']);
    }

    // Hiển thị ảnh (sử dụng ảnh đầu tiên nếu chỉ có 1 ảnh, hoặc lặp qua tất cả ảnh nếu có nhiều hơn)
    $img1_src = isset($img['img1']) ? $img['img1'] : "./assets/img/img_product/default_img.jpeg";
    $img2_src = isset($img['img2']) ? $img['img2'] : "./assets/img/img_product/default_img.jpeg";
    $img3_src = isset($img['img3']) ? $img['img3'] : "./assets/img/img_product/default_img.jpeg";
    $img4_src = isset($img['img4']) ? $img['img4'] : "./assets/img/img_product/default_img.jpeg";
    $img5_src = isset($img['img5']) ? $img['img5'] : "./assets/img/img_product/default_img.jpeg";
    // echo "
    // <script>alert('" . $imgProduct['img1'] . "')</script>
    // ";
    $flag = 0;
    $html_code = '
    <div class="product-detail">
      <div><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this)\">x</button></div>
      <h1>Chi tiết sản phẩm</h1>
      <div class="content-detail">
        <div class="show-img">
          <div>
            <img class="main-img"
              src="' . $img1_src . '"
              alt="hinhTo"
            />
          </div>
          <div class="total-img">
            <img class="active detail-img  photo1" onclick="showPhoto(this)"
              src="' . $img1_src . '"
              alt="hinh1"
            />
            <img class="detail-img photo2" onclick="showPhoto(this)"
              src="' . $img2_src . '"
              alt="hinh2"
            />
            <img class="detail-img photo3" onclick="showPhoto(this)"
              src="' . $img3_src . '"
              alt="hinh3"
            />
            <img class="detail-img photo4" onclick="showPhoto(this)"
              src="' . $img4_src . '"
              alt="hinh4"
            />
            <img class="detail-img photo5" onclick="showPhoto(this)"
              src="' . $img5_src . '"
              alt="hinh5"
            />
          </div>
        </div>
        <div class="show-info">
          <h2>' . $data1[0]['name'] . '</h2>
          <div class="id_type">
            <div>Mã sản phẩm: <span>' . $data1[0]['id'] . '</span></div>
            <div>Phân loại: <span>' . $data1[0]['type'] . '</span></div>
          </div>
          <div class="brand">Thương hiệu: <span>' . $data1[0]['brand'] . '</span></div>
          <div class="price">Giá sản phẩm: <span>' . number_format($data1[0]['price'], 0, ',', '.') . '</span></div>
          <div class="size">
            <div>Size:</div>
        ';
    foreach ($data1 as $key => $row) {
        // $flag_newProduct = isset($row['size']) ? 1 : 0;
        if ($img1_src == "./assets/img/img_product/default_img.jpeg") {
            // printJS("hello");
            $sizeCode = initSize($row['type_id']);
            $html_code .= $sizeCode;
            break;
        } else {
            if ($flag == 0) {
                $flag = 1;

                $html_code .=
                    '
                <div class="size-btn-container">
                    <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                    <button type="button" class="size-btn" onclick="showSize(this)">' . $row['size'] . '</button type="button">
                    ';
            } else {
                $html_code .= '
                    <button type="button" class="size-btn" onclick="showSize(this)">' . $row['size'] . '</button type="button">
                ';
            }
        }
    }
    $flag = 0;
    foreach ($data1 as $key => $row) {
        $quantity_all = isset($row_tmp[0]['quantity']) ? $row_tmp[0]['quantity'] : 0;
        $quantity = isset($row['quantity']) ? $row['quantity'] : 0;
        if ($img1_src == "./assets/img/img_product/default_img.jpeg") {
            $html_code .= '
                </div>
            </div>
            <div class="stock size-all">Kho: <span>' . $quantity_all . '</span></div>';
            break;
        } else {
            if ($flag == 0) {
                $flag = 1;
                $html_code .= '
                </div>
            </div>
            <div class="stock size-all">Kho: <span>' . $quantity_all . '</span></div>
            <div class="stock size-' . $row['size'] . '" hidden>Kho: <span>' . $quantity . '</span></div>
                ';
            } else {
                $html_code .=
                    '
                <div class="stock size-' . $row['size'] . '" hidden>Kho: <span>' . $quantity . '</span></div>
                ';
            }
        }
    }
    $html_code .= '
        </div>
      </div>
    </div>
        ';
    return $html_code;
}

function html_update($data1)
{
    $data = new DB_product();
    $row_tmp = $data->getProductByID($_GET['product_id']);
    $flag = 0;
    $img = $data->getImagesProduct($_GET['product_id']);

    if (empty($img)) {
        $img = [
            'img1' => "./assets/img/img_product/default_img.jpeg",
            'img2' => "./assets/img/img_product/default_img.jpeg",
            'img3' => "./assets/img/img_product/default_img.jpeg",
            'img4' => "./assets/img/img_product/default_img.jpeg",
            'img5' => "./assets/img/img_product/default_img.jpeg"
        ];
    }

    // Hiển thị ảnh (sử dụng ảnh đầu tiên nếu chỉ có 1 ảnh, hoặc lặp qua tất cả ảnh nếu có nhiều hơn)
    $img1_src = isset($img['img1']) ? $img['img1'] : "./assets/img/img_product/default_img.jpeg";
    $img2_src = isset($img['img2']) ? $img['img2'] : "./assets/img/img_product/default_img.jpeg";
    $img3_src = isset($img['img3']) ? $img['img3'] : "./assets/img/img_product/default_img.jpeg";
    $img4_src = isset($img['img4']) ? $img['img4'] : "./assets/img/img_product/default_img.jpeg";
    $img5_src = isset($img['img5']) ? $img['img5'] : "./assets/img/img_product/default_img.jpeg";

    $html_code = '      
    <div class="product-update">
        <div><button class=\"close-detail\" type=\"button\" onclick=\"closeFakeBG1(this)\">x</button></div>
      <h1>Chỉnh sửa sản phẩm</h1>
      <div class="content-update">
        <div class="show-img">
          <div>
            <div>
              <img
                class="main-img"
                src="' . $img1_src . '"
                alt="hinhTo"
              />
            </div>
            <div
              style="
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
              "
            >
              <div class="total-img">
                <img
                  class="active update-img photo1"
                  onclick="showPhoto(this)"
                  src="' . $img1_src . '"
                  alt="hinh1"
                />
                <img
                  class="update-img photo2"
                  onclick="showPhoto(this)"
                  src="' . $img2_src . '"
                  alt="hinh2"
                />
                <img
                  class="update-img photo3"
                  onclick="showPhoto(this)"
                  src="' . $img3_src . '"
                  alt="hinh3"
                />
                <img
                  class="update-img photo4"
                  onclick="showPhoto(this)"
                  src="' . $img4_src . '"
                  alt="hinh4"
                />
                <img
                  class="update-img photo5"
                  onclick="showPhoto(this)"
                  src="' . $img5_src . '"
                  alt="hinh5"
                />
              </div>
            </div>
          </div>
            <input type="hidden" id="selected-image-index" name="selected-image-index" value = "1">
          <div>
            <div>
              <img
                class="main-img"
                id="second-main-img"
                src="./assets/img/img_product/default_img.jpeg"
                alt="hinhTo"
              />
            </div>
            <div style="padding-left: 20px">
              <input
                type="file"
                id="image-input"
                accept="image/*"
                onchange="updateImage()"
              />
            </div>
          </div>
        </div>
        <div class="show-info">
          <div><input style="width: 400px; margin: 5px 0;" type="text" id="update-name" name="update-name" value="' . $data1[0]['name'] . '" /></div>
          <div class="id_type">
            <div>Mã sản phẩm: <span>' . $data1[0]['id'] . '</span></div>
            <div>Phân loại:
                <select name="update-type">
                    <option value="1" ' . ($data1[0]['type_id'] == 1 ? 'selected' : '') . '>Quần áo CLB</option>
                    <option value="2" ' . ($data1[0]['type_id'] == 2 ? 'selected' : '') . '>Quần áo đội tuyển quốc gia</option>
                    <option value="3" ' . ($data1[0]['type_id'] == 3 ? 'selected' : '') . '>Quần áo bóng đá trẻ em</option>
                    <option value="4" ' . ($data1[0]['type_id'] == 4 ? 'selected' : '') . '>Giày cỏ nhân tạo</option>
                    <option value="5" ' . ($data1[0]['type_id'] == 5 ? 'selected' : '') . '>Giày cỏ tự nhiên</option>
                    <option value="6" ' . ($data1[0]['type_id'] == 6 ? 'selected' : '') . '>Giày bóng đá trẻ em</option>
                </select>
            </div>
          </div>
          <div class="brand">Thương hiệu:
                <select name="update-brand">
                    <option value="1" ' . ($data1[0]['brand'] == "Nike" ? 'selected' : '') . '>Nike</option>
                    <option value="2" ' . ($data1[0]['brand'] == "Adidas" ? 'selected' : '') . '>Adidas</option>
                    <option value="3" ' . ($data1[0]['brand'] == "Puma" ? 'selected' : '') . '>Puma</option>
                </select>
          </div>
          <div class="price">Giá sản phẩm: 
            <input type="text" id="fmt-update-price" name="fmt-update-price" value="' . number_format($data1[0]['price'], 0, ',', '.') . '" oninput="formatCurrency(this, \'update-price\')"/>
            <input type="text" id="update-price" name="update-price" value="' . $data1[0]['price'] . '" hidden/>
          </div>
        </div>
          <div class="btn">
                <button type="button" onclick="closeFakeBG1(this)">Hủy</button>
                <button id="btn-update-product" type="button" onclick="updateProduct(), closeFakeBG1(this)">Xác nhận </button> 
            </div>
      </div>
    </div>';
    return $html_code;
}
?>

<!-- XOAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['isDeleted'])) {
    $id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    // echo "
    // <script>
    //     alert(" . $id . ");
    // </script>";
    $dbProduct->deleteProduct($id);
    echo "
        <script>
            window.onload = function() {
                // let table = document.getElementsByClassName(\"fakebg1\")[0];
                // table.style.display = \"none\";
                showSuccessfulAlert(`Xóa sản phẩm thành công`);
                setTimeout(function() {
                    window.location.href = 'index.php?page=product&current=" . $_GET['current'] . "';
                }, 1000); // Đợi 2 giây trước khi chuyển hướng
            }
        </script>";
}
?>

<!-- CHINH SUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA  -->
<?php
function updateProduct($dbProduct)
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['new_name']) && isset($_GET['new_brand']) && isset($_GET['new_category_id']) && isset($_GET['new_price'])) {
        $id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        $result = $dbProduct->updateProduct($id, $_GET['new_name'], $_GET['new_category_id'], $_GET['new_brand'], $_GET['new_price']);
        // $result_img = $dbProduct->updateProductImage($id, $_GET['img_index'], $_GET['new_img']);
        if ($result) {
            // Cập nhật thành công, clear URL và chuyển hướng
            echo "<script>
                    const params = new URLSearchParams(window.location.search);
                    params.set('page', 'product');
                    params.delete('option');
                    params.delete('new_name');
                    params.delete('new_category_id');
                    params.delete('new_brand');
                    params.delete('new_price');
                    window.location.href = 'index.php?' + params.toString();
                    showSuccessfulAlert('Sửa sản phẩm thành công');
                </script>";
        } else {
            // Cập nhật thất bại, hiển thị thông báo lỗi
            echo "<script>
                    showFailedAlert('Lỗi khi sửa sản phẩm');
                </script>";
        }
    }
}

function printJS($mess)
{
    echo "
        <script>alert('" . $mess . "')</script>
        ";
}

function initSize($category)
{
    $code = "";
    switch ($category) {
        case 1:
        case 2:
            $code .= '
                <div class="size-btn-container">
                <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">S</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">M</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">L</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">XL</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">XXL</button type="button">
                ';
            break;
        case 3:
            $code .= '
                <div class="size-btn-container">
                <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">XS</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">S</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">M</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">L</button type="button">
                ';
            break;
        case 4:
        case 5:
            $code .= '
                <div class="size-btn-container">
                <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">38</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">39</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">40</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">41</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">42</button type="button">
                ';
            break;
        case 6:
            $code .= '
                <div class="size-btn-container">
                <button type="button" class="size-btn active" onclick="showSize(this)">Tất cả</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">28</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">30</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">32</button type="button">
                <button type="button" class="size-btn" onclick="showSize(this)">34</button type="button">
                ';
            break;
    }
    return $code;
}
?>

<!-- THÊM SẢN PHẨMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['add_name'])) {
    $add_name = $_GET['add_name'];
    $add_category = $_GET['add_category_id'];
    $add_brand = $_GET['add_brand'];
    $add_price = $_GET['add_price'];
    $dbProduct->addProduct($add_name, $add_category, $add_brand, $add_price);
    printJS("document.addEventListener('DOMContentLoaded', function() {
        window.location.href = 'index.php?page=product&current=1';});");
}
?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let category = document.getElementsByName("hidden-category")[0];
        let brand = document.getElementsByName("hidden-brand")[0];
        let price1 = document.getElementsByName("hidden-price1")[0];
        let price2 = document.getElementsByName("hidden-price2")[0];
        let name = document.getElementsByName("hidden-name")[0];

        // checkFormFilter();

        var innerText = "";
        if (category.value != "") {
            innerText +=
                `
                    <div>Loại: &nbsp;<span><?php echo $category ?></span></div>
                `;
        }
        if (brand.value != "") {
            innerText +=
                `
                    <div>Thương hiệu: &nbsp;<span>${brand.value}</span></div>
                `;
        }


        if (price1.value != "") {
            innerText +=
                `
                    <div>Giá: &nbsp;<span>${formatMoney(price1)}đ - ${formatMoney(price2)}đ</span></div>
                `;
        }

        if (name.value != "") {
            innerText +=
                `
                    <div>Tìm kiếm: &nbsp;<span>${name.value}</span></div>
                `;
        }

        // alert(innerText);

        let filterResult = document.getElementsByClassName('filter-result')[0];


        if (category.value == "" && brand.value == "" && price1.value == "" && name.value == "") {
            filterResult.style.display = 'none';
            // alert("hello");
            return;
        }

        filterResult.style.display = 'block';
        filterResult.innerHTML = `  
                <a href='index.php?page=product' class='btn-option success'>  
                    <div>${innerText}</div>   
                    <div><button type='button' class='btn'>x</button>  </div>
                </a>  
            `;

        // Gán sự kiện cho nút x  
        filterResult.querySelector('.btn').addEventListener('click', function() {
            filterResult.style.display = 'none';
        });
    });
</script>



<!-- Hiển thị chi tiết sản phẩm -->
<div class="fakebg1"></div>


<!-- Dialog ẩn mặc định -->
<dialog id="addDialog">
    <h2>Thêm sản phẩm mới</h2>
    <form id="Form">
        <label for="add_productName">Tên sản phẩm:</label>
        <input type="text" id="add_productName" name="add_productName" required>

        <label for="add_category">Phân loại:</label>
        <select name="add_category" id="add_category">
            <option value="1">Quần áo CLB</option>
            <option value="2">Quần áo đội tuyển quốc gia</option>
            <option value="3">Quần áo bóng đá trẻ em</option>
            <option value="4">Giày cỏ nhân tạo</option>
            <option value="5">Giày cỏ tự nhiên</option>
            <option value="6">Giày bóng đá trẻ em</option>
        </select>

        <label for="add_brand">Thương hiệu</label>
        <select name="add_brand" id="add_brand">
            <option value="1">Nike</option>
            <option value="2">Adidas</option>
            <option value="3">Puma</option>
        </select>

        <label for="format_add_price">Giá tiền</label>
        <input type="text" id="format_add_price" name="format_add_price" required oninput="formatCurrency(this, 'add_price')">
        <input type="text" id="add_price" name="add_price" hidden>

        <!-- <label for="email">Email</label>
        <input type="text" id="email" name="email" required> -->

        <div class="dialog-buttons">
            <button type="button" onclick="closeDialog()">Hủy</button>
            <button type="submit_add" onclick="addProduct()">Lưu</button>
        </div>
    </form>
</dialog>

<script>
    function addProduct() {
        const productName = document.getElementById("add_productName").value;
        // alert(productName);
        const productType = document.getElementById('add_category').value;
        // alert(productType);
        const productBrand = document.getElementById('add_brand').value;
        // alert(productBrand);
        const productPrice = document.getElementById('add_price').value;

        // <?php
            //     $dbProduct->updateProductImage($_GET['id'])
            // 
            ?>
        var newBrand = ""
        if (productBrand == 1) newBrand = "Nike";
        if (productBrand == 2) newBrand = "Adidas";
        if (productBrand == 3) newBrand = "Puma";
        // const formattedPrice = document.getElementById("fmt-update-price").value;
        // const productPrice = document.getElementById("update-price").value; // Giá trị đã được xử lý

        // Kiểm tra xem các trường có được điền đầy đủ hay không
        if (!productName || !productType || !productBrand || !productPrice) {
            showFailedAlert("Vui lòng điền đầy đủ thông tin sản phẩm.");
            return; // Ngăn việc thực thi tiếp nếu thông tin bị thiếu
        }

        // Tạo đối tượng chứa dữ liệu sản phẩm
        const productData = {
            add_name: productName,
            add_category_id: productType,
            add_brand: newBrand,
            add_price: productPrice,
        };

        // Tạo tham số URL để gửi dữ liệu cập nhật
        const params = new URLSearchParams(window.location.search);
        params.set('page', 'product');
        params.delete('option');

        // Thêm dữ liệu sản phẩm vào tham số URL
        for (const key in productData) {
            params.set(key, productData[key]);
        }

        // Cập nhật URL và chuyển hướng
        window.location.href = `index.php?${params.toString()}`;
    }
</script>


<script>
    function updateProduct() {
        const productName = document.getElementById("update-name").value;
        // alert(productName);
        const productType = document.querySelector('select[name="update-type"]').value;
        // alert(productType);
        const productBrand = document.querySelector('select[name="update-brand"]').value;
        // alert(productBrand);
        const selectedImgIndex = document.querySelector("#selected-image-index").value;
        // const selectedImage = document.getElementById("second-main-img").src;

        // <?php
            //     $dbProduct->updateProductImage($_GET['id'])
            // 
            ?>
        var newBrand = ""
        if (productBrand == 1) newBrand = "Nike";
        if (productBrand == 2) newBrand = "Adidas";
        if (productBrand == 3) newBrand = "Puma";
        const formattedPrice = document.getElementById("fmt-update-price").value;
        const productPrice = document.getElementById("update-price").value; // Giá trị đã được xử lý

        // Kiểm tra xem các trường có được điền đầy đủ hay không
        if (!productName || !productType || !productBrand || !productPrice) {
            showFailedAlert("Vui lòng điền đầy đủ thông tin sản phẩm.");
            return; // Ngăn việc thực thi tiếp nếu thông tin bị thiếu
        }

        // Tạo đối tượng chứa dữ liệu sản phẩm
        const productData = {
            new_name: productName,
            new_category_id: productType,
            new_brand: newBrand,
            new_price: productPrice,
            img_index: selectedImgIndex,
            // new_img: selectedImage,
        };

        // Tạo tham số URL để gửi dữ liệu cập nhật
        const params = new URLSearchParams(window.location.search);
        params.set('page', 'product');
        params.set('option', 'update'); // Thêm option để server biết đây là cập nhật
        params.set('product_id', <?php echo $_GET['product_id'] ?? ""; ?>); // Lấy ID sản phẩm từ PHP

        // Thêm dữ liệu sản phẩm vào tham số URL
        for (const key in productData) {
            params.set(key, productData[key]);
        }

        // Cập nhật URL và chuyển hướng
        window.location.href = `index.php?${params.toString()}`;
    }
</script>

<script>
    // Lấy phần tử dialog
    const addDialog = document.getElementById("addDialog");
    const addButton = document.querySelector(".btn-add");

    // Khi nhấn nút thêm, hiển thị dialog
    addButton.addEventListener("click", () => {
        addDialog.showModal();
    });

    // Đóng dialog khi nhấn nút "Hủy"
    function closeDialog() {
        addDialog.close();
    }

    // Xử lý sự kiện khi form trong dialog được submit
    document.getElementById("Form").addEventListener("submit", function(e) {
        e.preventDefault(); // Ngăn chặn reload trang

        // Lấy dữ liệu từ form
        const name = document.getElementById("add_productName").value;
        const category = document.getElementById("add_category").value;
        const brand = document.getElementById("add_brand").value;
        const price = document.getElementById("add_price").value;

        // In ra console hoặc thực hiện logic lưu
        console.log({
            name,
            category,
            brand,
            price,
        });

        // Sau khi lưu, đóng dialog
        closeDialog();

        // Reset form
        this.reset();
    });
</script>

<?php include "layout/alert/alert.php" ?>