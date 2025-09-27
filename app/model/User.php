<?php
// app/model/User.php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mencari user berdasarkan email.
     * Mengembalikan data user jika ditemukan, atau false jika tidak.
     */
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Membuat user baru di database.
     * Mengembalikan true jika berhasil, false jika gagal.
     */
    public function createUser($name, $email, $password) {
        // Hash password sebelum disimpan untuk keamanan
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (id_users, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)"
        );
        
        // Buat ID unik untuk user
        $userId = 'USR' . time(); 

        try {
            // Role default adalah 'user'
            $stmt->execute([$userId, $name, $email, $passwordHash, 'user']);
            return true;
        } catch (PDOException $e) {
            // Gagal jika email sudah ada (karena UNIQUE constraint) atau error lain
            return false;
        }
    }
}
?>