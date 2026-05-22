# Arquitectura del proyecto

## Visión general

Mercado Ropa es una aplicación full-stack con dos capas principales:

1. Backend REST con Node.js, Express y TypeScript.
2. Frontend SPA con React, TypeScript y Vite.

La comunicación entre frontend y backend se realiza mediante HTTP `/api` y un proxy de desarrollo configurado en Vite.

## Backend

### `backend/src/index.ts`

- Inicializa Express.
- Configura CORS y JSON.
- Expone rutas:
  - `/api/productos`
  - `/api/auth`
  - `/api/carrito`
  - `/api/disenadores`
  - `/api/pagos`
  - `/api/admin`
- Sirve archivos estáticos de imagen en `/img`.

### Rutas y responsabilidades

- `auth.ts`:
  - Login y registro de usuarios.
  - Genera token JWT.
- `productos.ts`:
  - Obtiene productos y búsqueda por texto.
- `carrito.ts`:
  - Gestiona el carrito del usuario autenticado.
- `disenadores.ts`:
  - Devuelve la lista de diseñadores.
- `pagos.ts`:
  - Prepara pedidos y crea sesiones de Stripe.
- `admin.ts`:
  - Gestiona usuarios, productos, diseñadores, categorías, carritos y pedidos.

### Middleware

- `authMiddleware.ts`:
  - Valida token JWT en encabezado `Authorization`.
  - `adminMiddleware` restringe rutas a usuarios con rol `admin`.

### Base de datos

- `config/db.ts` crea una conexión a MySQL usando `mysql2/promise`.
- Las rutas consultan y actualizan tablas como `usuarios`, `productos`, `carritos`, `pedidos`, `disenadores` y `categorias`.

### Pagos

- Usa Stripe para crear sesiones de checkout.
- El pedido se registra en la base de datos antes de redirigir al pago.

## Frontend

### `frontend/src/App.tsx`

- Configura rutas con React Router.
- Incluye páginas principales y panel admin.

### Contextos

- `AuthContext.tsx`:
  - Controla sesión del usuario.
  - Almacena token JWT y datos de usuario en `localStorage`.
- `CartContext.tsx`:
  - Gestiona el estado del carrito.
  - Usa la API para leer, añadir y eliminar productos.

### Cliente HTTP

- `api/client.ts` crea una instancia de Axios.
- Agrega automáticamente `Authorization: Bearer <token>` si existe.

### Páginas y componentes

- `pages/` contiene las vistas de usuario y administrador.
- `components/` contiene elementos comunes como `Navbar` y `Footer`.

### Proxy Vite

- `frontend/vite.config.ts` redirige `/api` y `/img` a `http://localhost:3001`.

## Flujo de datos

1. El cliente React solicita datos al backend con Axios.
2. El backend consulta MySQL y devuelve JSON.
3. Para operaciones protegidas, el frontend envía un token JWT.
4. Para pagos, el backend crea un pedido y una sesión Stripe.

## Seguridad

- El acceso a datos de carrito, pagos y administración requiere JWT.
- El rol `admin` controla la administración completa.
- Las contraseñas se almacenan cifradas con `bcryptjs`.

## Desarrollo

- Backend: `npm run dev` en `backend/`
- Frontend: `npm run dev` en `frontend/`

## Mantenimiento

- Agregar validaciones adicionales en frontend y backend.
- Asegurar la rotación de claves JWT y Stripe.
- Manejar errores de Stripe y tokens expirados con mayor detalle.
