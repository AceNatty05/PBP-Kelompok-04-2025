<?php
// app/model/Order.php

class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Membuat pesanan baru (ini adalah proses transaksi)
    public function createOrder($userId, $cartItems, $address) {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $orderId = 'ORD' . time();
        
        try {
            $this->pdo->beginTransaction();

            // 1. Masukkan data ke tabel 'orders'
            $stmt = $this->pdo->prepare(
                "INSERT INTO orders (id_orders, users_id_users, total, status, address_text) VALUES (?, ?, ?, 'pending', ?)"
            );
            $stmt->execute([$orderId, $userId, $total, $address]);

            // 2. Masukkan setiap item keranjang ke 'order_items'
            $stmtItem = $this->pdo->prepare(
                "INSERT INTO order_items (id_order_items, orders_id_orders, products_id_products, price, qty, subtotal) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmtUpdateStock = $this->pdo->prepare(
                "UPDATE products SET stock = stock - ? WHERE id_products = ?"
            );

            foreach ($cartItems as $item) {
                $orderItemId = 'OI' . time() . rand(10, 99);
                $subtotal = $item['price'] * $item['qty'];
                $stmtItem->execute([$orderItemId, $orderId, $item['products_id_products'], $item['price'], $item['qty'], $subtotal]);
                
                // 3. Kurangi stok produk
                $stmtUpdateStock->execute([$item['qty'], $item['products_id_products']]);
            }
            
            $this->pdo->commit();
            return $orderId; // Berhasil, kembalikan ID pesanan

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage()); // Log error untuk debugging
            return false; // Gagal
        }
    }
    
    // Mengambil semua pesanan untuk admin
    public function getAllOrders() {
        $stmt = $this->pdo->prepare(
            "SELECT o.*, u.name as user_name 
             FROM orders o 
             JOIN users u ON o.users_id_users = u.id_users 
             ORDER BY o.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mengubah status pesanan
    public function updateStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
        return $stmt->execute([$status, $orderId]);
    }
}
?>