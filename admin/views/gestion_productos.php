<?php
// Mostramos alertas si existen
if (isset($_GET['msg'])) {
    echo "<div class='alerta alerta-exito'>Operación realizada con éxito.</div>";
}

// Consulta de productos
try {
    $query = "SELECT p.*, c.nombre as categoria_nombre 
              FROM productos p 
              LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
              ORDER BY p.id_producto DESC";

    $stmt = $conexion->query($query);
} catch (PDOException $e) {
    echo "<div class='alerta alerta-error'>Error en la consulta: " . $e->getMessage() . "</div>";
}
?>

<div class="tabla-header">
    <h2><i class="fa-solid fa-boxes-stacked"></i> Listado de Productos</h2>
    <a href="index.php?p=nuevo_producto" class="btn-nuevo">
        <i class="fa-solid fa-plus"></i> Añadir Producto
    </a>
</div>

<table class="tabla-admin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td class="col-id">#<?php echo $row['id_producto']; ?></td>

                <td>
                    <?php 
                    $img = $row['imagen_url'] ?? ''; 
                    $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                    if (empty($img)): ?>
                        <span class="badge-rol">Sin foto</span>
                    <?php elseif (!in_array($extension, ['png', 'jpg', 'jpeg'])): ?>
                        <span class="alerta-error" style="padding: 2px 5px; font-size: 10px; border-radius: 4px;">Formato no válido</span>
                    <?php else: ?>
                        <img src="../public/img/productos/<?php echo $img; ?>" class="img-tabla" alt="prod">
                    <?php endif; ?>
                </td>

                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['categoria_nombre'] ?? 'Sin categoría'); ?></td>
                <td style="font-weight: 600;"><?php echo number_format($row['precio'], 2); ?>€</td>
                
                <td class="text-center">
                    <a href="index.php?p=editar_producto&id=<?php echo $row['id_producto']; ?>" class="btn-edit" title="Editar">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    
                    <a href="index.php?p=productos&accion=eliminar&id=<?php echo $row['id_producto']; ?>"
                       class="btn-delete" title="Eliminar"
                       onclick="return confirm('¿Seguro que quieres borrarlo?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>