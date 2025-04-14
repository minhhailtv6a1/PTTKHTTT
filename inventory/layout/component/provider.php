<link rel="stylesheet" href="./css/provider.css">
<script src="./js/provider.js"></script>
<?php
include "./connectDatabase/DB_provider.php";
$dbProvider = new DB_provider();
// global $category_id;
$provider_name = isset($_GET['provider_name']) ? $_GET['provider_name'] : "";
$data = $dbProvider->getAllProvider();
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
// $endIndex = min($startIndex + $perPage, $totalItems);

$provider_name = isset($_GET['provider_name']) ? $_GET['provider_name'] : "";
?>

<input type="text" name="hidden-name" value="<?php echo htmlspecialchars($_GET['provider_name'] ?? ''); ?>" class="hidden-name" hidden>
<div class="provider container-content no-filter">
    <div class="head-content">
        <div class="heading">Nhà cung cấp</div>
    </div>

    <div class="mid-content">
        <div style="display:flex; flex-direction: row; justify-content: space-between">
            <div class="addition-btn">
                <button class="btn-add">Thêm nhà cung cấp</button>
            </div>
            <div style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center;">
                <input class="input" type="text" id="search-provider" name="search-provider" placeholder="Tìm kiếm nhà cung cấp">
                <button style="margin-left: 5px; padding: 10px 8px; cursor: pointer; background-color: #eb6532; border: 0; border-radius: 5px;" type="button" onclick="searchProvider()"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>

        <div class="table">
            <div class="filter-result"></div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Địa chỉ</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- <tr>
                        <td>101</td>
                        <td>Công ty TNHH ABC</td>
                        <td>123 Trần Bình Trọng, quận 5, TP.HCM</td>
                        <td>0147852369</td>
                        <td>abc@example.com</td>
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
                        <td>Công ty TNHH DMT</td>
                        <td>123  Nguyễn Trãi, quận 5, TP.HCM</td>
                        <td>0147852369</td>
                        <td>abc@example.com</td>
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
                    </tr> -->
                    <?php
                    $new_data = $dbProvider->getProviderInRange($startIndex, $perPage);
                    if (isset($_GET['provider_name'])) {
                        $new_data = $dbProvider->getProviderByName($startIndex, $perPage, $_GET['provider_name']);
                        $totalItems = count($dbProvider->getAllProviderByName($_GET['provider_name']));
                        $totalPage = ceil($totalItems / $perPage);
                        // printJS(count($new_data));
                        // printJS($totalItems);
                        // printJS($totalPage);
                    }
                    if (is_array($new_data) && !empty($new_data)) {
                        foreach ($new_data as $key => $row) {
                            // Lấy ra địa chỉ của NCC
                            $address = $dbProvider->getAddressProvider($row['id']);
                            $addressToStr = strAddress($address);
                            echo "
                            <tr>  
                            <td>" . $row['id'] . "</td>  
                            <td>" . $row['name'] . "</td>  
                            <td>" . $addressToStr . "</td>  
                            <td>" . $row['numberPhone'] . "</td>  
                            <td>" . $row['email'] . "</td>  
                            <td>  
                                <div class='flex-row-space-evenly'>  
                                    <a href='index.php?page=provider&current=" . $currentPage . "&provider_id=" . $row['id'] . "&option=update' class='btn-option success' style='  background-color: #dcf4f0 !important;
  color: #37b886 !important;''>  
                                        <i class='fa-regular fa-pen-to-square'></i>     
                                    </a> 
                                    <a href='index.php?page=provider&current=" . $currentPage . "&provider_id=" . $row['id'] . "&option=delete' class='btn-option wrong'>  
                                        <i class='fa-regular fa-trash-can'></i>   
                                    </a> 
                                </div>  
                            </td>  
                        </tr>";
                        }
                    } else {
                        echo "
                        <tr>
                            <td colspan=6 style=\"color: #eb6532\">Không có sản phẩm nào</td>
                        </tr>
                        ";
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
            if (isset($_GET['provider_name'])) {
                echo "<a href=\"index.php?page=provider&current=$i&provider_name=$provider_name\"";
            }
            echo "<a href=\"index.php?page=provider&current=$i\" class='page-link $activeClass'>" . $i . "</a>";
        }
        echo "</div>";

        ?>
    </div>
