<?php
require_once 'models/UsuarioModel.php';

class LoginController
{
    public function index()
    {
        require_once 'views/login.php';
    }

    public function login()
    {
        // Aseguramos que la sesión esté activa para guardar los datos
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['contrasena'] ?? '';

        $modelo = new UsuarioModel();
        $usuario = $modelo->verificarUsuario($email, $password);

        if ($usuario) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = trim($usuario['rol']);

            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin/admin.php");
            } else {
                header("Location: index.php?ver=inicio");
            }
            exit();
        } else {
            // Si quieres saber qué falla, puedes quitar el header y poner un echo:
            // die("Error: Usuario o contraseña incorrectos para: " . $email);
            header("Location: index.php?ver=login&error=1");
            exit();
        }
    }
}