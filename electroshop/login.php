<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

<section class="login-page">

    <div class="container">

        <div class="login-box">

            <div class="login-left">

                <img src="../img/banner/login-banner.png" alt="">

            </div>

            <div class="login-right">

                <h2>Đăng nhập</h2>

                <p>Chào mừng bạn quay trở lại ElectroShop.</p>

                <form action="" method="POST">

                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Nhập email">

                    </div>

                    <div class="form-group">

                        <label>Mật khẩu</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Nhập mật khẩu">

                    </div>

                    <div class="remember">

                        <label>

                            <input type="checkbox">

                            Ghi nhớ đăng nhập

                        </label>

                        <a href="#">Quên mật khẩu?</a>

                    </div>

                    <button class="btn-login">

                        Đăng nhập

                    </button>

                </form>

                <div class="login-footer">

                    Chưa có tài khoản?

                    <a href="register.php">

                        Đăng ký ngay

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

</main>

<?php
include 'includes/footer.php';
?>