# Manual de usuario

## Público objetivo

Este manual está dirigido a usuarios finales de Mercado Ropa y administradores del sitio.

## Flujo de usuario

### 1. Registro y acceso

- `GET /registro` accede al formulario de registro.
- El usuario ingresa nombre, email y contraseña.
- Tras registrarse, el backend crea un usuario con rol `cliente`.
- `GET /login` permite iniciar sesión con email y contraseña.
- Después del login, el sistema guarda un token JWT en `localStorage`.

### 2. Navegación principal

- `/`: Página de inicio.
- `/catalogo`: Lista de productos.
- `/disenadores`: Información de diseñadores.

### 3. Catálogo y búsqueda

- Los productos se cargan desde la API `/api/productos`.
- El usuario puede buscar por nombre o descripción.
- Cada producto muestra datos básicos y botón para agregar al carrito.

### 4. Carrito de compras

- Solo usuarios autenticados pueden usar el carrito.
- Al añadir un producto, se llama a `/api/carrito/agregar`.
- Para ver el carrito, el usuario accede a `/carrito`.
- El carrito muestra los productos, cantidad, precio y total.
- El usuario puede eliminar ítems con `/api/carrito/eliminar/:id_producto`.

### 5. Pago

- El checkout se prepara mediante `/api/pagos/preparar`.
- El backend genera un pedido en estado `pendiente` y crea una sesión de Stripe.
- Tras finalizar el pago, el usuario es redirigido a `/pago-exitoso`.

### 6. Diseñadores

- La página `/disenadores` muestra los diseñadores disponibles.
- Los datos se obtienen de `/api/disenadores`.

## Panel administrativo

### Requisitos

- El usuario debe tener rol `admin`.
- El token JWT se valida en todas las rutas `/api/admin/*`.

### Funcionalidades de administrador

- Gestión de usuarios:
  - Ver lista de usuarios
  - Cambiar rol entre `admin` y `cliente`
  - Eliminar usuarios
- Gestión de productos:
  - Crear, editar y eliminar productos
  - Subir imágenes de productos
- Gestión de diseñadores:
  - Crear, editar y eliminar diseñadores
- Gestión de categorías:
  - Crear, editar y eliminar categorías
- Gestión de carritos:
  - Ver carritos activos y convertidos
  - Consultar ítems de un carrito
  - Cambiar el estado de un carrito
- Gestión de pedidos:
  - Ver pedidos y su estado
  - Consultar detalles de cada pedido
  - Actualizar el estado de un pedido

## Recomendaciones de uso

- Mantén la sesión activa solo en navegadores seguros.
- No compartas el token JWT.
- Usa un administrador con rol `admin` para las tareas de gestión.
- Si el pago no finaliza, revisa la clave de Stripe en el backend.
