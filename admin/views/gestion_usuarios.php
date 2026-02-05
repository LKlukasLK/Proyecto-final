<?php
// 1. Incluimos la clase de base de datos y conectamos
require_once __DIR__ . '/../../config/db.php'; 

// Intentamos conectar. Si ya existe una conexión en el index.php, la reutilizamos, si no, la creamos.
if (!isset($db)) {
    $db = Database::conectar();
}

// Asegúrate de que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

// 2. Mostrar alertas
if (isset($_GET['msg'])) {
    $mensajes = [
        'usuario_eliminado' => 'Usuario borrado correctamente.',
        'usuario_modificado' => 'Datos actualizados con éxito.'
    ];
    $texto = $mensajes[$_GET['msg']] ?? 'Operación realizada.';
    echo "<div class='alerta-exito' style='background: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px;'>{$texto}</div>";
}

if (isset($_GET['error'])) {
    echo "<div class='alerta-error' style='background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border-radius: 5px;'>Error: " . htmlspecialchars($_GET['error']) . "</div>";
}

try {
    // Ahora $db sí existe y funciona
    $stmt = $db->query("SELECT * FROM Usuarios"); 
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<div class="tabla-header">
    <h2><i class="fa-solid fa-users"></i> Gestión de Usuarios</h2>
</div>

<table class="tabla-admin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($f = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td>#<?php echo $f['id_usuario']; ?></td>
            <td><?php echo htmlspecialchars($f['nombre']); ?></td>
            <td><?php echo htmlspecialchars($f['email']); ?></td>
            <td>
                <span class="badge <?php echo $f['rol']; ?>">
                    <?php echo $f['rol']; ?>
                </span>
            </td>
            <td>
                <a href="index.php?p=editar_usuario&id=<?php echo $f['id_usuario']; ?>" class="btn-edit">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
                
                <a href="../controllers/UsuarioController.php?accion=eliminar_usuario&id=<?php echo $f['id_usuario']; ?>" 
                   class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>