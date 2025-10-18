<?php
// app/controller/OrderController.php

require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../model/Cart.php';

class OrderController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "403 Forbidden";
            exit;
        }
    }

    // pastikan user sudah login
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $loginUrl = (defined('BASE_URL') ? BASE_URL : '') . '/login';
            header('Location: ' . $loginUrl);
            exit;
        }
    }

    // Memproses checkout
    public function checkout() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = trim($_POST['address'] ?? '');
            if ($address === '') {
                $cartUrl = (defined('BASE_URL') ? BASE_URL : '') . '/cart?error=empty_address';
                header('Location: ' . $cartUrl);
                exit();
            }

            $userId = $_SESSION['user_id'];

            try {
                $cartModel = new Cart($this->pdo);
                $cart = $cartModel->getOrCreateCartByUserId($userId);
                if (!isset($cart['id_carts'])) {
                    // fallback: jika model berbeda, coba gunakan key 'id'
                    $cartId = $cart['id'] ?? null;
                } else {
                    $cartId = $cart['id_carts'];
                }

                $cartItems = $cartModel->getCartItems($cartId);

                if (empty($cartItems)) {
                    $cartUrl = (defined('BASE_URL') ? BASE_URL : '') . '/cart?error=empty';
                    header('Location: ' . $cartUrl);
                    exit();
                }

                $orderModel = new Order($this->pdo);
                $orderId = $orderModel->createOrder($userId, $cartItems, $address);

                if ($orderId) {
                    // Pesanan berhasil, kosongkan keranjang
                    $cartModel->clearCart($cartId);
                    $successUrl = (defined('BASE_URL') ? BASE_URL : '') . '/order/success?order_id=' . urlencode($orderId);
                    header('Location: ' . $successUrl);
                    exit();
                } else {
                    $cartUrl = (defined('BASE_URL') ? BASE_URL : '') . '/cart?error=failed';
                    header('Location: ' . $cartUrl);
                    exit();
                }
            } catch (Throwable $e) {
                // log error bila ada (jika Anda punya mekanisme log)
                error_log('OrderController::checkout error: ' . $e->getMessage());
                $cartUrl = (defined('BASE_URL') ? BASE_URL : '') . '/cart?error=server';
                header('Location: ' . $cartUrl);
                exit();
            }
        } else {
            // bukan POST -> redirect ke keranjang
            $cartUrl = (defined('BASE_URL') ? BASE_URL : '') . '/cart';
            header('Location: ' . $cartUrl);
            exit();
        }
    }

    // Menampilkan halaman sukses setelah checkout
    public function success() {
        $this->checkAuth();
        // Redirect ke beranda dengan parameter agar tidak perlu membuat file baru
        $orderId = isset($_GET['order_id']) ? urlencode($_GET['order_id']) : null;
        $redirect = (defined('BASE_URL') ? BASE_URL : '') . '/';
        if ($orderId) {
            $redirect .= '?order_success=1&order_id=' . $orderId;
        } else {
            $redirect .= '?order_success=1';
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function index() {
        $this->checkAdmin();
        $stmt = $this->pdo->prepare("SELECT o.id, o.user_id, o.total, o.status, o.created_at, u.name AS customer FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load view
        require __DIR__ . '/../../views/admin_orders.php';
    }

    public function updateStatus() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $redirect = (defined('BASE_URL') ? BASE_URL : '') . '/admin/orders';
            header('Location: ' . $redirect);
            exit;
        }

        $id = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;
        $allowed = ['pending','processing','shipped','completed','cancelled'];

        if (!$id || !in_array($status, $allowed, true)) {
            $redirect = (defined('BASE_URL') ? BASE_URL : '') . '/admin/orders';
            header('Location: ' . $redirect . '?error=invalid');
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $status, ':id' => $id]);
        } catch (Throwable $e) {
            error_log('OrderController::updateStatus error: ' . $e->getMessage());
            $redirect = (defined('BASE_URL') ? BASE_URL : '') . '/admin/orders';
            header('Location: ' . $redirect . '?error=server');
            exit;
        }

        header('Location: ' . (defined('BASE_URL') ? BASE_URL : '') . '/admin/orders');
        exit;
    }
}
?>