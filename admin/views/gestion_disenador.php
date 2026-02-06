<?php
// Consulta de diseñadores
try {
    // Cambiamos la tabla de productos a disenadores
    $query = "SELECT * FROM Disenadores ORDER BY id_disenador DESC";
    $stmt = $conexion->query($query);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>

<section class="seccion-tabla">
    <div class="tabla-header">
        <h2>Listado de Productos</h2>
        <a href="views/nuevo_disenador.php" class="btn-nuevo">+ Añadir Producto</a>
    </div>

    <table class="tabla-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Biografía</th>
                <th>Web URL</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Consulta simplificada para la tabla Disenadores
            $stmt = $conexion->query("SELECT * FROM Disenadores ORDER BY id_disenador DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <tr>
                    <td>#<?php echo $row['id_disenador']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['biografia'] ?? '', 0, 50)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($row['web_url'] ?? ''); ?></td>
                    <td>
                        <a href="index.php?p=editar_disenador&id=<?php echo $row['id_disenador']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="index.php?p=gestion_disenador&accion=eliminar&id=<?php echo $row['id_disenador']; ?>" class="btn-delete" onclick="return confirm('¿Borrar diseñador?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>