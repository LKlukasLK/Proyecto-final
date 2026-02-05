# 🗄️ Documentación de Base de Datos

## 📋 Resumen Ejecutivo

**SGBD:** MySQL / MariaDB  
**Versión Mínima:** 5.7  
**Charset:** UTF-8 (utf8mb4)  
**Total de Tablas:** 6 (+ índices para optimización)  
**Relaciones:** Clave primaria y foránea implementadas  

---

## 📊 Diagrama de Entidades

```
┌─────────────┐
│  usuarios   │
├─────────────┤
│ id (PK)     │
│ nombre      │───┐
│ email       │   │
│ contraseña  │   │
│ rol         │   │
└─────────────┘   │
                  │
                  ├──→ ┌─────────────────┐
                  │    │  ordenes        │
                  │    ├─────────────────┤
                  │    │ id (PK)         │
                  │    │ usuario_id (FK) │───┐
                  │    │ total           │   │
                  │    │ estado          │   │
                  │    │ fecha           │   │
                  │    └─────────────────┘   │
                  │            │             │
                  │            ├──→ ┌─────────────────────┐
                  │            │    │ orden_detalles      │
                  │            │    ├─────────────────────┤
                  │            │    │ id (PK)             │
                  │            │    │ orden_id (FK)       │
                  │            │    │ producto_id (FK)    │
                  │            │    │ cantidad            │
                  │            │    │ precio              │
                  │            │    └─────────────────────┘
                  │            │
                  │            └──→ ┌─────────────────┐
                  │                 │  pagos          │
                  │                 ├─────────────────┤
                  │                 │ id (PK)         │
                  │                 │ usuario_id (FK) │
                  │                 │ orden_id (FK)   │
                  │                 │ monto           │
                  │                 │ estado          │
                  │                 │ metodo_pago     │
                  │                 └─────────────────┘
                  │
                  └──→ ┌──────────────────────┐
                       │  lista_espera        │
                       ├──────────────────────┤
                       │ id (PK)              │
                       │ usuario_id (FK)     │
                       │ producto_id (FK)    │
                       │ estado               │
                       │ fecha_registro       │
                       └──────────────────────┘

┌─────────────────┐
│  productos      │
├─────────────────┤
│ id (PK)         │
│ nombre          │
│ descripcion     │
│ precio          │
│ stock           │
│ categoria       │
│ imagen          │
│ fecha_creacion  │
└─────────────────┘
```

---

## 🔑 Tablas Detalladas

### 1️⃣ usuarios

Almacena información de todos los usuarios del sistema (clientes y administradores).

```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
);
```

**Campos:**

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id` | INT | Identificador único | PK, AUTO_INCREMENT |
| `nombre` | VARCHAR(100) | Nombre completo | NOT NULL |
| `email` | VARCHAR(100) | Email único | UNIQUE, NOT NULL |
| `contrasena` | VARCHAR(255) | Contraseña hash | NOT NULL |
| `rol` | ENUM | Tipo de usuario | cliente \| admin |
| `fecha_registro` | TIMESTAMP | Cuándo se registró | DEFAULT NOW() |
| `activo` | BOOLEAN | Cuenta activa | DEFAULT TRUE |

**Ejemplo de Inserción:**
```php
$nombre = 'Juan Pérez';
$email = 'juan@example.com';
$hash = password_hash('micontraseña123', PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (nombre, email, contrasena, rol) 
        VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $email, $hash, 'cliente']);
```

---

### 2️⃣ productos

Catálogo de todos los productos disponibles para venta.

```sql
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    categoria VARCHAR(100),
    imagen VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    INDEX idx_categoria (categoria),
    INDEX idx_nombre (nombre),
    FULLTEXT idx_búsqueda (nombre, descripcion)
);
```

**Campos:**

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id` | INT | ID único | PK, AUTO_INCREMENT |
| `nombre` | VARCHAR(150) | Nombre del producto | NOT NULL |
| `descripcion` | TEXT | Detalles completos | NULL permitido |
| `precio` | DECIMAL(10,2) | Precio unitario | NOT NULL |
| `stock` | INT | Cantidad disponible | DEFAULT 0 |
| `categoria` | VARCHAR(100) | Categoría | Ej: 'remeras', 'pantalones' |
| `imagen` | VARCHAR(255) | Path relativo | Ej: 'img/producto.jpg' |
| `fecha_creacion` | TIMESTAMP | Cuándo se agregó | DEFAULT NOW() |
| `activo` | BOOLEAN | Disponible para venta | DEFAULT TRUE |

