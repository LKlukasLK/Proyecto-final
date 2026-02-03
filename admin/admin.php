<?php
session_start();
// 1. Incluir la conexión (Ruta correcta: subir un nivel y entrar en config)
require_once '../config/db.php'; 
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php?ver=login");
    exit();
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
                        <li><a href="admin.php?p=productos">Productos</a></li>
                        <li><a href="admin.php?p=categorias">Categorías</a></li>
                        <li><a href="admin.php?p=usuarios">Usuarios</a></li>
                        <li><a href="admin.php?p=pedidos">Pedidos</a></li>
                        <li><a href="admin.php?p=stock">Stock y Tallas</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="contenedor-admin">
        <?php
        switch ($seccion) {
            case 'productos':  include 'views/gestion_productos.php'; break;
            case 'categorias': include 'views/gestion_categorias.php'; break;
            case 'usuarios':   include 'views/gestion_usuarios.php'; break;
            case 'pedidos':    include 'views/gestion_pedidos.php'; break;
            case 'stock':      include 'views/gestion_stock.php'; break;
            default:
                echo "<div class='bienvenida'>
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