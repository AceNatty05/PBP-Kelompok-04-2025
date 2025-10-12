<?php
// public/index.php

session_start();

// 1. Muat Koneksi Database
require_once __DIR__ . '/../config/database.php';

// 2. Muat semua Model dan Controller
require_once __DIR__ . '/../app/model/User.php';
require_once __DIR__ . '/../app/model/Product.php';
// ... (tambahkan model lain seperti Cart.php dan Order.php nanti)

require_once __DIR__ . '/../app/controller/HomeController.php';
require_once __DIR__ . '/../app/controller/AuthController.php'; // Tetap ada untuk logout dan session
require_once __DIR__ . '/../app/controller/ProductController.php';
require_once __DIR__ . '/../app/controller/AdminController.php';

// 3. Simple Routing
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/PBP-KELOMPOK-04-2025/public';
$route = str_replace($base_path, '', $request_uri);
$route = strtok($route, '?'); // Hapus query string

switch ($route) {
    // Rute Umum & Produk
    case '/':
        $controller = new HomeController($pdo);
        $controller->index();
        break;
    case '/products':
        $controller = new ProductController($pdo);
        $controller->listProducts();
        break;
    
    // Rute Autentikasi
    case '/login':
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case '/register':
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;
    case '/logout':
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    // Rute Admin
    case '/admin':
        $controller = new AdminController($pdo);
        $controller->dashboard();
        break;
    case '/admin/products/create':
        $controller = new AdminController($pdo);
        $controller->createProduct();
        break;
    // ... (tambahkan rute admin lain untuk edit, delete, orders)

    // Rute Keranjang & Pesanan (akan ditambahkan nanti)
    // case '/cart':
    // case '/checkout':

    default:
        http_response_code(404);
        echo "<h1>404 Page Not Found</h1>";
        break;
}
?>