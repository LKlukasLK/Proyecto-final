<?php
require_once __DIR__.'/../config/db.php';

class UsuarioModel {
    
    // MÉTODO PARA REGISTRAR
    public function registrarUsuario($nombre, $email, $password) {
        $pdo = Database::conectar();

        // 1. Primero verificamos si el email ya está en uso
        $checkSql = "SELECT id FROM usuarios WHERE email = :email";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->fetch()) {
            return "email_existe"; // Retornamos un aviso si ya existe
        }

        // 2. Encriptamos la contraseña (Seguridad)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 3. Insertamos el nuevo usuario (el rol por defecto será 'cliente')
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'cliente')";
        $stmt = $pdo->prepare($sql);
        
        try {
            return $stmt->execute([
                'nombre'   => $nombre,
                'email'    => $email,
                'password' => $passwordHash
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    // MÉTODO PARA LOGIN (Corregido para contraseñas encriptadas)
    public function verificarUsuario($email, $password) {
        $pdo = Database::conectar();
        
        // Buscamos al usuario solo por email
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        // Si existe el usuario, comparamos la contraseña encriptada
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario; // Login exitoso
        }

        return false; // Login fallido
    }
}