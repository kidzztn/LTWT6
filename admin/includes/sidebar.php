<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$isActive = static function (string $needle) use ($currentPath): string {
    return strpos($currentPath, $needle) !== false ? 'active' : '';
};
?>

<div class="sidebar">

    <div class="logo">

        ElectroShop

    </div>

    <ul>

        <li class="<?php echo $isActive('/admin/index.php'); ?>">

            <a href="/LTWT6/admin/index.php">

                <i class="fa-solid fa-house"></i>

                Dashboard

            </a>

        </li>

        <li class="<?php echo $isActive('/admin/products/'); ?>">

            <a href="/LTWT6/admin/products/index.php">

                <i class="fa-solid fa-box"></i>

                Sản phẩm

            </a>

        </li>

        <li class="<?php echo $isActive('/admin/categories/'); ?>">

            <a href="/LTWT6/admin/categories/index.php">

                <i class="fa-solid fa-layer-group"></i>

                Danh mục

            </a>

        </li>

        <li class="<?php echo $isActive('/admin/orders/'); ?>">

            <a href="/LTWT6/admin/orders/index.php">

                <i class="fa-solid fa-cart-shopping"></i>

                Đơn hàng

            </a>

        </li>

        <li class="<?php echo $isActive('/admin/users/'); ?>">

            <a href="/LTWT6/admin/users/index.php">

                <i class="fa-solid fa-users"></i>

                Người dùng

            </a>

        </li>

        <li>

            <a href="/LTWT6/admin/logout.php">

                <i class="fa-solid fa-right-from-bracket"></i>

                Đăng xuất

            </a>

        </li>

    </ul>

</div>