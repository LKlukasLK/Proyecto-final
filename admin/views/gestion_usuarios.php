<?php
// Reutilizamos conexión del index
if (!isset($conexion)) {
    require_once __DIR__ . '/../../config/db.php';
    $conexion = Database::conectar();
}

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

// Alertas de mensajes
if (isset($_GET['msg'])) {
    $mensajes = [
        'usuario_eliminado' => 'Usuario borrado correctamente.',
        'usuario_modificado' => 'Datos actualizados con éxito.',
        'usuario_creado' => 'Nuevo usuario registrado.'
    ];
    $texto = $mensajes[$_GET['msg']] ?? 'Operación realizada.';
    echo "<div class='alerta alerta-exito'>{$texto}</div>";
}
?>

<div class="tabla-header">
    <h2><i class="fa-solid fa-users"></i> Listado de Usuarios</h2>
    <a href="index.php?p=nuevo_usuario" class="btn-nuevo">
        <i class="fa-solid fa-plus"></i> Añadir Usuario
    </a>
</div>

<div class="contenedor-admin">
    <table class="tabla-admin">
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Consulta de usuarios
            $stmt = $conexion->query("SELECT * FROM Usuarios");
            while($f = $stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
            <tr>
                <td class="col-id">#<?php echo $f['id_usuario']; ?></td>
                <td><?php echo htmlspecialchars($f['nombre']); ?></td>
                <td><?php echo htmlspecialchars($f['email']); ?></td>
                <td>
                    <span class="badge-rol <?php echo ($f['rol'] === 'admin') ? 'rol-admin' : 'rol-cliente'; ?>">
                        <?php echo $f['rol']; ?>
                    </span>
                </td>
                <td class="text-center">
                    <a href="index.php?p=editar_usuario&id=<?php echo $f['id_usuario']; ?>" class="btn-edit" title="Editar">
                        <i class="fa-solid fa-user-gear"></i>
                    </a>
                    
                    <a href="../controllers/UsuarioController.php?accion=eliminar_usuario&id=<?php echo $f['id_usuario']; ?>" 
                       class="btn-delete" title="Borrar"
                       onclick="return confirm('¿Borrar a <?php echo $f['nombre']; ?>?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>