<?php
// app/controller/AdminController.php

require_once __DIR__ . '/../model/Product.php';

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
    }

    // Middleware sederhana untuk memastikan hanya admin yang bisa akses
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /PBP-KELOMPOK-04-2025/public/login");
            exit();
        }
    }

    // Menampilkan dashboard admin dengan daftar produk (User Story 6 & 7)
    public function dashboard() {
        $productModel = new Product($this->pdo);
        $products = $productModel->getAllProductsForAdmin();
        
        // Di sini juga akan mengambil data pesanan nanti
        // $orderModel = new Order($this->pdo);
        // $orders = $orderModel->getAllOrders();

        require __DIR__ . '/../../views/admin.php';
    }

    // Memproses penambahan produk baru (User Story 6)
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product($this->pdo);
            $success = $productModel->createProduct(
                $_POST['name'],
                $_POST['price'],
                $_POST['stock'],
                $_POST['description'],
                $_POST['gambar']
            );
            
            // Redirect kembali ke halaman admin setelah berhasil
            header("Location: /PBP-KELOMPOK-04-2025/public/admin");
            exit();
        }
    }

    // Fungsi lain untuk update dan delete produk, serta manajemen order
    // akan ditambahkan di sini.
}
?>