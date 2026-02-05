# �️ Tienda Online - Documentación Completa

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-00000f?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)

Plataforma de comercio electrónico completa desarrollada con **PHP nativo**, **MySQL** y **JavaScript puro**. Incluye sistema de pagos, notificaciones por email, carrito de compras y gestión de órdenes.

---

## 📖 Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Estructura](#estructura)
- [Documentación](#documentación)
- [Guías de Uso](#guías-de-uso)
- [API Reference](#api-reference)
- [Troubleshooting](#troubleshooting)

---

## ✨ Características

### 🔐 Autenticación
- Registro de usuarios con validación
- Login seguro con contraseñas encriptadas
- Control de sesiones
- Roles (cliente, administrador)

### 🛒 Carrito de Compras
- Agregar/remover productos
- Modificar cantidades
- Aplicación de descuentos
- Persistencia en sesión

### 💳 Sistema de Pagos
- Crear pagos
- Confirmar/cancelar pagos
- Procesar reembolsos (total/parcial)
- Múltiples métodos de pago
- Integración con Stripe (disponible)

### 📧 Notificaciones
- Email de confirmación de compra
- Email de disponibilidad de producto
- Email de confirmación de pago
- Email de reembolso
- Email de cancelación

### 📦 Gestión de Órdenes
- Crear órdenes
- Consultar estado
- Historial de compras
- Detalles de productos

### 🔄 Sistema de Reembolsos
- Reembolsos totales
- Reembolsos parciales
- Rastreo de reembolsos
- Notificaciones automáticas

### 📊 Panel de Administrador
- Gestión de productos
- Gestión de stock
- Gestión de usuarios
- Reportes de ventas

---

## 📋 Requisitos

- **PHP:** 7.4 o superior
- **MySQL:** 5.7 o superior
- **Composer:** Última versión
- **Git:** Para clonar el repositorio
- **Extensiones PHP:** 
  - PDO
  - mbstring
  - json

---

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
git clone <URL_REPOSITORIO>
cd Proyecto-final
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
# Editar .env con tus credenciales
```

Contenido de `.env`:
```env
# Base de Datos
DB_HOST=localhost
DB_PORT=3306
DB_NAME=tienda_online
DB_USER=root
DB_PASS=tu_contraseña

# SMTP (Email)
SMTP_HOST=smtp.mailtrap.io
SMTP_USER=tu_usuario@mailtrap.io
SMTP_PASS=tu_contraseña_app
SMTP_PORT=587

# Stripe (Opcional)
STRIPE_SECRET_KEY=tu_clave_secreta
STRIPE_PUBLIC_KEY=tu_clave_publica
```

### 4. Crear base de datos
```bash
mysql -u root -p < config/script.sql
```

### 5. Insertar datos de prueba
```bash
mysql -u root -p tienda_online < config/datos_script.sql
```

### 6. Iniciar servidor
```bash
php -S localhost:8000
```

Accede a: `http://localhost:8000`

---

## 📁 Estructura del Proyecto

```
Proyecto-final/
├── 📄 index.php                    # Punto de entrada principal
├── 📄 .env                         # Variables de entorno
├── 📄 .env.example                 # Plantilla
├── 📄 composer.json                # Dependencias
│
├── 📁 config/
│   ├── db.php                      # Conexión a BD
│   ├── script.sql                  # Script inicial
│   └── datos_script.sql            # Datos de prueba
│
├── 📁 controllers/
│   ├── AdminController.php
│   ├── CarritoController.php       # ⭐ Gestión carrito
│   ├── CatalogoController.php
│   ├── HomeController.php
│   ├── LoginController.php
│   ├── mensajeriaController.php    # ⭐ Notificaciones
│   ├── PagosController.php         # ⭐ Pagos
│   └── RegistroController.php
│
├── 📁 models/
│   ├── ProductoModel.php
│   ├── ServicioModel.php
│   └── UsuarioModel.php
│
├── 📁 views/
│   ├── carrito.php                 # Vista carrito
│   ├── catalogo.php
│   ├── inicio.php
│   ├── login.php
│   ├── registro.php
│   └── layout/
│       ├── head.php
│       └── header.php
│
├── 📁 public/
│   ├── css/
│   ├── img/
│   └── js/
│
├── 📁 admin/
│   └── views/
│       ├── gestion_productos.php
│       ├── gestion_stock.php
│       └── gestion_usuarios.php
│
├── 📁 docs/                        # ⭐ Documentación
│   ├── README.md
│   ├── QUICKSTART.md
│   ├── API_REFERENCE.md
│   ├── BASE_DATOS.md
│   ├── FUNCIONES_NOTIFICACION.md
│   ├── SISTEMA_PAGOS.md
│   └── SISTEMA_CARRITO.md
│
└── 📁 vendor/                      # Composer
    └── autoload.php
```

---

## 📚 Documentación

### Guías Principales

| Guía | Descripción |
|------|-------------|
| **[QUICKSTART.md](docs/QUICKSTART.md)** | Comienza aquí - Guía rápida |
| **[INSTALACION.md](docs/INSTALACION.md)** | Instalación paso a paso |
| **[API_REFERENCE.md](docs/API_REFERENCE.md)** | Referencia de todos los métodos |
| **[BASE_DATOS.md](docs/BASE_DATOS.md)** | Schema y relaciones de BD |
| **[FUNCIONES_NOTIFICACION.md](docs/FUNCIONES_NOTIFICACION.md)** | Sistema de emails |
| **[SISTEMA_PAGOS.md](docs/SISTEMA_PAGOS.md)** | Gestión de pagos |
| **[SISTEMA_CARRITO.md](docs/SISTEMA_CARRITO.md)** | Carrito de compras |

---

## 🎮 Guías de Uso

### Para Clientes

**Registrarse:**
1. Ir a `/registro.php`
2. Llenar formulario
3. Iniciar sesión
4. Comenzar a comprar

**Realizar Compra:**
1. Ver catálogo (`/catalogo.php`)
2. Agregar productos al carrito
3. Ir a checkout
4. Ingresar datos de envío
5. Seleccionar método de pago
6. Confirmar compra
7. Recibir email de confirmación

---

### Para Desarrolladores

**Crear un pago:**
```php
require_once 'controllers/PagosController.php';
$pagos = new PagosController();

$resultado = $pagos->crearPago(
    $userId,        // ID del usuario
    $orderId,       // ID de la orden
    250.50,         // Monto
    'tarjeta'       // Método
);
```

**Procesar compra completa:**
```php
require_once 'controllers/CarritoController.php';
$carrito = new CarritoController();

$resultado = $carrito->procesarCompra(
    $_SESSION['userId'],
    $_SESSION['carrito'],
    $total,
    $descuento
);
```

**Enviar email de compra:**
```php
$resultado = notifyPurchase(
    $userId,
    $email,
    $nombre,
    $detalles,
    $total,
    $orderId
);
```

Ver [QUICKSTART.md](docs/QUICKSTART.md) para más ejemplos.

---

## 🔌 API Reference

### PagosController

```php
// Crear pago
$pagos->crearPago($userId, $orderId, $amount, $method, $desc)

// Confirmar pago
$pagos->confirmarPago($pagoId, $comprobante)

// Cancelar pago
$pagos->cancelarPago($pagoId, $razon)

// Obtener pago
$pagos->obtenerPago($pagoId)

// Obtener pagos del usuario
$pagos->obtenerPagosUsuario($userId, $estado)

// Obtener pagos de orden
$pagos->obtenerPagosOrden($orderId)

// Resumen de pagos
$pagos->obtenerResumenPagos($fechaInicio, $fechaFin)

// Procesar reembolso
$pagos->procesarReembolso($pagoId, $monto, $razon)
```

### CarritoController

```php
// Ver carrito
$carrito->verCarrito()

// Procesar compra
$carrito->procesarCompra($userId, $items, $total, $descuento)
```

### Sistema de Notificaciones

```php
// Notificar disponibilidad
notifyCustomers($productId, $name, $url, $image)

// Notificar compra
notifyPurchase($userId, $email, $name, $details, $total, $orderId)

// Notificar compra con descuento
notifyPurchaseWithDiscount($userId, $email, $name, $details, $total, $discount, $orderId)
```

Ver [API_REFERENCE.md](docs/API_REFERENCE.md) para documentación completa.

---

## 🗄️ Base de Datos

### Tablas Principales

**usuarios**
- id, nombre, email, contrasena, rol, fecha_registro

**productos**
- id, nombre, descripcion, precio, stock, categoria, imagen

**ordenes**
- id, usuario_id, total, estado, fecha

**orden_detalles**
- id, orden_id, producto_id, cantidad, precio

**pagos**
- id, usuario_id, orden_id, monto, metodo_pago, estado, id_transaccion

**lista_espera**
- id, usuario_id, producto_id, estado, fecha_registro

Ver [BASE_DATOS.md](docs/BASE_DATOS.md) para schema completo.

---

## 🔒 Seguridad

✅ Contraseñas con `password_hash()` y `password_verify()`  
✅ Prepared statements para prevenir SQL injection  
✅ Validación de entrada en todos los formularios  
✅ Control de acceso por roles  
✅ Sesiones seguras  
✅ Variables de entorno para credenciales  

---

## 🧪 Testing

### Prueba del sistema de pagos
```bash
php ejemplos_sistema_pagos.php
```

### Prueba de notificaciones
```bash
php test_notificaciones.php
```

### Prueba del carrito
```bash
# Acceder a http://localhost:8000/views/carrito.php
```

---

## 🐛 Troubleshooting

### Error: "Table 'ordenes' not found"
```bash
mysql -u root -p tienda_online < config/script.sql
```

### Error: "Email not sent"
- Verificar credenciales SMTP en `.env`
- Usar Mailtrap para testing

### Error: "Connection refused"
- Verificar MySQL está corriendo
- Verificar credenciales en `.env`

### Error: "Composer not found"
```bash
curl -sS https://getcomposer.org/installer | php
```

Ver [TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md) para más soluciones.

---

## 📊 Estados de Pago

| Estado | Descripción |
|--------|-------------|
| `pendiente` | Pago creado, esperando confirmación |
| `completado` | Pago confirmado |
| `cancelado` | Pago cancelado |
| `reembolsado` | Reembolso completo |
| `parcialmente_reembolsado` | Reembolso parcial |

---

## 📊 Estados de Orden

| Estado | Descripción |
|--------|-------------|
| `pendiente` | Esperando pago |
| `pagada` | Pago completado |
| `enviada` | Enviada a cliente |
| `entregada` | Recibida por cliente |
| `cancelada` | Orden cancelada |

---

## 🎨 Mejoras Futuras

- [ ] Integración con Stripe completamente
- [ ] Dashboard de administrador mejorado
- [ ] Sistema de calificaciones
- [ ] Carrito guardado en BD
- [ ] Recuperación de contraseña por email
- [ ] Two-factor authentication
- [ ] App móvil (React Native)
- [ ] CDN para imágenes

---

## 📝 Licencia

MIT License - Ver LICENSE para detalles

---

## 🤝 Contribuir

1. Fork el proyecto
2. Crear rama: `git checkout -b feature/nueva-feature`
3. Commit: `git commit -am 'Agregar nueva-feature'`
4. Push: `git push origin feature/nueva-feature`
5. Pull Request

---

## 📞 Soporte

Para reportar bugs o sugerencias: Abrir un Issue en GitHub

---

## 📅 Información

- **Versión:** 1.0.0
- **Última actualización:** 2026-02-05
- **Estado:** ✅ En desarrollo activo
- **Autor:** Equipo de desarrollo



### 🎨 Frontend & Vistas
- [ ] 👕 **Página de Productos:** Diseño y maquetación del catálogo principal. (**Lucas**)
- [ ] 🔍 **Vista de Producto:** Interfaz detallada para la visualización individual de prendas. (**Ancor**)
- [ ] 🔑 **Login / Registro:** Sistema de acceso, validación de formularios y seguridad. (**Abi**)
- [ ] 🛒 **Carrito de Compras:** Desarrollo de la lógica de compra y el menú desplegable (mini-cart). (**Cristian**)

---

## ❓ Pendientes de Preguntar
> [!IMPORTANT]
> **Estructura del Panel Administrativo:**  
> ¿Se implementará como una sección protegida dentro de la carpeta `/views` o como un directorio independiente (`/admin`) para separar totalmente la lógica de mensajería y gestión de productos?

---
---

# ‼️Herramientas necesarias
*  **Better Comments** (https://marketplace.visualstudio.com/items?itemName=aaron-bond.better-comments)
*  **Image preview** (https://marketplace.visualstudio.com/items?itemName=kisstkondoros.vscode-gutter-preview)