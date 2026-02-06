<?php
require_once __DIR__ . '/../config/db.php'; 
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

class UsuarioController {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function eliminar_usuario($id) {
        try {
            if($id == $_SESSION['id_usuario']) {
                header("Location: ../admin/admin.php?p=usuarios&error=autoborrado");
                exit();
            }
            $stmt = $this->conexion->prepare("DELETE FROM Usuarios WHERE id_usuario = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            header("Location: ../admin/index.php?p=usuarios&msg=usuario_eliminado");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }

    public function modificar_usuario($id) {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $rol    = trim($_POST['rol'] ?? '');
            $password = $_POST['password'] ?? '';

            // SQL dinámico usando "contrasena" 
            $sql = "UPDATE Usuarios SET nombre = :nombre, email = :email, rol = :rol";
            $params = [':nombre' => $nombre, ':email' => $email, ':rol' => $rol, ':id' => $id];

            if (!empty($password)) {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $sql .= ", contrasena = :pass";
                $params[':pass'] = $hashed;
            }

            $sql .= " WHERE id_usuario = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            header("Location: ../admin/index.php?p=usuarios&msg=usuario_modificado");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }
}

// --- DESPACHADOR DE ACCIONES ---
$db = Database::conectar();
$gestion = new UsuarioController($db);

// Captura eliminar (GET)
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar_usuario') {
    $gestion->eliminar_usuario($_GET['id']);
}

// Captura modificar (POST)
if (isset($_POST['accion']) && $_POST['accion'] === 'modificar_usuario') {
    $id = $_POST['id_usuario'] ?? null;
    if ($id) {
        $gestion->modificar_usuario($id);
    }
}