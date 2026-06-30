<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

<section class="register-page">

<div class="container">

<div class="register-box">

<h2>Tạo tài khoản ElectroShop</h2>

<form action="" method="POST">

<div class="row">

<div class="col">

<label>Họ tên</label>

<input type="text">

</div>

<div class="col">

<label>Số điện thoại</label>

<input type="text">

</div>

</div>

<label>Email</label>

<input type="email">

<label>Mật khẩu</label>

<input type="password">

<label>Nhập lại mật khẩu</label>

<input type="password">

<button class="btn-register">

Đăng ký

</button>

</form>

<div class="register-footer">

Đã có tài khoản?

<a href="login.php">

Đăng nhập

</a>

</div>

</div>

</div>

</section>

</main>

<?php
include 'includes/footer.php';
?>