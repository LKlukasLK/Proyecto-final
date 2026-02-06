<?php
// LÓGICA DE BORRADO
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conexion->prepare("DELETE FROM Disenadores WHERE id_disenador = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>window.location.href='index.php?p=gestion_disenador';</script>";
    exit;
}

$resultado = $conexion->query("SELECT * FROM Disenadores ORDER BY id_disenador ASC");
?>

<section class="seccion-tabla">
    <div class="tabla-header">
        <h2>Gestión de Diseñadores</h2>
        <a href="index.php?p=nuevo_disenador" class="btn-nuevo">+ Añadir Diseñador</a>
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
            $contador = 1; 
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <tr>
                    <td>#<?php echo $contador++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars(mb_strimwidth($row['biografia'] ?? '', 0, 40, "...")); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($row['web_url']); ?>" target="_blank" style="color: #2196F3;">Link</a>
                    </td>
                    <td>
                        <a href="index.php?p=editar_disenador&id=<?php echo $row['id_disenador']; ?>" class="btn-edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="index.php?p=gestion_disenador&accion=eliminar&id=<?php echo $row['id_disenador']; ?>" 
                           class="btn-delete" onclick="return confirm('¿Eliminar?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>