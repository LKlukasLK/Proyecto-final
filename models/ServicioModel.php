<?php
require_once 'config/db.php';

class ServicioModel {
    public function obtenerTodos() {
        $pdo = Database::conectar();
        // Seleccionamos todo de la tabla servicios
        $stmt = $pdo->query("SELECT * FROM servicios");
        return $stmt->fetchAll();
    }
}
?>