</div>

<!-- Dialog ẩn mặc định -->
<dialog id="addProviderDialog">
    <h2>Thêm nhà cung cấp</h2>
    <form id="providerForm">
        <label for="add_providerName">Tên Nhà cung cấp:</label>
        <input type="text" id="add_providerName" name="add_providerName" required>

        <label for="add_numberPhone">Số điện thoại</label>
        <input type="text" id="add_numberPhone" name="add_numberPhone" required maxlength="11">

        <label for="add_email">Email</label>
        <input type="text" id="add_email" name="add_email" required>
        <hr>
        <div class="add_address">
            <h2>Địa chỉ</h2>
            <div class="flex-row">
                <div style="margin-right: 30px;">
                    <label for="add_numberHouse">Số nhà</label>
                    <input type="text" id="add_numberHouse" name="add_numberHouse" value="" placeholder="123" required />
                </div>

                <div>
                    <label for="add_street">Đường</label>
                    <input type="text" id="add_street" name="add_street" value="" placeholder="An Dương Vương" required />
                </div>
            </div>

            <div class="flex-row">
                <div style="margin-right: 30px;">
                    <label for="add_ward">Phường</label>
                    <input type="text" id="add_ward" name="add_ward" value="" placeholder="Phường 10" required />
                </div>

                <div>
                    <label for="add_district">Quận</label>
                    <input type="text" id="add_district" name="add_district" value="" placeholder="Quận 1" required />
                </div>
            </div>

            <div>
                <label for="add_city">Thành phố</label>
                <input type="text" id="add_city" name="add_city" value="" required />
            </div>
        </div>

        <div class="dialog-buttons">
            <button type="button" onclick="closeDialog()">Hủy</button>
            <button type="submit" onclick="addProvider()">Lưu</button>
        </div>
    </form>
</dialog>


<!-- Hiển thị fake background -->
<div class="fakebg1"></div>

