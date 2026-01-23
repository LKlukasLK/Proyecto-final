<?php
require_once __DIR__.'/../models/UsuarioModel.php';

class LoginController {
    // Muestra el formulario
    public function index() {
        require_once __DIR__.'/../views/login.html';
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
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                header("Location: index.php?ver=inicio"); 
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.php?ver=login';</script>";
            }
        }
    }
}
?>