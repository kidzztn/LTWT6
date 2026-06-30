<?php
?>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="admin">

    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <div class="main">

        <?php include __DIR__ . "/includes/topbar.php"; ?>

        <div class="content">

            <h2>Dashboard</h2>
            <p>Xin chào Admin 👋</p>

            <div class="card-box">

                <div class="card">
                    <i class="fa-solid fa-box"></i>
                    <h3>120</h3>
                    <span>Sản phẩm</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>58</h3>
                    <span>Đơn hàng</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-users"></i>
                    <h3>35</h3>
                    <span>Khách hàng</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <h3>760M</h3>
                    <span>Doanh thu</span>
                </div>

            </div>

            <div class="table-box">

                <div class="table-header">
                    <h3>Đơn hàng mới</h3>
                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>

                    <tr>
                        <td>#1001</td>
                        <td>Nguyễn Văn A</td>
                        <td>iPhone 16 Pro Max</td>
                        <td>34.990.000đ</td>
                        <td><span class="status success">Đã thanh toán</span></td>
                    </tr>

                    <tr>
                        <td>#1002</td>
                        <td>Trần Văn B</td>
                        <td>Macbook Air M4</td>
                        <td>28.990.000đ</td>
                        <td><span class="status pending">Đang xử lý</span></td>
                    </tr>

                    <tr>
                        <td>#1003</td>
                        <td>Lê Minh C</td>
                        <td>RTX 5070</td>
                        <td>21.500.000đ</td>
                        <td><span class="status cancel">Đã hủy</span></td>
                    </tr>

                </table>

            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>