<!-- XU LY CAC NUT TUY CHINHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['provider_id'])) {
    $id = intval($_GET['provider_id']); // Get the value of 'id' from the URL
    $option = isset($_GET['option']) ? $_GET['option'] : "";

    if ($option == 'delete') {
        $html_code = html_delete();
    }

    if ($option == 'update') {
        /// Nếu update rồi thì update và đóng fakebg1
        if (isset($_GET['new_providerName'])) {
            // printJS("hello new name");
            updateProvider($dbProvider);
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
        $data1 = $dbProvider->getProviderById($_GET['provider_id']);
        // printJS($data1);
        if (empty($data1)) {
            die("Không tìm thấy chi tiết sản phẩm với ID: " . $id);
        }

        if (!empty($data1)) {
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
        <div class="provider-delete">
            <div>
                <button class="close-detail" type="button" onclick="closeFakeBG1(this)">
                x
                </button>
            </div>
            <div class="content-delete">
                <p>
                Nhà cung cấp này sẽ được xóa khỏi website, bạn chắc chắn muốn xóa nhà cung cấp
                này?
                </p>
            </div>
            <div class="btn">
                <button type="button" onclick="closeFakeBG1(this)">Hủy</button>
                <a href=\'index.php?page=provider&current=' . $_GET['current'] . '&provider_id=' . $_GET['provider_id'] . '&option=delete&isDeleted=true\'
                    <button id="btn-delete-provider" type="button" onclick="closeFakeBG1(this)">Xóa nhà cung cấp</button> 
                </a> 
            </div>
        </div>
        ';
    return $html_code;
}

function html_update($data1)
{
    // $data = new DB_provider();
    // printJS($data1['name']);
    $dbProvider = new DB_provider();
    $html_code = '      
    <div class="provider-update">
      <div><button class="close-detail" type="button" onclick="closeFakeBG1(this)">x</button></div>
    <h1>Chỉnh sửa thông tin</h1>
    <div class="content-update">
      <div class="show-info">
        <div>
          <label for="update-name">Tên nhà cung cấp</label> <br>
          <input style="width: 400px; margin: 5px 0;" type="text" id="update-name" name="update-name" value="' . $data1['name'] . '" required/>
        </div>

        <div>
          <label for="update-numberPhone">Số điện thoại</label> <br>
          <input style="width: 400px; margin: 5px 0;" type="text" id="update-numberPhone" name="update-numberPhone" value="' . $data1['numberPhone'] . '" maxlength="11" required/>
        </div>

        <div>
          <label for="update-email">Email</label> <br>
          <input style="width: 400px; margin: 5px 0;" type="text" id="update-email" name="update-email" value="' . $data1['email'] . '" required/>
        </div>

        <hr>

        <div class="address">
          <h2>Địa chỉ</h2>
          <div class="flex-row">
            <div>
              <label for="update-numberHouse">Số nhà</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-numberHouse" name="update-numberHouse" value="' . $data1['numberHouse'] . '" required/>
            </div>
  
            <div>
              <label for="update-street">Đường</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-street" name="update-street" value="' . $data1['street'] . '" required/>
            </div>
          </div>

          <div class="flex-row">
            <div>
              <label for="update-ward">Phường</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-ward" name="update-ward" value="' . $data1['ward'] . '" required/>
            </div>

            <div>
              <label for="update-district">Quận</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-district" name="update-district" value="' . $data1['district'] . '" required/>
            </div>
          </div>

          <div>
            <label for="update-city">Thành phố</label> <br>
            <input style="width: 400px; margin: 5px 0;" type="text" id="update-city" name="update-city" value="' . $data1['city'] . '" required/>
          </div>
        </div>

        <div class="btn">
          <button type="button" onclick="closeFakeBG1(this)">Hủy</button>
          <button id="btn-update-provider" type="button" onclick="updateProvider(), closeFakeBG1(this)">Xác nhận </button> 
    </div>
    </div>
  </div>';
    return $html_code;
}
?>


<!-- Lấy ra chuyển địa chỉ thành string -->
<?php
function strAddress($row)
{
    $str = $row['numberHouse'] . " " . $row['streetName'] . ", " . $row['ward'] . ", " . $row['district'] . ", " . $row['city'];
    return $str;
}

function printJS($mess)
{
    echo "
        <script>alert('" . $mess . "')</script>
        ";
}
?>

<!-- XOAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['isDeleted'])) {
    $id = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : 0;
    // echo "
    // <script>
    //     alert(" . $id . ");
    // </script>";
    $dbProvider->deleteProvider($id);
    echo "
        <script>
            window.onload = function() {
                // let table = document.getElementsByClassName(\"fakebg1\")[0];
                // table.style.display = \"none\";
                showSuccessfulAlert(`Xóa nhà cung cấp thành công`);
                setTimeout(function() {
                    window.location.href = 'index.php?page=provider&current=" . $_GET['current'] . "';
                }, 1000); // Đợi 2 giây trước khi chuyển hướng
            }
        </script>";
}
?>

<!-- CHINH SUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA  -->

<?php
function updateProvider($dbProvider)
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['new_providerName']) && isset($_GET['new_numberPhone']) && isset($_GET['new_email'])) {
        $id = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : 0;
        $result = $dbProvider->updateProvider($id, $_GET['new_providerName'], $_GET['new_numberPhone'], $_GET['new_email']);
        $result2 = $dbProvider->updateProviderAddress($id, $_GET['new_numberHouse'], $_GET['new_street'], $_GET['new_ward'], $_GET['new_district'], $_GET['new_city']);
        // $result_img = $dbProduct->updateProductImage($id, $_GET['img_index'], $_GET['new_img']);
        // printJS("hello");
        if ($result && $result2) {
            // Cập nhật thành công, clear URL và chuyển hướng
            echo "<script>
                                const params = new URLSearchParams(window.location.search);
                                params.set('page', 'provider');
                                params.delete('option');
                                params.delete('new_name');
                                params.delete('new_numberPhone');
                                params.delete('new_email');
                                params.delete('new_numberHouse');
                                params.delete('new_street');
                                params.delete('new_ward');
                                params.delete('new_district');
                                params.delete('new_city');
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
?>

<!-- THÊM SẢN PHẨMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['add_providerName'])) {
    $add_providerName = $_GET['add_providerName'];
    $add_numberPhone = $_GET['add_numberPhone'];
    $add_email = $_GET['add_email'];
    $add_numberHouse = $_GET['add_numberHouse'];
    $add_street = $_GET['add_street'];
    $add_ward = $_GET['add_ward'];
    $add_district = $_GET['add_district'];
    $add_city = $_GET['add_city']; // Sửa lại từ $_GET['add_district']

    try {
        $providerId = $dbProvider->addProvider($add_providerName, $add_numberPhone, $add_email);

        // Thêm địa chỉ của nhà cung cấp vào bảng provider_address
        $dbProvider->addProviderAddress(
            $providerId,
            $add_numberHouse,
            $add_street,
            $add_ward,
            $add_district,
            $add_city
        );

        printJS("document.addEventListener('DOMContentLoaded', function() {
            window.location.href = 'index.php?page=provider&current=1';
        });");
    } catch (Exception $e) {
        // Xử lý lỗi nếu cần
        echo "Lỗi: " . $e->getMessage();
    }
}
?>


