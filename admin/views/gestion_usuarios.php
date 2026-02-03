<?php
// 1. IMPORTANTE: No incluyas la conexión aquí, ya viene de admin.php
// 2. Usamos el método query() de PDO en lugar de mysqli_query
try {
    $stmt = $conexion->query("SELECT * FROM usuarios");
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
        <?php 
        // 3. En PDO usamos fetch() en lugar de mysqli_fetch_assoc
        while($f = $stmt->fetch(PDO::FETCH_ASSOC)): 
        ?>
        <tr>
            <!-- Usamos id_usuario porque así se llama en tu base de datos -->
            <td>#<?php echo $f['id_usuario']; ?></td>
            <td><?php echo htmlspecialchars($f['nombre']); ?></td>
            <td><?php echo htmlspecialchars($f['email']); ?></td>
            <td>
                <span class="badge <?php echo $f['rol']; ?>">
                    <?php echo $f['rol']; ?>
                </span>
            </td>
            <td>
                <!-- Enlaces para futuras acciones -->
                <a href="editar_usuario.php?id=<?php echo $f['id_usuario']; ?>" class="btn-edit">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
                <a href="../controllers/gestion_controller.php?accion=eliminar_usuario&id=<?php echo $f['id_usuario']; ?>" 
                   class="btn-delete" onclick="return confirm('¿Borrar este usuario?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>