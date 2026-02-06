<?php
session_start();

// Conexión a la BD
require_once __DIR__ . '/../config/db.php';
$conexion = Database::conectar();

// Seguridad: solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php?ver=login");
    exit();
}

// --- Lógica para eliminar producto ---
if (isset($_GET['p']) && $_GET['p'] === 'productos' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    require_once '../controllers/ProductoController.php';
    $productoCtrl = new ProductoController();
    
    // Borramos y recargamos la página
    if ($productoCtrl->eliminar($_GET['id'])) {
        header("Location: index.php?p=productos");
        exit();
    }
}

$seccion = isset($_GET['p']) ? $_GET['p'] : 'inicio';
$inicial = strtoupper(substr($_SESSION['nombre'], 0, 1));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Gestión Total</title>
    <link rel="stylesheet" href="../public/css/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="box_reg">
            <div class="user-menu">
                <span class="user-name-text"><?php echo $_SESSION['nombre']; ?></span>
                <a href="../index.php?ver=logout" class="profile-circle" title="Cerrar Sesión">
                    <?php echo $inicial; ?>
                    <span class="logout-tooltip">Cerrar Sesión</span>
                </a>
            </div>

            <div class="header-center">
                <h1>Panel de Control <span>ADMIN</span></h1>
                <nav class="Desplegable">
                    <button class="dropbtn">Gestionar Tablas ▾</button>
                    <ul class="dropdown-content">
                        <li><a href="index.php?p=productos">Productos</a></li>
                        <li><a href="index.php?p=diseñador">Diseñador</a></li>
                        <li><a href="index.php?p=usuarios">Usuarios</a></li>
                        <li><a href="index.php?p=pedidos">Pedidos</a></li>
                        <li><a href="index.php?p=stock">Stock y Tallas</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="contenedor-admin">
        <?php
        // Enrutador de vistas
        switch ($seccion) {
            case 'productos':
                include 'views/gestion_productos.php';
                break;
            
            case 'editar_producto':
                include 'views/editar_producto.php';
                break;

            case 'nuevo_producto':
                include 'views/nuevo_producto.php';
                break;

            case 'diseñador':
                include 'views/gestion_disenador.php';
                break;
                    // ESTA LÍNEA ES VITAL:
            case 'gestion_disenador': 
                include 'views/gestion_disenador.php';
                break;

            case 'nuevo_disenador':
                include 'views/nuevo_disenador.php';
                break;

            case 'usuarios':
                include 'views/gestion_usuarios.php';
                break;

            case 'editar_usuario':
                include 'views/editar_usuario.php';
                break;

            case 'pedidos':
                include 'views/gestion_pedidos.php';
                break;
            
            case 'detalle_pedido':
                include 'views/ver_detalle_pedido.php';
                break;

            case 'stock':
                include 'views/gestion_stock.php';
                break;

            default:
                echo "  <div class='bienvenida'>
                            <i class='fa-solid fa-screwdriver-wrench' style='font-size:4rem; color:#bdc3c7'></i>
                            <h2>Bienvenido, administrador</h2>
                            <p>Selecciona una categoría en el menú superior para gestionar la base de datos.</p>
                        </div>";
                break;
        }
        ?>
    </main>
</body>

</html>