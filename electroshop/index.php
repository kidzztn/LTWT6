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
?>

<main>

    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="container">

            <div class="hero-wrapper">

                <!-- Banner lớn -->

                <div class="hero-left">

                    <div class="hero-slider">

                        <img src="../img/uploads/1.webp" alt="Banner">

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

                        <img src="../img/uploads/2.webp" alt="">

                    </div>

                    <div class="small-banner">

                        <img src="../img/uploads/3.webp" alt="">

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
                    <a href="products.php?category=<?php echo urlencode($category['slug']); ?>" class="product-card" style="text-decoration:none; color:inherit; display:block; text-align:center;">
                        <img src="<?php echo htmlspecialchars($categoryImages[$index % count($categoryImages)]); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" style="height:180px; width:100%; object-fit:cover; border-radius:12px;">
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

                    <i class="fa-solid fa-chip"></i>

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

                <?php for($i=1;$i<=8;$i++): ?>

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

                <?php for($i=1;$i<=8;$i++): ?>

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

                <?php for($i=1;$i<=8;$i++): ?>

                <div class="product-card">

                    <span class="discount">-25%</span>

                    <img src="../img/products/mouse.png" alt="">

                    <h4>Logitech G Pro X Superlight</h4>

                    <div class="rating">

                        ★★★★★

                    </div>

                    <div class="price">

                        <span class="new-price">

                            2.390.000₫

                        </span>

                        <span class="old-price">

                            2.990.000₫

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



    <!-- ================= THƯƠNG HIỆU ================= -->

    <section class="brand">

        <div class="container">

            <div class="section-title">

                <h2>⭐ Thương hiệu nổi bật</h2>

            </div>

            <div class="brand-grid">

                <img src="../img/brands/apple.png">

                <img src="../img/brands/asus.png">

                <img src="../img/brands/dell.png">

                <img src="../img/brands/hp.png">

                <img src="../img/brands/msi.png">

                <img src="../img/brands/acer.png">

                <img src="../img/brands/lenovo.png">

                <img src="../img/brands/samsung.png">

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

                <?php for($i=1;$i<=8;$i++): ?>

                <div class="product-card">

                    <span class="discount">Best Seller</span>

                    <img src="../img/products/laptop.png" alt="">

                    <h4>MacBook Air M4 16GB 512GB</h4>

                    <div class="rating">
                        ★★★★★
                    </div>

                    <div class="price">

                        <span class="new-price">
                            32.990.000₫
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

                    <img src="../img/banner/news1.jpg" alt="">

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

                    <img src="../img/banner/news2.jpg" alt="">

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

                    <img src="../img/banner/news3.jpg" alt="">

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

                    <img src="../img/avatar/avatar1.png" alt="">

                    <h4>Nguyễn Văn A</h4>

                    <p>

                        "Giao hàng rất nhanh, laptop chính hãng,
                        đóng gói cẩn thận."

                    </p>

                    ★★★★★

                </div>

                <div class="testimonial-card">

                    <img src="../img/avatar/avatar2.png" alt="">

                    <h4>Trần Thị B</h4>

                    <p>

                        "Nhân viên hỗ trợ nhiệt tình,
                        giá rẻ hơn nhiều nơi."

                    </p>

                    ★★★★★

                </div>

                <div class="testimonial-card">

                    <img src="../img/avatar/avatar3.png" alt="">

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





    <!-- ================= NEWSLETTER ================= -->

    <section class="newsletter">

        <div class="container">

            <div class="newsletter-box">

                <h2>

                    Đăng ký nhận khuyến mãi

                </h2>

                <p>

                    Nhận thông tin giảm giá và sản phẩm mới nhất.

                </p>

                <form>

                    <input

                        type="email"

                        placeholder="Nhập Email của bạn">

                    <button>

                        Đăng ký

                    </button>

                </form>

            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>