<?php
class Product {
    // Properti untuk menampung koneksi database
    private $pdo;

    // Constructor untuk menerima koneksi database saat objek dibuat
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * READ: Mengambil semua produk yang aktif.
     * @return array Daftar semua produk.
     */
    public function getAllProducts() {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_active = TRUE ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * READ: Mengambil satu produk berdasarkan ID-nya.
     * @param string $id ID produk yang akan dicari.
     * @return mixed Array berisi data produk jika ditemukan, atau false jika tidak.
     */
    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id_products = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE: Menambahkan produk baru ke database.
     * @param array $data Data produk baru (misal: ['name' => ..., 'price' => ..., 'stock' => ..., 'gambar' => ...]).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function createProduct($data) {
        // Generate ID unik untuk produk baru
        $id = uniqid('PROD_');

        $sql = "INSERT INTO products (id_products, name, price, stock, gambar) VALUES (:id, :name, :price, :stock, :gambar)";
        $stmt = $this->pdo->prepare($sql);

        // Binding parameter untuk keamanan
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':gambar', $data['gambar']); // Nama file gambar

        return $stmt->execute();
    }

    /**
     * UPDATE: Memperbarui data produk yang ada berdasarkan ID.
     * @param string $id ID produk yang akan diperbarui.
     * @param array $data Data baru untuk produk.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function updateProduct($id, $data) {
        $sql = "UPDATE products SET name = :name, price = :price, stock = :stock, gambar = :gambar WHERE id_products = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    /**
     * DELETE: Menghapus produk dari database (Soft Delete).
     * Metode ini tidak benar-benar menghapus data, hanya mengubah status is_active menjadi false.
     * Ini lebih aman karena data masih bisa dipulihkan jika perlu.
     * @param string $id ID produk yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function deleteProduct($id) {
        // Ini adalah "Soft Delete", cara yang lebih aman
        $sql = "UPDATE products SET is_active = FALSE WHERE id_products = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
        
        /* // Jika Anda benar-benar ingin menghapus data secara permanen (Hard Delete):
        $sql = "DELETE FROM products WHERE id_products = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
        */
    }
}
?>