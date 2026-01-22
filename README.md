# 👕 ClothStyle - Tienda de Ropa (PHP Puro)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

Este proyecto es una plataforma de comercio electrónico desarrollada **completamente desde cero con PHP nativo**, sin el uso de frameworks. Se enfoca en la gestión de inventario para ventas físicas y un sistema de carrito para ventas online, priorizando el aprendizaje de la lógica pura de programación.

---

## ⚙️ Arquitectura del Proyecto (Desarrollo Manual)

Al ser un desarrollo a mano, el proyecto implementa las siguientes lógicas personalizadas:

*   **🔐 Gestión de Sesiones:** Control de acceso para usuarios y administradores mediante `session_start()` y validación de cookies.
*   **📦 CRUD de Productos:** Creación, lectura, actualización y borrado de inventario mediante sentencias SQL preparadas para mayor seguridad.
*   **🛒 Carrito de Compras:** Lógica manual para gestionar productos en un array de sesión (`$_SESSION['carrito']`).
*   **💬 Mensajería Directa:** Sistema de tickets guardados en base de datos para la comunicación entre cliente y vendedor.

---

## 🛠️ Tecnologías Usadas

*   **Backend:** PHP Nativo (Arquitectura modular).
*   **Base de Datos:** MySQL con extensión **MySQLi** o **PDO**.
*   **Frontend:** HTML5 y CSS3 puro (Diseño responsivo con **Flexbox y Grid**).
*   **Comunicación:** Procesamiento de formularios mediante métodos `POST` y `GET`.

---

## 📂 Estructura de Archivos

El proyecto sigue una estructura organizada para mantener la escalabilidad del código:

```text
📂 Proyecto-final/
├── 📂 config/        # Conexión a la base de datos
├── 📂 controller/    # Lógica de control y procesos
├── 📂 model/         # Consultas a DB (Pull/Push de datos)
├── 📂 views/         # Interfaz de usuario (Plantillas)
├── 📂 assets/        # Recursos estáticos (CSS, Imágenes, JS)
└── index.php         # Punto de entrada y Catálogo principal
```

## 📋 Planificación y Responsabilidades

### 🛠️ Backend & Base de Datos
- [ ] 🗄️ **Base de Datos:** Diseño de tablas, relaciones y scripts SQL. (**Aaron**)
- [ ] ⚙️ **Módulo Admin:** Desarrollo del panel para gestión de stock, disponibilidad y carga de nuevos productos.

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