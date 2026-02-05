<?php
require_once '../../vendor/autoload.php';
require_once '../../config/db.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Configuramos la llave secreta
Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Recibimos los datos del carrito/pedido
$id_pedido = $_POST['id_pedido'];
$total_decimal = $_POST['total']; // Ejemplo: 25.50

try {
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => "Compra en Tienda Online - Pedido #$id_pedido",
                ],
                'unit_amount' => $total_decimal * 100, // Stripe usa céntimos (2550)
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        // Si el pago sale bien, Stripe vuelve aquí:
        'success_url' => 'http://localhost/Proyecto-final/views/pago_exitoso.php?id=' . $id_pedido,
        'cancel_url' => 'http://localhost/Proyecto-final/views/pago_cancelado.php',
    ]);

    // Redirigimos al cliente a Stripe
    header("Location: " . $session->url);
} catch (Exception $e) {
    echo "Error al conectar con Stripe: " . $e->getMessage();
}