<?php
// Inicia la sesión para el sistema de login
session_start();

// Reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Captura la página solicitada
$pagina = $_GET['ver'] ?? 'inicio';

// ENRUTADOR PRINCIPAL
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

    // Vista de Login (abi/login.html)
    case 'login':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index(); // Este método debe cargar views/abi/login.html
        break;

    // Acción de validar credenciales
    case 'autenticar':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->login();
        break;

    // Vista de Registro (abi/registro.html)
    case 'registro':
        require_once 'controllers/RegistroController.php';
        $controller = new RegistroController();
        $controller->index(); // Este método debe cargar views/abi/registro.html
        break;

    // Acción de guardar nuevo usuario (username, email, password)
    case 'procesar_registro':
        require_once 'controllers/RegistroController.php';
        $controller = new RegistroController();
        $controller->registrar();
        break;

    case 'reservar':
        require_once 'controllers/CitaController.php';
        $controller = new CitaController();
        $controller->index();
        break;

    case 'guardar_cita':
        require_once 'controllers/CitaController.php';
        $controller = new CitaController();
        $controller->guardar();
        break;

    case 'logout':
        $_SESSION = array();
        session_destroy();
        header("Location: index.php?ver=inicio");
        exit();
        break;

    default:
        echo "<h1>404 - Página no encontrada</h1><a href='index.php'>Volver al inicio</a>";
        break;
}