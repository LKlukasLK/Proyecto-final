# Guía de instalación

## Requisitos

- Node.js 18+ o 20+
- npm 9+ o yarn
- MySQL 8+
- Cuenta de Stripe para obtener la clave secreta

## Clonar el repositorio

```bash
git clone <repositorio> mercado-ropa
cd mercado-ropa
```

## Backend

### 1. Configurar variables de entorno

Crea el archivo `backend/.env` basado en `backend/.env.example`.

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=mercado_ropa
DB_USER=root
DB_PASS=secret
JWT_SECRET=mi_clave_secreta
STRIPE_SECRET_KEY=sk_test_xxx
FRONTEND_URL=http://localhost:5173
```

### 2. Crear la base de datos

Conecta a MySQL y crea la base de datos:

```sql
CREATE DATABASE mercado_ropa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mercado_ropa;
```

### 3. Crear las tablas necesarias

```sql
CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente'
);

CREATE TABLE categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT
);

CREATE TABLE disenadores (
  id_disenador INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  biografia TEXT,
  web_url VARCHAR(255)
);

CREATE TABLE productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  imagen_url VARCHAR(255),
  id_categoria INT,
  id_disenador INT,
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL,
  FOREIGN KEY (id_disenador) REFERENCES disenadores(id_disenador) ON DELETE SET NULL
);

CREATE TABLE carritos (
  id_carrito INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  estado ENUM('activo','convertido','abandonado') NOT NULL DEFAULT 'activo',
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE detalles_carrito (
  id_carrito INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL DEFAULT 1,
  PRIMARY KEY (id_carrito, id_producto),
  FOREIGN KEY (id_carrito) REFERENCES carritos(id_carrito) ON DELETE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

CREATE TABLE pedidos (
  id_pedido INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2) NOT NULL,
  estado VARCHAR(50) NOT NULL DEFAULT 'pendiente',
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE detalles_pedido (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);
```

### 4. Instalar dependencias backend

```bash
cd backend
npm install
```

### 5. Ejecutar backend en desarrollo

```bash
npm run dev
```

### 6. Construir backend para producción

```bash
npm run build
npm start
```

## Frontend

### 1. Instalar dependencias frontend

```bash
cd frontend
npm install
```

### 2. Ejecutar frontend en desarrollo

```bash
npm run dev
```

### 3. Vista de la aplicación

Abre `http://localhost:5173` en el navegador.

## Notas adicionales

- El frontend usa proxy Vite para redirigir `/api` a `http://localhost:3001`
- Las imágenes se cargan y consumen desde `/img`
- Si cambias el puerto del backend, actualiza `frontend/vite.config.ts`
