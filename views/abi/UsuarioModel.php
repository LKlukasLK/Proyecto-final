<?php
require_once 'config/db.php'; // Asegúrate de que esta sea la ruta a tu conexión

class UsuarioModel {
    private $db;

    public function __construct() {
        // Conexión a la base de datos
        $this->db = Database::conectar(); 
    }

    // Método para el Login
    public function verificarUsuario($email, $password) {
        // Buscamos al usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificamos si existe y si la pass coincide
        if ($usuario && $password === $usuario['password']) {
            return $usuario;
        }
        return false;
    }

    // Nuevo método para el Registro de Abi
    public function registrarUsuario($nombre, $email, $password) {
        try {
            // Insertamos los 3 campos: nombre, email y pass
            // Por defecto, ponemos el rol como 'cliente'
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) 
                    VALUES (:nombre, :email, :password, 'cliente')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // En un proyecto real aquí usarías password_hash
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}