<?php

class LoginController {

    // Muestra el login con el diseño de Abi
    public function index() {
        // Cargo la vista pro con el sombreado exterior
        include 'views/abi/login.html';
    }

    // Valida las credenciales del usuario
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recojo el email y la pass del formulario
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Lógica simple de prueba (ajusta con tu base de datos)
            if ($email === 'admin@tienda.com' && $password === '123') {
                // Guardamos la sesión del usuario
                $_SESSION['user_email'] = $email;
                
                // Redirigimos al catálogo o inicio tras loguear
                header("Location: index.php?ver=catalogo");
                exit();
            } else {
                // Si falla, vuelve al login
                header("Location: index.php?ver=login");
                exit();
            }
        }
    }
}