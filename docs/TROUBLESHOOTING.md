# 🆘 Guía de Problemas y Soluciones

## 📑 Tabla de Contenidos

- [Errores Generales](#errores-generales)
- [Base de Datos](#base-de-datos)
- [Autenticación](#autenticación)
- [Email y Notificaciones](#email-y-notificaciones)
- [Carrito y Compras](#carrito-y-compras)
- [Pagos](#pagos)
- [Archivo y Sistema](#archivo-y-sistema)

---

## 🔴 Errores Generales

### ❌ "Fatal error: Uncaught Exception"

**Síntomas:**
```
Fatal error: Uncaught Exception: La excepción no fue capturada
Stack trace: ...
```

**Causas Posibles:**
1. Archivo no encontrado
2. Clase no definida
3. Base de datos no conectada

**Solución:**

```php
// Agregar al inicio de index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ver stack trace completo
try {
    // Tu código aquí
} catch (Exception $e) {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    die("Error: " . $e->getMessage());
}
```

---

### ❌ "Undefined variable"

**Ejemplo:**
```
Notice: Undefined variable: carrito in /path/file.php
```

**Solución:**

```php
// ❌ Mal
echo $_SESSION['carrito'];

// ✅ Bien
echo $_SESSION['carrito'] ?? '[]';

// O inicializar primero
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
echo count($_SESSION['carrito']);
```

---

### ❌ "Headers already sent"

**Síntomas:**
```
Warning: Cannot modify header information
Headers already sent in /path/file.php:5
```

**Causa:** Hay salida antes de `session_start()` o `header()`

**Solución:**

```php
// ✅ Correcto - session_start() primero
<?php
session_start();
require_once 'config/db.php';
// Sin espacios en blanco aquí arriba
?>

// Luego puedo hacer redirecciones
header('Location: /index.php');
```

---

## 🗄️ Base de Datos

### ❌ "Connection refused"

**Síntomas:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Causas Posibles:**
- MySQL no está corriendo
- Puerto incorrecto
- Host incorrecto

**Solución:**

```bash
# Verificar que MySQL está corriendo
ps aux | grep mysqld

# Iniciar MySQL
# Mac:
brew services start mysql

# Linux:
sudo systemctl start mysql

# Windows (Services):
net start MySQL80
```

**Verificar conexión:**

```bash
mysql -u root -p
# Enter password

mysql> SELECT VERSION();
```

**Revisar .env:**
```env
DB_HOST=localhost      # No 127.0.0.1
DB_PORT=3306
DB_USER=root
DB_PASS=tu_contraseña
```

---

### ❌ "Access denied for user"

**Síntomas:**
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'
```

**Causas:**
- Contraseña incorrecta
- Usuario no existe
- Host incorrecto

**Solución:**

```bash
# Conectarse sin contraseña
mysql -u root

# O con contraseña vacía
mysql -u root -p
# Enter password: (dejar vacío)

# Si olvidaste contraseña, resetearla
# Linux/Mac:
sudo mysql -u root
mysql> FLUSH PRIVILEGES;
mysql> ALTER USER 'root'@'localhost' IDENTIFIED BY 'nueva_contraseña';

# Windows:
# Detener MySQL
net stop MySQL80
# Iniciar sin verificación
mysqld --skip-grant-tables
# Luego cambiar contraseña
```

---

### ❌ "Table doesn't exist"

**Síntomas:**
```
SQLSTATE[42S02]: Table 'tienda_online.usuarios' doesn't exist
```

**Causa:** La base de datos no fue creada correctamente

**Solución:**

```bash
# Reimportar script
mysql -u root -p tienda_online < config/script.sql

# Verificar tablas
mysql -u root -p -e "USE tienda_online; SHOW TABLES;"

# Debería mostrar:
# +--------------------+
# | Tables_in_tienda_online |
# +--------------------+
# | usuarios           |
# | productos          |
# | ordenes            |
# | orden_detalles     |
# | pagos              |
# | lista_espera       |
# +--------------------+
```

---

### ❌ "Syntax error in SQL"

**Ejemplo:**
```
SQLSTATE[42000]: Syntax error or access violation
```

**Causas Comunes:**
- Comillas mal cerradas
- Typo en nombre de columna
- Tipo de dato incorrecto

**Debugging:**

```php
// Agregar logging de queries
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    error_log("SQL Error: " . $e->getMessage());
    error_log("Query: " . $sql);
    error_log("Params: " . json_encode($params));
    throw $e;
}
```

---

### ❌ "Deadlock detected"

**Síntomas:**
```
SQLSTATE[40001]: Serialization failure: 1213 Deadlock found
```

**Causa:** Transacciones en conflicto

**Solución:**

```php
// Reintentar transacción
$maxTries = 3;
for ($i = 0; $i < $maxTries; $i++) {
    try {
        $pdo->beginTransaction();
        // Tu código aquí
        $pdo->commit();
        break;
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($i === $maxTries - 1) throw $e;
        sleep(1); // Esperar 1 segundo antes de reintentar
    }
}
```

---

## 🔐 Autenticación

### ❌ Login no funciona

**Síntomas:**
- Usuario y contraseña correctos pero no entra
- Sesión no se mantiene

**Solución:**

```php
// Verificar que session_start() está en index.php
<?php
session_start(); // ✅ Debe estar aquí
?>

// En LoginController.php
if (password_verify($password_ingresada, $usuario['contrasena'])) {
    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_email'] = $usuario['email'];
    $_SESSION['user_rol'] = $usuario['rol'];
    
    // Verificar que se guardó
    error_log("Session: " . json_encode($_SESSION));
    
    header('Location: /index.php');
} else {
    echo "Contraseña incorrecta";
}
```

---

### ❌ "Undefined index: user_id"

**Síntomas:**
```
Notice: Undefined index: user_id in /views/carrito.php
```

**Causa:** Usuario no está logueado

**Solución:**

```php
// Al inicio de cada página protegida
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /views/login.php');
    exit;
}

// Ahora es seguro acceder
$userId = $_SESSION['user_id'];
?>
```

---

### ❌ Contraseña incorrecta aunque ingreso bien

**Causa:** Contraseña no está hasheada correctamente

**Solución:**

```php
// Al registrar: SIEMPRE hashear
$hash = password_hash($password, PASSWORD_BCRYPT);

INSERT INTO usuarios (nombre, email, contrasena) 
VALUES (?, ?, ?);
// Parámetros: nombre, email, hash

// Al verificar:
if (password_verify($password_ingresada, $hash_de_bd)) {
    // ✅ Correcto
} else {
    // ❌ Incorrecto
}
```

---

## 📧 Email y Notificaciones

### ❌ "SMTP connect() failed"

**Síntomas:**
```
SMTP -> ERROR: Failed to connect to server: Connection timed out
```

**Causas:**
- Credenciales SMTP incorrectas
- Firewall bloqueando puerto 587
- Mailtrap no configurado

**Solución:**

```env
# Verificar .env con datos de Mailtrap
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_USER=tu_usuario
SMTP_PASS=tu_password

# Verificar que están bien copiados (sin espacios extra)
```

**Probar conexión:**

```php
<?php
require_once 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->SMTPDebug = 2; // Ver detalles de conexión
$mail->isSMTP();
$mail->Host = $_ENV['SMTP_HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['SMTP_USER'];
$mail->Password = $_ENV['SMTP_PASS'];
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

if (!$mail->smtpConnect()) {
    echo "Error: " . $mail->ErrorInfo;
} else {
    echo "✅ Conexión SMTP OK";
}
?>
```

---

### ❌ "Email not sent"

**Síntomas:**
- Función retorna false
- No aparece error específico

**Solución:**

```php
// En mensajeriaController.php
function notifyPurchase(...) {
    try {
        $mail = new PHPMailer();
        // ... configuración ...
        
        if (!$mail->send()) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
        
        error_log("Email enviado a: " . $destinatario);
        return true;
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        return false;
    }
}
```

---

### ❌ ".env no se carga"

**Síntomas:**
- Variables de entorno devuelven null
- `$_ENV['SMTP_HOST']` es null

**Solución:**

```php
// Verificar que Dotenv está cargado
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Verificar que .env existe
if (!file_exists(__DIR__ . '/.env')) {
    die("Archivo .env no encontrado");
}

// Verificar contenido
echo $_ENV['SMTP_HOST'] ?? 'NO CARGADO';
```

---

## 🛒 Carrito y Compras

### ❌ Carrito vacío después de agregar producto

**Síntomas:**
- Agrego producto pero no aparece
- Count de carrito es 0

**Solución:**

```php
// Verificar session_start() está en cada página
<?php
session_start(); // ✅ REQUERIDO

// Inicializar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Debuggear
error_log("Carrito actual: " . json_encode($_SESSION['carrito']));
?>
```

**Problema común:** Falta `session_start()` en el archivo que procesa agregar

---

### ❌ "Duplicate entry in list"

**Síntomas:**
- Producto aparece duplicado en carrito
- Cantidad no se suma

**Solución:**

```php
// ❌ Mal - Agrega siempre nuevo item
$_SESSION['carrito'][] = [
    'id' => 1,
    'cantidad' => 1
];

// ✅ Bien - Verifica si existe
$productoId = 1;
$cantidad = 2;

$existe = false;
foreach ($_SESSION['carrito'] as &$item) {
    if ($item['id'] === $productoId) {
        $item['cantidad'] += $cantidad;
        $existe = true;
        break;
    }
}

if (!$existe) {
    $_SESSION['carrito'][] = [
        'id' => $productoId,
        'cantidad' => $cantidad,
        // ... otros datos
    ];
}
```

---

### ❌ Cálculo de total incorrecto

**Síntomas:**
- Total no coincide con suma manual
- Descuento se aplica mal

**Solución:**

```php
function calcularTotalCarrito() {
    $total = 0;
    
    foreach ($_SESSION['carrito'] as $item) {
        // Validar tipos de dato
        $precio = (float) $item['precio'];
        $cantidad = (int) $item['cantidad'];
        
        $subtotal = $precio * $cantidad;
        $total += $subtotal;
    }
    
    // Aplicar descuento si existe
    if (isset($_SESSION['descuento'])) {
        $total -= (float) $_SESSION['descuento'];
    }
    
    // Validar no sea negativo
    return max(0, round($total, 2));
}
```

---

## 💳 Pagos

### ❌ "Pago creado pero no se confirma"

**Síntomas:**
- Estado sigue siendo 'pendiente'
- `confirmarPago()` retorna false

**Solución:**

```php
// Verificar que el pago existe
$pago = $pagos->obtenerPago($pagoId);
if (!$pago) {
    echo "Pago no encontrado";
    return;
}

// Verificar estado actual
if ($pago['estado'] !== 'pendiente') {
    echo "Pago no está en estado pendiente";
    return;
}

// Intentar confirmar con logging
try {
    $resultado = $pagos->confirmarPago($pagoId, 'TXN_12345');
    error_log("Confirmación resultado: " . ($resultado ? 'true' : 'false'));
} catch (Exception $e) {
    error_log("Error al confirmar: " . $e->getMessage());
}
```

---

### ❌ "Reembolso no funciona"

**Síntomas:**
- `procesarReembolso()` retorna false
- Estado no cambia a 'reembolsado'

**Solución:**

```php
// Verificar requisitos
$pago = $pagos->obtenerPago($pagoId);

// 1. Debe estar completado
if ($pago['estado'] !== 'completado') {
    echo "Solo se pueden reembolsar pagos completados";
    return;
}

// 2. Monto debe ser válido
$monto = 50.00;
if ($monto > $pago['monto']) {
    echo "Monto de reembolso no puede ser mayor al pago";
    return;
}

// 3. Intentar reembolso
if ($monto == $pago['monto']) {
    // Reembolso total
    $resultado = $pagos->procesarReembolso($pagoId, null, 'Solicitud cliente');
} else {
    // Reembolso parcial
    $resultado = $pagos->procesarReembolso($pagoId, $monto, 'Defecto en producto');
}

echo $resultado ? "✅ Reembolso procesado" : "❌ Error";
```

---

## 📁 Archivo y Sistema

### ❌ "Class not found"

**Síntomas:**
```
Fatal error: Class 'ProductoModel' not found
```

**Solución:**

```php
// ✅ Correcto
require_once __DIR__ . '/models/ProductoModel.php';

// ❌ Evitar rutas relativas problemáticas
require_once '../../models/ProductoModel.php';

// O usar autoload de Composer
require_once 'vendor/autoload.php';
```

---

### ❌ "File not found" imagen

**Síntomas:**
- Imágenes no cargan en carrito
- Rutas rotas de productos

**Solución:**

```php
// Verificar que la imagen existe
$imagePath = 'public/img/producto.jpg';

if (file_exists($imagePath)) {
    echo "<img src='$imagePath'>";
} else {
    echo "<img src='public/img/default.jpg' alt='No disponible'>";
    error_log("Imagen no encontrada: $imagePath");
}

// O usar rutas absolutas desde raíz
$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/public/img/producto.jpg';
echo "<img src='$url'>";
```

---

### ❌ "Permission denied" escribiendo archivo

**Síntomas:**
```
Warning: file_put_contents(): open_failed: Permission denied
```

**Solución:**

```bash
# Linux/Mac
chmod -R 755 .
chmod -R 777 logs/
chmod -R 777 public/uploads/

# Windows (desde Command Prompt como admin)
icacls "logs" /grant:r %username%:F /t
```

---

### ❌ Memoria insuficiente

**Síntomas:**
```
Fatal error: Allowed memory size exceeded
```

**Solución:**

```php
// En config/db.php o inicio de script
ini_set('memory_limit', '256M');

// O en php.ini
memory_limit = 256M

// O ejecutar Composer sin límite
composer install --no-memory-limit
```

---

## 🧪 Debugging Utilities

### Script de Debug

```php
<?php
// debug.php - Ejecutar: php debug.php

echo "=== INFORMACIÓN DEL SISTEMA ===\n";
echo "PHP: " . phpversion() . "\n";
echo "OS: " . php_uname() . "\n";

echo "\n=== EXTENSIONES ===\n";
$required = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
foreach ($required as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✅" : "❌") . "\n";
}

echo "\n=== BASE DE DATOS ===\n";
try {
    require_once 'config/db.php';
    $conn = Database::conectar();
    echo "Conexión: ✅\n";
    
    $result = $conn->query("SELECT COUNT(*) as count FROM usuarios");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "Usuarios en BD: " . $row['count'] . "\n";
} catch (Exception $e) {
    echo "Conexión: ❌ - " . $e->getMessage() . "\n";
}

echo "\n=== ARCHIVOS ===\n";
$files = ['.env', 'config/db.php', 'vendor/autoload.php'];
foreach ($files as $file) {
    echo "$file: " . (file_exists($file) ? "✅" : "❌") . "\n";
}

echo "\n=== PERMISOS ===\n";
$dirs = ['logs/', 'public/img/'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "$dir: " . (is_writable($dir) ? "📝 writable" : "🔒 read-only") . "\n";
    }
}
?>
```

---

## 📞 Soporte Adicional

Si el error no está en esta guía:

1. **Revisar logs:**
   ```bash
   tail -f logs/error.log
   tail -f /var/log/apache2/error.log
   ```

2. **Buscar en Google** el mensaje de error exacto

3. **Crear issue en GitHub** con:
   - Versión PHP
   - Versión MySQL
   - Error exacto
   - Pasos para reproducir

---

**¡Esperamos haberte ayudado! 🛠️**
