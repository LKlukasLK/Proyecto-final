# 🔧 Guía de Instalación Completa

## 📋 Tabla de Contenidos

- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación Paso a Paso](#instalación-paso-a-paso)
- [Configuración Inicial](#configuración-inicial)
- [Verificación](#verificación)
- [Primeros Pasos](#primeros-pasos)
- [Troubleshooting](#troubleshooting)

---

## 💻 Requisitos del Sistema

### Hardware Mínimo

- **Procesador:** Intel Core 2 Duo o equivalente
- **RAM:** 512 MB
- **Espacio en disco:** 500 MB libres

### Software Requerido

- **PHP:** 7.4 o superior
- **MySQL:** 5.7 o superior (o MariaDB 10.3+)
- **Composer:** 2.0 o superior
- **Git:** (opcional, para clonar repositorio)

### Extensiones PHP Necesarias

```bash
php -m | grep -E "pdo|mbstring|json"
```

Deben estar habilitadas:
- ✅ pdo (PDO)
- ✅ pdo_mysql (MySQLi PDO)
- ✅ mbstring
- ✅ json
- ✅ openssl (para emails seguros)

---

## 🚀 Instalación Paso a Paso

### Paso 1: Clonar o Descargar Proyecto

**Opción A: Usar Git**
```bash
git clone https://github.com/usuario/Proyecto-final.git
cd Proyecto-final
```

**Opción B: Descargar ZIP**
```bash
# Descargar desde https://github.com/usuario/Proyecto-final/archive/main.zip
unzip Proyecto-final-main.zip
cd Proyecto-final-main
```

### Paso 2: Verificar PHP

```bash
php -v
# Debe mostrar: PHP 7.4.0 o superior
```

Si no tienes PHP:

**Windows:**
1. Descargar desde [php.net](https://www.php.net/downloads)
2. Extraer en `C:\php`
3. Agregar a variable `PATH`

**Mac:**
```bash
brew install php
```

**Linux:**
```bash
sudo apt-get install php php-cli php-mysql
```

### Paso 3: Verificar MySQL

```bash
mysql --version
# Debe mostrar: mysql  Ver 8.0.x o superior
```

**Iniciar MySQL:**

```bash
# Windows (desde Services)
net start MySQL80

# Mac
brew services start mysql

# Linux
sudo systemctl start mysql
```

**Verificar Conexión:**
```bash
mysql -u root -p
# Enter password: (dejar vacío o tu contraseña)
```

### Paso 4: Instalar Composer

```bash
# Descargar
curl -sS https://getcomposer.org/installer | php

# O desde Windows
# Descargar instalador desde https://getcomposer.org/Composer-Setup.exe

# Verificar
composer -V
# Debe mostrar: Composer version x.x.x
```

### Paso 5: Instalar Dependencias PHP

```bash
cd Proyecto-final
composer install
```

**Salida esperada:**
```
Loading composer repositories with package definitions
Updating dependencies
...
Writing lock file
Installing dependencies from lock file
...
Successfully installed packages
```

---

## ⚙️ Configuración Inicial

### Paso 1: Copiar Archivo de Configuración

```bash
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

### Paso 2: Editar .env

Abre `.env` en tu editor favorito y configura:

```env
# ============ BASE DE DATOS ============
DB_HOST=localhost
DB_PORT=3306
DB_NAME=tienda_online
DB_USER=root
DB_PASS=tu_contraseña_mysql

# ============ SERVIDOR ============
APP_URL=http://localhost:8000
APP_ENV=development

# ============ EMAIL (SMTP) ============
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_USER=tu_usuario@mailtrap.io
SMTP_PASS=tu_contraseña_app
SMTP_FROM=noreply@tienda.com

# ============ STRIPE (Opcional) ============
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...
```

### Paso 3: Crear Base de Datos

**Opción A: Desde línea de comandos**

```bash
mysql -u root -p < config/script.sql
```

**Opción B: Desde MySQL Workbench**

1. Abrir MySQL Workbench
2. Conectarse al servidor
3. File → Open SQL Script
4. Seleccionar `config/script.sql`
5. Ejecutar (Ctrl+Shift+Enter)

**Opción C: Manualmente**

```bash
mysql -u root -p
```

Luego pegar:
```sql
CREATE DATABASE tienda_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_online;
-- Pegar contenido de config/script.sql aquí
```

### Paso 4: Importar Datos de Prueba

```bash
mysql -u root -p tienda_online < config/datos_script.sql
```

**Usuarios de Prueba:**
- Email: `admin@ejemplo.com` | Contraseña: `admin123`
- Email: `cliente@ejemplo.com` | Contraseña: `cliente123`

### Paso 5: Configurar SMTP (Emails)

Para pruebas, usar **Mailtrap** (gratuito):

1. Ir a [mailtrap.io](https://mailtrap.io)
2. Crear cuenta
3. Crear proyecto "Demo"
4. Copiar credenciales SMTP:
   - Host: `smtp.mailtrap.io`
   - Port: `587`
   - Username: (copiar de Mailtrap)
   - Password: (copiar de Mailtrap)
5. Pegar en `.env`

---

## ✅ Verificación

### Test 1: PHP y Composer

```bash
php -v
composer -V
```

Ambos deben mostrar versión sin errores.

### Test 2: MySQL

```bash
mysql -u root -p -e "SELECT VERSION();"
```

Debe conectarse sin errores.

### Test 3: Base de Datos

```bash
mysql -u root -p -e "USE tienda_online; SHOW TABLES;"
```

Debe mostrar 6 tablas: usuarios, productos, ordenes, orden_detalles, pagos, lista_espera

### Test 4: Dependencias PHP

```bash
php -r "require_once 'vendor/autoload.php'; echo 'OK';"
```

Debe mostrar "OK".

---

## 🎮 Primeros Pasos

### 1. Iniciar Servidor

```bash
php -S localhost:8000
```

**Salida esperada:**
```
[Wed Feb 05 10:30:00 2026] PHP 7.4.33 Development Server started at http://localhost:8000
```

### 2. Acceder a la Aplicación

Abre tu navegador y ve a:
```
http://localhost:8000
```

### 3. Registrarse como Cliente

1. Click en "Registro"
2. Llenar formulario
3. Submit
4. Verificar que se creó en BD

### 4. Iniciar Sesión

1. Click en "Login"
2. Usar credenciales de prueba
3. Ver catálogo de productos

### 5. Realizar una Compra

1. Agregar producto al carrito
2. Ver carrito
3. Click en "Comprar"
4. Verificar que se creó orden en BD
5. Revisar email en Mailtrap

### 6. Acceder a Admin

1. Logout
2. Login con `admin@ejemplo.com`
3. Acceder a `/admin/`
4. Ver panel de administración

---

## 🐛 Troubleshooting

### ❌ Error: "Failed to open stream" config/db.php

**Causa:** Ruta incorrecta en `require_once`

**Solución:**
```php
// ❌ Malo
require_once '../../config/db.php';

// ✅ Bueno
require_once __DIR__ . '/../config/db.php';
```

---

### ❌ Error: "Connection refused" MySQL

**Causas Posibles:**
- MySQL no está corriendo
- Puerto incorrecto
- Credenciales inválidas

**Soluciones:**

```bash
# Verificar si MySQL está corriendo
ps aux | grep mysql

# Iniciar MySQL
mysql.server start          # Mac
sudo systemctl start mysql  # Linux
net start MySQL80          # Windows

# Verificar puerto
mysql -u root -p -h 127.0.0.1 -P 3306
```

---

### ❌ Error: "Access denied for user 'root'@'localhost'"

**Solución:**
```bash
# Conectarse sin contraseña
mysql -u root

# O con contraseña
mysql -u root -p
# Ingresa tu contraseña
```

---

### ❌ Error: "SMTP connect() failed"

**Causa:** Credenciales SMTP inválidas

**Solución:**
1. Verificar credenciales en `.env`
2. Usar Mailtrap para testing
3. Verificar puerto 587 abierto
4. Verificar firewall

```env
# Correcto
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_USER=abc123def456@mailtrap.io
SMTP_PASS=abc123def456abc123
```

---

### ❌ Error: "Table 'tienda_online.ordenes' doesn't exist"

**Solución:**

```bash
# Reimportar script
mysql -u root -p tienda_online < config/script.sql

# Verificar tablas
mysql -u root -p tienda_online -e "SHOW TABLES;"
```

---

### ❌ Error: "Call to undefined function" PDO

**Causa:** PDO no está habilitado

**Solución:**

```bash
# Ver extensiones habilitadas
php -m | grep -i pdo

# Habilitar en php.ini
# Windows: Descomentar: extension=pdo_mysql
# Linux: sudo apt-get install php-mysql
```

---

### ❌ Página blanca, sin errores

**Solución:**

1. Habilitar display de errores:

```php
// En index.php o config.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

2. Revisar logs:

```bash
# Linux
tail -f /var/log/apache2/error.log

# Mac
tail -f /var/log/apache2/error_log

# PHP built-in server
# Errores aparecen en consola
```

---

### ❌ Composer: "Memory limit exceeded"

**Solución:**

```bash
composer install --no-memory-limit
```

---

### ❌ Archivos de permisos insuficientes

**Linux/Mac:**

```bash
chmod -R 755 .
chmod -R 777 storage/      # Si existe
chmod -R 777 logs/         # Si existe
chmod -R 777 public/img/   # Para uploads
```

---

## 🔐 Configuración de Seguridad

### 1. Cambiar Contraseña Admin

```bash
mysql -u root -p tienda_online

UPDATE usuarios SET 
    contrasena = PASSWORD('nueva_contraseña_fuerte')
WHERE email = 'admin@ejemplo.com';
```

### 2. Crear Usuario MySQL Específico

```bash
mysql -u root -p

CREATE USER 'tienda_user'@'localhost' IDENTIFIED BY 'contraseña_fuerte';
GRANT ALL PRIVILEGES ON tienda_online.* TO 'tienda_user'@'localhost';
FLUSH PRIVILEGES;
```

Actualizar `.env`:
```env
DB_USER=tienda_user
DB_PASS=contraseña_fuerte
```

### 3. Desabilitar Modo Desarrollo

En `config/db.php` o `.env`:
```php
APP_ENV=production
// Y establecer:
error_reporting(0);
ini_set('display_errors', 0);
```

### 4. Usar HTTPS en Producción

```env
APP_URL=https://tudominio.com
```

---

## 📊 Estructura de Directorios post-instalación

```
Proyecto-final/
├── .env                    # ✅ Configurado
├── .gitignore
├── composer.json
├── composer.lock           # ✅ Creado por composer
├── README.md
│
├── config/
│   ├── db.php              # Conexión lista
│   ├── script.sql          # ✅ Ejecutado
│   └── datos_script.sql    # ✅ Ejecutado
│
├── controllers/            # ✅ Listos
├── models/                 # ✅ Listos
├── views/                  # ✅ Listos
├── public/
│   ├── css/
│   ├── img/
│   └── js/
│
├── vendor/                 # ✅ Instalado por composer
│   ├── autoload.php
│   ├── composer/
│   ├── phpmailer/
│   ├── phpdotenv/
│   └── ... (otras librerías)
│
└── logs/                   # (opcional)
```

---

## ✨ Verificación Final

Antes de usar en producción, ejecutar:

```bash
# 1. Test de conexión BD
php -r "require_once 'config/db.php'; echo Database::conectar() ? 'OK' : 'ERROR';"

# 2. Test de mail
php test_notificaciones.php

# 3. Test del sistema de pagos
php ejemplos_sistema_pagos.php

# 4. Revisar permisos
ls -la | grep "^d.*w"

# 5. Backup inicial
mysqldump -u root -p tienda_online > backup_inicial.sql
```

---

## 🎉 ¡Instalación Completada!

Si todo funcionó correctamente:

✅ Base de datos creada y poblada  
✅ Dependencias PHP instaladas  
✅ Configuración de emails lista  
✅ Servidor corriendo en http://localhost:8000  
✅ Usuario admin disponible  

**Próximos pasos:**
1. Leer [QUICKSTART.md](QUICKSTART.md) para ejemplos rápidos
2. Explorar [API_REFERENCE.md](API_REFERENCE.md) para métodos disponibles
3. Revisar [BASE_DATOS.md](BASE_DATOS.md) para entender la BD

---

**¡Ahora está listo para desarrollar! 🚀**
