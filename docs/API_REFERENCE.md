# Referencia de la API

Base URL del backend: `http://localhost:3001`

> El frontend usa proxy Vite para redirigir `/api` a este backend.

## Autenticación

### POST `/api/auth/login`

- Body:
  - `email` (string)
  - `contrasena` (string)
- Respuesta OK:
  - `token`
  - `usuario`: `id_usuario`, `nombre`, `email`, `rol`

### POST `/api/auth/registro`

- Body:
  - `nombre` (string)
  - `email` (string)
  - `contrasena` (string)
- Respuesta OK:
  - `{ message: 'Usuario registrado correctamente' }`

## Productos

### GET `/api/productos`

- Query opcional:
  - `q` para buscar por nombre o descripción
- Respuesta: lista de productos.

### GET `/api/productos/:id`

- Obtiene un producto por su `id`.

## Carrito

> Requiere token JWT en header `Authorization: Bearer <token>`.

### GET `/api/carrito`

- Obtiene el carrito activo del usuario.

### POST `/api/carrito/agregar`

- Body:
  - `id_producto` (número)
- Añade un producto al carrito o incrementa su cantidad.

### DELETE `/api/carrito/eliminar/:id_producto`

- Elimina el producto especificado del carrito.

## Diseñadores

### GET `/api/disenadores`

- Devuelve la lista de diseñadores.

## Pagos

> Requiere token JWT.

### POST `/api/pagos/preparar`

- Body:
  - `total` (número)
  - `items` (array de objetos con `id_producto`, `nombre`, `precio`, `cantidad`)
- Crea un pedido y una sesión de Stripe.
- Respuesta:
  - `url` de Stripe Checkout
  - `orderId`

## ADMIN (solo rol `admin`)

> Todas las rutas usan token JWT y validación de rol.

### Usuarios

- GET `/api/admin/usuarios`
- PUT `/api/admin/usuarios/:id` — body: `{ rol }`
- DELETE `/api/admin/usuarios/:id`

### Carritos

- GET `/api/admin/carritos`
- GET `/api/admin/carritos/:id/items`
- PUT `/api/admin/carritos/:id` — body: `{ estado }`

### Productos

- GET `/api/admin/productos`
- POST `/api/admin/productos` — multipart/form-data con campos: `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `id_categoria`, `id_disenador` y archivo `imagen`
- PUT `/api/admin/productos/:id` — multipart/form-data igual que creación
- DELETE `/api/admin/productos/:id`

### Diseñadores

- GET `/api/admin/disenadores`
- POST `/api/admin/disenadores` — body: `{ nombre, biografia, web_url }`
- PUT `/api/admin/disenadores/:id`
- DELETE `/api/admin/disenadores/:id`

### Categorías

- GET `/api/admin/categorias`
- POST `/api/admin/categorias` — body: `{ nombre, descripcion }`
- PUT `/api/admin/categorias/:id`
- DELETE `/api/admin/categorias/:id`

### Pedidos

- GET `/api/admin/pedidos`
- GET `/api/admin/pedidos/:id/detalles`
- PUT `/api/admin/pedidos/:id` — body: `{ estado }`

## Recursos estáticos

- Imágenes: `http://localhost:3001/img/<nombre_archivo>`
