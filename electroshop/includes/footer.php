<footer class="footer">
    <div class="footer-newsletter">
        <div class="container">
            <div class="newsletter-strip">
                <div>
                    <h3>Nhận ưu đãi độc quyền</h3>
                    <p>Đăng ký ngay để nhận thông tin khuyến mãi, giảm giá và sản phẩm mới nhất.</p>
                </div>
                <form class="newsletter-inline" action="products.php" method="get">
                    <input type="hidden" name="query" value="khuyến mãi">
                    <input type="email" placeholder="Email của bạn" required>
                    <button type="submit">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="footer-grid">
            <div class="footer-item">
                <h3>ElectroShop</h3>
                <p>Chuyên cung cấp laptop, điện thoại, PC gaming, phụ kiện và thiết bị công nghệ chính hãng với giá tốt nhất.</p>
            </div>

            <div class="footer-item">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li><a href="products.php">Chính sách bảo hành</a></li>
                    <li><a href="products.php">Chính sách đổi trả</a></li>
                    <li><a href="products.php">Hướng dẫn mua hàng</a></li>
                    <li><a href="products.php">Liên hệ</a></li>
                </ul>
            </div>

            <div class="footer-item">
                <h3>Danh mục</h3>
                <ul>
                    <li><a href="products.php?category=laptop">Laptop</a></li>
                    <li><a href="products.php?category=dien-thoai">Điện thoại</a></li>
                    <li><a href="products.php?query=gaming">PC Gaming</a></li>
                    <li><a href="products.php?category=linh-kien-may-tinh">Linh kiện</a></li>
                </ul>
            </div>

            <div class="footer-item">
                <h3>Liên hệ</h3>
                <p><i class="fa-solid fa-location-dot"></i> Bình Dương, Việt Nam</p>
                <p><i class="fa-solid fa-phone"></i> 1900 1234</p>
                <p><i class="fa-solid fa-envelope"></i> support@electroshop.vn</p>
                <div class="social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>© <?php echo date("Y"); ?> ElectroShop. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script src="../js/script.js?v=1.1"></script>

</body>
</html>