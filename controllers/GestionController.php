<?php
// 1. Ruta corregida (con la barra / inicial)
require_once __DIR__ . '/../config/db.php'; 
session_start();

// 2. Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

class Gestion_controller {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function eliminar_producto($id) {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM productos WHERE id_producto = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            // RUTA CORREGIDA
            header("Location: ../admin/admin.php?p=productos&msg=eliminado");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }

    public function eliminar_usuario($id) {
        try {
            if($id == $_SESSION['id_usuario']) {
                // RUTA CORREGIDA
                header("Location: ../admin/admin.php?p=usuarios&error=autoborrado");
                exit();
            }
            $stmt = $this->conexion->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            // RUTA CORREGIDA
            header("Location: ../admin/admin.php?p=usuarios&msg=usuario_eliminado");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }
}

// 3. Ejecución
$db = Database::conectar();
$gestion = new Gestion_controller($db);

if (isset($_GET['accion'])) {
    $id = $_GET['id'] ?? null;
    if ($_GET['accion'] === 'eliminar_usuario' && $id) $gestion->eliminar_usuario($id);
    if ($_GET['accion'] === 'eliminar_producto' && $id) $gestion->eliminar_producto($id);
}