**Ejemplo:**
```sql
INSERT INTO productos 
(nombre, descripcion, precio, stock, categoria, imagen) 
VALUES 
('Remera Roja Premium', 'Remera de algodón 100%', 25.00, 50, 'remeras', 'img/remera_roja.jpg');
```

---

### 3️⃣ ordenes

Registro de todas las compras realizadas.

```sql
CREATE TABLE ordenes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    estado ENUM('pendiente', 'pagada', 'enviada', 'entregada', 'cancelada') DEFAULT 'pendiente',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_envio DATETIME,
    fecha_entrega DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha)
);
```

**Campos:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único de orden |
| `usuario_id` | INT | ID del cliente (FK) |
| `total` | DECIMAL(10,2) | Monto final |
| `estado` | ENUM | Estado actual de la orden |
| `fecha` | TIMESTAMP | Cuándo se creó |
| `fecha_envio` | DATETIME | Cuándo fue enviada |
| `fecha_entrega` | DATETIME | Cuándo fue entregada |

**Estados Permitidos:**
- `pendiente` - Esperando pago
- `pagada` - Pago confirmado
- `enviada` - En tránsito
- `entregada` - Recibida por cliente
- `cancelada` - Cancelada

---

### 4️⃣ orden_detalles

Productos y cantidades en cada orden (línea a línea).

```sql
CREATE TABLE orden_detalles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_orden (orden_id),
    INDEX idx_producto (producto_id)
);
```

**Campos:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único |
| `orden_id` | INT | ID de la orden (FK) |
| `producto_id` | INT | ID del producto (FK) |
| `cantidad` | INT | Unidades compradas |
| `precio` | DECIMAL(10,2) | Precio al momento de compra |

**Ejemplo:**
```sql
-- Si orden 12 tiene: 2x Remera ($25) + 1x Pantalón ($45)
INSERT INTO orden_detalles (orden_id, producto_id, cantidad, precio) VALUES
(12, 1, 2, 25.00),
(12, 3, 1, 45.00);
```

---

### 5️⃣ pagos

Registro de todos los pagos realizados.

```sql
CREATE TABLE pagos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    orden_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    metodo_pago ENUM('tarjeta', 'transferencia', 'efectivo', 'stripe') DEFAULT 'tarjeta',
    estado ENUM('pendiente', 'completado', 'cancelado', 'reembolsado', 'parcialmente_reembolsado') DEFAULT 'pendiente',
    id_transaccion VARCHAR(100),
    monto_reembolsado DECIMAL(10, 2) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_confirmacion DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (orden_id) REFERENCES ordenes(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_orden (orden_id),
    INDEX idx_estado (estado),
    UNIQUE KEY unique_orden_pago (orden_id)
);
```

**Campos:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único del pago |
| `usuario_id` | INT | Quién pagó (FK) |
| `orden_id` | INT | Qué orden fue pagada (FK) |
| `monto` | DECIMAL | Monto a pagar |
| `metodo_pago` | ENUM | Cómo pagó |
| `estado` | ENUM | Estado actual |
| `id_transaccion` | VARCHAR | ID del banco/Stripe |
| `monto_reembolsado` | DECIMAL | Cuánto fue reembolsado |
| `fecha_creacion` | TIMESTAMP | Cuándo se registró |
| `fecha_confirmacion` | DATETIME | Cuándo fue confirmado |

**Estados Posibles:**
- `pendiente` - Esperando confirmación
- `completado` - Pago confirmado
- `cancelado` - Pago cancelado
- `reembolsado` - Reembolso completo
- `parcialmente_reembolsado` - Reembolso parcial

---

### 6️⃣ lista_espera

Usuarios interesados en productos agotados.

