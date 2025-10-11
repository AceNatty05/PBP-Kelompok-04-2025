<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BumiNadi</title>
    <link rel="stylesheet" href="/PBP-Kelompok-04-2025/public/css/style.css">
</head>
<body>
    <header>
        <nav class="admin-navbar">
            <div class="nav-brand">
                <h1>Admin Panel</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/views/index.php">Kembali ke Toko</a></li>
                <li><span id="admin-welcome">Halo, Admin!</span></li>
                <li><button id="logout-btn" class="logout-btn">Logout</button></li>
            </ul>
        </nav>
    </header>

    <main class="admin-main">
        <div class="admin-container">
            <h2>Manajemen Produk</h2>
            
            <div class="admin-actions">
                <button id="add-product-btn" class="add-product-btn">+ Tambah Produk Baru</button>
                <div class="search-box">
                    <input type="text" id="search-product" placeholder="Cari produk...">
                    <button id="search-btn">Cari</button>
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Produk</h3>
                    <p id="total-products">0</p>
                </div>
                <div class="stat-card">
                    <h3>Produk Terlaris</h3>
                    <p id="best-seller">-</p>
                </div>
            </div>
            
            <div class="products-table-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Stok</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        <!-- Data produk akan dimuat melalui JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination" id="pagination">
                <!-- Pagination akan dimuat melalui JavaScript -->
            </div>
        </div>
    </main>

    <!-- Modal untuk tambah/edit produk -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="modal-title">Tambah Produk Baru</h3>
            <form id="product-form">
                <input type="hidden" id="product-id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="product-name">Nama Produk *</label>
                        <input type="text" id="product-name" required>
                    </div>
                    <div class="form-group">
                        <label for="product-price">Harga (Rp) *</label>
                        <input type="number" id="product-price" min="0" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="product-stock">Stok *</label>
                        <input type="number" id="product-stock" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="product-category">Kategori *</label>
                        <select id="product-category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="fashion">Fashion</option>
                            <option value="rumah-tangga">Rumah Tangga</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product-description">Deskripsi Produk *</label>
                    <textarea id="product-description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="product-image">URL Gambar *</label>
                    <input type="text" id="product-image" required>
                    <small>Masukkan URL gambar yang valid</small>
                </div>
                <div class="form-actions">
                    <button type="button" id="cancel-btn" class="cancel-btn">Batal</button>
                    <button type="submit" class="submit-btn">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal konfirmasi hapus -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content confirm-modal">
            <h3>Konfirmasi Hapus</h3>
            <p id="confirm-message">Apakah Anda yakin ingin menghapus produk ini?</p>
            <div class="confirm-actions">
                <button id="confirm-cancel" class="cancel-btn">Batal</button>
                <button id="confirm-delete" class="delete-btn">Hapus</button>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>