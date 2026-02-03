<?php
require_once 'models/UsuarioModel.php';

class RegistroController {

    // 1. Muestra el formulario
    public function index() {
    require_once 'views/registro.php'; // <--- Verifica que el nombre sea exacto
}

    // 2. Procesa los datos del formulario
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $modelo = new UsuarioModel();
            $resultado = $modelo->registrarUsuario($nombre, $email, $password);

            if ($resultado === true) {
                // Si todo sale bien, lo mandamos al login con un mensaje de éxito
                header("Location: index.php?ver=login&registro=ok");
            } elseif ($resultado === "email_existe") {
                echo "<script>alert('Ese correo ya está registrado'); window.history.back();</script>";
            } else {
                echo "Hubo un error al guardar el usuario.";
            }
        }
    }
}