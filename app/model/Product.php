<?php
class Product {
    // Properti untuk menampung koneksi database
    private $pdo;

    // Constructor untuk menerima koneksi database saat objek dibuat
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fungsi untuk mengambil semua data produk dari database
    public function getAllProducts() {
        // Siapkan query SQL untuk mengambil semua produk
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_active = TRUE ORDER BY created_at DESC");
        $stmt->execute();
        // Kembalikan hasilnya dalam bentuk array
        return $stmt->fetchAll();
    }

    // Anda bisa menambahkan fungsi lain di sini nanti, misalnya:
    // public function getProductById($id) { ... }
    // public function createProduct($data) { ... }
    // public function updateProduct($id, $data) { ... }
    // public function deleteProduct($id) { ... }
}
?>