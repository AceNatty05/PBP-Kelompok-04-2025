<?php
// app/model/Product.php

class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil semua produk yang aktif. (User Story 1)
     */
    public function getAllActiveProducts() {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_active = TRUE ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mencari produk berdasarkan nama. (User Story 2)
     */
    public function searchProducts($searchTerm) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE name LIKE ? AND is_active = TRUE");
        $stmt->execute(['%' . $searchTerm . '%']);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil semua produk (untuk admin).
     */
    public function getAllProductsForAdmin() {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories_x_products cxp ON p.id_products = cxp.products_id_products LEFT JOIN categories c ON cxp.categories_id_categories = c.id_categories ORDER BY p.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Membuat produk baru. (User Story 6)
     */
    public function createProduct($name, $price, $stock, $description, $gambar) {
        // ID Produk unik
        $productId = 'PRD' . time();

        $stmt = $this->pdo->prepare(
            "INSERT INTO products (id_products, name, gambar, price, stock, description) VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$productId, $name, $gambar, $price, $stock, $description]);
    }

    /**
     * Mengupdate data produk. (User Story 6)
     */
    public function updateProduct($id, $name, $price, $stock, $description, $gambar) {
        $stmt = $this->pdo->prepare(
            "UPDATE products SET name = ?, gambar = ?, price = ?, stock = ?, description = ? WHERE id_products = ?"
        );
        return $stmt->execute([$name, $gambar, $price, $stock, $description, $id]);
    }

    /**
     * Menghapus produk (soft delete dengan mengubah is_active). (User Story 6)
     */
    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("UPDATE products SET is_active = FALSE WHERE id_products = ?");
        return $stmt->execute([$id]);
    }
}
?>