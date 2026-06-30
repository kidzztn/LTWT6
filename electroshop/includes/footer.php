<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="container">

            <div class="hero-wrapper">

                <!-- Banner lớn -->

                <div class="hero-left">

                    <div class="hero-slider">

                        <img src="../img/banner/banner1.jpg" alt="Banner">

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

                        <img src="../img/banner/banner2.jpg" alt="">

                    </div>

                    <div class="small-banner">

                        <img src="../img/banner/banner3.jpg" alt="">

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

    <!-- ================= CATEGORY ================= -->

    <section class="category">

        <div class="container">

            <div class="section-title">

                <h2>Danh mục nổi bật</h2>

            </div>

            <div class="category-grid">

                <a href="products.php" class="category-item">

                    <img src="../img/icons/laptop.png">

                    <h4>Laptop</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/phone.png">

                    <h4>Điện thoại</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/pc.png">

                    <h4>PC Gaming</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/headphone.png">

                    <h4>Tai nghe</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/mouse.png">

                    <h4>Chuột</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/keyboard.png">

                    <h4>Bàn phím</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/ssd.png">

                    <h4>SSD</h4>

                </a>

                <a href="products.php" class="category-item">

                    <img src="../img/icons/ram.png">

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

                <?php for($i=1;$i<=8;$i++): ?>

                <div class="product-card">

                    <span class="discount">-20%</span>

                    <img src="../img/products/laptop.png">

                    <h4>ASUS ROG STRIX G16</h4>

                    <div class="rating">

                        ★★★★★

                    </div>

                    <div class="price">

                        <span class="new-price">

                            25.990.000đ

                        </span>

                        <span class="old-price">

                            30.990.000đ

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



<?php include 'includes/footer.php'; ?>
</main>
