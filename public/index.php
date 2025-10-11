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
// Tambahkan controller lain di sini saat Anda membuatnya, contoh:
// require_once __DIR__ . '/../app/controller/CartController.php';

// 3. Definisikan BASE_URL untuk menangani subdirektori
// PENTING: Sesuaikan nama folder ini jika nama folder proyek Anda berbeda
define('BASE_URL', '/PBP-Kelompok-04-2025');

// 4. Logika Routing Sederhana
// Ambil path lengkap dari URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Hapus bagian BASE_URL dari path untuk mendapatkan rute yang bersih
$route = str_replace(BASE_URL, '', $requestUri);
// Jika rutenya kosong setelah dihapus, berarti itu adalah halaman utama ('/')
if (empty($route)) {
    $route = '/';
}

// 5. Jalankan Controller berdasarkan rute yang didapat
switch ($route) {
    // Rute Halaman Utama
    case '/':
        $controller = new ProductController($pdo);
        $controller->index();
        break;

    // Rute-rute Autentikasi
    case '/login':
        $controller = new AuthController($pdo);
        // Cek apakah form login disubmit (POST) atau hanya ditampilkan (GET)
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

    // Rute untuk Keranjang Belanja (Contoh placeholder)
    case '/cart':
        // Nanti ini akan memanggil CartController yang sebenarnya
        echo "<h1>Halaman Keranjang Belanja</h1><p>Fitur ini sedang dalam pengembangan.</p>";
        // Contoh pemanggilan controller di masa depan:
        // $controller = new CartController($pdo);
        // $controller->index();
        break;

    // Halaman Tidak Ditemukan (404)
    default:
        http_response_code(404);
        // Anda bisa membuat file view khusus untuk halaman 404
        // require_once __DIR__ . '/../views/404.php';
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        break;
}

?>