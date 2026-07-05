START TRANSACTION;

UPDATE orders o
LEFT JOIN customers c ON c.id = o.customer_id
SET o.customer_id = NULL
WHERE o.customer_id IS NOT NULL
  AND c.id IS NULL;

ALTER TABLE orders
    MODIFY customer_id INT(11) NULL;

SET @orders_customer_fk_name := (
    SELECT kcu.CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE kcu
    JOIN information_schema.TABLE_CONSTRAINTS tc
      ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
     AND tc.TABLE_NAME = kcu.TABLE_NAME
     AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
    WHERE kcu.TABLE_SCHEMA = DATABASE()
      AND kcu.TABLE_NAME = 'orders'
      AND kcu.COLUMN_NAME = 'customer_id'
      AND kcu.REFERENCED_TABLE_NAME = 'customers'
      AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
    LIMIT 1
);

SET @drop_fk_sql := IF(
    @orders_customer_fk_name IS NULL,
    'SELECT 1',
    CONCAT('ALTER TABLE orders DROP FOREIGN KEY `', @orders_customer_fk_name, '`')
);
PREPARE stmt_drop_fk FROM @drop_fk_sql;
EXECUTE stmt_drop_fk;
DEALLOCATE PREPARE stmt_drop_fk;

SET @has_customer_idx := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'orders'
      AND COLUMN_NAME = 'customer_id'
      AND SEQ_IN_INDEX = 1
);

SET @add_idx_sql := IF(
    @has_customer_idx = 0,
    'ALTER TABLE orders ADD INDEX idx_orders_customer_id (customer_id)',
    'SELECT 1'
);
PREPARE stmt_add_idx FROM @add_idx_sql;
EXECUTE stmt_add_idx;
DEALLOCATE PREPARE stmt_add_idx;

ALTER TABLE orders
    ADD CONSTRAINT fk_orders_customer_id
    FOREIGN KEY (customer_id) REFERENCES customers(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;

COMMIT;
