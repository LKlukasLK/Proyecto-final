<?php
// Consulta de productos
try {
    $query = "SELECT p.*, c.nombre as categoria_nombre 
              FROM productos p 
              LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
              ORDER BY p.id_producto DESC";

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
                    <td>#<?php echo $row['id_producto']; ?></td>

                    <td>
                        <?php 
                        // Imagen url de la base de datos
                        $img = $row['imagen_url'] ?? ''; 
                        $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                        if (empty($img)): ?>
                            <span>Sin foto</span>
                        <?php elseif (!in_array($extension, ['png', 'jpg', 'jpeg'])): ?>
                            <span style="color: #d32f2f; font-weight: bold; font-size: 11px;">⚠️ Formato no válido</span>
                        <?php else: ?>
                            <img src="../public/img/productos/<?php echo $img; ?>" width="50" style="border-radius: 5px;" alt="prod">
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria_nombre'] ?? 'Sin categoría'); ?></td>
                    <td><?php echo number_format($row['precio'], 2); ?>€</td>
                    
                    <td>
                        <a href="index.php?p=editar_producto&id=<?php echo $row['id_producto']; ?>" class="btn-edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        
                        <a href="index.php?p=productos&accion=eliminar&id=<?php echo $row['id_producto']; ?>"
                            class="btn-delete" onclick="return confirm('¿Seguro que quieres borrarlo por completo? Esta acción no se puede deshacer.')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>