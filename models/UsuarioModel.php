<?php
require_once __DIR__.'/../config/db.php';

class UsuarioModel {
    public function verificarUsuario($email, $password) {
        $pdo = Database::conectar();
        $sql = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'password' => $password]);
        return $stmt->fetch(); // Devuelve los datos del usuario o false
    }
}
?>