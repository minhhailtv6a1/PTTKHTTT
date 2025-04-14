<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Đăng Ký</title>
    <link rel="stylesheet" href="../css/log_in.css" />
</head>

<body>
    <div class="wrapper">
        <div class="img-homepage"></div>
        <h1 class="heading">Quản lí kho</h1>
        <div class="login-container">
            <form class="login-form" method="POST" onsubmit="return checkForm()">
                <h2>Đăng ký</h2>
                <div class="form-group">
                    <label for="accountName">Tên tài khoản</label>
                    <input type="text" id="accountName" name="accountName" required value="<?php echo $_POST['accountName'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" required value="<?php echo $_POST['username'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required value="<?php echo $_POST['password'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <label for="re_password">Nhập lại mật khẩu</label>
                    <input type="password" id="re_password" name="re_password" required value="<?php echo $_POST['re_password'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <label for="numberPhone">Số điện thoại</label>
                    <input type="text" id="numberPhone" name="numberPhone" required value="<?php echo $_POST['numberPhone'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <!-- <span><a href="">Quên mật khẩu</a></span>
                    <span> / </span> -->
                    <span><a href="../layout/register-signin.php">Đăng nhập</a></span>
                </div>
                <button type="submit">Đăng ký</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php
include "../layout/alert/alert.php";
include "../connectDatabase/DB_account.php";
?>

<script>
    function checkForm() {
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        let repassword = document.getElementById("re_password");
        let numberPhone = document.getElementById("numberPhone");

        if (password.value.trim() !== repassword.value.trim()) {
            showFailedAlert("Mật khẩu nhập lại không khớp!");
            repassword.focus();
            return false;
        }

        let regexPhone = /^[0]\d{9}$/;
        if (!regexPhone.test(numberPhone.value.trim())) {
            showFailedAlert("Số điện thoại bị sai định dạng!");
            numberPhone.focus()
            return false;
        }
        return true;
    }
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $accountName = $_POST['accountName'] ?? "";
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $repassword = $_POST['re_password'] ?? "";
    $numberPhone = $_POST['numberPhone'] ?? "";
    $account = new DB_account();
    $list = $account->getAllAccount();
    // echoJS("alert('hello')");
    foreach ($list as $key => $row) {
        if ($row['userName'] == $username) {
            // echoJS("showFailedAlert('Tên đăng nhập đã tồn tại');");
            echoJS("
                document.addEventListener('DOMContentLoaded', function() {
                    showFailedAlert('Tên đăng nhập đã tồn tại');
                });
                document.getElementById('username').focus();
            ");
            return;
        }
        if ($row['numberPhone'] == $numberPhone) {
            // echoJS("alert('Số điện thoại đã được sử dụng');");
            echoJS("
                document.addEventListener('DOMContentLoaded', function() {
                    showFailedAlert('Số điện thoại đã được sử dụng');
                });
                document.getElementById('numberPhone').focus();
            ");
            return;
        }
    }
    echoJS("
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessfulAlert('Đăng ký thành công');
        });
    ");

    // echo date("Y-m-d"); // Kết quả: 2025-04-03 (nếu ngày hiện tại là 3 tháng 4 năm 2025)

    $user_id = $account->addUser($accountName, date("Y-m-d"));
    $account->addAccount($user_id, $accountName, $username, $password, $numberPhone);

    echoJS("
        setTimeout(function() {
            window.location.href = '../layout/register-signin.php';
        }, 2000); // Đợi 2 giây trước khi chuyển hướng
    ");
}
?>

<?php
function echoJS($mess)
{
    echo "<script>$mess</script>";
}
?>