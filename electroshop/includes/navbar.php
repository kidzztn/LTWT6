<?php require_once __DIR__ . '/cart-functions.php'; require_once __DIR__ . '/customer-auth.php'; ?>
<header>

    <!-- Top Header -->
    <div class="top-header">
        <div class="container d-flex justify-content-between">

            <div>
                <i class="fa-solid fa-truck-fast"></i>
                Miễn phí vận chuyển toàn quốc
            </div>

            <div class="top-right">

                <a href="#">
                    <i class="fa-solid fa-location-dot"></i>
                    Hệ thống cửa hàng
                </a>

                <a href="#">
                    <i class="fa-solid fa-receipt"></i>
                    Tra cứu đơn hàng
                </a>

                <a href="#">
                    <i class="fa-solid fa-phone"></i>
                    1900 1234
                </a>

            </div>

        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">

        <div class="container">

            <div class="header-wrapper">

                <!-- Logo -->
                <a href="index.php" class="logo">

                    <i class="fa-solid fa-bolt"></i>

                    <span>ElectroShop</span>

                </a>

                <!-- Search -->
                <div class="search-box">

                    <input type="text"
                        placeholder="Bạn cần tìm gì hôm nay?">

                    <button>

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </button>

                </div>

                <!-- Right -->
                <div class="header-action">

                    <?php if (isCustomerLoggedIn()): ?>
                        <a href="orders.php">
                            <i class="fa-regular fa-user"></i>
                            <span><?php echo htmlspecialchars(getCurrentCustomer()['name']); ?></span>
                        </a>
                        <a href="logout.php">
                            <span>Đăng xuất</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php">
                            <i class="fa-regular fa-user"></i>
                            <span>Tài khoản</span>
                        </a>
                    <?php endif; ?>

                    <a href="#">

                        <i class="fa-regular fa-heart"></i>

                        <span>Yêu thích</span>

                    </a>

                    <a href="cart.php" class="cart">

                        <i class="fa-solid fa-cart-shopping"></i>

                        <span>Giỏ hàng</span>

                        <div class="cart-count"><?php echo getCartCount(); ?></div>

                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- Navigation -->
    <nav class="navbar-menu">

        <div class="container">

            <ul>

                <li><a href="index.php">Trang chủ</a></li>

                <li><a href="products.php">Laptop</a></li>

                <li><a href="products.php">Điện thoại</a></li>

                <li><a href="products.php">PC Gaming</a></li>

                <li><a href="products.php">Linh kiện</a></li>

                <li><a href="products.php">Màn hình</a></li>

                <li><a href="products.php">Tai nghe</a></li>

                <li><a href="products.php">Khuyến mãi</a></li>

            </ul>

        </div>

    </nav>

</header>