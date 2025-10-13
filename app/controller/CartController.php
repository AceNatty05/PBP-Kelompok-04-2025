<?php
// app/controller/CartController.php

require_once __DIR__ . '/../model/Cart.php';

class CartController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }

    // Menampilkan halaman keranjang
    public function index() {
        $this->checkAuth();
        $cartModel = new Cart($this->pdo);
        $cart = $cartModel->getOrCreateCartByUserId($_SESSION['user_id']);
        $cartItems = $cartModel->getCartItems($cart['id_carts']);
        
        require __DIR__ . '/../../views/cart.php';
    }

    // Menambah item ke keranjang
    public function add() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $qty = $_POST['qty'] ?? 1;

            $cartModel = new Cart($this->pdo);
            $cart = $cartModel->getOrCreateCartByUserId($_SESSION['user_id']);
            $cartModel->addItem($cart['id_carts'], $productId, $qty);
            
            header('Location: /cart');
            exit();
        }
    }
    
    // Menghapus item dari keranjang
    public function remove() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemId = $_POST['cart_item_id'];
            $cartModel = new Cart($this->pdo);
            $cartModel->removeItem($cartItemId);

            header('Location: /cart');
            exit();
        }
    }
}
?>