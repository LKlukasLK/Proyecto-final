<?php
// 1. Siempre lo primero: iniciar sesión para que el carrito funcione
session_start(); 

// 2. Reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. Obtener la página actual
$pagina = $_GET['ver'] ?? 'inicio';

// 4. ENRUTADOR PRINCIPAL
switch ($pagina) {
    case 'inicio':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    
    case 'catalogo':
        require_once 'controllers/CatalogoController.php';
        $controller = new CatalogoController();
        $controller->verCatalogo();
        break;

    // Procesa el botón "Añadir a la cesta"
    case 'añadir_carrito':
        require_once 'controllers/CatalogoController.php';
        $controller = new CatalogoController();
        $controller->añadirAlCarrito(); 
        break;

    // Procesa el enlace "Eliminar" dentro del carrito
    case 'eliminar_item':
        require_once 'controllers/CatalogoController.php';
        $controller = new CatalogoController();
        $controller->eliminarDelCarrito();
        break;

    case 'carrito':
        // Asegúrate de que CarritoController cargue la vista: views/layout/carrito.php
        require_once 'controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->verCarrito();
        break;

    case 'login':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index();
        break;

    case 'autenticar':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->login();
        break;

    case 'registro':
        require_once 'controllers/RegistroController.php';
        $controller = new RegistroController();
        $controller->index();
        break;

    case 'procesar_registro':
        require_once 'controllers/RegistroController.php';
        $controller = new RegistroController();
        $controller->registrar();
        break;

    case 'logout':
        $_SESSION = array(); 
        session_destroy();   
        header("Location: index.php?ver=inicio");
        exit();
        break;
    
    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    default:
        echo "<h1>404 - Página no encontrada</h1><a href='index.php'>Volver al inicio</a>";
        break;
}