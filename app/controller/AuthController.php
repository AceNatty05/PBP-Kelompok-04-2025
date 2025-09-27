<?php
// app/controller/AuthController.php

require_once __DIR__ . '/../model/User.php';

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Menampilkan halaman form login
    public function showLoginForm() {
        require __DIR__ . '/../../views/login.php';
    }

    // Menampilkan halaman form registrasi
    public function showRegisterForm() {
        require __DIR__ . '/../../views/register.php';
    }

    // Memproses data dari form registrasi
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User($this->pdo);
            
            // Cek apakah email sudah ada
            if ($userModel->findByEmail($_POST['email'])) {
                // Kirim pesan error kembali ke form registrasi
                $error = "Email sudah terdaftar!";
                require __DIR__ . '/../../views/register.php';
                return;
            }

            // Buat user baru
            $success = $userModel->createUser($_POST['fullname'], $_POST['email'], $_POST['password']);

            if ($success) {
                // Jika berhasil, redirect ke halaman login
                header("Location: /PBP-KELOMPOK-04-2025/public/login?status=reg_success");
                exit();
            } else {
                $error = "Registrasi gagal, silakan coba lagi.";
                require __DIR__ . '/../../views/register.php';
            }
        }
    }

    // Memproses data dari form login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User($this->pdo);
            $user = $userModel->findByEmail($_POST['email']);

            // Verifikasi user dan password
            if ($user && password_verify($_POST['password'], $user['password_hash'])) {
                // Simpan informasi user ke session
                session_start();
                $_SESSION['user_id'] = $user['id_users'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect ke halaman utama
                header("Location: /PBP-KELOMPOK-04-2025/public/");
                exit();
            } else {
                $error = "Email atau password salah!";
                require __DIR__ . '/../../views/login.php';
            }
        }
    }

    // Fungsi untuk logout
    public function logout() {
        session_start();
        session_unset(); // Hapus semua variabel session
        session_destroy(); // Hancurkan session
        header("Location: /PBP-KELOMPOK-04-2025/public/login");
        exit();
    }
}
?>