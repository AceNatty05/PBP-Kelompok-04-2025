// Data produk (diambil dari localStorage)
let products = JSON.parse(localStorage.getItem('products')) || [];

// Fungsi untuk menampilkan produk
function displayProducts() {
    const productsGrid = document.getElementById('products-grid');
    
    if (productsGrid) {
        productsGrid.innerHTML = '';
        
        if (products.length === 0) {
            productsGrid.innerHTML = `
                <div class="no-products">
                    <p>Belum ada produk yang tersedia.</p>
                    <p>Silakan hubungi administrator.</p>
                </div>
            `;
            return;
        }
        
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="product-category">${product.category}</p>
                    <p class="product-description">${product.description}</p>
                    <div class="product-details">
                        <p class="product-price">Rp ${product.price.toLocaleString('id-ID')}</p>
                        <p class="product-stock ${product.stock === 0 ? 'low' : ''}">Stok: ${product.stock}</p>
                    </div>
                    <button class="add-to-cart ${product.stock === 0 ? 'out-of-stock' : ''}" 
                            data-id="${product.id}" 
                            ${product.stock === 0 ? 'disabled' : ''}>
                        ${product.stock === 0 ? 'Stok Habis' : 'Tambah ke Keranjang'}
                    </button>
                </div>
            `;
            productsGrid.appendChild(productCard);
        });
        
        // Event listener untuk tombol tambah ke keranjang
        const addToCartButtons = document.querySelectorAll('.add-to-cart:not(.out-of-stock)');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = parseInt(this.getAttribute('data-id'));
                addToCart(productId);
            });
        });
    }
}

// Fungsi untuk menambah produk ke keranjang
function addToCart(productId) {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!user) {
        alert('Silakan login terlebih dahulu untuk menambahkan produk ke keranjang!');
        window.location.href = 'login.html';
        return;
    }
    
    const product = products.find(p => p.id === productId);
    
    if (product && product.stock > 0) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Cek apakah produk sudah ada di keranjang
        const existingItem = cart.find(item => item.productId === productId && item.userId === user.id);
        
        if (existingItem) {
            // Tambah quantity jika produk sudah ada
            existingItem.quantity += 1;
        } else {
            // Tambah produk baru ke keranjang
            cart.push({
                productId: product.id,
                userId: user.id,
                name: product.name,
                price: product.price,
                quantity: 1
            });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        
        // Tampilkan pesan sukses
        alert(`"${product.name}" berhasil ditambahkan ke keranjang!`);
    }
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
    // Perbarui data produk dari localStorage
    products = JSON.parse(localStorage.getItem('products')) || [];
    
    displayProducts();
    updateCartCount();
    
    // Hamburger menu untuk mobile
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
});

// Efek tambahan untuk tema dark
document.addEventListener('DOMContentLoaded', function() {
    // Efek hover halus pada kartu produk
    const cards = document.querySelectorAll('.product-card, .cart-item');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
        });
    });

    // Efek ketik pada hero text (jika ada)
    const heroText = document.querySelector('.hero-content h2');
    if (heroText) {
        const text = heroText.textContent;
        heroText.textContent = '';
        let i = 0;
        
        function typeWriter() {
            if (i < text.length) {
                heroText.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        }
        typeWriter();
    }

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});