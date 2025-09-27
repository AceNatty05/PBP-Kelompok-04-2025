// Fungsi untuk mengecek status login
function checkLoginStatus() {
    const user = JSON.parse(localStorage.getItem('currentUser'));
    const authLinks = document.getElementById('auth-links');
    const userInfo = document.getElementById('user-info');
    const adminLink = document.getElementById('admin-link');
    const usernameDisplay = document.getElementById('username-display');
    
    if (user) {
        if (authLinks) authLinks.style.display = 'none';
        if (userInfo) userInfo.style.display = 'block';
        if (usernameDisplay) usernameDisplay.textContent = `Halo, ${user.fullname}`;
        
        // Tampilkan link admin jika user adalah admin
        if (user.role === 'admin' && adminLink) {
            adminLink.style.display = 'block';
        }
    } else {
        if (authLinks) authLinks.style.display = 'block';
        if (userInfo) userInfo.style.display = 'none';
        if (adminLink) adminLink.style.display = 'none';
    }
}

// Fungsi untuk logout
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'index.html';
}

// Event listener untuk form login
document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
    
    // Event listener untuk tombol logout
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
    }
    
    // Event listener untuk form login
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');
            
            // Ambil data user dari localStorage
            const users = JSON.parse(localStorage.getItem('users')) || [];
            const user = users.find(u => u.email === email && u.password === password);
            
            if (user) {
                // Simpan user yang login ke localStorage
                localStorage.setItem('currentUser', JSON.stringify(user));
                window.location.href = 'index.html';
            } else {
                errorMessage.textContent = 'Email atau password salah!';
                errorMessage.style.display = 'block';
            }
        });
    }
    
    // Event listener untuk form register
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const errorMessage = document.getElementById('error-message');
            
            // Validasi password
            if (password !== confirmPassword) {
                errorMessage.textContent = 'Password tidak cocok!';
                errorMessage.style.display = 'block';
                return;
            }
            
            // Ambil data user dari localStorage
            const users = JSON.parse(localStorage.getItem('users')) || [];
            
            // Cek apakah email sudah terdaftar
            if (users.find(u => u.email === email)) {
                errorMessage.textContent = 'Email sudah terdaftar!';
                errorMessage.style.display = 'block';
                return;
            }
            
            // Tambahkan user baru (default role adalah 'user')
            const newUser = {
                id: Date.now(),
                fullname,
                email,
                password,
                role: 'user'
            };
            
            users.push(newUser);
            localStorage.setItem('users', JSON.stringify(users));
            
            // Redirect ke halaman login
            window.location.href = 'login.html';
        });
    }
});