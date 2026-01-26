<?php
class Database {
    public static function conectar() {
        $host = '127.0.0.1'; // Usa la IP para evitar problemas de resolución
        $port = '3309';
        $db   = 'mercado_ropa_db'; 
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        try {
            // CORRECCIÓN: Se añade "port=$port" a la cadena de conexión
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // IMPORTANTE: Asegúrate de que esta línea tenga el "return"
            return new PDO($dsn, $user, $pass, $options);

        } catch (PDOException $e) {
            die("¡Error de conexión!: " . $e->getMessage());
        }
    }
}
?>