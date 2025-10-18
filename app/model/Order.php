<?php
// app/model/Order.php

class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Buat order dari cart items.
     * Mengembalikan order_id atau false saat gagal.
     */
    public function createOrder(int $userId, array $cartItems, string $address) {
        try {
            $this->pdo->beginTransaction();

            // Hitung total
            $total = 0;
            foreach ($cartItems as $it) {
                $price = isset($it['price']) ? (float)$it['price'] : 0.0;
                $qty = isset($it['qty']) ? (int)$it['qty'] : 0;
                $total += $price * $qty;
            }

            // Insert ke tabel orders (pastikan tabel orders punya kolom sesuai)
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total, status, address, created_at) VALUES (:user_id, :total, 'pending', :address, NOW())");
            $stmt->execute([
                ':user_id' => $userId,
                ':total' => $total,
                ':address' => $address
            ]);
            $orderId = $this->pdo->lastInsertId();

            // Insert item order (jika tabel order_items ada)
            $stmtItem = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (:order_id, :product_id, :qty, :price)");
            foreach ($cartItems as $it) {
                $productId = $it['product_id'] ?? $it['id'] ?? null;
                $qty = $it['qty'] ?? 0;
                $price = $it['price'] ?? 0;
                if ($productId) {
                    $stmtItem->execute([
                        ':order_id' => $orderId,
                        ':product_id' => $productId,
                        ':qty' => $qty,
                        ':price' => $price
                    ]);
                }
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            error_log('Order::createOrder error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Optional helper: ambil detail order
     */
    public function getById($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;
        $stmt2 = $this->pdo->prepare("SELECT oi.*, p.name, p.gambar FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $stmt2->execute([':order_id' => $orderId]);
        $order['items'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }
}
?>