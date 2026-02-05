# 📖 Referencia Completa de API

## 📑 Tabla de Contenidos

- [PagosController](#pagoscontroller)
- [CarritoController](#carritocontroller)
- [Sistema de Notificaciones](#sistema-de-notificaciones)
- [Tablas de Referencia](#tablas-de-referencia)

---

## PagosController

Gestión completa de pagos, reembolsos y transacciones.

### crearPago()

Crea un nuevo registro de pago en la base de datos.

```php
public function crearPago(
    int $userId,           // ID del usuario
    int $orderId,          // ID de la orden
    float $amount,         // Monto a pagar
    string $method,        // Método: 'tarjeta', 'transferencia', 'efectivo'
    string $description = '' // Descripción opcional
): bool
```

**Ejemplo:**
```php
$pagos = new PagosController();
$resultado = $pagos->crearPago(
    userId: 5,
    orderId: 12,
    amount: 250.50,
    method: 'tarjeta',
    description: 'Compra de remeras'
);

if ($resultado) {
    echo "✅ Pago creado exitosamente";
} else {
    echo "❌ Error al crear pago";
}
```

**Estados después:**
- `estado`: 'pendiente'

---

### confirmarPago()

Marca un pago como completado y envía email de confirmación.

```php
public function confirmarPago(
    int $pagoId,           // ID del pago a confirmar
    string $comprobante    // Referencia de comprobante/transacción
): bool
```

**Ejemplo:**
```php
$resultado = $pagos->confirmarPago(
    pagoId: 5,
    comprobante: 'TXN_202602051030'
);

if ($resultado) {
    echo "✅ Pago confirmado. Email enviado.";
}
```

**Acciones automáticas:**
- Cambiar estado a 'completado'
- Guardar ID de transacción
- Enviar email de confirmación a cliente
- Actualizar estado de orden

---

### cancelarPago()

Cancela un pago y notifica al usuario.

```php
public function cancelarPago(
    int $pagoId,           // ID del pago
    string $razon = ''     // Razón de cancelación
): bool
```

**Ejemplo:**
```php
$resultado = $pagos->cancelarPago(
    pagoId: 5,
    razon: 'Solicitud del cliente'
);
```

**Estados después:**
- `estado`: 'cancelado'
- Email enviado al usuario

---

### obtenerPago()

Obtiene información detallada de un pago específico.

```php
public function obtenerPago(int $pagoId): array|false
```

**Retorna:**
```php
[
    'id' => 5,
    'usuario_id' => 12,
    'orden_id' => 8,
    'monto' => 250.50,
    'metodo_pago' => 'tarjeta',
    'estado' => 'completado',
    'id_transaccion' => 'TXN_12345',
    'fecha_creacion' => '2026-02-05 10:30:00',
    'fecha_confirmacion' => '2026-02-05 10:35:00'
]
```

**Ejemplo:**
```php
$pago = $pagos->obtenerPago(5);

if ($pago) {
    echo "Monto: " . $pago['monto'];
    echo "Estado: " . $pago['estado'];
} else {
    echo "Pago no encontrado";
}
```

---

### obtenerPagosUsuario()

Lista todos los pagos de un usuario, opcionalmente filtrados por estado.

```php
public function obtenerPagosUsuario(
    int $userId,
    string $estado = null  // Opcional: 'pendiente', 'completado', 'cancelado'
): array
```

**Retorna:**
```php
[
    [
        'id' => 1,
        'monto' => 100.00,
        'estado' => 'completado',
        'fecha' => '2026-02-01'
    ],
    [
        'id' => 2,
        'monto' => 150.00,
        'estado' => 'pendiente',
        'fecha' => '2026-02-05'
    ]
]
```

**Ejemplo:**
```php
// Todos los pagos del usuario
$todos = $pagos->obtenerPagosUsuario(userId: 5);

// Solo pagos pendientes
$pendientes = $pagos->obtenerPagosUsuario(userId: 5, estado: 'pendiente');

// Solo pagos completados
$completados = $pagos->obtenerPagosUsuario(userId: 5, estado: 'completado');

foreach ($completados as $pago) {
    echo $pago['monto'] . " - " . $pago['fecha'];
}
```

---

### obtenerPagosOrden()

Obtiene todos los pagos asociados a una orden.

```php
public function obtenerPagosOrden(int $orderId): array
```

**Ejemplo:**
```php
$pagosOrden = $pagos->obtenerPagosOrden(orderId: 12);

echo "Total de pagos para orden 12: " . count($pagosOrden);
```

---

### obtenerResumenPagos()

Genera estadísticas de pagos en un rango de fechas.

```php
public function obtenerResumenPagos(
    string $fechaInicio,  // 'YYYY-MM-DD'
    string $fechaFin      // 'YYYY-MM-DD'
): array
```

**Retorna:**
```php
[
    'total_pagos' => 45,
    'monto_total' => 5250.00,
    'completados' => 40,
    'monto_completado' => 4950.00,
    'pendientes' => 3,
    'monto_pendiente' => 200.00,
    'cancelados' => 2,
    'monto_cancelado' => 100.00,
    'reembolsos' => 1,
    'monto_reembolsos' => 50.00
]
```

**Ejemplo:**
```php
$resumen = $pagos->obtenerResumenPagos('2026-01-01', '2026-02-05');

echo "Ingresos: " . $resumen['monto_completado'];
echo "Pendiente cobrar: " . $resumen['monto_pendiente'];
```

---

### procesarReembolso()

Procesa reembolsos totales o parciales de un pago.

```php
public function procesarReembolso(
    int $pagoId,          // ID del pago a reembolsar
    float $monto = null,  // null = reembolso total, número = parcial
    string $razon = ''    // Razón del reembolso
): bool
```

**Ejemplo:**

**Reembolso Total:**
```php
$resultado = $pagos->procesarReembolso(
    pagoId: 5,
    razon: 'Devolución de producto'
);
```

**Reembolso Parcial:**
```php
$resultado = $pagos->procesarReembolso(
    pagoId: 5,
    monto: 50.00,
    razon: 'Reembolso parcial por defecto'
);
```

**Acciones automáticas:**
- Actualizar estado a 'reembolsado' o 'parcialmente_reembolsado'
- Guardar monto reembolsado
- Enviar email de confirmación
- Crear registro en tabla de reembolsos

---

## CarritoController

Gestión del carrito y procesamiento de compras.

### verCarrito()

Muestra la página del carrito con los productos actuales.

```php
public function verCarrito(): void
```

**Ejemplo:**
```php
$carrito = new CarritoController();
$carrito->verCarrito();
```

**Requiere:**
- `$_SESSION['carrito']` inicializado
- User debe estar logueado

---

### procesarCompra()

Procesa una compra completa, crea orden, genera pago y envía emails.

```php
public function procesarCompra(
    int $userId,
    array $cartItems,      // Items del carrito
    float $totalAmount,    // Monto total
    float $discountAmount = 0  // Descuento aplicado
): bool
```

**Estructura de cartItems:**
```php
$cartItems = [
    [
        'producto_id' => 1,
        'cantidad' => 2,
        'precio_unitario' => 25.00
    ],
    [
        'producto_id' => 3,
        'cantidad' => 1,
        'precio_unitario' => 45.00
    ]
];
```

**Ejemplo:**
```php
$carrito = new CarritoController();

$items = [
    [
        'producto_id' => 1,
        'cantidad' => 2,
        'precio_unitario' => 25.00
    ]
];

$resultado = $carrito->procesarCompra(
    userId: 5,
    cartItems: $items,
    totalAmount: 50.00,
    discountAmount: 10.00  // 20% de descuento
);

if ($resultado) {
    echo "✅ Compra procesada. Email enviado.";
    $_SESSION['carrito'] = []; // Vaciar carrito
} else {
    echo "❌ Error al procesar compra";
}
```

**Acciones automáticas:**
- Crear registro en tabla `ordenes`
- Crear detalles en tabla `orden_detalles`
- Crear registro de pago (estado: pendiente)
- Actualizar stock de productos
- Enviar email de confirmación con descuento (si aplica)
- Limpiar sesión de carrito

---

## Sistema de Notificaciones

Funciones globales para enviar emails desde cualquier parte del código.

**Ubicación:** `controllers/mensajeriaController.php`

### notifyCustomers()

Notifica a usuarios en lista de espera que un producto está disponible.

```php
function notifyCustomers(
    int $productId,        // ID del producto
    string $productName,   // Nombre del producto
    string $productUrl,    // URL del producto
    string $productImage   // Path de la imagen
): bool
```

**Ejemplo:**
```php
$resultado = notifyCustomers(
    productId: 3,
    productName: 'Pantalón Premium Negro',
    productUrl: 'http://tienda.com/producto/3',
    productImage: 'public/img/pantalon_negro.jpg'
);

if ($resultado) {
    echo "✅ Clientes notificados";
}
```

**Email generado:**
- Destinatarios: Usuarios en `lista_espera` para ese producto
- Asunto: "¡Tu producto está disponible!"
- Cuerpo: HTML formateado con imagen y enlace

---

### notifyPurchase()

Envía confirmación de compra al cliente (sin descuento).

```php
function notifyPurchase(
    int $userId,           // ID del usuario
    string $userEmail,     // Email del usuario
    string $userName,      // Nombre del usuario
    string $orderDetails,  // Detalle de productos
    float $totalAmount,    // Monto total
    int $orderId           // ID de la orden
): bool
```

**Ejemplo:**
```php
$resultado = notifyPurchase(
    userId: 5,
    userEmail: 'juan@example.com',
    userName: 'Juan Pérez',
    orderDetails: 'Remera Roja (x2), Pantalón Negro (x1)',
    totalAmount: 95.00,
    orderId: 12
);
```

**Email generado:**
- Asunto: "Confirmación de tu compra #12"
- Cuerpo: Detalles de compra formateados en HTML
- Incluye: ID orden, total, fecha

---

### notifyPurchaseWithDiscount()

Envía confirmación de compra destacando descuento aplicado.

```php
function notifyPurchaseWithDiscount(
    int $userId,           // ID del usuario
    string $userEmail,     // Email del usuario
    string $userName,      // Nombre del usuario
    string $orderDetails,  // Detalle de productos
    float $totalAmount,    // Monto total FINAL
    float $discountAmount, // Monto descuento
    int $orderId           // ID de la orden
): bool
```

**Ejemplo:**
```php
$resultado = notifyPurchaseWithDiscount(
    userId: 5,
    userEmail: 'juan@example.com',
    userName: 'Juan Pérez',
    orderDetails: 'Remera Roja (x2), Pantalón Negro (x1)',
    totalAmount: 85.00,
    discountAmount: 10.00,  // 10% off
    orderId: 12
);
```

**Email generado:**
- Asunto: "¡Confirmación + Descuento Aplicado! Orden #12"
- Cuerpo: Destaca monto del descuento en color diferente
- Nota especial: "¡Obtuviste un descuento de $10.00!"

---

## Tablas de Referencia

### Estados de Pago

| Estado | Descripción | Transiciones |
|--------|-------------|--------------|
| `pendiente` | Recién creado, sin confirmar | → completado, cancelado |
| `completado` | Pago confirmado | → reembolsado, parcialmente_reembolsado |
| `cancelado` | Cancelado por usuario o admin | → pendiente (en algunos casos) |
| `reembolsado` | Reembolso total procesado | Final |
| `parcialmente_reembolsado` | Reembolso parcial | → reembolsado |

### Estados de Orden

| Estado | Descripción |
|--------|-------------|
| `pendiente` | Esperando pago |
| `pagada` | Pago completado |
| `enviada` | Enviada a cliente |
| `entregada` | Recibida por cliente |
| `cancelada` | Orden cancelada |

### Métodos de Pago Soportados

| Método | Descripción |
|--------|-------------|
| `tarjeta` | Tarjeta de crédito/débito |
| `transferencia` | Transferencia bancaria |
| `efectivo` | Pago en efectivo contra entrega |
| `stripe` | Integración con Stripe (en desarrollo) |

---

## 🔗 Flujos Completos

### Flujo: Compra Completa

```
1. Usuario agrega productos al carrito
   → $_SESSION['carrito'][] = ...

2. Usuario hace checkout
   → GET /views/carrito.php

3. Sistema procesa compra
   → $carrito->procesarCompra(...)
   
4. Se crea orden y pago (pendiente)
   → INSERT INTO ordenes...
   → INSERT INTO pagos (estado: 'pendiente')
   
5. Se envía email de confirmación
   → notifyPurchase() o notifyPurchaseWithDiscount()
   
6. Admin o sistema confirma pago
   → $pagos->confirmarPago($pagoId)
   
7. Estado de orden cambia a 'pagada'
   → UPDATE ordenes SET estado = 'pagada'
   
8. Se envía email de confirmación de pago
   → Email automático del sistema
```

### Flujo: Reembolso Parcial

```
1. Cliente solicita reembolso
   → Contacta a soporte

2. Admin marca reembolso
   → $pagos->procesarReembolso($pagoId, $monto, $razon)
   
3. Sistema:
   → Actualiza estado a 'parcialmente_reembolsado'
   → Guarda monto reembolsado
   → Envía email al cliente
   
4. Orden permanece como 'entregada'
   → Los detalles se mantienen
```

---

## ⚠️ Validaciones y Restricciones

### En crearPago()
- `$amount` debe ser mayor a 0
- `$method` debe ser 'tarjeta', 'transferencia', 'efectivo' o 'stripe'
- `$userId` debe existir en tabla usuarios
- `$orderId` debe existir en tabla ordenes

### En confirmarPago()
- El pago debe estar en estado 'pendiente'
- `$comprobante` no puede estar vacío

### En procesarReembolso()
- El pago debe estar en estado 'completado'
- Si se especifica `$monto`, debe ser menor al monto del pago
- Solo se puede procesar un reembolso por pago

---

## 🧪 Ejemplos de Integración

### Webhook de Stripe

```php
<?php
require_once 'controllers/PagosController.php';

$evento = json_decode(file_get_contents('php://input'));

if ($evento->type === 'payment_intent.succeeded') {
    $pagos = new PagosController();
    $pagos->confirmarPago(
        pagoId: $evento->data->object->metadata->pago_id,
        comprobante: $evento->data->object->id
    );
}
?>
```

### Carrito Persistente

```php
<?php
session_start();

// Obtener carrito de BD (no sesión)
$carrito = $_SESSION['carrito'] ?? [];

// Procesar compra
if ($_POST['action'] === 'checkout') {
    $total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
    
    $procesador = new CarritoController();
    $procesador->procesarCompra(
        userId: $_SESSION['user_id'],
        cartItems: $carrito,
        totalAmount: $total
    );
}
?>
```

---

**¡Ahora tienes toda la referencia de API! Consulta estos métodos según tus necesidades.** 📚
