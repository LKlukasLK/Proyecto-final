<?php
// controllers/PagosController.php

// Usamos rutas absolutas basadas en la ubicación del archivo actual
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config/db.php';


use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dotenv\Dotenv;

class PagosController {

    public function procesar(): void {
        
        // Cargamos el .env desde la raíz del proyecto (un nivel arriba)
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        if (!isset($_SESSION['id_usuario'])) {
            die("Error: Sesión no iniciada. Por favor, inicia sesión.");
        }

        $id_pedido = intval($_POST['id_pedido'] ?? 0);
        $total_decimal = floatval($_POST['total'] ?? 0);

        try {
            $pdo = Database::conectar();
            
            // Verificamos en la tabla 'pedidos' de tu base de datos 'prueba1tiendaonline'
            $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
            $stmt->execute([$id_pedido]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                die("Error: El pedido #$id_pedido no existe en la base de datos.");
            }

            // Configuramos Stripe con tu clave secreta del archivo .env
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            
            $baseUrl = "http://192.168.0.106/pagweb";

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "Compra Pedido #$id_pedido",
                        ],
                        'unit_amount' => round($total_decimal * 100), // Stripe usa céntimos
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => "{$baseUrl}/views/pago_exitoso.php?id={$id_pedido}",
                'cancel_url' => "{$baseUrl}/views/pago_cancelado.php",
            ]);

            // Redirección final a Stripe
            header("Location: {$session->url}");
            exit();

        } catch (Exception $e) {
            die("Error crítico en la conexión con Stripe: " . $e->getMessage());
        }
    }
}