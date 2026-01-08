<?php

// echo "<h2>¿Qué hay en la carpeta models?</h2>";
// echo "<pre>";
// $archivos = scandir('models'); // Escanea la carpeta models
// print_r($archivos);
// echo "</pre>";
// echo "<hr>";

session_start(); // Iniciamos sesiones para saber si el usuario está logueado

// Obtenemos la página que quiere ver el usuario (si no hay, va a 'inicio')
$pagina = $_GET['ver'] ?? 'inicio';

// ENRUTADOR SIMPLE
switch ($pagina) {
    case 'inicio':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index();
        break;

    case 'autenticar': // Procesa el formulario de login
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->login();
        break;

    case 'reservar':
        require_once 'controllers/CitaController.php';
        $controller = new CitaController();
        $controller->index();
        break;

    case 'guardar_cita': // Procesa el formulario de reserva
        require_once 'controllers/CitaController.php';
        $controller = new CitaController();
        $controller->guardar();
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php");
        break;

    default:
        echo "Página no encontrada";
        break;
}
?>