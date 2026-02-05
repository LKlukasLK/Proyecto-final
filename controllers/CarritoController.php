<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/mensajeriaController.php';

class CarritoController {
    
    public function verCarrito() {
        // --- LIMPIEZA DE SESIÓN (30 DÍAS) ---
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            $segundos_en_30_dias = 15;
            $ahora = time();
            $cambios = false;

            foreach ($_SESSION['carrito'] as $indice => $item) {
                // Si el item tiene fecha y es mayor a 30 días, lo quitamos
                if (isset($item['fecha']) && ($ahora - $item['fecha'] > $segundos_en_30_dias)) {
                    unset($_SESSION['carrito'][$indice]);
                    $cambios = true;
                }
            }

            if ($cambios) {
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
        }

        require_once 'views/carrito.php';
    }

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

            // 2. Crear la orden
            $stmt = $pdo->prepare("INSERT INTO ordenes (usuario_id, total, estado, fecha) VALUES (?, ?, 'confirmada', NOW())");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $pdo->lastInsertId();

            // 3. Insertar detalles y limpiar
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO orden_detalles (orden_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], 1, $item['precio']]);
            }

            // Limpiar persistencia en BD tras comprar
            $stmt = $pdo->prepare("DELETE FROM detalles_carrito WHERE id_carrito = (SELECT id_carrito FROM carritos WHERE id_usuario = ?)");
            $stmt->execute([$userId]);

            $_SESSION['carrito'] = [];

            // 4. Notificación
            $resultado = notifyPurchase($userId, $usuario['email'], $usuario['nombre'], $cartItems, $totalAmount, $orderId);

            return [
                "success" => true,
                "message" => "Compra procesada correctamente",
                "orderId" => $orderId,
                "emailEnviado" => $resultado
            ];

        } catch (Exception $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
}