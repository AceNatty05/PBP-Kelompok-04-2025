<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BumiNadi</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <h1>BumiNadi</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/">Beranda</a></li>
                <li><a href="#products">Produk</a></li>
                <li><a href="/cart">Keranjang (<span id="cart-count">0</span>)</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="/admin" class="admin-btn">Admin Panel</a></li>
                    <?php endif; ?>
                    
                    <li>
                        <span style="color: white;">Halo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="/logout" class="logout-btn" style="margin-left: 10px;">Logout</a>
                    </li>

                <?php else: ?>
                    <li>
                        <a href="/PBP-KELOMPOK-04-2025/public/login" class="login-btn">Login</a>
                        <a href="/PBP-KELOMPOK-04-2025/public/register" class="register-btn">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Selamat Datang di BumiNadi</h2>
                <p>Temukan produk terbaik dengan harga terjangkau</p>
                <a href="#products" class="cta-button">Belanja Sekarang</a>
            </div>
        </section>

        <section id="products" class="products-section">
            <h2>Produk Kami</h2>
            <div class="products-grid" id="products-grid">
                <?php if (empty($products)): ?>
                    <div class="no-products">
                        <p>Belum ada produk yang tersedia saat ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img src="/public/images/products/<?php echo htmlspecialchars($product['gambar']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                
                                <div class="product-details">
                                    <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                                    <p class="product-stock">Stok: <?php echo htmlspecialchars($product['stock']); ?></p>
                                </div>
                
                                <button class="add-to-cart" data-id="<?php echo htmlspecialchars($product['id_products']); ?>">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 BumiNadi. All rights reserved.</p>
    </footer>

    <script src="<?= BASE_URL ?>/public/js/auth.js"></script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>