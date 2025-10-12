<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BumiNadi</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/auth.css">
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
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <form class="auth-form" id="login-form" action="/PBP-KELOMPOK-04-2025/public/login" method="POST">
                <h2>Login</h2>

                <?php if (isset($error)): ?>
                    <p style="color: red; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($_GET['status']) && $_GET['status'] == 'reg_success'): ?>
                    <p style="color: green; text-align: center;">Registrasi berhasil! Silakan login.</p>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                    <button type="submit" class="submit-btn">Login</button>
                <div class="auth-link">
                    <p>Belum punya akun? <a href="/PBP-KELOMPOK-04-2025/public/register">Daftar di sini</a></p>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BumiNadi. All rights reserved.</p>
    </footer>

    <script src="public/js/auth.js"></script>
</body>
</html>