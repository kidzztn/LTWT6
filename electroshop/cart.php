<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

<section class="cart-page">

<div class="container">

<h2>Giỏ hàng</h2>

<table class="cart-table">

<thead>

<tr>

<th>Ảnh</th>

<th>Sản phẩm</th>

<th>Giá</th>

<th>Số lượng</th>

<th>Tổng</th>

<th></th>

</tr>

</thead>

<tbody>

<?php for($i=1;$i<=3;$i++): ?>

<tr>

<td>

<img src="../img/products/laptop.png">

</td>

<td>

ASUS ROG STRIX G16

</td>

<td>

29.990.000₫

</td>

<td>

<input type="number" value="1">

</td>

<td>

29.990.000₫

</td>

<td>

<button>

Xóa

</button>

</td>

</tr>

<?php endfor; ?>

</tbody>

</table>

<div class="cart-total">

<h3>

Tổng cộng

89.970.000₫

</h3>

<a href="checkout.php" class="checkout-btn">

Thanh toán

</a>

</div>

</div>

</section>

</main>

<?php
include 'includes/footer.php';
?>