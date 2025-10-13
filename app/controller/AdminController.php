<?php
// app/controller/AdminController.php

require_once __DIR__ . '/../model/Product.php';
require_once __DIR__ . '/../model/Order.php'; // Tambahkan model Order

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login"); // Path lebih bersih
            exit();
        }
    }

    // Menampilkan dashboard utama
    public function dashboard() {
        $productModel = new Product($this->pdo);
        $products = $productModel->getAllProductsForAdmin();
        
        $orderModel = new Order($this->pdo);
        $orders = $orderModel->getAllOrders();

        require __DIR__ . '/../../views/admin.php';
    }

    // API untuk mengambil semua produk (untuk AJAX)
    public function getProductsJson() {
        header('Content-Type: application/json');
        $productModel = new Product($this->pdo);
        $products = $productModel->getAllProductsForAdmin();
        echo json_encode(['success' => true, 'products' => $products]);
        exit();
    }

    // Menambah produk
    public function createProduct() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product($this->pdo);
            $success = $productModel->createProduct(
                $_POST['name'], $_POST['price'], $_POST['stock'], 
                $_POST['description'], $_POST['gambar']
            );
            echo json_encode(['success' => $success]);
            exit();
        }
    }

    // Mengupdate produk
    public function updateProduct() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product($this->pdo);
            $success = $productModel->updateProduct(
                $_POST['id'], $_POST['name'], $_POST['price'], $_POST['stock'], 
                $_POST['description'], $_POST['gambar']
            );
            echo json_encode(['success' => $success]);
            exit();
        }
    }

    // Menghapus produk (soft delete)
    public function deleteProduct() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product($this->pdo);
            $success = $productModel->deleteProduct($_POST['id']);
            echo json_encode(['success' => $success]);
            exit();
        }
    }
    
    // Mengupdate status pesanan
    public function updateOrderStatus() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = new Order($this->pdo);
            $success = $orderModel->updateStatus($_POST['order_id'], $_POST['status']);
            echo json_encode(['success' => $success]);
            exit();
        }
    }
}
?>