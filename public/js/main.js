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
                <img src="${product.image}" alt="${product.name}" class="product-image" onerror="this.src='public/images/placeholder.jpg'">
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="product-category">${product.category}</p>
                    <p class="product-description">${product.description}</p>
                    <div class="product-details">
                        <p class="product-price">Rp ${product.price.toLocaleString('id-ID')}</p>
                        <p class="product-stock">Stok: ${product.stock}</p>
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

// ... (fungsi lainnya tetap sama, hanya perlu menyesuaikan dengan struktur produk baru)

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