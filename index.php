<?php
// 1. Siempre lo primero: iniciar sesión
session_start(); 

// 2. Opcional: Configurar reporte de errores (útil mientras programas)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. Obtener la página
$pagina = $_GET['ver'] ?? 'inicio';

// 4. ENRUTADOR
switch ($pagina) {
    case 'inicio':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    
    case 'catalogo':
        require_once 'controllers/CatalogoController.php';
        $controller = new CatalogoController();
        $controller->catalogo();
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
        // Limpieza total de sesión
        $_SESSION = array(); // Vacía el array
        session_destroy();   // Destruye la sesión en el servidor
        header("Location: index.php?ver=inicio");
        exit();
        break;
    
    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    case 'carrito':
        require_once 'controllers/CarritoController.php';
        $controller = new CarritoController();
        $controller->verCarrito();
        break;

    default:
        // En lugar de un simple echo, podrías cargar una vista de error 404
        echo "<h1>404 - Página no encontrada</h1><a href='index.php'>Volver al inicio</a>";
        break;
}