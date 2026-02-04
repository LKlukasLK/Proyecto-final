<?php
require_once __DIR__ . '/../config/db.php';

class ProductoModel {
    private $db;

    public function __construct() {
        // Obtenemos la conexión desde tu clase Database
        $this->db = Database::conectar();
    }

    // Método para obtener todos los productos
    public function obtenerTodos() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Método para buscar productos por nombre
    public function buscarPorNombre($q) {
        $sql = "SELECT * FROM productos WHERE nombre LIKE :busqueda";
        $stmt = $this->db->prepare($sql);
        $termino = "%$q%";
        $stmt->execute(['busqueda' => $termino]);
        return $stmt->fetchAll();
    }
}