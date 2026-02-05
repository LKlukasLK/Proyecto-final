<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/MensajeriaController.php';

class CarritoController {
    public function verCarrito() {
        // Aquí iría la lógica para obtener los productos en el carrito
        // Por simplicidad, solo incluimos la vista directamente
        require_once 'views/carrito.php';
    }

    /**
     * Procesa la compra y envía notificación al cliente
     * @param int $userId - ID del usuario que compra
     * @param array $cartItems - Array con los productos del carrito
     * @param float $totalAmount - Monto total de la compra
     * @param float $discountAmount - (Opcional) Monto del descuento aplicado
     */
    public function procesarCompra($userId, $cartItems, $totalAmount, $discountAmount = 0) {
        try {
            $pdo = Database::conectar();

            // Obtener datos del usuario
            $stmt = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
            $stmt->execute([$userId]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ["success" => false, "message" => "Usuario no encontrado"];
            }

            // Insertar la orden en la base de datos
            $stmt = $pdo->prepare("INSERT INTO ordenes (usuario_id, total, estado, fecha) VALUES (?, ?, 'confirmada', NOW())");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $pdo->lastInsertId();

            // Insertar detalles de la orden
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO orden_detalles (orden_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], $item['cantidad'], $item['precio']]);
            }

            // Enviar notificación de compra
                
            $resultado = notifyPurchase(
                $userId,
                $usuario['email'],
                $usuario['nombre'],
                $cartItems,
                $totalAmount,
                $orderId);

            

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
?>