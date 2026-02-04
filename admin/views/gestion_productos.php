<?php
// Usamos PDO para la consulta de productos
try {
    $query = "SELECT p.*, c.nombre as categoria_nombre 
              FROM productos p 
              LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";

    $stmt = $conexion->query($query);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>

<section class="seccion-tabla">
    <div class="tabla-header">
        <h2>Listado de Productos</h2>
        <a href="nuevo_producto.php" class="btn-nuevo">+ Añadir Producto </a>
    </div>

    <table class="tabla-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>#<?php echo $row['id_producto'] ?? $row['id']; ?></td>

                    <td>
                        <?php 
                        $img = $row['imagen'];
                        // Sacamos la extensión del archivo para comprobarla
                        $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                        if (empty($img)): ?>
                            <span>Sin foto</span>
                        <?php elseif ($extension !== 'png'): ?>
                            <span style="color: #d32f2f; font-weight: bold; font-size: 11px;">⚠️ ERROR: el archivo no es compatible (solo formato en png)</span>
                        <?php else: ?>
                            <img src="../public/img/<?php echo $img; ?>" width="50" alt="prod">
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria_nombre'] ?? 'Sin categoría'); ?></td>
                    <td><?php echo number_format($row['precio'], 2); ?>€</td>
                    <td>
                        <a href="editar_producto.php?id=<?php echo $row['id_producto'] ?? $row['id']; ?>" class="btn-edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="controladores/gestion_controller.php?accion=eliminar_producto&id=<?php echo $row['id_producto'] ?? $row['id']; ?>"
                            class="btn-delete" onclick="return confirm('¿Seguro?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>