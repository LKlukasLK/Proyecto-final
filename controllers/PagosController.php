<?php
require_once '../../vendor/autoload.php';
require_once '../../config/db.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dotenv\Dotenv;

// Iniciamos la sesión para acceder a los datos del usuario
session_start();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Verificamos que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    die("Error: Debes estar autenticado para realizar un pago");
}

$usuario_id = $_SESSION['usuario_id'];

// Verificamos que se hayan enviado los datos necesarios para el pago
if (!isset($_POST['id_pedido']) || !isset($_POST['total'])) {
    http_response_code(400);
    die("Error: Datos incompletos para procesar el pago");
}

$id_pedido = intval($_POST['id_pedido']); // Convertir a entero 
$total_decimal = floatval($_POST['total']); // Convertir a float

// Verificamos que el ID del pedido y el total sean válidos
if ($id_pedido <= 0 || $total_decimal <= 0) {
    http_response_code(400);
    die("Error: ID de pedido o total inválido");
}

// Verificamos que el pedido exista, que pertenezca al usuario y que el total coincida con lo que tenemos en la base de datos
$pdo = Database::conectar();
$sqlPedido = "SELECT id, usuario_id, total FROM pedidos WHERE id = :id_pedido";
$stmtPedido = $pdo->prepare($sqlPedido);
$stmtPedido->execute(['id_pedido' => $id_pedido]);
$pedido = $stmtPedido->fetch();

if (!$pedido) {
    http_response_code(404);
    die("Error: El pedido no existe");
}

// Verificamos que el pedido pertenezca al usuario que está intentando pagar
if ($pedido['usuario_id'] != $usuario_id) {
    http_response_code(403);
    die("Error: No tienes permiso para pagar este pedido");
}

// Verificamos que el total enviado desde el cliente coincida con el total registrado en la base de datos para evitar manipulaciones
$total_bd = floatval($pedido['total']);
if (abs($total_bd - $total_decimal) > 0.01) { 
    http_response_code(400);
    die("Error: El total del pedido no coincide. Total esperado: €" . $total_bd);
}

// Configuramos la llave secreta
Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

try {
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => "Compra en Tienda Online - Pedido #$id_pedido",
                ],
                'unit_amount' => $total_decimal * 100, 
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        // Si el pago es exitoso, redirigimos a una página de éxito con el ID del pedido
        'success_url' => 'http://localhost/Proyecto-final/views/pago_exitoso.php?id=' . $id_pedido,
        'cancel_url' => 'http://localhost/Proyecto-final/views/pago_cancelado.php',
    ]);

    // Redirigimos al cliente a Stripe
    header("Location: " . $session->url);
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al conectar con Stripe: " . $e->getMessage();
}