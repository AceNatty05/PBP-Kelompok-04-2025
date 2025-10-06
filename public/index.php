<?php
// public/index.php

// Mulai session di awal
session_start();

// Muat file konfigurasi dan controller
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controller/ProductController.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
// Perbaikan: Tambahkan controller baru di sini nanti saat Anda membuatnya
// require_once __DIR__ . '/../app/controller/CartController.php';
// require_once __DIR__ . '/../app/controller/AdminController.php';

// Parsing URL (Versi lebih sederhana untuk Laragon)
$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Logika routing
switch ($route) {
    // Rute Produk
    case '/':
        $controller = new ProductController($pdo);
        $controller->index();
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

    // Perbaikan: Tambahkan rute untuk keranjang dan admin
    case '/cart':
        // Nanti ini akan memanggil CartController
        echo "<h1>Ini Halaman Keranjang Belanja</h1>";
        // Contoh:
        // $controller = new CartController($pdo);
        // $controller->index();
        break;

    case '/admin':
        // Nanti ini akan memanggil AdminController
        echo "<h1>Ini Halaman Admin Panel</h1>";
        // Contoh:
        // $controller = new AdminController($pdo);
        // $controller->dashboard();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 Halaman Tidak Ditemukan</h1>";
        break;
}
?>