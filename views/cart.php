<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - BumiNadi</title>
    <link rel="stylesheet" href="/css/style.css"> </head>
<body>
    <header>
        </header>

    <main>
        <section class="cart-section">
            <h2>Keranjang Belanja</h2>
            <?php if (isset($_GET['error'])): ?>
                <p style="color:red;">Checkout gagal, silakan coba lagi.</p>
            <?php endif; ?>

            <div class="cart-items">
                <?php if (empty($cartItems)): ?>
                    <p>Keranjang Anda kosong.</p>
                <?php else: 
                    $total = 0;
                    foreach ($cartItems as $item): 
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                ?>
                    <div class="cart-item">
                        <img src="/images/products/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div>
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Rp <?php echo number_format($item['price']); ?> x <?php echo $item['qty']; ?></p>
                            <p>Subtotal: Rp <?php echo number_format($subtotal); ?></p>
                        </div>
                        <form action="/cart/remove" method="POST">
                            <input type="hidden" name="cart_item_id" value="<?php echo $item['id_cart_items']; ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Total: <span>Rp <?php echo number_format($total); ?></span></h3>
                <form action="/order/checkout" method="POST">
                    <div class="form-group">
                        <label for="address">Alamat Pengiriman:</label>
                        <textarea name="address" id="address" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="checkout-btn">Checkout</button>
                </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer><p>&copy; 2025 BumiNadi. All rights reserved.</p></footer>
</body>
</html>