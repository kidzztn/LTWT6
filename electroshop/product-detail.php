<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main>

    <!-- Breadcrumb -->

    <section class="breadcrumb-section">

        <div class="container">

            <a href="index.php">Trang chủ</a>

            <span>/</span>

            <a href="products.php">Laptop</a>

            <span>/</span>

            <span>ASUS ROG STRIX G16</span>

        </div>

    </section>


    <!-- Product Detail -->

    <section class="product-detail">

        <div class="container">

            <div class="detail-wrapper">

                <!-- Left -->

                <div class="product-gallery">

                    <div class="main-image">

                        <img src="../img/products/laptop.png" alt="">

                    </div>

                    <div class="thumb-list">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                        <img src="../img/products/laptop.png">

                    </div>

                </div>

                <!-- Right -->

                <div class="product-info">

                    <h1>

                        ASUS ROG STRIX G16 RTX4060

                    </h1>

                    <div class="rating">

                        ★★★★★

                        <span>(256 đánh giá)</span>

                    </div>

                    <div class="price-box">

                        <span class="new-price">

                            29.990.000₫

                        </span>

                        <span class="old-price">

                            34.990.000₫

                        </span>

                        <span class="discount">

                            -15%

                        </span>

                    </div>

                    <div class="promotion">

                        <h4>Khuyến mãi</h4>

                        <ul>

                            <li>✔ Giảm ngay 1.000.000đ</li>

                            <li>✔ Trả góp 0%</li>

                            <li>✔ Tặng balo Gaming</li>

                            <li>✔ Miễn phí giao hàng</li>

                        </ul>

                    </div>

                    <div class="quantity">

                        <label>Số lượng</label>

                        <input type="number" value="1" min="1">

                    </div>

                    <div class="detail-button">

                        <button class="buy-now">

                            MUA NGAY

                        </button>

                        <button class="add-cart">

                            THÊM GIỎ HÀNG

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- Description -->

    <section class="description">

        <div class="container">

            <h2>Mô tả sản phẩm</h2>

            <p>

                ASUS ROG STRIX G16 là mẫu laptop gaming cao cấp
                sử dụng Intel Core i7 thế hệ mới,
                RTX 4060,
                RAM DDR5,
                SSD PCIe Gen4
                và màn hình 165Hz.

            </p>

        </div>

    </section>



    <!-- Specification -->

    <section class="specification">

        <div class="container">

            <h2>Thông số kỹ thuật</h2>

            <table>

                <tr>

                    <td>CPU</td>

                    <td>Intel Core i7-14700HX</td>

                </tr>

                <tr>

                    <td>RAM</td>

                    <td>16GB DDR5</td>

                </tr>

                <tr>

                    <td>Ổ cứng</td>

                    <td>SSD 1TB NVMe</td>

                </tr>

                <tr>

                    <td>Card đồ họa</td>

                    <td>RTX4060 8GB</td>

                </tr>

                <tr>

                    <td>Màn hình</td>

                    <td>16 inch 165Hz</td>

                </tr>

            </table>

        </div>

    </section>



    <!-- Related -->

    <section class="related-product">

        <div class="container">

            <div class="section-title">

                <h2>Sản phẩm liên quan</h2>

            </div>

            <div class="product-grid">

                <?php for($i=1;$i<=4;$i++): ?>

                <div class="product-card">

                    <img src="../img/products/laptop.png">

                    <h4>ASUS TUF Gaming</h4>

                    <div class="price">

                        <span class="new-price">

                            27.990.000₫

                        </span>

                    </div>

                    <a href="product-detail.php">

                        Xem chi tiết

                    </a>

                </div>

                <?php endfor; ?>

            </div>

        </div>

    </section>

</main>

<?php
include 'includes/footer.php';
?>