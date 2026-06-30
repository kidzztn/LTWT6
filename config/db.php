<?php
// ===================================================
// KẾT NỐI DATABASE
// Đổi DB_USER / DB_PASS nếu XAMPP MySQL của bạn có mật khẩu
// ===================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'electroshop');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    return $pdo;
}

function ensureDatabaseSchema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(120) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT DEFAULT NULL,
            name VARCHAR(150) NOT NULL,
            slug VARCHAR(180) NOT NULL,
            price DECIMAL(12,0) NOT NULL DEFAULT 0,
            stock INT NOT NULL DEFAULT 0,
            image VARCHAR(255) DEFAULT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            password_hash VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    try {
        $pdo->exec("ALTER TABLE customers ADD COLUMN password_hash VARCHAR(255) DEFAULT NULL");
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false && strpos($e->getMessage(), 'already exists') === false) {
            throw $e;
        }
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT DEFAULT NULL,
            total DECIMAL(12,0) NOT NULL DEFAULT 0,
            status ENUM('pending','success','cancel') NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT DEFAULT NULL,
            product_name VARCHAR(150) NOT NULL,
            price DECIMAL(12,0) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $adminCount = (int) $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCount === 0) {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password, full_name) VALUES (?, ?, ?)");
        $stmt->execute([
            'admin',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Quản trị viên'
        ]);
    }

    $categoryCount = (int) $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    if ($categoryCount === 0) {
        $pdo->exec("INSERT INTO categories (name, slug) VALUES
            ('Điện thoại', 'dien-thoai'),
            ('Laptop', 'laptop'),
            ('Linh kiện máy tính', 'linh-kien-may-tinh')");
    }

    $productCount = (int) $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($productCount === 0) {
        $pdo->exec("INSERT INTO products (category_id, name, slug, price, stock, description) VALUES
            (1, 'iPhone 16 Pro Max', 'iphone-16-pro-max', 34990000, 20, 'Flagship Apple mới nhất'),
            (2, 'Macbook Air M4', 'macbook-air-m4', 28990000, 15, 'Laptop mỏng nhẹ hiệu năng cao'),
            (3, 'RTX 5070', 'rtx-5070', 21500000, 10, 'Card đồ họa chơi game/AI')");
    }

    $customerCount = (int) $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    if ($customerCount === 0) {
        $pdo->exec("INSERT INTO customers (name, email, phone) VALUES
            ('Nguyễn Văn A', 'a.nguyen@example.com', '0901111111'),
            ('Trần Văn B', 'b.tran@example.com', '0902222222'),
            ('Lê Minh C', 'c.le@example.com', '0903333333')");
    }

    $orderCount = (int) $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    if ($orderCount === 0) {
        $pdo->exec("INSERT INTO orders (customer_id, total, status) VALUES
            (1, 34990000, 'success'),
            (2, 28990000, 'pending'),
            (3, 21500000, 'cancel')");
        $pdo->exec("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES
            (1, 1, 'iPhone 16 Pro Max', 34990000, 1),
            (2, 2, 'Macbook Air M4', 28990000, 1),
            (3, 3, 'RTX 5070', 21500000, 1)");
    }
}

try {
    $pdo = getPdo();
    ensureDatabaseSchema($pdo);
} catch (PDOException $e) {
    die("Kết nối database thất bại: " . $e->getMessage());
}