```sql
CREATE TABLE lista_espera (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    estado ENUM('activo', 'notificado', 'cancelado') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_espera (usuario_id, producto_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_producto (producto_id),
    INDEX idx_estado (estado)
);
```

**Campos:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único |
| `usuario_id` | INT | Quién espera (FK) |
| `producto_id` | INT | Qué producto (FK) |
| `estado` | ENUM | Estado de la espera |
| `fecha_registro` | TIMESTAMP | Cuándo se registró |

**Ejemplo:**
```sql
-- Juan espera que esté disponible el producto "Remera Roja"
INSERT INTO lista_espera (usuario_id, producto_id, estado)
VALUES (5, 1, 'activo');

-- Cuando el producto vuelve a stock:
UPDATE lista_espera SET estado = 'notificado' WHERE id = 1;
-- Y se ejecuta: notifyCustomers(1, 'Remera Roja', ...)
```

---

## 🔗 Relaciones y Restricciones

### Foreign Keys Implementadas

```
usuarios.id ←┬─ ordenes.usuario_id (ON DELETE CASCADE)
             ├─ pagos.usuario_id
             └─ lista_espera.usuario_id (ON DELETE CASCADE)

productos.id ←┬─ orden_detalles.producto_id
              └─ lista_espera.producto_id (ON DELETE CASCADE)

ordenes.id ←┬─ orden_detalles.orden_id (ON DELETE CASCADE)
            └─ pagos.orden_id
```

**Nota:** `ON DELETE CASCADE` significa que si se elimina un usuario, se eliminan automáticamente sus órdenes, pagos y registros en lista de espera.

---

## 📈 Índices Implementados

### Búsqueda Rápida

```sql
-- Usuarios
CREATE INDEX idx_email ON usuarios(email);
CREATE INDEX idx_rol ON usuarios(rol);

-- Productos
CREATE INDEX idx_categoria ON productos(categoria);
CREATE INDEX idx_nombre ON productos(nombre);
CREATE FULLTEXT INDEX idx_búsqueda ON productos(nombre, descripcion);

-- Órdenes
CREATE INDEX idx_usuario ON ordenes(usuario_id);
CREATE INDEX idx_estado ON ordenes(estado);
CREATE INDEX idx_fecha ON ordenes(fecha);

-- Detalles de Orden
CREATE INDEX idx_orden ON orden_detalles(orden_id);
CREATE INDEX idx_producto ON orden_detalles(producto_id);

-- Pagos
CREATE INDEX idx_usuario ON pagos(usuario_id);
CREATE INDEX idx_orden ON pagos(orden_id);
CREATE INDEX idx_estado ON pagos(estado);
```

### Búsqueda Full-Text

```sql
-- Buscar productos por nombre o descripción
SELECT * FROM productos 
WHERE MATCH(nombre, descripcion) 
AGAINST('remera roja' IN BOOLEAN MODE);
```

---

## 📊 Consultas Comunes

### 1. Obtener todas las órdenes de un usuario

```sql
SELECT o.* 
FROM ordenes o
WHERE o.usuario_id = 5
ORDER BY o.fecha DESC;
```

### 2. Detalles completos de una orden

```sql
SELECT 
    o.id,
    o.total,
    o.estado,
    od.producto_id,
    p.nombre,
    od.cantidad,
    od.precio
FROM ordenes o
JOIN orden_detalles od ON o.id = od.orden_id
JOIN productos p ON od.producto_id = p.id
WHERE o.id = 12;
```

### 3. Pagos completados en un rango de fechas

```sql
SELECT 
    COUNT(*) as total_pagos,
    SUM(monto) as monto_total
FROM pagos
WHERE estado = 'completado'
AND fecha_confirmacion BETWEEN '2026-01-01' AND '2026-02-05';
```

### 4. Productos sin stock

```sql
SELECT * FROM productos
WHERE stock = 0
AND activo = TRUE;
```

### 5. Usuarios en lista de espera de un producto

```sql
SELECT u.* 
FROM usuarios u
JOIN lista_espera le ON u.id = le.usuario_id
WHERE le.producto_id = 1
AND le.estado = 'activo';
```

### 6. Ingresos por categoría

