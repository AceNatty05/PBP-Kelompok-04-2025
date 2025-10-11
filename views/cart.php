<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - BumiNadi</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <h1>BumiNadi</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="index.php#products">Produk</a></li>
                <li><a href="cart.php">Keranjang (<span id="cart-count">0</span>)</a></li>
                <li id="auth-links">
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="register.php" class="register-btn">Daftar</a>
                </li>
                <li id="admin-link" style="display: none;">
                    <a href="admin/admin.php" class="admin-btn">Admin Panel</a>
                </li>
                <li id="user-info" style="display: none;">
                    <span id="username-display"></span>
                    <button id="logout-btn" class="logout-btn">Logout</button>
                </li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <main>
        <section class="cart-section">
            <h2>Keranjang Belanja</h2>
            <div id="cart-items" class="cart-items">
                <!-- Item keranjang akan dimuat melalui JavaScript -->
            </div>
            <div class="cart-summary">
                <h3>Total: <span id="cart-total">Rp 0</span></h3>
                <button id="checkout-btn" class="checkout-btn">Checkout</button>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 BumiNadi. All rights reserved.</p>
    </footer>

    <script src="public/js/auth.js"></script>
    <script src="public/js/cart.js"></script>
</body>
</html>