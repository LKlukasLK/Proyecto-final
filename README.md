# 👗 ClothStyle - Tienda de Ropa (PHP Puro)

Este proyecto es una plataforma de comercio electrónico desarrollada **completamente desde cero con PHP nativo**, sin el uso de frameworks. Se enfoca en la gestión de inventario para ventas físicas y un sistema de carrito para ventas online.

---

## ⚙️ Arquitectura del Proyecto (Desarrollo Manual)

Al ser un desarrollo a mano, el proyecto implementa las siguientes lógicas manuales:

* **Gestión de Sesiones:** Control de usuarios y administradores mediante `session_start()` y validación de cookies.
* **CRUD de Productos:** Creación, lectura, actualización y borrado de inventario mediante sentencias SQL preparadas.
* **Carrito de Compras:** Lógica manual para almacenar productos en un array de sesión (`$_SESSION['carrito']`).
* **Mensajería Directa:** Sistema de tickets guardados en base de datos para comunicación cliente-vendedor.

---

## 🛠️ Tecnologías Usadas
* **Backend:** PHP Nativo (Procedural o POO).
* **Base de Datos:** MySQL con extensión **MySQLi** o **PDO**.
* **Frontend:** HTML5 y CSS3 puro (con Flexbox/Grid para el diseño de ropa).
* **Comunicación:** Formularios `POST` y `GET` procesados manualmente.

---

## 📂 Estructura de Archivos

Para mantener el orden "a mano", el proyecto sigue esta estructura:

```text
📂 Proyecto-final/
├── 📂 config/        # Conexion a DB
├── 📂 controller/    #
├── 📂 model/         # Comunicacion con DB, obtencio de datos(pull) y push
├── 📂 views/         # Vistas de las pagina
├── index.php         # Página principal (Catálogo)
└── /