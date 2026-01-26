<?php
// CORRECCIÓN: Llama al archivo de la base de datos real, no al test
require_once __DIR__ . '/../config/db.php';

class ServicioModel {
    public function obtenerTodos() {
        $pdo = Database::conectar();

        // Verificamos que realmente tengamos una conexión antes de hacer la query
        if ($pdo === null) {
            return []; // Retorna un array vacío si no hay conexión
        }

        // Seleccionamos de la tabla 'productos' (según tu nueva base de datos)
        $stmt = $pdo->query("SELECT * FROM productos");
        return $stmt->fetchAll();
    }
}
?>