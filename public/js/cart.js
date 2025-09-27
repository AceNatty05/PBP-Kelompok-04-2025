// Fungsi untuk menampilkan item keranjang
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    if (!cartItemsContainer) return;
    
    const user = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!user) {
        cartItemsContainer.innerHTML = `
            <div class="cart-login-required">
                <p>Silakan login untuk melihat keranjang belanja</p>
                <div class="auth-buttons">
                    <a href="login.html" class="login-btn">Login</a>
                    <a href="register.html" class="register-btn">Daftar</a>
                </div>
            </div>
        `;
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const userCart = cart.filter(item => item.userId === user.id);
    
    if (userCart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="cart-empty">
                <p>Keranjang belanja kosong</p>
                <a href="index.html#products" class="cta-button">Lihat Produk</a>
            </div>
        `;
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        if (cartTotalElement) cartTotalElement.textContent = 'Rp 0';
        return;
    }
    
    cartItemsContainer.innerHTML = '';
    let total = 0;
    
    userCart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-details">
                <h3>${item.name}</h3>
                <p class="item-price">Rp ${item.price.toLocaleString('id-ID')} per item</p>
            </div>
            <div class="cart-item-quantity">
                <button class="quantity-btn minus" data-id="${item.productId}" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                <span>${item.quantity}</span>
                <button class="quantity-btn plus" data-id="${item.productId}">+</button>
            </div>
            <div class="cart-item-total">
                <p>Rp ${itemTotal.toLocaleString('id-ID')}</p>
            </div>
            <div class="cart-item-remove">
                <button class="remove-btn" data-id="${item.productId}">Hapus</button>
            </div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
    
    if (cartTotalElement) cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    if (checkoutBtn) {
        checkoutBtn.style.display = 'block';
        checkoutBtn.disabled = false;
    }
    
    // Event listener untuk tombol quantity dan hapus
    setupCartEventListeners();
    
    // Event listener untuk tombol checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            checkoutCart();
        });
    }
}

// Fungsi untuk menampilkan item keranjang
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    if (!cartItemsContainer) return;
    
    const user = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!user) {
        cartItemsContainer.innerHTML = `
            <div class="cart-login-required">
                <p>Silakan login untuk melihat keranjang belanja</p>
                <div class="auth-buttons">
                    <a href="login.html" class="login-btn">Login</a>
                    <a href="register.html" class="register-btn">Daftar</a>
                </div>
            </div>
        `;
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const userCart = cart.filter(item => item.userId === user.id);
    
    if (userCart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="cart-empty">
                <p>Keranjang belanja kosong</p>
                <a href="index.html#products" class="cta-button">Lihat Produk</a>
            </div>
        `;
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        if (cartTotalElement) cartTotalElement.textContent = 'Rp 0';
        return;
    }
    
    cartItemsContainer.innerHTML = '';
    let total = 0;
    
    userCart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}" onerror="this.src='public/images/placeholder.jpg'">
            </div>
            <div class="cart-item-details">
                <h3>${item.name}</h3>
                <p>Rp ${item.price.toLocaleString('id-ID')}</p>
            </div>
            <div class="cart-item-quantity">
                <button class="quantity-btn minus" data-id="${item.productId}" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                <span>${item.quantity}</span>
                <button class="quantity-btn plus" data-id="${item.productId}">+</button>
            </div>
            <div class="cart-item-total">
                <p>Rp ${itemTotal.toLocaleString('id-ID')}</p>
            </div>
            <div class="cart-item-remove">
                <button class="remove-btn" data-id="${item.productId}">Hapus</button>
            </div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
    
    if (cartTotalElement) cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    if (checkoutBtn) {
        checkoutBtn.style.display = 'block';
        checkoutBtn.disabled = false;
    }
    
    // Event listener untuk tombol quantity dan hapus
    setupCartEventListeners();
    
    // Event listener untuk tombol checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            checkoutCart();
        });
    }
}

// Fungsi untuk setup event listeners keranjang
function setupCartEventListeners() {
    const minusButtons = document.querySelectorAll('.quantity-btn.minus');
    const plusButtons = document.querySelectorAll('.quantity-btn.plus');
    const removeButtons = document.querySelectorAll('.remove-btn');
    
    minusButtons.forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(parseInt(this.getAttribute('data-id')), -1);
        });
    });
    
    plusButtons.forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(parseInt(this.getAttribute('data-id')), 1);
        });
    });
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            removeFromCart(parseInt(this.getAttribute('data-id')));
        });
    });
}

// Fungsi untuk memperbarui quantity item
function updateQuantity(productId, change) {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const itemIndex = cart.findIndex(item => item.productId === productId && item.userId === user.id);
    
    if (itemIndex !== -1) {
        cart[itemIndex].quantity += change;
        
        // Hapus item jika quantity <= 0
        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCartItems();
        updateCartCount();
    }
}

// Fungsi untuk menghapus item dari keranjang
function removeFromCart(productId) {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
        return;
    }
    
    const user = JSON.parse(localStorage.getItem('currentUser'));
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    cart = cart.filter(item => !(item.productId === productId && item.userId === user.id));
    
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCartItems();
    updateCartCount();
    
    alert('Produk berhasil dihapus dari keranjang!');
}

// Fungsi untuk checkout
function checkoutCart() {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const userCart = cart.filter(item => item.userId === user.id);
    
    if (userCart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin melakukan checkout?')) {
        // Simpan riwayat pesanan
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        const newOrder = {
            id: Date.now(),
            userId: user.id,
            items: userCart,
            total: userCart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
            date: new Date().toISOString(),
            status: 'pending'
        };
        orders.push(newOrder);
        localStorage.setItem('orders', JSON.stringify(orders));
        
        // Hapus item keranjang setelah checkout
        cart = cart.filter(item => item.userId !== user.id);
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update stok produk
        updateProductStock(userCart);
        
        alert('Checkout berhasil! Pesanan Anda sedang diproses.');
        displayCartItems();
        updateCartCount();
    }
}

// Fungsi untuk update stok produk setelah checkout
function updateProductStock(cartItems) {
    let products = JSON.parse(localStorage.getItem('products')) || [];
    
    cartItems.forEach(cartItem => {
        const productIndex = products.findIndex(p => p.id === cartItem.productId);
        if (productIndex !== -1) {
            products[productIndex].stock -= cartItem.quantity;
            if (products[productIndex].stock < 0) {
                products[productIndex].stock = 0;
            }
        }
    });
    
    localStorage.setItem('products', JSON.stringify(products));
}

// Fungsi untuk memperbarui jumlah item di keranjang
function updateCartCount() {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    const cartCountElements = document.querySelectorAll('#cart-count');
    
    if (user) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const userCart = cart.filter(item => item.userId === user.id);
        const totalItems = userCart.reduce((total, item) => total + item.quantity, 0);
        
        cartCountElements.forEach(element => {
            element.textContent = totalItems;
        });
    } else {
        cartCountElements.forEach(element => {
            element.textContent = '0';
        });
    }
}

// Event listener ketika halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    displayCartItems();
    updateCartCount();
});