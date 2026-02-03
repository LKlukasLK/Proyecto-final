<?php
require_once __DIR__.'/../config/db.php';

class UsuarioModel {
    
    // MÉTODO PARA REGISTRAR
    public function registrarUsuario($nombre, $email, $contrasena) {
        $pdo = Database::conectar();

        // 1. Primero verificamos si el email ya está en uso
        $checkSql = "SELECT email FROM usuarios WHERE email = :email"; 
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->fetch()) {
            return "email_existe"; // Retornamos un aviso si ya existe
        }

        // 2. Encriptamos la contraseña (Seguridad)
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        // 3. Insertamos el nuevo usuario (el rol por defecto será 'cliente')
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (:nombre, :email, :contrasena, 'cliente')";
        $stmt = $pdo->prepare($sql);
        
        try {
            return $stmt->execute([
                'nombre'   => $nombre,
                'email'    => $email,
                'contrasena' => $contrasenaHash
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    // MÉTODO PARA LOGIN (Corregido para contraseñas encriptadas)
    public function verificarUsuario($email, $contrasena) {
        $pdo = Database::conectar();
        
        // Buscamos al usuario solo por email
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        // Si existe el usuario, comparamos la contraseña encriptada
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario; // Login exitoso
        }

        return false; // Login fallido
    }
}