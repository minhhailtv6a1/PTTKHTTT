
<?php
include "../connectDatabase/DB_account.php";
include "../layout/alert/alert.php";
// include "";

// Kiểm tra xem người dùng đã gửi form hay chưa  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin tên đăng nhập và mật khẩu  
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ở đây bạn có thể thay thế bằng cách kiểm tra dữ liệu trong cơ sở dữ liệu  
    $account = new DB_account();
    $list = $account->getAllAccount();
    foreach ($list as $key => $row) {
        if ($row['userName'] == $username && $row['passWord'] == $password && $row['level'] == "inventoryStaff") {
            session_start(); // Bắt đầu phiên làm việc  
            $accountName = $row['name'];
            // echoJS("alert('" . $accountName . "')");
            $_SESSION['username'] = $accountName; // Lưu thông tin vào phiên  
            $_SESSION['id'] = $row['user_id'];
            echoJS("
                document.addEventListener('DOMContentLoaded', function() {
                    showSuccessfulAlert('Welcome " . $accountName . "');
                });
            ");


            echoJS("
                setTimeout(function() {
                    window.location.href = '../index.php';
                }, 1500); // Đợi 2 giây trước khi chuyển hướng
            ");
            return;
        }
    }

    echoJS("
        document.addEventListener('DOMContentLoaded', function() {
                showFailedAlert('Tên đăng nhập hoặc mật khẩu nhập sai!');
                setTimeout(function() {
                    window.location.href = '../layout/register-signin.php';
                }, 1000); // Đợi 2 giây trước khi chuyển hướng
            });
    ");
}
?>

<?php
function echoJS($mess)
{
    echo "<script>$mess</script>";
}
?>