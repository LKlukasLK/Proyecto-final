<?php
require_once 'vendor/autoload.php';
require_once 'config/db.php';

use Stripe\Stripe;
use Stripe\Webhook;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);


$endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    // Verificar que la llamada viene realmente de Stripe
    $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
} catch(\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// 3. Manejar el evento de "Pago Exitoso"
if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;

    // Recuperamos el ID del pedido que enviamos en el metadato
    $id_pedido = $session->metadata->id_pedido;
    $metodo_pago = 'tarjeta';

    try {
        $db = Database::conectar();
        // Ejecutamos tu procedimiento almacenado de SQL
        $stmt = $db->prepare("CALL ConfirmarPedido(?, ?)");
        $stmt->execute([$id_pedido, $metodo_pago]);
        

        
    } catch (Exception $e) {
        file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
    }
}

http_response_code(200);