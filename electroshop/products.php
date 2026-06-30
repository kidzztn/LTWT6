<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="products-page">

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <a href="index.php">Trang chủ</a>
            <span>/</span>
            <span>Sản phẩm</span>
        </div>
    </section>

    <section class="products">

        <div class="container">

            <div class="products-layout">

                <!-- Sidebar -->

                <aside class="sidebar">

                    <div class="filter-box">

                        <h3>Danh mục</h3>

                        <ul>

                            <li><input type="checkbox"> Laptop</li>

                            <li><input type="checkbox"> Điện thoại</li>

                            <li><input type="checkbox"> PC Gaming</li>

                            <li><input type="checkbox"> Màn hình</li>

                            <li><input type="checkbox"> Chuột</li>

                            <li><input type="checkbox"> Bàn phím</li>

                            <li><input type="checkbox"> Tai nghe</li>

                        </ul>

                    </div>

                    <div class="filter-box">

                        <h3>Mức giá</h3>

                        <ul>

                            <li><input type="checkbox"> Dưới 10 triệu</li>

                            <li><input type="checkbox"> 10 - 20 triệu</li>

                            <li><input type="checkbox"> 20 - 30 triệu</li>

                            <li><input type="checkbox"> Trên 30 triệu</li>

                        </ul>

                    </div>

                    <div class="filter-box">

                        <h3>Thương hiệu</h3>

                        <ul>

                            <li><input type="checkbox"> ASUS</li>

                            <li><input type="checkbox"> MSI</li>

                            <li><input type="checkbox"> Dell</li>

                            <li><input type="checkbox"> Lenovo</li>

                            <li><input type="checkbox"> Acer</li>

                            <li><input type="checkbox"> HP</li>

                            <li><input type="checkbox"> Apple</li>

                        </ul>

                    </div>

                </aside>

                <!-- Content -->

                <div class="product-content">

                    <div class="product-toolbar">

                        <h2>Tất cả sản phẩm</h2>

                        <select>

                            <option>Sắp xếp mới nhất</option>

                            <option>Giá tăng dần</option>

                            <option>Giá giảm dần</option>

                            <option>Bán chạy</option>

                        </select>

                    </div>

                    <div class="product-grid">

                        <?php for($i=1;$i<=16;$i++): ?>

                        <div class="product-card">

                            <span class="discount">-15%</span>

                            <img src="../img/products/laptop.png" alt="">

                            <h4>ASUS TUF Gaming A15</h4>

                            <div class="rating">

                                ★★★★★

                            </div>

                            <div class="price">

                                <span class="new-price">

                                    28.990.000₫

                                </span>

                                <span class="old-price">

                                    31.990.000₫

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

                    <div class="pagination">

                        <a href="#">«</a>

                        <a class="active" href="#">1</a>

                        <a href="#">2</a>

                        <a href="#">3</a>

                        <a href="#">4</a>

                        <a href="#">»</a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php
include 'includes/footer.php';
?>