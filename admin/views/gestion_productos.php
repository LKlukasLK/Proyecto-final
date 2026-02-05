<?php
// Consulta limpia para productos y categorías
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
        <a href="index.php?p=nuevo_producto" class="btn-nuevo">+ Añadir Producto</a>
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
                        <?php if (!empty($row['imagen'])): ?>
                            <img src="../public/img/<?php echo $row['imagen']; ?>" width="50" alt="prod">
                        <?php else: ?>
                            <span>Sin foto</span>
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