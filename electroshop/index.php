<?php
require_once __DIR__ . '/../config/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$categories = $pdo->query('SELECT id, name, slug FROM categories ORDER BY id')->fetchAll();
$featuredProducts = $pdo->query('SELECT id, name, slug, price, image FROM products ORDER BY id DESC LIMIT 8')->fetchAll();
$categoryImages = [
    '../img/uploads/4.webp',
    '../img/uploads/5.webp',
    '../img/uploads/6.webp',
    '../img/uploads/7.webp',
    '../img/uploads/8.webp',
    '../img/uploads/9.webp',
    '../img/uploads/10.webp',
    '../img/uploads/11.webp',
];

// Map specific category slugs to images so labels match visuals
$categoryImagesMap = [
    'dien-thoai' => '../img/uploads/11.webp',
    'laptop' => '../img/uploads/5.webp',
    'linh-kien-may-tinh' => '../img/uploads/6.webp',
    'pc-gaming' => '../img/uploads/7.webp',
    'tai-nghe' => '../img/uploads/8.webp',
    'chuot' => '../img/uploads/9.webp',
    'ban-phim' => '../img/uploads/10.webp',
    'ssd' => '../img/uploads/11.webp',
];
?>

<main>

    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="container">

            <div class="hero-wrapper">

                <!-- Banner lớn -->

                <div class="hero-left">

                    <div class="hero-slider">

                        <img src="/LTWT6/img/uploads/1.webp" alt="Banner laptop gaming" class="hero-banner-img">

                        <div class="hero-content">

                            <span>FLASH SALE</span>

                            <h1>Laptop Gaming Chính Hãng</h1>

                            <p>
                                Giảm giá lên đến <strong>50%</strong> cho các dòng
                                ASUS, MSI, Acer Predator, Lenovo Legion...
                            </p>

                            <a href="products.php" class="btn-buy">
                                Mua ngay
                            </a>

                        </div>

                    </div>

                </div>

                <!-- Banner phải -->

                <div class="hero-right">

                    <div class="small-banner">

                        <img src="/LTWT6/img/uploads/2.webp" alt="Banner khuyến mãi 1">

                    </div>

                    <div class="small-banner">

                        <img src="/LTWT6/img/uploads/3.webp" alt="Banner khuyến mãi 2">

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ================= SERVICE ================= -->

    <section class="service">

        <div class="container">

            <div class="service-grid">

                <div class="service-item">

                    <i class="fa-solid fa-truck-fast"></i>

                    <h5>Miễn phí vận chuyển</h5>

                    <p>Cho đơn từ 500.000đ</p>

                </div>

                <div class="service-item">

                    <i class="fa-solid fa-shield-halved"></i>

                    <h5>Chính hãng 100%</h5>

                    <p>Bảo hành toàn quốc</p>

                </div>

                <div class="service-item">

                    <i class="fa-solid fa-credit-card"></i>

                    <h5>Trả góp 0%</h5>

                    <p>Qua thẻ tín dụng</p>

                </div>

                <div class="service-item">

                    <i class="fa-solid fa-headset"></i>

                    <h5>Hỗ trợ 24/7</h5>

                    <p>Hotline miễn phí</p>

                </div>

            </div>

        </div>

    </section>

    <section class="promo-banner">
        <div class="container">
            <div class="promo-banner-box">
                <div>
                    <span>Ưu đãi tháng 7</span>
                    <h3>Giảm tới 30% cho laptop gaming, điện thoại và phụ kiện cao cấp</h3>
                    <p>Freeship, bảo hành chính hãng và hỗ trợ trả góp 0% cho đơn hàng trong tuần này.</p>
                </div>
                <a href="products.php?query=gaming" class="btn-buy">Mua ngay</a>
            </div>
        </div>
    </section>

    <section class="category" style="padding-top: 10px;">
        <div class="container">
            <div class="section-title">
                <h2>Khám phá công nghệ</h2>
                <a href="products.php">Xem tất cả</a>
            </div>
            <div class="product-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                <?php foreach ($categories as $index => $category): ?>
                    <?php $img = $categoryImagesMap[$category['slug']] ?? $categoryImages[$index % count($categoryImages)]; ?>
                    <a href="products.php?category=<?php echo urlencode($category['slug']); ?>" class="product-card" style="text-decoration:none; color:inherit; display:block; text-align:center;">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" style="height:180px; width:auto; max-width:100%; object-fit:contain; border-radius:12px; margin:0 auto; display:block;">
                        <h4 style="margin-top: 15px;"><?php echo htmlspecialchars($category['name']); ?></h4>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ================= CATEGORY ================= -->

    <section class="category">

        <div class="container">

            <div class="section-title">

                <h2>Danh mục nổi bật</h2>

            </div>

            <div class="category-grid">

                <a href="products.php?category=laptop" class="category-item">

                    <i class="fa-solid fa-laptop"></i>

                    <h4>Laptop</h4>

                </a>

                <a href="products.php?category=dien-thoai" class="category-item">

                    <i class="fa-solid fa-mobile-screen-button"></i>

                    <h4>Điện thoại</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-gamepad"></i>

                    <h4>PC Gaming</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-headphones"></i>

                    <h4>Tai nghe</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-mouse"></i>

                    <h4>Chuột</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-keyboard"></i>

                    <h4>Bàn phím</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-memory"></i>

                    <h4>SSD</h4>

                </a>

                <a href="products.php?category=linh-kien-may-tinh" class="category-item">

                    <i class="fa-solid fa-microchip"></i>

                    <h4>RAM</h4>

                </a>

            </div>

        </div>

    </section>

    <!-- ================= FLASH SALE ================= -->

    <section class="flash-sale">

        <div class="container">

            <div class="section-title">

                <div>

                    <h2>🔥 Flash Sale</h2>

                    <p>Giảm giá cực sốc hôm nay</p>

                </div>

                <div class="countdown">

                    <span>02</span>

                    :

                    <span>15</span>

                    :

                    <span>45</span>

                </div>

            </div>

            <div class="product-grid">

                <?php foreach ($featuredProducts as $product): ?>

                <div class="product-card">

                    <span class="discount">-20%</span>

                    <img src="<?php echo htmlspecialchars(normalizeProductImagePath($product['image'] ?? null)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <p class="product-category">Sản phẩm nổi bật</p>
                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>

                    <div class="product-meta">
                        <span class="stock-badge">Còn hàng</span>
                        <span class="rating">★★★★★</span>
                    </div>

                    <div class="price">

                        <span class="new-price">

                            <?php echo number_format((float) $product['price'], 0, ',', '.'); ?>₫

                        </span>

                        <span class="old-price">

                            <?php echo number_format((float) $product['price'] * 1.2, 0, ',', '.'); ?>₫

                        </span>

                    </div>

                    <div class="product-action">

                        <a href="product-detail.php?id=<?php echo (int) $product['id']; ?>">

                            Xem chi tiết

                        </a>

                        <a href="cart.php" class="icon-btn" type="button">

                            <i class="fa-solid fa-cart-shopping"></i>

                        </a>

                    </div>

                </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <!-- ================= BANNER ADS ================= -->

    <section class="banner-middle">

        <div class="container">

            <img src="../img/banner/banner4.jpg">

        </div>

    </section>

       <!-- ================= LAPTOP NỔI BẬT ================= -->

    <section class="featured-product">

        <div class="container">

            <div class="section-title">

                <h2>💻 Laptop nổi bật</h2>

                <a href="products.php">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>

            </div>

            <div class="product-grid">

                <?php for($i=1;$i<=4;$i++): ?>

                <div class="product-card">

                    <span class="discount">-15%</span>

                    <img src="../img/products/laptop.png" alt="">

                    <h4>ASUS TUF Gaming A15 Ryzen 7 RTX 4060</h4>

                    <div class="rating">

                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>

                    </div>

                    <div class="price">

                        <span class="new-price">28.990.000₫</span>

                        <span class="old-price">32.990.000₫</span>

                    </div>

                    <div class="product-action">

                        <a href="product-detail.php">Xem chi tiết</a>

                        <button>

                            <i class="fa-solid fa-cart-shopping"></i>

                        </button>

                    </div>

                </div>

                <?php endfor; ?>

            </div>

        </div>

    </section>



    <!-- ================= BANNER ================= -->

    <section class="ads-banner">

        <div class="container">

            <img src="../img/banner/banner5.jpg" alt="">

        </div>

    </section>



    <!-- ================= ĐIỆN THOẠI ================= -->

    <section class="featured-product">

        <div class="container">

            <div class="section-title">

                <h2>📱 Điện thoại nổi bật</h2>

                <a href="products.php">Xem tất cả</a>

            </div>

            <div class="product-grid">

                <?php for($i=1;$i<=4;$i++): ?>

                <div class="product-card">

                    <span class="discount">-8%</span>

                    <img src="../img/products/iphone.png" alt="">

                    <h4>iPhone 16 Pro Max 256GB</h4>

                    <div class="rating">

                        ★★★★★

                    </div>

                    <div class="price">

                        <span class="new-price">

                            30.990.000₫

                        </span>

                        <span class="old-price">

                            33.990.000₫

                        </span>

                    </div>

                    <div class="product-action">

                        <a href="product-detail.php">

                            Xem chi tiết

                        </a>

                        <button>

                            <i class="fa-solid fa-cart-shopping"></i>

                        </button>

                    </div>

                </div>

                <?php endfor; ?>

            </div>

        </div>

    </section>



    <!-- ================= GAMING GEAR ================= -->

    <section class="featured-product">

        <div class="container">

            <div class="section-title">

                <h2>🎮 Gaming Gear</h2>

                <a href="products.php">

                    Xem tất cả

                </a>

            </div>

            <div class="product-grid">

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/gaming-mouse-real.jpg" alt="Gaming Mouse">
                    <h4>Chuột Gaming Razer Viper</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">1.190.000₫</span>
                        <span class="old-price">1.590.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-mouse"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/gaming-keyboard-real.jpg" alt="Gaming Keyboard">
                    <h4>Bàn phím Gaming Corsair K70</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">2.090.000₫</span>
                        <span class="old-price">2.790.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-keyboard"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/6.webp" alt="Gaming Keyboard">
                    <h4>Bàn phím RGB HyperX</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">1.890.000₫</span>
                        <span class="old-price">2.490.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-keyboard"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/24.webp" alt="Xiaomi Smartphone">
                    <h4>Điện thoại Xiaomi 13</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">12.990.000₫</span>
                        <span class="old-price">16.490.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-mobile-screen-button"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/5.webp" alt="MacBook Air">
                    <h4>MacBook Air 15" M2</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">29.490.000₫</span>
                        <span class="old-price">32.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/3.webp" alt="MSI Gaming Laptop">
                    <h4>MSI Gaming 16GB RTX</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">33.990.000₫</span>
                        <span class="old-price">39.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/7.webp" alt="Phone Stand">
                    <h4>Móc điện thoại đa năng</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">190.000₫</span>
                        <span class="old-price">250.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-mobile-screen-button"></i></button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">-25%</span>
                    <img src="/LTWT6/img/uploads/30.webp" alt="Gaming Monitor">
                    <h4>Màn hình Gaming 27" 144Hz</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">8.490.000₫</span>
                        <span class="old-price">10.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-tv"></i> </button>
                    </div>
                </div>

            </div>

        </div>

    </section>



    <!-- ================= THƯƠNG HIỆU ================= -->

    <section class="brand">

        <div class="container">

            <div class="section-title">

                <h2>⭐ Thương hiệu nổi bật</h2>

            </div>

            <div class="brand-grid">

                <img src="/LTWT6/img/uploads/14.webp" alt="Apple">

                <img src="/LTWT6/img/uploads/15.webp" alt="Asus">
   
                <img src="/LTWT6/img/uploads/17.webp" alt="HP"> 

                <img src="/LTWT6/img/uploads/19.webp" alt="Acer">

                <img src="/LTWT6/img/uploads/20.webp" alt="Lenovo">

            </div>

        </div>

    </section>
        <!-- ================= BÁN CHẠY NHẤT ================= -->

    <section class="best-seller">

        <div class="container">

            <div class="section-title">

                <h2>🔥 Sản phẩm bán chạy</h2>

                <a href="products.php">
                    Xem tất cả
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

            <div class="product-grid">

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/1.webp" alt="MacBook Air M2">
                    <h4>MacBook Air M2 16GB 512GB</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">31.490.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/4.webp" alt="LG Gram Book 16">
                    <h4>LG Gram Book 16 16GB 512GB</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">29.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/3.webp" alt="MSI Gaming Laptop">
                    <h4>MSI Gaming Laptop 16GB RTX</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">38.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-gamepad"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/2.webp" alt="ASUS Vivobook Flip">
                    <h4>ASUS Vivobook Flip 14" 8GB</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">18.990.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop-code"></i></button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/5.webp" alt="MacBook Air 15">
                    <h4>MacBook Air 15" 8GB 256GB</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">29.490.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-laptop"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/6.webp" alt="Gaming Keyboard">
                    <h4>Razer Gaming Keyboard</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">2.490.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-keyboard"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/8.webp" alt="Camera Lens">
                    <h4>Lens Máy Ảnh 50mm</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">1.290.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-camera"></i> </button>
                    </div>
                </div>

                <div class="product-card">
                    <span class="discount">Best Seller</span>
                    <img src="/LTWT6/img/uploads/9.webp" alt="PS5 Controller">
                    <h4>Tay cầm PS5 DualSense</h4>
                    <div class="rating">★★★★★</div>
                    <div class="price">
                        <span class="new-price">1.190.000₫</span>
                    </div>
                    <div class="product-action">
                        <a href="product-detail.php">Xem chi tiết</a>
                        <button><i class="fa-solid fa-gamepad"></i> </button>
                    </div>
                </div>

            </div>

        </div>

    </section>





    <!-- ================= TIN CÔNG NGHỆ ================= -->

    <section class="news">

        <div class="container">

            <div class="section-title">

                <h2>📰 Tin công nghệ</h2>

                <a href="#">
                    Xem thêm
                </a>

            </div>

            <div class="news-grid">

                <div class="news-card">

                    <?php
                        $webBase = '/LTWT6/img/uploads/';
                        $fsBase = rtrim($_SERVER['DOCUMENT_ROOT'], "\\/") . $webBase;
                        $macFileWebp = 'macbook-m5.webp';
                        $macFileJpg = 'macbook-m5.jpg';
                        $fallbackFile = '5.webp';
                        if (file_exists($fsBase . $macFileJpg)) {
                            $imgWeb = $webBase . $macFileJpg;
                        } elseif (file_exists($fsBase . $macFileWebp)) {
                            $imgWeb = $webBase . $macFileWebp;
                        } else {
                            $imgWeb = $webBase . $fallbackFile;
                        }
                    ?>
                    <img src="<?= $imgWeb ?>" alt="MacBook Air M5">

                    <div class="news-content">

                        <span>25/04/2026</span>

                        <h3>

                            Apple chuẩn bị ra mắt MacBook Air M5

                        </h3>

                        <p>

                            Dòng MacBook thế hệ mới dự kiến sẽ có thời lượng pin
                            vượt mốc 24 giờ...

                        </p>

                    </div>

                </div>

                <div class="news-card">

                    <?php
                        $webBase = '/LTWT6/img/uploads/';
                        $fsBase = rtrim($_SERVER['DOCUMENT_ROOT'], "\\/") . $webBase;
                        $rtxJpg = 'rtx-6080.jpg';
                        $rtxWebp = '6.webp';
                        if (file_exists($fsBase . $rtxJpg)) {
                            $news2Img = $webBase . $rtxJpg;
                        } elseif (file_exists($fsBase . $rtxWebp)) {
                            $news2Img = $webBase . $rtxWebp;
                        } else {
                            $news2Img = $webBase . '11.webp';
                        }
                    ?>
                    <img src="<?= $news2Img ?>" alt="RTX 6080">

                    <div class="news-content">

                        <span>24/04/2026</span>

                        <h3>

                            RTX 6080 chính thức mở bán

                        </h3>

                        <p>

                            NVIDIA công bố thế hệ VGA mới với hiệu năng tăng
                            hơn 40%...

                        </p>

                    </div>

                </div>

                <div class="news-card">

                    <img src="../img/uploads/3.webp" alt="Gaming laptop - MSI">

                    <div class="news-content">

                        <span>23/04/2026</span>

                        <h3>

                            Top Laptop Gaming đáng mua 2026

                        </h3>

                        <p>

                            Tổng hợp những mẫu laptop chơi game mạnh nhất
                            hiện nay...

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>





    <!-- ================= KHÁCH HÀNG ĐÁNH GIÁ ================= -->

    <section class="testimonial">

        <div class="container">

            <div class="section-title">

                <h2>💬 Khách hàng nói gì?</h2>

            </div>

            <div class="testimonial-grid">

                <div class="testimonial-card">

                    <img src="/LTWT6/img/uploads/user4.jpg" alt="Nguyễn Văn A">

                    <h4>Nguyễn Văn A</h4>

                    <p>

                        "Giao hàng rất nhanh, laptop chính hãng,
                        đóng gói cẩn thận."

                    </p>

                    ★★★★★

                </div>

                <div class="testimonial-card">

                    <img src="/LTWT6/img/uploads/user2.jpg" alt="Trần Thị B">

                    <h4>Trần Thị B</h4>

                    <p>

                        "Nhân viên hỗ trợ nhiệt tình,
                        giá rẻ hơn nhiều nơi."

                    </p>

                    ★★★★★

                </div>

                <div class="testimonial-card">

                    <img src="/LTWT6/img/uploads/user5.jpg" alt="Lê Minh C">

                    <h4>Lê Minh C</h4>

                    <p>

                        "Mình sẽ tiếp tục mua hàng
                        ở ElectroShop."

                    </p>

                    ★★★★★

                </div>

            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>