<?php
// app/controller/HomeController.php

require_once __DIR__ . '/../model/Product.php';

class HomeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $productModel = new Product($this->pdo);
        $products = $productModel->getAllActiveProducts();
        
        // Memuat view dan melemparkan data produk
        require __DIR__ . '/../../views/index.php';
    }
}
?>