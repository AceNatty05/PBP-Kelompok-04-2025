// Data produk (disimpan di localStorage)
let products = JSON.parse(localStorage.getItem('products')) || [
    {
        id: 1,
        name: 'Laptop Gaming',
        price: 12000000,
        image: '../public/images/laptop.jpg',
        description: 'Laptop gaming dengan spesifikasi tinggi untuk pengalaman bermain game yang optimal',
        stock: 15,
        category: 'elektronik',
        createdAt: new Date('2023-01-15').toISOString()
    },
    {
        id: 2,
        name: 'Smartphone',
        price: 5000000,
        image: '../public/images/smartphone.jpg',
        description: 'Smartphone dengan kamera canggih dan performa tinggi',
        stock: 30,
        category: 'elektronik',
        createdAt: new Date('2023-02-20').toISOString()
    },
    {
        id: 3,
        name: 'Headphone Wireless',
        price: 1500000,
        image: '../public/images/headphone.jpg',
        description: 'Headphone dengan kualitas suara terbaik dan fitur noise cancellation',
        stock: 25,
        category: 'elektronik',
        createdAt: new Date('2023-03-10').toISOString()
    },
    {
        id: 4,
        name: 'Smart Watch',
        price: 2500000,
        image: '../public/images/smartwatch.jpg',
        description: 'Smartwatch dengan fitur kesehatan dan notifikasi smartphone',
        stock: 20,
        category: 'elektronik',
        createdAt: new Date('2023-04-05').toISOString()
    }
];

// Variabel global
let currentPage = 1;
const productsPerPage = 5;
let filteredProducts = [];
let productToDelete = null;

// Cek apakah user adalah admin
document.addEventListener('DOMContentLoaded', function() {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!user || user.role !== 'admin') {
        alert('Anda tidak memiliki akses ke halaman admin!');
        window.location.href = '../index.html';
        return;
    }
    
    initializeAdminPanel();
});

// Fungsi untuk inisialisasi admin panel
function initializeAdminPanel() {
    displayProducts();
    setupEventListeners();
    updateStats();
}

