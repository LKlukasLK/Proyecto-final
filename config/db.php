<?php
class Database {
    public static function conectar() {
        $host = 'localhost:3309';
        $db   = 'barberia_db'; // <--- Confirma que este es el nombre en phpMyAdmin
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("¡Error de conexión!: " . $e->getMessage());
        }
    }
}
?>