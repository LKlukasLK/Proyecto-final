<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/MensajeriaController.php';

class CarritoController {
    
    /**
     * Muestra la vista del carrito sin restricciones de tiempo
     */
    public function verCarrito() {
        require_once 'views/carrito.php';
    }

    /**
     * Inicia el proceso de pago: Crea la orden y redirige a Stripe
     */
    public function iniciarProcesoPago() {
        if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrito'])) {
            header("Location: index.php?ver=carrito");
            exit();
        }

        $userId = $_SESSION['id_usuario'];
        $total = $_POST['total'] ?? 0;
        $items = $_SESSION['carrito'];

        $resultado = $this->procesarCompra($userId, $items, $total);

        if ($resultado['success']) {
            // Redirigimos al enrutador principal con el parámetro 'ver'
            echo "
            <form id='stripeRedirect' action='index.php?ver=finalizar_pago' method='POST'>
                <input type='hidden' name='id_pedido' value='{$resultado['orderId']}'>
                <input type='hidden' name='total' value='{$total}'>
            </form>
            <script>document.getElementById('stripeRedirect').submit();</script>";
            exit();
        }
    }

    /**
     * Procesa la compra en la BD y prepara los datos
     */
    public function procesarCompra($userId, $cartItems, $totalAmount, $discountAmount = 0) {
        try {
            $pdo = Database::conectar();

            // 1. Obtener datos del usuario
            $stmt = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$userId]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ["success" => false, "message" => "Usuario no encontrado"];
            }

            // 2. Insertar la orden (Estado 'pendiente' hasta confirmar pago)
            $stmt = $pdo->prepare("INSERT INTO pedidos (id_pedido, id_usuario, fecha_pedido, total, estado) VALUES (?, ?, NOW(), ?, 'pendiente')");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $pdo->lastInsertId();

            // 3. Insertar detalles de la orden
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO detalles_pedido (id_detalle, id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], 1, $item['cantidad'], $item['precio']]);
            }

            // 4. Limpiar carritos existentes (Evita error de cardinalidad)
            // Se usa IN para prevenir el error 'Subquery returns more than 1 row'
            $stmt = $pdo->prepare("DELETE FROM detalles_carrito WHERE id_carrito IN (SELECT id_carrito FROM carritos WHERE id_usuario = ?)");
            $stmt->execute([$userId]);

            // 5. Limpiar cabecera de carrito
            $stmt = $pdo->prepare("DELETE FROM carritos WHERE id_usuario = ?");
            $stmt->execute([$userId]);

            // 6. Limpiar carrito de la sesión
            $_SESSION['carrito'] = [];

            return [
                "success" => true,
                "message" => "Orden registrada en espera de pago",
                "orderId" => $orderId
            ];

        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Error al procesar la compra: " . $e->getMessage()
            ];
        }
    }
}