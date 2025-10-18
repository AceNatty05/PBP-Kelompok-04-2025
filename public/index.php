<?php
/**
 * --------------------------------------------------------------------------
 * TITIK MASUK UTAMA APLIKASI (FRONT CONTROLLER)
 * --------------------------------------------------------------------------
 *
 * Semua request dari browser akan diarahkan ke file ini. File ini bertugas
 * untuk memuat semua konfigurasi awal, menganalisis URL, dan
 * memanggil Controller yang sesuai.
 */

// 1. Mulai session di paling awal untuk seluruh aplikasi
session_start();

// 2. Muat file konfigurasi dan semua Controller
// Pastikan path ini benar dari lokasi public/index.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controller/ProductController.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/OrderController.php';
// Tambahkan controller lain di sini saat Anda membuatnya, contoh:
// require_once __DIR__ . '/../app/controller/CartController.php';

// 3. Definisikan BASE_URL untuk menangani subdirektori
// PENTING: Sesuaikan nama folder ini jika nama folder proyek Anda berbeda
define('BASE_URL', '/PBP-Kelompok-04-2025');

// 4. Ambil path dari URL dan normalisasi
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = str_replace(BASE_URL, '', $requestUri);
if ($route === '' || $route === '/index.php') {
    $route = '/';
}

// 5. Routing
switch ($route) {
    // Rute Halaman Utama
    case '/':
        $controller = new ProductController($pdo);
        $controller->index();
        exit;

    // Rute-rute Autentikasi
    case '/login':
        $auth = new AuthController($pdo);
        // Cek apakah form login disubmit (POST) atau hanya ditampilkan (GET)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login(); // proses login
        } else {
            $auth->showLogin(); // tampilkan form login
        }
        exit;

    case '/register':
        $auth = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        exit;

    case '/logout':
        $auth = new AuthController($pdo);
        $auth->logout();
        exit;

    // Rute untuk Manajemen Pesanan (Admin)
    case '/admin/orders':
        $orderCtrl = new OrderController($pdo);
        $orderCtrl->index();
        exit;

    case '/admin/orders/update':
        $orderCtrl = new OrderController($pdo);
        $orderCtrl->updateStatus();
        exit;

    // Halaman Tidak Ditemukan (404)
    default:
        http_response_code(404);
        // Anda bisa membuat file view khusus untuk halaman 404
        // require_once __DIR__ . '/../views/404.php';
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        exit;
}
?>