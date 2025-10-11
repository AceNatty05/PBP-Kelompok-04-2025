<?php
// Sertakan file model yang dibutuhkan
require_once __DIR__ . '/../model/Product.php';

class ProductController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fungsi untuk menampilkan halaman utama dengan daftar produk
    public function index() {
        // Buat instance dari model Product
        $productModel = new Product($this->pdo);
        // Panggil fungsi untuk mendapatkan semua produk
        $products = $productModel->getAllProducts();

        // Panggil file view dan kirimkan data $products ke sana
        // Variabel $products akan bisa diakses di dalam file view
        require __DIR__ . '/../../views/index.php';
    }

    // Anda bisa menambahkan fungsi lain di sini nanti, misalnya:
    // public function showCart() { ... }
    // public function checkout() { ... }
}
?>