<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Đăng Nhập</title>
    <link rel="stylesheet" href="../css/log_in.css" />
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="wrapper">
        <div class="img-homepage"></div>
        <h1 class="heading">Quản lí kho</h1>
        <div class="login-container">
            <form class="login-form" method="POST" action="../process/login.php" onsubmit="return checkForm()">
                <h2>Đăng Nhập</h2>
                <div class="form-group">
                    <label for="username">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" required value="<?php echo $_POST['username'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <label for="password">Mật Khẩu</label>
                    <input type="password" id="password" name="password" required value="<?php echo $_POST['password'] ?? "" ?>" />
                </div>
                <div class="form-group">
                    <span><a href="../layout/register.php">Đăng kí</a></span>
                </div>
                <button type="submit">Đăng Nhập</button>
            </form>
        </div>
    </div>
</body>

</html>