// Fungsi untuk menampilkan produk di tabel dengan pagination
function displayProducts(page = 1) {
    const tableBody = document.getElementById('products-table-body');
    const searchTerm = document.getElementById('search-product').value.toLowerCase();
    
    // Filter produk berdasarkan pencarian
    filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm)
    );
    
    // Hitung total halaman
    const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
    currentPage = Math.max(1, Math.min(page, totalPages));
    
    // Hitung indeks awal dan akhir
    const startIndex = (currentPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const productsToShow = filteredProducts.slice(startIndex, endIndex);
    
    tableBody.innerHTML = '';
    
    if (productsToShow.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 2rem;">
                    <p>Tidak ada produk yang ditemukan</p>
                    <button id="add-first-product" class="add-product-btn" style="margin-top: 1rem;">Tambah Produk Pertama</button>
                </td>
            </tr>
        `;
        
        document.getElementById('add-first-product').addEventListener('click', function() {
            openModal();
        });
    } else {
        productsToShow.forEach((product, index) => {
            const rowNumber = startIndex + index + 1;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${rowNumber}</td>
                <td class="product-image-cell">
                    <img src="${product.image}" alt="${product.name}" onerror="this.src='../public/images/placeholder.jpg'">
                </td>
                <td>${product.name}</td>
                <td>Rp ${product.price.toLocaleString('id-ID')}</td>
                <td>${product.description.length > 50 ? product.description.substring(0, 50) + '...' : product.description}</td>
                <td>${product.stock}</td>
                <td><span class="category-badge">${product.category}</span></td>
                <td class="actions-cell">
                    <button class="edit-btn" data-id="${product.id}">Edit</button>
                    <button class="delete-btn" data-id="${product.id}">Hapus</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // Tampilkan pagination
    displayPagination(totalPages);
}

// Fungsi untuk menampilkan pagination
function displayPagination(totalPages) {
    const paginationContainer = document.getElementById('pagination');
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let paginationHTML = '';
    
    // Tombol Previous
    if (currentPage > 1) {
        paginationHTML += `<button onclick="changePage(${currentPage - 1})">← Previous</button>`;
    }
    
    // Tombol halaman
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `<button class="active">${i}</button>`;
        } else {
            paginationHTML += `<button onclick="changePage(${i})">${i}</button>`;
        }
    }
    
    // Tombol Next
    if (currentPage < totalPages) {
        paginationHTML += `<button onclick="changePage(${currentPage + 1})">Next →</button>`;
    }
    
    paginationContainer.innerHTML = paginationHTML;
}

// Fungsi untuk mengganti halaman
function changePage(page) {
    currentPage = page;
    displayProducts(page);
}

// Fungsi untuk update statistik
function updateStats() {
    document.getElementById('total-products').textContent = products.length;
    
    // Cari produk dengan stok terbanyak (sebagai contoh produk terlaris)
    if (products.length > 0) {
        const bestSeller = products.reduce((prev, current) => 
            (prev.stock > current.stock) ? prev : current
        );
        document.getElementById('best-seller').textContent = bestSeller.name;
    }
}

// Fungsi untuk setup event listeners
function setupEventListeners() {
    // Tombol tambah produk
    const addProductBtn = document.getElementById('add-product-btn');
    addProductBtn.addEventListener('click', function() {
        openModal();
    });
    
    // Pencarian produk
    const searchInput = document.getElementById('search-product');
    const searchBtn = document.getElementById('search-btn');
    
    searchInput.addEventListener('input', function() {
        displayProducts(1);
    });
    
    searchBtn.addEventListener('click', function() {
        displayProducts(1);
    });
    
    // Event delegation untuk tombol edit dan hapus
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) {
            const productId = parseInt(e.target.getAttribute('data-id'));
            editProduct(productId);
        }
        
        if (e.target.classList.contains('delete-btn')) {
            const productId = parseInt(e.target.getAttribute('data-id'));
            confirmDelete(productId);
        }
    });
    
    // Modal
    const modal = document.getElementById('product-modal');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancel-btn');
    const productForm = document.getElementById('product-form');
    
    closeBtn.addEventListener('click', closeModal);
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Form produk
    productForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveProduct();
    });
    
    // Modal konfirmasi hapus
    const confirmModal = document.getElementById('confirm-modal');
    const confirmCancel = document.getElementById('confirm-cancel');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    
    confirmCancel.addEventListener('click', function() {
        confirmModal.style.display = 'none';
        productToDelete = null;
    });
    
    confirmDeleteBtn.addEventListener('click', function() {
        if (productToDelete) {
            deleteProduct(productToDelete);
            confirmModal.style.display = 'none';
            productToDelete = null;
        }
    });
    
    // Tombol logout
    const logoutBtn = document.getElementById('logout-btn');
    logoutBtn.addEventListener('click', function() {
        localStorage.removeItem('currentUser');
        window.location.href = '../index.html';
    });
}

// Fungsi untuk membuka modal
function openModal(product = null) {
    const modal = document.getElementById('product-modal');
    const modalTitle = document.getElementById('modal-title');
    
    if (product) {
        modalTitle.textContent = 'Edit Produk';
        document.getElementById('product-id').value = product.id;
        document.getElementById('product-name').value = product.name;
        document.getElementById('product-price').value = product.price;
        document.getElementById('product-stock').value = product.stock;
        document.getElementById('product-category').value = product.category;
        document.getElementById('product-description').value = product.description;
        document.getElementById('product-image').value = product.image;
    } else {
        modalTitle.textContent = 'Tambah Produk Baru';
        document.getElementById('product-form').reset();
        document.getElementById('product-id').value = '';
    }
    
    modal.style.display = 'block';
}

// Fungsi untuk menutup modal
function closeModal() {
    const modal = document.getElementById('product-modal');
    modal.style.display = 'none';
}

// Fungsi untuk edit produk
function editProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        openModal(product);
    }
}

// Fungsi untuk konfirmasi hapus
function confirmDelete(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        productToDelete = productId;
        const confirmModal = document.getElementById('confirm-modal');
        const confirmMessage = document.getElementById('confirm-message');
        
        confirmMessage.textContent = `Apakah Anda yakin ingin menghapus produk "${product.name}"? Tindakan ini tidak dapat dibatalkan.`;
        confirmModal.style.display = 'block';
    }
}

// Fungsi untuk hapus produk
function deleteProduct(productId) {
    products = products.filter(p => p.id !== productId);
    localStorage.setItem('products', JSON.stringify(products));
    displayProducts(currentPage);
    updateStats();
    
    // Perbarui juga produk di halaman utama
    updateMainPageProducts();
}

// Fungsi untuk menyimpan produk
function saveProduct() {
    const productId = document.getElementById('product-id').value;
    const name = document.getElementById('product-name').value;
    const price = parseInt(document.getElementById('product-price').value);
    const stock = parseInt(document.getElementById('product-stock').value);
    const category = document.getElementById('product-category').value;
    const description = document.getElementById('product-description').value;
    const image = document.getElementById('product-image').value;
    
    // Validasi
    if (!name || !price || !stock || !category || !description || !image) {
        alert('Semua field harus diisi!');
        return;
    }
    
    if (price < 0 || stock < 0) {
        alert('Harga dan stok tidak boleh negatif!');
        return;
    }
    
    if (productId) {
        // Edit produk yang sudah ada
        const index = products.findIndex(p => p.id === parseInt(productId));
        if (index !== -1) {
            products[index] = {
                ...products[index],
                name,
                price,
                stock,
                category,
                description,
                image,
                updatedAt: new Date().toISOString()
            };
        }
    } else {
        // Tambah produk baru
        const newProduct = {
            id: Date.now(),
            name,
            price,
            stock,
            category,
            description,
            image,
            createdAt: new Date().toISOString()
        };
        products.push(newProduct);
    }
    
    localStorage.setItem('products', JSON.stringify(products));
    displayProducts(currentPage);
    updateStats();
    closeModal();
    
    // Perbarui juga produk di halaman utama
    updateMainPageProducts();
    
    alert(`Produk "${name}" berhasil ${productId ? 'diperbarui' : 'ditambahkan'}!`);
}

// Fungsi untuk memperbarui produk di halaman utama
function updateMainPageProducts() {
    // Simpan data produk yang terbaru ke localStorage
    localStorage.setItem('products', JSON.stringify(products));
}