<!-- TÌM KIÉMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let innerText = "";
        let name = document.getElementsByName("hidden-name")[0];
        // alert(name.value);
        if (name.value != "") {
            innerText +=
                `
                    <div>Tìm kiếm: &nbsp;<span>${name.value}</span></div>
                `;
        }

        // alert(innerText);

        let filterResult = document.getElementsByClassName('filter-result')[0];


        if (name.value == "") {
            filterResult.style.display = 'none';
            return;
        }
        // alert("hello");

        filterResult.style.display = 'block';
        filterResult.innerHTML = `  
                <a href='index.php?page=provider' class='btn-option success'>  
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


<script>
    function updateProvider() {
        const providerName = document.getElementById("update-name").value;
        // alert(productName);
        const numberPhone = document.querySelector('#update-numberPhone').value;
        // alert(productType);
        const email = document.querySelector('#update-email').value;
        // alert(productBrand);
        const numberHouse = document.querySelector("#update-numberHouse").value;
        const street = document.querySelector("#update-street").value;
        const ward = document.querySelector("#update-ward").value;
        const district = document.querySelector("#update-district").value;
        const city = document.querySelector("#update-city").value;
        // const selectedImage = document.getElementById("second-main-img").src;

        // <?php
            //     $dbProduct->updateProductImage($_GET['id'])
            // 
            ?>


        // Kiểm tra xem các trường có được điền đầy đủ hay không
        if (!providerName || !numberPhone || !email || !numberHouse || !street || !ward || !district || !city) {
            showFailedAlert("Vui lòng điền đầy đủ thông tin nhà cung cấp.");
            return; // Ngăn việc thực thi tiếp nếu thông tin bị thiếu
        }

        // Tạo đối tượng chứa dữ liệu sản phẩm
        const providerData = {
            new_providerName: providerName,
            new_numberPhone: numberPhone,
            new_email: email,
            new_numberHouse: numberHouse,
            new_street: street,
            new_ward: ward,
            new_district: district,
            new_city: city,
            // new_img: selectedImage,
        };

        // Tạo tham số URL để gửi dữ liệu cập nhật
        const params = new URLSearchParams(window.location.search);
        params.set('page', 'provider');
        params.set('option', 'update'); // Thêm option để server biết đây là cập nhật
        params.set('provider_id', <?php echo $_GET['provider_id'] ?? ""; ?>); // Lấy ID sản phẩm từ PHP

        // Thêm dữ liệu sản phẩm vào tham số URL
        for (const key in providerData) {
            params.set(key, providerData[key]);
        }

        // Cập nhật URL và chuyển hướng
        window.location.href = `index.php?${params.toString()}`;
    }
</script>



<!-- THEEMMMMMMMMMMMM NHÀ CUNG CẤPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP -->
<script>
    function addProvider() {
        const providerName = document.getElementById("add_providerName").value;
        // alert(productName);
        const numberPhone = document.getElementById('add_numberPhone').value;
        // alert(productType);
        const email = document.getElementById('add_email').value;
        // alert(productBrand);
        const numberHouse = document.getElementById('add_numberHouse').value;
        const street = document.getElementById('add_street').value;
        const ward = document.getElementById('add_ward').value;
        const district = document.getElementById('add_district').value;
        const city = document.getElementById('add_city').value;


        // Tạo đối tượng chứa dữ liệu sản phẩm
        const providerData = {
            add_providerName: providerName,
            add_numberPhone: numberPhone,
            add_email: email,
            add_numberHouse: numberHouse,
            add_street: street,
            add_ward: ward,
            add_district: district,
            add_city: city,
        };

        // Tạo tham số URL để gửi dữ liệu cập nhật
        const params = new URLSearchParams(window.location.search);
        params.set('page', 'provider');
        params.delete('option');
        params.delete('provider_name');


        // Thêm dữ liệu sản phẩm vào tham số URL
        for (const key in providerData) {
            params.set(key, providerData[key]);
        }

        // Cập nhật URL và chuyển hướng
        window.location.href = `index.php?${params.toString()}`;
    }
</script>

<script>
    // Lấy phần tử dialog
    const addProviderDialog = document.getElementById("addProviderDialog");
    const addButton = document.querySelector(".btn-add");

    // Khi nhấn nút thêm, hiển thị dialog
    addButton.addEventListener("click", () => {
        addProviderDialog.showModal();
    });

    // Đóng dialog khi nhấn nút "Hủy"
    function closeDialog() {
        addProviderDialog.close();
    }

    // Xử lý sự kiện khi form trong dialog được submit
    document.getElementById("providerForm").addEventListener("submit", function(e) {
        e.preventDefault(); // Ngăn chặn reload trang

        // Lấy dữ liệu từ form
        // const supplierName = document.getElementById("supplierName").value;
        // const date = document.getElementById("date").value;
        // const address = document.getElementById("address").value;
        // const amount = document.getElementById("amount").value;

        // In ra console hoặc thực hiện logic lưu
        // console.log({
        //     supplierName,
        //     date,
        //     address,
        //     amount
        // });

        // Sau khi lưu, đóng dialog
        closeDialog();

        // Reset form
        this.reset();
    });
</script>

<?php include "layout/alert/alert.php" ?>

<!-- <div class="flex-row">
            <div>
                <label for="update-city">Thành phố</label> <br>
                <select id="update-city" name="update-city" style="margin: 5px 0;">
                    <option value="">Chọn tỉnh/thành phố</option>
                </select>
            </div>

            <div>
                <label for="update-district">Quận</label> <br>
                <select id="update-district" name="update-district" style="margin: 5px 0;">
                    <option value="">Chọn quận/huyện</option>
                </select>
            </div>
          </div>

          <div>
                <label for="update-ward">Phường</label> <br>
                <select id="update-ward" name="update-ward" style="margin: 5px 0;">
                    <option value="">Chọn phường/xã</option>
                </select>
          </div>
          <div class="flex-row">
            <div>
              <label for="update-numberHouse">Số nhà</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-numberHouse" name="update-numberHouse" value="' . $data1['numberHouse'] . '" required/>
            </div>
  
            <div>
              <label for="update-street">Đường</label> <br>
              <input style="width: 400px; margin: 5px 0;" type="text" id="update-street" name="update-street" value="' . $data1['street'] . '" required/>
            </div>
          </div> -->