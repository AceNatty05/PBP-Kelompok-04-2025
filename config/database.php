<?php

$host = 'localhost';
$dbname = 'umkm_commerce'; 
$username = 'root';
$password = '';

try {
    // Buat dan kembalikan objek koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Baris ini penting agar hasil query berupa array asosiatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    // Hentikan eksekusi dan tampilkan error jika koneksi gagal
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>