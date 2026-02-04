<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use Dotenv\Dotenv;

class Database {
    public static function conectar() {
        try {
            // 2. Cargamos las variables del archivo .env que está en la raíz
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            // 3. Extraemos los datos del .env usando la superglobal $_ENV
            $host    = $_ENV['DB_HOST'];
            $port    = $_ENV['DB_PORT'];
            $db      = $_ENV['DB_NAME'];
            $user    = $_ENV['DB_USER'];
            $pass    = $_ENV['DB_PASS'];
            $charset = 'utf8mb4';

            // 4. Configuramos el DSN
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            return new PDO($dsn, $user, $pass, $options);

        } catch (PDOException $e) {
            die("¡Error de conexión!: " . $e->getMessage());
        } catch (Exception $e) {
            die("Error cargando configuración: " . $e->getMessage());
        }
    }
}