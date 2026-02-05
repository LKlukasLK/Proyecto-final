<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/mensajeriaController.php';

class CarritoController {
    
    /**
     * Muestra la vista del carrito sin restricciones de tiempo
     */
    public function verCarrito() {
        // Se ha eliminado la limpieza automática por 30 días.
        // Los productos permanecerán en la sesión hasta que el usuario los borre o compre.
        require_once 'views/carrito.php';
    }

    /**
     * Procesa la compra y envía notificación al cliente
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

            // 2. Insertar la orden
            $stmt = $pdo->prepare("INSERT INTO ordenes (usuario_id, total, estado, fecha) VALUES (?, ?, 'confirmada', NOW())");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $pdo->lastInsertId();

            // 3. Insertar detalles de la orden
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO orden_detalles (orden_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], 1, $item['precio']]);
            }

            /**
             * 4. Limpiar carrito de la base de datos tras la compra.
             * Ahora este es el único momento en el que se eliminan los registros de detalles_carrito.
             */
            $stmt = $pdo->prepare("DELETE FROM detalles_carrito WHERE id_carrito = (SELECT id_carrito FROM carritos WHERE id_usuario = ?)");
            $stmt->execute([$userId]);

            // 5. Limpiar carrito de la sesión
            $_SESSION['carrito'] = [];

            // 6. Enviar notificación
            $resultado = notifyPurchase(
                $userId,
                $usuario['email'],
                $usuario['nombre'],
                $cartItems,
                $totalAmount,
                $orderId
            );

            return [
                "success" => true,
                "message" => "Compra procesada correctamente",
                "orderId" => $orderId,
                "emailEnviado" => $resultado
            ];

        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Error al procesar la compra: " . $e->getMessage()
            ];
        }
    }
}