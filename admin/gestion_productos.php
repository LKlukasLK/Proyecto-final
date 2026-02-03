<?php
// Conexión (Asegúrate de tener tu archivo de conexión)
include '../config/conexion.php'; 

// Consulta para obtener los productos
$query = "SELECT p.*, c.nombre as categoria_nombre 
          FROM productos p 
          LEFT JOIN categorias c ON p.id_categoria = c.id";
$resultado = mysqli_query($conexion, $query);
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
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><img src="../public/img/<?php echo $row['imagen']; ?>" width="50"></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['categoria_nombre']; ?></td>
                <td><?php echo $row['precio']; ?>€</td>
                <td>
                    <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                    <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('¿Seguro?')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>