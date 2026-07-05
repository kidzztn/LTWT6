<?php
require_once __DIR__ . '/cart-functions.php';
require_once __DIR__ . '/customer-auth.php';
$searchValue = trim($_GET['query'] ?? '');
?>
<header class="site-header">
    <div class="top-header">
        <div class="container">
            <div class="top-header-left">
                <i class="fa-solid fa-truck-fast"></i>
                Miễn phí vận chuyển toàn quốc cho đơn từ 500.000đ
            </div>
            <div class="top-right">
                <a href="products.php?query=gaming">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                    Hot deals
                </a>
                <a href="products.php?category=laptop">
                    <i class="fa-solid fa-location-dot"></i>
                    Laptop mới
                </a>
                <a href="products.php?category=dien-thoai">
                    <i class="fa-solid fa-phone"></i>
                    1900 1234
                </a>
            </div>
        </div>
    </div>

    <div class="main-header">
        <div class="container">
            <div class="header-wrapper">
                <a href="index.php" class="logo">
                    <i class="fa-solid fa-bolt"></i>
                    <span>ElectroShop</span>
                </a>

                <form class="search-box" action="products.php" method="get">
                    <input type="text" name="query" value="<?php echo htmlspecialchars($searchValue); ?>" placeholder="Bạn cần tìm gì hôm nay?">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <div class="header-action">
                    <?php if (isCustomerLoggedIn()): ?>
                        <a href="profile.php" title="Thông tin tài khoản">
                            <i class="fa-regular fa-user"></i>
                            <span>Tài khoản</span>
                        </a>
                        <a href="orders.php" title="Lịch sử đơn hàng">
                            <i class="fa-solid fa-box-archive"></i>
                            <span>Đơn hàng</span>
                        </a>
                        <a href="logout.php" title="Đăng xuất">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Đăng xuất</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php">
                            <i class="fa-regular fa-user"></i>
                            <span>Tài khoản</span>
                        </a>
                    <?php endif; ?>

                    <a href="cart.php" class="cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Giỏ hàng</span>
                        <div class="cart-count"><?php echo getCartCount(); ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar-menu">
        <div class="container">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="products.php?category=laptop">Laptop</a></li>
                <li><a href="products.php?category=dien-thoai">Điện thoại</a></li>
                <li><a href="products.php?category=linh-kien-may-tinh">Linh kiện</a></li>
                <li><a href="products.php?query=gaming">PC Gaming</a></li>
            </ul>
        </div>
    </nav>
</header>