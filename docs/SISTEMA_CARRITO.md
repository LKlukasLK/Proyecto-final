# 🎁 Sistema de Carrito de Compras

## 📑 Tabla de Contenidos

- [Visión General](#visión-general)
- [Arquitectura](#arquitectura)
- [Flujo del Carrito](#flujo-del-carrito)
- [Implementación](#implementación)
- [Ejemplos Prácticos](#ejemplos-prácticos)
- [Manejo de Errores](#manejo-de-errores)

---

## 👀 Visión General

El sistema de carrito de compras permite a los clientes:

✅ Agregar productos al carrito  
✅ Ver lista de productos en carrito  
✅ Modificar cantidades  
✅ Eliminar productos  
✅ Aplicar descuentos  
✅ Procesar compra completamente  
✅ Generar orden y pago automáticamente  
✅ Recibir email de confirmación  

---

## 🏗️ Arquitectura

### Almacenamiento

El carrito se almacena en **sesión PHP** (no en base de datos durante la compra):

```php
$_SESSION['carrito'] = [
    [
        'id' => 1,
        'nombre' => 'Remera Roja',
        'precio' => 25.00,
        'cantidad' => 2,
        'imagen' => 'img/remera_roja.jpg'
    ],
    [
        'id' => 3,
        'nombre' => 'Pantalón Negro',
        'precio' => 45.00,
        'cantidad' => 1,
        'imagen' => 'img/pantalon_negro.jpg'
    ]
];
```

### Controlador Responsable

**Ubicación:** `controllers/CarritoController.php`

**Métodos Principales:**
- `verCarrito()` - Muestra página del carrito
- `procesarCompra()` - Procesa compra completa

### Vista Asociada

**Ubicación:** `views/carrito.php`

---

## 🔄 Flujo del Carrito

```
1. INICIO
   └─ Usuario entra a tienda

2. AGREGAR PRODUCTOS
   ├─ Usuario selecciona producto
   ├─ Sistema agrega a $_SESSION['carrito']
   └─ Mostrar cantidad de items en carrito

3. VER CARRITO
   ├─ GET /views/carrito.php
   ├─ Mostrar todos los productos
   ├─ Mostrar subtotal y totales
   └─ Permitir editar cantidades/eliminar

4. DESCUENTOS (Opcional)
   ├─ Usuario ingresa código descuento
   ├─ Sistema valida código
   ├─ Aplica descuento a total
   └─ Muestra nuevo total

5. CHECKOUT
   ├─ Usuario hace click "Comprar"
   ├─ Sistema valida
   ├─ Ejecuta $carrito->procesarCompra()
   └─ Crear orden en BD

6. PROCESAMIENTO
   ├─ Crear registro en `ordenes`
   ├─ Crear detalles en `orden_detalles`
   ├─ Crear pago (estado: pendiente)
   ├─ Actualizar stock de productos
   ├─ Enviar email de confirmación
   └─ Vaciar $_SESSION['carrito']

7. CONFIRMACIÓN
   ├─ Mostrar mensaje de éxito
   ├─ Cliente recibe email
   └─ Orden lista para pago
```

---

## 💻 Implementación

### 1. Inicializar Sesión

En `index.php` o en cada página:

```php
<?php
session_start();

// Inicializar carrito vacío
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Usuario debe estar logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: /views/login.php');
    exit;
}
?>
```

### 2. Agregar Producto al Carrito

En `views/catalogo.php` o AJAX:

```php
<?php
session_start();
require_once 'config/db.php';
require_once 'models/ProductoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'agregar') {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'] ?? 1;
    
    // Obtener datos del producto
    $productoModel = new ProductoModel();
    $producto = $productoModel->obtenerPorId($producto_id);
    
    if (!$producto) {
        echo json_encode(['error' => 'Producto no encontrado']);
        exit;
    }
    
    // Verificar stock disponible
    if ($producto['stock'] < $cantidad) {
        echo json_encode(['error' => 'Stock insuficiente']);
        exit;
    }
    
    // Agregar a carrito
    $_SESSION['carrito'][] = [
        'id' => $producto_id,
        'nombre' => $producto['nombre'],
        'precio' => $producto['precio'],
        'cantidad' => $cantidad,
        'imagen' => $producto['imagen']
    ];
    
    echo json_encode([
        'success' => true,
        'carrito_items' => count($_SESSION['carrito']),
        'total' => calcularTotalCarrito()
    ]);
}

function calcularTotalCarrito() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    return $total;
}
?>
```

### 3. Ver Carrito

```php
<?php
require_once 'controllers/CarritoController.php';

$carrito = new CarritoController();
$carrito->verCarrito(); // Renderiza views/carrito.php
?>
```

### 4. Procesar Compra

```php
<?php
session_start();
require_once 'controllers/CarritoController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'comprar') {
    $carrito = new CarritoController();
    
    // Preparar datos
    $items = $_SESSION['carrito'];
    $total = array_sum(
        array_map(fn($i) => $i['precio'] * $i['cantidad'], $items)
    );
    
    // Aplicar descuento si existe
    $descuento = $_SESSION['descuento'] ?? 0;
    $total_final = $total - $descuento;
    
    // Procesar compra
    $resultado = $carrito->procesarCompra(
        userId: $_SESSION['user_id'],
        cartItems: $items,
        totalAmount: $total_final,
        discountAmount: $descuento
    );
    
    if ($resultado) {
        // Limpiar carrito
        $_SESSION['carrito'] = [];
        unset($_SESSION['descuento']);
        
        echo json_encode(['success' => true, 'mensaje' => 'Compra procesada. Email enviado.']);
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'Error al procesar compra']);
    }
}
?>
```

### 5. Modificar Cantidad

```php
<?php
if ($_POST['accion'] === 'actualizar_cantidad') {
    $indice = $_POST['indice'];
    $nueva_cantidad = $_POST['cantidad'];
    
    if (isset($_SESSION['carrito'][$indice])) {
        if ($nueva_cantidad <= 0) {
            // Eliminar si cantidad es 0
            unset($_SESSION['carrito'][$indice]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        } else {
            $_SESSION['carrito'][$indice]['cantidad'] = $nueva_cantidad;
        }
    }
}
?>
```

### 6. Eliminar Producto

```php
<?php
if ($_POST['accion'] === 'eliminar') {
    $indice = $_POST['indice'];
    
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
    }
}
?>
```

### 7. Vaciar Carrito

```php
<?php
if ($_POST['accion'] === 'vaciar') {
    $_SESSION['carrito'] = [];
    unset($_SESSION['descuento']);
}
?>
```

---

## 📋 Ejemplos Prácticos

### Ejemplo 1: Tienda Completa

**views/catalogo.php**

```html
<?php
session_start();
require_once 'config/db.php';
require_once 'models/ProductoModel.php';

$productoModel = new ProductoModel();
$productos = $productoModel->obtenerTodos();
?>

<div class="catalogo">
    <?php foreach ($productos as $prod): ?>
    <div class="producto">
        <h3><?= $prod['nombre'] ?></h3>
        <img src="<?= $prod['imagen'] ?>">
        <p>$<?= number_format($prod['precio'], 2) ?></p>
        <p>Stock: <?= $prod['stock'] ?></p>
        
        <form method="POST" class="agregar-form">
            <input type="hidden" name="accion" value="agregar">
            <input type="hidden" name="producto_id" value="<?= $prod['id'] ?>">
            
            <input type="number" name="cantidad" value="1" min="1" max="<?= $prod['stock'] ?>">
            <button type="submit">Agregar al carrito</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>

<a href="/views/carrito.php" class="btn-carrito">
    Ver carrito (<?= count($_SESSION['carrito']) ?> items)
</a>
```

### Ejemplo 2: Vista del Carrito

**views/carrito.php**

```html
<?php
session_start();
require_once 'controllers/CarritoController.php';

$carrito = new CarritoController();
$carrito->verCarrito();

if (empty($_SESSION['carrito'])): ?>
    <p>Tu carrito está vacío</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['carrito'] as $index => $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $item['nombre'] ?></td>
                <td>$<?= number_format($item['precio'], 2) ?></td>
                <td>
                    <input type="number" value="<?= $item['cantidad'] ?>" min="1"
                           onchange="actualizarCantidad(<?= $index ?>, this.value)">
                </td>
                <td>$<?= number_format($subtotal, 2) ?></td>
                <td>
                    <button onclick="eliminarProducto(<?= $index ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="resumen">
        <p>Subtotal: $<?= number_format($total, 2) ?></p>
        
        <?php if (isset($_SESSION['descuento'])): ?>
        <p class="descuento">
            Descuento: -$<?= number_format($_SESSION['descuento'], 2) ?>
        </p>
        <p><strong>Total: $<?= number_format($total - $_SESSION['descuento'], 2) ?></strong></p>
        <?php else: ?>
        <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
        <?php endif; ?>
    </div>
    
    <form method="POST">
        <input type="hidden" name="accion" value="comprar">
        <button type="submit" class="btn-comprar">Proceder a Checkout</button>
    </form>
<?php endif; ?>
```

### Ejemplo 3: Aplicar Descuento

```php
<?php
if ($_POST['accion'] === 'aplicar_descuento') {
    $codigo = $_POST['codigo_descuento'];
    
    // Validar código
    $descuentos = [
        'PROMO2026' => 10,      // $10 descuento
        'BLACKFRIDAY' => 50,    // $50 descuento
        'OFFERTA20' => 20       // $20 descuento
    ];
    
    if (isset($descuentos[$codigo])) {
        $_SESSION['descuento'] = $descuentos[$codigo];
        echo json_encode(['success' => true, 'descuento' => $descuentos[$codigo]]);
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'Código inválido']);
    }
}
?>
```

---

## ⚠️ Manejo de Errores

### Validaciones Implementadas

```php
// 1. Usuario debe estar logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: /views/login.php');
    exit;
}

// 2. Validar stock disponible
if ($cantidad > $producto['stock']) {
    throw new Exception('Stock insuficiente para la cantidad solicitada');
}

// 3. Validar carrito no vacío
if (empty($_SESSION['carrito'])) {
    throw new Exception('El carrito está vacío');
}

// 4. Validar monto mínimo
$total = calcularTotalCarrito();
if ($total < 10) {
    throw new Exception('El monto mínimo de compra es $10');
}

// 5. Validar información de envío
if (empty($_POST['direccion']) || empty($_POST['ciudad'])) {
    throw new Exception('Información de envío incompleta');
}
```

### Manejo de Excepciones

```php
try {
    $resultado = $carrito->procesarCompra(
        $_SESSION['user_id'],
        $_SESSION['carrito'],
        $total
    );
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
    exit;
}
```

---

## 🧪 Testing del Carrito

### Test 1: Agregar Producto

```bash
curl -X POST http://localhost:8000/views/catalogo.php \
  -d "accion=agregar&producto_id=1&cantidad=2"
```

### Test 2: Ver Carrito

```bash
curl http://localhost:8000/views/carrito.php
```

### Test 3: Procesar Compra

```bash
curl -X POST http://localhost:8000/views/carrito.php \
  -d "accion=comprar"
```

---

## 🎯 Mejoras Futuras

- [ ] Guardar carrito en BD para recuperación
- [ ] Cupones de descuento dinámicos
- [ ] Envío express con costo
- [ ] Productos recomendados
- [ ] Historial de carritos guardados
- [ ] Comparador de productos
- [ ] Notificaciones de cambio de precio

---

**Sistema de carrito completamente funcional y documentado ✅**
