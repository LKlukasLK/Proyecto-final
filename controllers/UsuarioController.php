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

    public function modificar_usuario($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../admin/admin.php?p=usuarios&error=metodo_no_permitido");
            exit();
            }

            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $rol      = trim($_POST['rol'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email) || empty($rol)) {
            header("Location: ../admin/admin.php?p=usuarios&error=campos_vacios");
            exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../admin/admin.php?p=usuarios&error=email_no_valido");
            exit();
            }

            // Evitar que un admin se cambie su propio rol accidentalmente
            if ($id == $_SESSION['id_usuario'] && $rol !== $_SESSION['rol']) {
            header("Location: ../admin/admin.php?p=usuarios&error=cambio_rol_propio");
            exit();
            }

            // Comprobar email duplicado en otro usuario
            $stmt = $this->conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = :email AND id_usuario != :id");
            $stmt->execute([':email' => $email, ':id' => $id]);
            if ($stmt->fetch()) {
            header("Location: ../admin/admin.php?p=usuarios&error=email_duplicado");
            exit();
            }

            // Construir SQL dinámico para no tocar la contraseña si viene vacía
            $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol";
            $params = [
            ':nombre' => $nombre,
            ':email'  => $email,
            ':rol'    => $rol,
            ':id'     => $id
            ];

            if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $sql .= ", password = :password";
            $params[':password'] = $hashed;
            }

            $sql .= " WHERE id_usuario = :id";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            header("Location: ../admin/admin.php?p=usuarios&msg=usuario_modificado");
            exit();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
// 3. Ejecución
$db = Database::conectar();
$gestion = new Gestion_controller($db);

if (isset($_GET['accion'])) {
    $id = $_GET['id'] ?? null;
    if ($_GET['accion'] === 'eliminar_usuario' && $id) $gestion->eliminar_usuario($id);
}