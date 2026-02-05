<?php
// Asegúrate de que la sesión esté iniciada en el archivo principal que incluye esta vista
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

// 1. Mostrar alertas según lo que devuelva el controlador
if (isset($_GET['msg'])) {
    $mensajes = [
        'usuario_eliminado' => 'Usuario borrado correctamente.',
        'usuario_modificado' => 'Datos actualizados con éxito.'
    ];
    echo "<div class='alerta-exito'>{$mensajes[$_GET['msg']]}</div>";
}
if (isset($_GET['error'])) {
    echo "<div class='alerta-error'>Error: " . htmlspecialchars($_GET['error']) . "</div>";
}

try {
    $stmt = $db->query("SELECT * FROM Usuarios"); // "db" o "conexion", asegúrate de usar la que tengas activa
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
                   class="btn-delete" onclick="return confirm('¿Borrar este usuario?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>