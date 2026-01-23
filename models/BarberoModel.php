<?php
require_once __DIR__.'/../config/db.php';

class BarberoModel {
    public function obtenerTodos() {
        $pdo = Database::conectar();
        // Seleccionamos solo los barberos que están activos (trabajando)
        $stmt = $pdo->query("SELECT * FROM barberos WHERE activo = 1");
        return $stmt->fetchAll();
    }
}
?>