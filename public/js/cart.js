// Fungsi untuk menampilkan item keranjang
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    if (!cartItemsContainer) return;
    
    const user = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!user) {
        cartItemsContainer.innerHTML = '<p>Silakan login untuk melihat keranjang belanja</p>';
        if (checkoutBtn) checkoutBtn.style.display = 'none';
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const userCart = cart.filter(item => item.userId === user.id);
    
    if (userCart.length === 0) {
        cartItemsContainer.innerHTML = '<p>Keranjang belanja kosong</p>';
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
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="cart-item-details">
                <h3>${item.name}</h3>
                <p>Rp ${item.price.toLocaleString('id-ID')}</p>
            </div>
            <div class="cart-item-quantity">
                <button class="quantity-btn minus" data-id="${item.productId}">-</button>
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
    if (checkoutBtn) checkoutBtn.style.display = 'block';
    
    // Event listener untuk tombol quantity dan hapus
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
    
    // Event listener untuk tombol checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            alert('Terima kasih! Pesanan Anda sedang diproses.');
            // Hapus item keranjang setelah checkout
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.userId !== user.id);
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
            updateCartCount();
        });
    }
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
    const user = JSON.parse(localStorage.getItem('currentUser'));
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    cart = cart.filter(item => !(item.productId === productId && item.userId === user.id));
    
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCartItems();
    updateCartCount();
}

// Event listener ketika halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    displayCartItems();
    updateCartCount();
});

// Fungsi untuk memperbarui jumlah item di keranjang (sama seperti di main.js)
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