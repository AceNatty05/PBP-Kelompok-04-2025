<?php
// app/controller/OrderController.php

require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../model/Cart.php';

class OrderController {
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

    // Memproses checkout
    public function checkout() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = $_POST['address'];
            $userId = $_SESSION['user_id'];
            
            $cartModel = new Cart($this->pdo);
            $cart = $cartModel->getOrCreateCartByUserId($userId);
            $cartItems = $cartModel->getCartItems($cart['id_carts']);

            if (empty($cartItems)) {
                header('Location: /cart?error=empty');
                exit();
            }

            $orderModel = new Order($this->pdo);
            $orderId = $orderModel->createOrder($userId, $cartItems, $address);

            if ($orderId) {
                // Pesanan berhasil, kosongkan keranjang
                $cartModel->clearCart($cart['id_carts']);
                header('Location: /order/success?order_id=' . $orderId);
                exit();
            } else {
                header('Location: /cart?error=failed');
                exit();
            }
        }
    }

    // Menampilkan halaman sukses setelah checkout
    public function success() {
        $this->checkAuth();
        require __DIR__ . '/../../views/order_success.php';
    }
}
?>