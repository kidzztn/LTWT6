<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

<section class="checkout-page">

<div class="container">

<div class="checkout-layout">

<div class="checkout-left">

<h2>Thông tin nhận hàng</h2>

<form>

<input type="text" placeholder="Họ tên">

<input type="text" placeholder="Số điện thoại">

<input type="email" placeholder="Email">

<input type="text" placeholder="Địa chỉ">

<select>

<option>Thanh toán khi nhận hàng</option>

<option>Chuyển khoản</option>

</select>

<textarea placeholder="Ghi chú"></textarea>

<button>

Đặt hàng

</button>

</form>

</div>

<div class="checkout-right">

<h2>Đơn hàng</h2>

<div class="order-item">

<span>ASUS ROG STRIX G16</span>

<span>29.990.000₫</span>

</div>

<div class="order-item">

<span>iPhone 16 Pro Max</span>

<span>30.990.000₫</span>

</div>

<hr>

<h3>

Tổng

60.980.000₫

</h3>

</div>

</div>

</div>

</section>

</main>

<?php
include 'includes/footer.php';
?>