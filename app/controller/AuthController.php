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
            $password = $_POST['password'];
            
            if ($userModel->findByEmail($_POST['email'])) {
                $error = "Email sudah terdaftar!";
                require __DIR__ . '/../../views/register.php';
                return;
            }

            // Validasi password
            if (strlen($password) < 8 ||
                !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[a-z]/', $password) ||
                !preg_match('/[0-9]/', $password)) {
                $error = "Password setidaknya 8 karakter, setidaknya 1 huruf kapital, 1 huruf kecil, dan 1 angka";
                require __DIR__ . '/../../views/register.php';
                return;
            }

            $success = $userModel->createUser($_POST['fullname'], $_POST['email'], $_POST['password']);

            if ($success) {
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

            if ($user && password_verify($_POST['password'], $user['password_hash'])) {
                // Regenerate session ID untuk keamanan
                session_regenerate_id(true); 
                
                $_SESSION['user_id'] = $user['id_users'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: /PBP-KELOMPOK-04-2025/public/admin");
                } else {
                    header("Location: /PBP-KELOMPOK-04-2025/public/");
                }
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
        session_unset();
        session_destroy();
        header("Location: /PBP-KELOMPOK-04-2025/public/login");
        exit();
    }
}
?>