<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ElectroShop Admin Login</title>

    <link rel="stylesheet" href="assets/css/admin.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="login-page">

<div class="login-container">

    <div class="login-left">

        <div class="overlay">

            <h1>ElectroShop</h1>

            <p>Hệ thống quản trị Website Thương mại điện tử</p>

        </div>

    </div>

    <div class="login-right">

        <div class="login-box">

            <h2>Đăng nhập Admin</h2>

            <p>Đăng nhập để quản lý hệ thống</p>

            <form action="" method="POST">

                <div class="input-group">

                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        name="username"
                        placeholder="Tên đăng nhập"
                        required>

                </div>

                <div class="input-group">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Mật khẩu"
                        required>

                </div>

                <button class="btn-login">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Đăng nhập

                </button>

            </form>

            <div class="copyright">

                © 2026 ElectroShop

            </div>

        </div>

    </div>

</div>

<script src="assets/js/admin.js"></script>

</body>

</html>