<?php
// app/model/Cart.php

class Cart {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Mendapatkan cart milik user, atau membuat yang baru jika belum ada
    public function getOrCreateCartByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM carts WHERE users_id_users = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();

        if (!$cart) {
            $cartId = 'CRT' . time();
            $stmt = $this->pdo->prepare("INSERT INTO carts (id_carts, users_id_users) VALUES (?, ?)");
            $stmt->execute([$cartId, $userId]);
            return ['id_carts' => $cartId, 'users_id_users' => $userId];
        }
        return $cart;
    }

    // Menambah item ke keranjang
    public function addItem($cartId, $productId, $qty) {
        // Cek apakah item sudah ada di keranjang
        $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE cart_id_carts = ? AND products_id_products = ?");
        $stmt->execute([$cartId, $productId]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Jika sudah ada, update kuantitasnya
            $newQty = $existingItem['qty'] + $qty;
            $stmt = $this->pdo->prepare("UPDATE cart_items SET qty = ? WHERE id_cart_items = ?");
            return $stmt->execute([$newQty, $existingItem['id_cart_items']]);
        } else {
            // Jika belum ada, tambahkan sebagai item baru
            $cartItemId = 'CI' . time() . rand(10, 99);
            $stmt = $this->pdo->prepare("INSERT INTO cart_items (id_cart_items, cart_id_carts, products_id_products, qty) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$cartItemId, $cartId, $productId, $qty]);
        }
    }
    
    // Mengambil semua item di keranjang beserta detail produknya
    public function getCartItems($cartId) {
        $stmt = $this->pdo->prepare(
            "SELECT ci.*, p.name, p.price, p.gambar 
             FROM cart_items ci 
             JOIN products p ON ci.products_id_products = p.id_products 
             WHERE ci.cart_id_carts = ?"
        );
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    // Menghapus item dari keranjang
    public function removeItem($cartItemId) {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE id_cart_items = ?");
        return $stmt->execute([$cartItemId]);
    }
    
    // Mengosongkan keranjang (setelah checkout)
    public function clearCart($cartId) {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE cart_id_carts = ?");
        return $stmt->execute([$cartId]);
    }
}
?>