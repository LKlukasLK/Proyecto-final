# ⚡ Guía Rápida - Empezar en 5 minutos

## 1️⃣ Instalación Rápida

```bash
# Clonar repo
git clone <URL> && cd Proyecto-final

# Instalar dependencias
composer install

# Copiar configuración
cp .env.example .env

# Configurar .env (editar con tus datos)
# - DB_HOST, DB_USER, DB_PASS
# - SMTP_HOST, SMTP_USER, SMTP_PASS
```

## 2️⃣ Base de Datos

```bash
# Crear BD desde script
mysql -u root -p < config/script.sql

# Importar datos de prueba
mysql -u root -p tienda_online < config/datos_script.sql
```

## 3️⃣ Iniciar Servidor

```bash
php -S localhost:8000
```

Accede a: **http://localhost:8000**

---

## 🎯 Casos de Uso Rápidos

### ✅ Cliente: Realizar Compra

```php
// 1. Usuario se registra
GET /registro.php

// 2. Usuario inicia sesión
GET /login.php

// 3. Ver catálogo
GET /views/catalogo.php

// 4. Carrito automático al agregar producto
$_SESSION['carrito'][] = ['id' => 1, 'cant' => 2];

// 5. Checkout
GET /views/carrito.php

// 6. Sistema envía email con confirmación
// Automático via notifyPurchase()
```

### ✅ Admin: Crear Producto

```php
// Acceder a panel admin
GET /admin/

// Ir a Gestión de Productos
GET /admin/views/gestion_productos.php

// Formulario crea producto en BD
INSERT INTO productos VALUES (...)

// Email notifica a lista de espera
notifyCustomers($id, $nombre, $url, $img)
```

### ✅ Dev: Procesar Pago

```php
<?php
require_once 'controllers/PagosController.php';
$pagos = new PagosController();

// Crear pago
$resultado = $pagos->crearPago(
    userId: 5,
    orderId: 12,
    amount: 150.00,
    method: 'tarjeta'
);

// Confirmar pago
$pagos->confirmarPago($pagoId, $comprobante);

// El sistema envía email automáticamente
?>
```

### ✅ Dev: Enviar Notificación

```php
<?php
require_once 'controllers/mensajeriaController.php';

// Email de compra simple
notifyPurchase(
    userId: 1,
    email: 'usuario@example.com',
    userName: 'Juan',
    orderDetails: 'Producto X, Cantidad 2',
    totalAmount: 100.00,
    orderId: 5
);

// Email de compra con descuento
notifyPurchaseWithDiscount(
    userId: 1,
    email: 'usuario@example.com',
    userName: 'Juan',
    orderDetails: 'Producto X, Cantidad 2',
    totalAmount: 150.00,
    discountAmount: 30.00,  // Descuento aplicado
    orderId: 5
);

// Email de disponibilidad de producto
notifyCustomers(
    productId: 3,
    productName: 'Pantalón Premium',
    productUrl: 'http://tienda.com/producto/3',
    productImage: 'img/pantalon.jpg'
);
?>
```

---

## 📊 Estructura de Datos Clave

### 🛒 Carrito en Sesión

```php
$_SESSION['carrito'] = [
    [
        'id' => 1,
        'nombre' => 'Remera',
        'precio' => 25.00,
        'cantidad' => 2,
        'imagen' => 'img/remera.jpg'
    ],
    [
        'id' => 3,
        'nombre' => 'Pantalón',
        'precio' => 45.00,
        'cantidad' => 1,
        'imagen' => 'img/pantalon.jpg'
    ]
];
```

### 💳 Estructura de Pago

```php
$pago = [
    'id' => 1,
    'usuario_id' => 5,
    'orden_id' => 12,
    'monto' => 150.00,
    'metodo_pago' => 'tarjeta',
    'estado' => 'completado',
    'id_transaccion' => 'tx_12345',
    'fecha' => '2026-02-05 10:30:00'
];
```

### 📦 Estructura de Orden

```php
$orden = [
    'id' => 12,
    'usuario_id' => 5,
    'total' => 150.00,
    'estado' => 'pagada',
    'fecha' => '2026-02-05'
];

$detalles = [
    [
        'orden_id' => 12,
        'producto_id' => 1,
        'cantidad' => 2,
        'precio' => 25.00
    ]
];
```

---

## 🔍 Tests Rápidos

### Test 1: Verificar Conexión BD

```php
<?php
require_once 'config/db.php';

try {
    $conn = Database::conectar();
    echo "✅ Base de datos conectada!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

### Test 2: Prueba Email

```php
<?php
require_once 'controllers/mensajeriaController.php';

$resultado = notifyPurchase(
    userId: 1,
    email: 'test@mailtrap.io',
    userName: 'Test User',
    orderDetails: 'Test Producto',
    totalAmount: 100,
    orderId: 999
);

echo $resultado ? "✅ Email enviado" : "❌ Error al enviar";
?>
```

### Test 3: Crear Pago

```php
<?php
require_once 'controllers/PagosController.php';
$pagos = new PagosController();

$resultado = $pagos->crearPago(1, 1, 50.00, 'tarjeta', 'Compra test');
echo $resultado ? "✅ Pago creado" : "❌ Error";
?>
```

---

## ⚡ Rutas Principales

| Ruta | Descripción |
|------|-------------|
| `/` | Home / Catálogo |
| `/views/login.php` | Login |
| `/views/registro.php` | Registro |
| `/views/catalogo.php` | Catálogo productos |
| `/views/carrito.php` | Carrito compras |
| `/admin/` | Panel admin |
| `/admin/views/gestion_productos.php` | Gestionar productos |

---

## 🐛 Errores Comunes

### ❌ "Table 'ordenes' not found"
```bash
# Solución: Ejecutar script SQL
mysql -u root -p tienda_online < config/script.sql
```

### ❌ "SMTP connect() failed"
- Verificar credenciales en `.env`
- Usar Mailtrap (gratis) para testing
- Verificar puerto SMTP: 587

### ❌ "Undefined variable: carrito"
```php
// Siempre iniciar sesión primero
session_start();
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
```

### ❌ "Connection refused"
- Verificar MySQL corriendo: `mysql -u root -p`
- Verificar localhost:3306 en `.env`

---

## 📚 Aprende Más

- **[README.md](README.md)** - Documentación general
- **[API_REFERENCE.md](API_REFERENCE.md)** - Todos los métodos
- **[BASE_DATOS.md](BASE_DATOS.md)** - Schema completo
- **[FUNCIONES_NOTIFICACION.md](FUNCIONES_NOTIFICACION.md)** - Sistema de emails
- **[SISTEMA_PAGOS.md](SISTEMA_PAGOS.md)** - Gestión de pagos

---

**¡Listo! Ya puedes empezar a desarrollar 🚀**