```sql
SELECT 
    p.categoria,
    SUM(od.cantidad * od.precio) as ingresos
FROM orden_detalles od
JOIN productos p ON od.producto_id = p.id
GROUP BY p.categoria
ORDER BY ingresos DESC;
```

### 7. Órdenes sin pagar

```sql
SELECT o.* 
FROM ordenes o
LEFT JOIN pagos pa ON o.id = pa.orden_id
WHERE o.estado = 'pendiente'
AND (pa.estado IS NULL OR pa.estado = 'pendiente');
```

---

## 🔄 Transacciones Importantes

### Compra Exitosa

```sql
START TRANSACTION;

-- 1. Crear orden
INSERT INTO ordenes (usuario_id, total, estado) 
VALUES (5, 95.00, 'pendiente');
SET @orden_id = LAST_INSERT_ID();

-- 2. Agregar detalles
INSERT INTO orden_detalles (orden_id, producto_id, cantidad, precio)
VALUES 
(@orden_id, 1, 2, 25.00),
(@orden_id, 3, 1, 45.00);

-- 3. Actualizar stock
UPDATE productos SET stock = stock - 2 WHERE id = 1;
UPDATE productos SET stock = stock - 1 WHERE id = 3;

-- 4. Crear pago
INSERT INTO pagos (usuario_id, orden_id, monto, estado)
VALUES (5, @orden_id, 95.00, 'pendiente');

COMMIT;
```

### Confirmar Pago

```sql
START TRANSACTION;

-- 1. Actualizar pago
UPDATE pagos 
SET estado = 'completado', 
    fecha_confirmacion = NOW(),
    id_transaccion = 'TXN_12345'
WHERE id = 1;

-- 2. Actualizar orden
UPDATE ordenes 
SET estado = 'pagada' 
WHERE id = @orden_id;

-- 3. Notificar lista de espera (si algún producto se agotó)
UPDATE lista_espera 
SET estado = 'notificado'
WHERE producto_id IN (1, 3) AND estado = 'activo';

COMMIT;
```

---

## 🗑️ Backup y Restauración

### Generar Backup

```bash
mysqldump -u root -p tienda_online > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restaurar Backup

```bash
mysql -u root -p tienda_online < backup_20260205_120000.sql
```

### Backup Incremental

```bash
# Habilitar binary logging en MySQL
SET GLOBAL binlog_format = 'ROW';

# Crear backup diferencial
mysqldump --single-transaction --flush-logs tienda_online > backup.sql
```

---

## ⚠️ Mantenimiento

### Optimizar Tablas

```sql
OPTIMIZE TABLE usuarios;
OPTIMIZE TABLE productos;
OPTIMIZE TABLE ordenes;
OPTIMIZE TABLE orden_detalles;
OPTIMIZE TABLE pagos;
OPTIMIZE TABLE lista_espera;
```

### Verificar Integridad

```sql
CHECK TABLE usuarios;
CHECK TABLE productos;
CHECK TABLE ordenes;
```

### Limpiar Datos Antiguos

```sql
-- Eliminar órdenes canceladas hace más de un año
DELETE FROM ordenes 
WHERE estado = 'cancelada' 
AND fecha < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

---

## 📊 Estadísticas de Rendimiento

### Tamaño de Base de Datos

```sql
SELECT 
    table_name,
    ROUND((data_length + index_length) / 1024 / 1024, 2) as tamaño_mb
FROM information_schema.tables
WHERE table_schema = 'tienda_online'
ORDER BY (data_length + index_length) DESC;
```

### Queries Lentas

```sql
-- Habilitar log de queries lentas
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Ver queries lentas
SELECT * FROM mysql.slow_log;
```

---

## ✅ Checklist de Seguridad

- [ ] Contraseñas hasheadas con `password_hash()` / BCRYPT
- [ ] Prepared statements en todas las queries
- [ ] Foreign keys con restricciones
- [ ] Índices en columnas frecuentemente consultadas
- [ ] Backups diarios
- [ ] Logs de auditoría para cambios críticos
- [ ] Restricciones de acceso por usuario BD

---

**Base de datos completamente documentada y optimizada ✅**
