<?php
// Mulai session di awal
session_start();

// Muat file konfigurasi dan controller
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controller/ProductController.php';
require_once __DIR__ . '/../app/controller/AuthController.php'; // Tambahkan ini

// Parsing URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/PBP-KELOMPOK-04-2025/public'; // GANTI INI
$route = str_replace($basePath, '', $requestUri);

// Logika routing
switch ($route) {
    // Rute Produk
    case '/':
    case '/index.php':
    case '':
        $controller = new ProductController($pdo);
        $controller->index();
        break;

    // Rute Autentikasi
    case '/login':
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login(); // Proses login
        } else {
            $controller->showLoginForm(); // Tampilkan form
        }
        break;
    
    case '/register':
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register(); // Proses registrasi
        } else {
            $controller->showRegisterForm(); // Tampilkan form
        }
        break;

    case '/logout':
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    // Rute lain bisa ditambahkan di sini (misal /cart, /admin)

    default:
        http_response_code(404);
        echo "<h1>404 Halaman Tidak Ditemukan</h1>";
        break;
}
?>