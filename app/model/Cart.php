<?php
// app/model/Cart.php

class Cart {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Mendapatkan cart milik user, atau membuat yang baru jika belum ada
    public function getOrCreateCartByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM carts WHERE user_id = :user_id LIMIT 1");
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cart) return $cart;

            $stmtIns = $this->pdo->prepare("INSERT INTO carts (user_id, created_at) VALUES (:user_id, NOW())");
            $stmtIns->execute([':user_id' => $userId]);
            $id = $this->pdo->lastInsertId();
            return ['id_carts' => $id, 'user_id' => $userId];
        } catch (Throwable $e) {
            error_log('Cart::getOrCreateCartByUserId error: ' . $e->getMessage());
            return null;
        }
    }

    // Menambah item ke keranjang
    public function addItem($cartId, $productId, $qty) {
        // Cek apakah item sudah ada di keranjang
        $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Jika sudah ada, update kuantitasnya
            $newQty = $existingItem['qty'] + $qty;
            $stmt = $this->pdo->prepare("UPDATE cart_items SET qty = :qty WHERE id_cart_items = :id");
            return $stmt->execute([':qty' => $newQty, ':id' => $existingItem['id_cart_items']]);
        } else {
            // Jika belum ada, tambahkan sebagai item baru
            $cartItemId = 'CI' . time() . rand(10, 99);
            $stmt = $this->pdo->prepare("INSERT INTO cart_items (id_cart_items, cart_id, product_id, qty) VALUES (:id, :cart_id, :product_id, :qty)");
            return $stmt->execute([':id' => $cartItemId, ':cart_id' => $cartId, ':product_id' => $productId, ':qty' => $qty]);
        }
    }
    
    // Mengambil semua item di keranjang beserta detail produknya
    public function getCartItems($cartId) {
        if (!$cartId) return [];
        try {
            $stmt = $this->pdo->prepare("SELECT ci.*, p.name, p.price, p.gambar FROM cart_items ci LEFT JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = :cart_id");
            $stmt->execute([':cart_id' => $cartId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log('Cart::getCartItems error: ' . $e->getMessage());
            return [];
        }
    }

    // Menghapus item dari keranjang
    public function removeItem($cartItemId) {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE id_cart_items = ?");
        return $stmt->execute([$cartItemId]);
    }
    
    // Mengosongkan keranjang (setelah checkout)
    public function clearCart($cartId) {
        if (!$cartId) return;
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            $stmt->execute([':cart_id' => $cartId]);
        } catch (Throwable $e) {
            error_log('Cart::clearCart error: ' . $e->getMessage());
        }
    }
}
?>