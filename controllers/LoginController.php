<?php
require_once 'models/UsuarioModel.php';

class LoginController {
    public function index() {
        require_once 'views/login.html';
    }

    public function login() {
        // 1. Iniciar sesión al principio si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'];
        $password = $_POST['contrasena'];

        $modelo = new UsuarioModel();
        $usuario = $modelo->verificarUsuario($email, $password);

        if ($usuario) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Usamos JavaScript para redirigir (es más fiable si hay problemas de headers)
            if ($usuario['rol'] === 'admin') {
                echo "<script>window.location.href = 'admin/admin.php';</script>";
            } else {
                echo "<script>window.location.href = 'index.php?ver=inicio';</script>";
            }
            exit(); 
        }
    }
}