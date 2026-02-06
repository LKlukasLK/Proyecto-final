<?php
// 1. Incluimos la clase de base de datos y conectamos
require_once __DIR__ . '/../../config/db.php'; 

// Reutilizamos o creamos conexión
if (!isset($db)) {
    $db = Database::conectar();
}

// Seguridad: sesión y rol admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

// 2. Alertas de mensajes
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
    $stmt = $db->query("SELECT * FROM Usuarios"); 
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<div class="tabla-header">
    <h2><i class="fa-solid fa-users"></i> Gestión de Usuarios</h2>
    <a href="index.php?p=nuevo_usuario" class="btn-nuevo">
        <i class="fa-solid fa-plus"></i> Añadir Usuario
    </a>
</div>

<table class="tabla-admin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th style="text-align: center;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($f = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td>#<?php echo $f['id_usuario']; ?></td>
            <td><?php echo htmlspecialchars($f['nombre']); ?></td>
            <td><?php echo htmlspecialchars($f['email']); ?></td>
            <td>
                <span class="badge <?php echo $f['rol']; ?>" style="text-transform: capitalize;">
                    <?php echo $f['rol']; ?>
                </span>
            </td>
            <td style="text-align: center;">
                <a href="index.php?p=editar_usuario&id=<?php echo $f['id_usuario']; ?>" class="btn-edit" title="Editar Usuario">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
                
                <a href="../controllers/UsuarioController.php?accion=eliminar_usuario&id=<?php echo $f['id_usuario']; ?>" 
                   class="btn-delete" title="Borrar Usuario"
                   onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>