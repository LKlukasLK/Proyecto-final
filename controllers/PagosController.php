<?php
// controllers/pagos/PagosController.php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dotenv\Dotenv;

class PagosController {

    public function procesar() {
        // Cargamos el .env desde la raíz del proyecto
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        if (!isset($_SESSION['id_usuario'])) {
            die("Error: Sesión no iniciada");
        }

        $id_pedido = intval($_POST['id_pedido'] ?? 0);
        $total_decimal = floatval($_POST['total'] ?? 0);

        try {
            $pdo = Database::conectar();
            // Verificamos en la tabla 'ordenes' que ya existe en tu DB
            $stmt = $pdo->prepare("SELECT * FROM ordenes WHERE id = ?");
            $stmt->execute([$id_pedido]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) die("Pedido no encontrado");

            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            // URL absoluta basada en tu IP detectada
            $baseUrl = "http://192.168.0.106/pagweb";

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => "Pedido #$id_pedido"],
                        'unit_amount' => round($total_decimal * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $baseUrl . '/views/pago_exitoso.php?id=' . $id_pedido,
                'cancel_url' => $baseUrl . '/views/pago_cancelado.php',
            ]);

            header("Location: " . $session->url);
            exit();

        } catch (Exception $e) {
            die("Error en Stripe: " . $e->getMessage());
        }
    }
}