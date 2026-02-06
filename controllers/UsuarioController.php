<?php
require_once __DIR__ . '/../config/db.php'; 
session_start();

// Seguridad admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

class UsuarioController {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    // Crea un nuevo usuario
    public function crear_usuario() {
        try {
            $nombre = trim($_POST['nombre']);
            $email  = trim($_POST['email']);
            $rol    = trim($_POST['rol']);
            // Encriptamos la clave antes de guardar
            $pass   = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Usamos "contrasena" como en tu BD
            $stmt = $this->conexion->prepare("INSERT INTO Usuarios (nombre, email, contrasena, rol) VALUES (:n, :e, :p, :r)");
            $stmt->execute([':n' => $nombre, ':e' => $email, ':p' => $pass, ':r' => $rol]);

            header("Location: ../admin/index.php?p=usuarios&msg=usuario_creado");
            exit();
        } catch (PDOException $e) { die("Error al crear: " . $e->getMessage()); }
    }

    // Borra un usuario (evita autoborrado)
    public function eliminar_usuario($id) {
        try {
            if($id == $_SESSION['id_usuario']) {
                header("Location: ../admin/index.php?p=usuarios&error=autoborrado");
                exit();
            }
            $stmt = $this->conexion->prepare("DELETE FROM Usuarios WHERE id_usuario = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            header("Location: ../admin/index.php?p=usuarios&msg=usuario_eliminado");
            exit();
        } catch (PDOException $e) { die("Error al eliminar: " . $e->getMessage()); }
    }

    // Actualiza datos de un usuario existente
    public function modificar_usuario($id) {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $rol    = trim($_POST['rol'] ?? '');
            $password = $_POST['password'] ?? '';

            // SQL dinámico para no machacar la clave si viene vacía
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
        } catch (PDOException $e) { die("Error al modificar: " . $e->getMessage()); }
    }
}

// --- DESPACHADOR DE ACCIONES ---
$db = Database::conectar();
$gestion = new UsuarioController($db);

// Crear usuario (POST)
if (isset($_POST['accion']) && $_POST['accion'] === 'crear_usuario') {
    $gestion->crear_usuario();
}

// Modificar usuario (POST)
if (isset($_POST['accion']) && $_POST['accion'] === 'modificar_usuario') {
    $id = $_POST['id_usuario'] ?? null;
    if ($id) { $gestion->modificar_usuario($id); }
}

// Eliminar usuario (GET)
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar_usuario') {
    $gestion->eliminar_usuario($_GET['id']);
}