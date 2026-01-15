<?php
require_once 'models/UsuarioModel.php';

class LoginController {
    // Muestra el formulario
    public function index() {
        require_once 'views/login.html';
    }

    // Procesa el formulario
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $modelo = new UsuarioModel();
            $usuario = $modelo->verificarUsuario($email, $password);

            if ($usuario) {
                // Guardamos datos en sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header("Location: index.php?ver=reservar"); // Lo mandamos a reservar
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.php?ver=login';</script>";
            }
        }
    }
}
?>