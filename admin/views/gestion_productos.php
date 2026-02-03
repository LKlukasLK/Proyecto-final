<?php
// 1. NO incluyas la conexión aquí. Ya viene de admin.php
// 2. Usamos PDO (query y fetch) en lugar de mysqli

try {
    // Consulta para obtener los productos (Asegúrate que los nombres de columnas id_categoria etc sean correctos)
    $query = "SELECT p.*, c.nombre as categoria_nombre 
              FROM productos p 
              LEFT JOIN categorias c ON p.id_categoria = c.id_categoria"; // Revisa si tu tabla categorias tiene 'id' o 'id_categoria'

    $stmt = $conexion->query($query);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>

<section class="seccion-tabla">
    <div class="tabla-header">
        <h2>Listado de Productos</h2>
        <a href="nuevo_producto.php" class="btn-nuevo">+ Añadir Producto</a>
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
            <?php
            // En PDO usamos fetch()
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                    <!-- OJO: Si en tu DB no es 'id', cambia a 'id_producto' -->
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