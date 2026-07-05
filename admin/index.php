<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

function fetchScalar(PDO $pdo, string $sql, array $params = [])
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn();
}

$rangeOptions = [
    'today' => ['label' => 'Hôm nay', 'days' => 1],
    '7d' => ['label' => '7 ngày', 'days' => 7],
    '30d' => ['label' => '30 ngày', 'days' => 30],
];

$selectedRange = (string) ($_GET['range'] ?? '7d');
if (!isset($rangeOptions[$selectedRange])) {
    $selectedRange = '7d';
}

$today = new DateTimeImmutable('today');
$startDate = $today->sub(new DateInterval('P' . ($rangeOptions[$selectedRange]['days'] - 1) . 'D'));
$startDateTime = $startDate->format('Y-m-d 00:00:00');
$rangeLabel = $rangeOptions[$selectedRange]['label'];

$productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$orderCount = (int) fetchScalar($pdo, 'SELECT COUNT(*) FROM orders o WHERE o.created_at >= :startDate', ['startDate' => $startDateTime]);
$customerCount = (int) $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$categoryCount = (int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$paidRevenue = (float) fetchScalar(
    $pdo,
    "SELECT COALESCE(SUM(o.total), 0)
     FROM orders o
     WHERE o.created_at >= :startDate
       AND (o.payment_status = 'paid' OR o.status = 'success')",
    ['startDate' => $startDateTime]
);
$pendingOrderCount = (int) fetchScalar(
    $pdo,
    "SELECT COUNT(*)
     FROM orders o
     WHERE o.created_at >= :startDate
       AND o.status = 'pending'",
    ['startDate' => $startDateTime]
);
$unpaidOrderCount = (int) fetchScalar(
    $pdo,
    "SELECT COUNT(*)
     FROM orders o
     WHERE o.created_at >= :startDate
       AND o.payment_status = 'unpaid'",
    ['startDate' => $startDateTime]
);
$paidOrderCount = (int) fetchScalar(
    $pdo,
    "SELECT COUNT(*)
     FROM orders o
     WHERE o.created_at >= :startDate
       AND (o.payment_status = 'paid' OR o.status = 'success')",
    ['startDate' => $startDateTime]
);
$todayOrderCount = (int) fetchScalar($pdo, 'SELECT COUNT(*) FROM orders o WHERE DATE(o.created_at) = CURDATE()');

$topSellingStmt = $pdo->prepare(
    "SELECT p.name, COALESCE(SUM(oi.quantity), 0) AS sold_qty
     FROM order_items oi
     INNER JOIN orders o ON o.id = oi.order_id
     INNER JOIN products p ON p.id = oi.product_id
     WHERE o.created_at >= :startDate
     GROUP BY p.id, p.name
     ORDER BY sold_qty DESC, p.id DESC
     LIMIT 5"
);
$topSellingStmt->execute(['startDate' => $startDateTime]);
$topSellingProducts = $topSellingStmt->fetchAll();

$lowStockProducts = $pdo->query(
    'SELECT id, name, stock FROM products ORDER BY stock ASC, id DESC LIMIT 5'
)->fetchAll();

$recentOrdersStmt = $pdo->prepare(
    "SELECT o.id, c.name AS customer_name, o.total, o.status, o.payment_method, o.payment_status, o.created_at
     FROM orders o
     LEFT JOIN customers c ON c.id = o.customer_id
     WHERE o.created_at >= :startDate
     ORDER BY o.created_at DESC
     LIMIT 5"
);
$recentOrdersStmt->execute(['startDate' => $startDateTime]);
$recentOrders = $recentOrdersStmt->fetchAll();

$paymentStats = [
    'cash' => ['orders' => 0, 'paid_revenue' => 0.0],
    'transfer' => ['orders' => 0, 'paid_revenue' => 0.0],
];

$paymentStatsStmt = $pdo->prepare(
    "SELECT COALESCE(o.payment_method, 'cash') AS payment_method,
            COUNT(*) AS total_orders,
            COALESCE(SUM(CASE WHEN o.payment_status = 'paid' OR o.status = 'success' THEN o.total ELSE 0 END), 0) AS paid_revenue
     FROM orders o
     WHERE o.created_at >= :startDate
     GROUP BY COALESCE(o.payment_method, 'cash')"
);
$paymentStatsStmt->execute(['startDate' => $startDateTime]);
foreach ($paymentStatsStmt->fetchAll() as $methodRow) {
    $method = (string) ($methodRow['payment_method'] ?? 'cash');
    if (!isset($paymentStats[$method])) {
        continue;
    }

    $paymentStats[$method]['orders'] = (int) $methodRow['total_orders'];
    $paymentStats[$method]['paid_revenue'] = (float) $methodRow['paid_revenue'];
}

$dailyRevenueStmt = $pdo->prepare(
    "SELECT DATE(o.created_at) AS revenue_date,
            COALESCE(SUM(o.total), 0) AS daily_revenue
     FROM orders o
     WHERE o.created_at >= :startDate
       AND (o.payment_status = 'paid' OR o.status = 'success')
     GROUP BY DATE(o.created_at)
     ORDER BY revenue_date ASC"
);
$dailyRevenueStmt->execute(['startDate' => $startDateTime]);
$dailyRevenueMap = [];
foreach ($dailyRevenueStmt->fetchAll() as $dayRow) {
    $dailyRevenueMap[(string) $dayRow['revenue_date']] = (float) $dayRow['daily_revenue'];
}

$chartLabels = [];
$chartValues = [];
$cursorDate = $startDate;
while ($cursorDate <= $today) {
    $dateKey = $cursorDate->format('Y-m-d');
    $chartLabels[] = $cursorDate->format('d/m');
    $chartValues[] = $dailyRevenueMap[$dateKey] ?? 0;
    $cursorDate = $cursorDate->add(new DateInterval('P1D'));
}

$rangeLinks = [];
foreach ($rangeOptions as $rangeKey => $rangeOption) {
    $rangeLinks[$rangeKey] = '?range=' . urlencode($rangeKey);
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main">

        <?php include __DIR__ . '/includes/topbar.php'; ?>

        <div class="content">

            <div class="content-header">
                <div>
                    <h2>Tổng quan hệ thống</h2>
                    <p>Dữ liệu đang hiển thị theo khoảng: <?php echo htmlspecialchars($rangeLabel); ?>.</p>
                </div>
                <span class="content-header-badge">
                    <i class="fa-solid fa-chart-line"></i>
                    Cập nhật theo dữ liệu thực tế
                </span>
            </div>

            <div class="dashboard-toolbar">
                <div class="range-filter" role="group" aria-label="Lọc thời gian dashboard">
                    <?php foreach ($rangeOptions as $rangeKey => $rangeOption): ?>
                        <a class="<?php echo $selectedRange === $rangeKey ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($rangeLinks[$rangeKey]); ?>">
                            <?php echo htmlspecialchars($rangeOption['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-box">

                <div class="card">
                    <i class="fa-solid fa-box"></i>
                    <h3><?php echo number_format($productCount); ?></h3>
                    <span>Sản phẩm</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3><?php echo number_format($orderCount); ?></h3>
                    <span>Đơn trong kỳ</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-users"></i>
                    <h3><?php echo number_format($customerCount); ?></h3>
                    <span>Khách hàng</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <h3><?php echo number_format($paidRevenue, 0, ',', '.'); ?>₫</h3>
                    <span>Doanh thu đã thu</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-receipt"></i>
                    <h3><?php echo number_format($unpaidOrderCount); ?></h3>
                    <span>Đơn chưa thanh toán</span>
                </div>

                <div class="card">
                    <i class="fa-solid fa-calendar-day"></i>
                    <h3><?php echo number_format($todayOrderCount); ?></h3>
                    <span>Đơn hôm nay</span>
                </div>

            </div>

            <div class="table-box">
                <div class="table-header">
                    <h3>Biểu đồ doanh thu theo ngày</h3>
                    <p class="muted-text">Doanh thu đã thanh toán trong khoảng <?php echo htmlspecialchars($rangeLabel); ?>.</p>
                </div>
                <div class="chart-wrap">
                    <canvas id="revenueByDayChart" aria-label="Biểu đồ doanh thu theo ngày" role="img"></canvas>
                </div>
            </div>

            <div class="payment-kpi-grid">
                <div class="payment-kpi">
                    <h4>COD</h4>
                    <p class="kpi-value"><?php echo number_format($paymentStats['cash']['orders']); ?> đơn</p>
                    <p class="kpi-sub">Đã thu: <?php echo number_format($paymentStats['cash']['paid_revenue'], 0, ',', '.'); ?>₫</p>
                </div>

                <div class="payment-kpi">
                    <h4>Chuyển khoản</h4>
                    <p class="kpi-value"><?php echo number_format($paymentStats['transfer']['orders']); ?> đơn</p>
                    <p class="kpi-sub">Đã thu: <?php echo number_format($paymentStats['transfer']['paid_revenue'], 0, ',', '.'); ?>₫</p>
                </div>
            </div>

            <div class="table-box">

                <div class="table-header">
                    <h3>Tổng quan vận hành</h3>
                </div>

                <table style="margin-bottom:20px;">
                    <tr>
                        <th>Chỉ số</th>
                        <th>Giá trị</th>
                    </tr>
                    <tr>
                        <td>Tổng danh mục</td>
                        <td><?php echo number_format($categoryCount); ?></td>
                    </tr>
                    <tr>
                        <td>Đơn đang xử lý</td>
                        <td><?php echo number_format($pendingOrderCount); ?></td>
                    </tr>
                    <tr>
                        <td>Đơn đã thanh toán</td>
                        <td><?php echo number_format($paidOrderCount); ?></td>
                    </tr>
                </table>

            </div>

            <div class="table-box" style="margin-top:20px;">

                <div class="table-header">
                    <h3>Sản phẩm bán chạy (theo đơn)</h3>
                </div>

                <table>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đã bán</th>
                    </tr>
                    <?php if (empty($topSellingProducts)): ?>
                        <tr>
                            <td colspan="2" class="empty-state">Chưa có dữ liệu bán hàng trong khoảng thời gian này.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topSellingProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo number_format((int) $product['sold_qty']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>

            </div>

            <div class="table-box" style="margin-top:20px;">

                <div class="table-header">
                    <h3>Cảnh báo tồn kho thấp</h3>
                </div>

                <table>
                    <tr>
                        <th>Mã</th>
                        <th>Sản phẩm</th>
                        <th>Tồn kho</th>
                    </tr>
                    <?php foreach ($lowStockProducts as $product): ?>
                        <tr>
                            <td>#<?php echo (int) $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo number_format((int) $product['stock']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>

            <div class="table-box" style="margin-top:20px;">

                <div class="table-header">
                    <h3>Đơn hàng mới</h3>
                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Ngày tạo</th>
                    </tr>

                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?php echo (int) $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai'); ?></td>
                            <td><?php echo number_format((float) $order['total'], 0, ',', '.'); ?>₫</td>
                            <td>
                                <?php
                                $statusClass = 'pending';
                                $statusText = 'Đang xử lý';
                                if ($order['status'] === 'success') {
                                    $statusClass = 'success';
                                    $statusText = 'Đã thanh toán';
                                } elseif ($order['status'] === 'cancel') {
                                    $statusClass = 'cancel';
                                    $statusText = 'Đã hủy';
                                }

                                $paymentMethodText = ($order['payment_method'] ?? 'cash') === 'transfer' ? 'Chuyển khoản' : 'COD';
                                $paymentStatusText = match ($order['payment_status'] ?? 'unpaid') {
                                    'paid' => 'Đã thanh toán',
                                    'refunded' => 'Đã hoàn tiền',
                                    default => 'Chưa thanh toán',
                                };
                                ?>
                                <span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($paymentMethodText . ' - ' . $paymentStatusText); ?></td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Không có đơn hàng trong khoảng thời gian đã chọn.</td>
                        </tr>
                    <?php endif; ?>

                </table>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
    const labels = <?php echo json_encode($chartLabels, JSON_UNESCAPED_UNICODE); ?>;
    const values = <?php echo json_encode($chartValues, JSON_UNESCAPED_UNICODE); ?>;
    const canvas = document.getElementById('revenueByDayChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: values,
                borderColor: '#E30019',
                backgroundColor: 'rgba(227, 0, 25, 0.12)',
                tension: 0.3,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: (value) => Number(value).toLocaleString('vi-VN') + '₫'
                    }
                }
            }
        }
    });
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
