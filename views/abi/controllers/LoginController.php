<?php
require_once 'models/UsuarioModel.php';

class LoginController {
    
    // Muestro la nueva vista de Abi
    public function index() {
        include 'views/abi/login.html';
    }

    // Proceso la autenticación
    public function login() {
        // Inicio sesión si no existe
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Recojo datos del formulario pro
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $modelo = new UsuarioModel();
        $usuario = $modelo->verificarUsuario($email, $password);

        if ($usuario) {
            // Guardamos info importante en la sesión
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirección profesional según rol
            if ($usuario['rol'] === 'admin') {
                echo "<script>window.location.href = 'admin/index.php';</script>";
            } else {
                echo "<script>window.location.href = 'index.php?ver=inicio';</script>";
            }
            exit(); 
        } else {
            // Si falla, volvemos al login de Abi
            header("Location: index.php?ver=login&error=1");
            exit();
        }
    }
}