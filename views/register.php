<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - BumiNadi</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <h1>BumiNadi</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/">Beranda</a></li>
                <li><a href="/#products">Produk</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <form class="auth-form" id="register-form" action="/PBP-KELOMPOK-04-2025/public/register" method="POST">
                <h2>Daftar Akun</h2>

                <?php if (isset($error)): ?>
                    <p style="color: red; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                    <button type="submit" class="submit-btn">Daftar</button>
                <div class="auth-link">
                    <p>Sudah punya akun? <a href="/PBP-KELOMPOK-04-2025/public/login">Login di sini</a></p>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BumiNadi. All rights reserved.</p>
    </footer>

    <script src="/js/auth.js"></script>
</body>
</html>