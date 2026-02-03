<?php
require_once 'models/UsuarioModel.php';

class RegistroController {

    // Muestro la vista de registro de la carpeta abi
    public function index() {
        include 'views/abi/registro.html';
    }

    // Registro del nuevo usuario (nombre, email y pass)
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Recojo los campos del formulario minimalista
            $nombre = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validación básica de campos vacíos
            if (!empty($nombre) && !empty($email) && !empty($password)) {
                $modelo = new UsuarioModel();
                
                // Intento registrar al usuario en la base de datos
                // Nota: Asegúrate de que tu UsuarioModel tenga este método o cámbialo por el tuyo
                $exito = $modelo->registrarUsuario($nombre, $email, $password);

                if ($exito) {
                    // Si todo va bien, al login pro
                    header("Location: index.php?ver=login&registro=success");
                } else {
                    // Si falla el insert, volvemos a intentar
                    header("Location: index.php?ver=registro&error=db");
                }
                exit();
            } else {
                // Faltan datos por rellenar
                header("Location: index.php?ver=registro&error=campos");
                exit();
            }
        }
    }
}