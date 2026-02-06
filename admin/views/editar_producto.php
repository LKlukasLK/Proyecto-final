<?php
// Usamos tu controlador y cargamos la BD
require_once '../../controllers/ProductoController.php';
$productoCtrl = new ProductoController();

$id = $_GET['id'] ?? null;

// Si no hay ID, volvemos a la tabla
if (!$id) {
    echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
    exit;
}

// 1. Cargamos datos actuales con tu método obtenerPorId
$producto = $productoCtrl->obtenerPorId($id);

// 2. Si se pulsa el botón de actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    
    // Mantenemos la foto si no sube una nueva
    $nombreFoto = $producto['imagen_url'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $nombreFoto = time() . "_" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/img/productos/" . $nombreFoto);
    }

    // Datos para tu método actualizar($id, $datos)
    $datos = [
        'nombre'       => $_POST['nombre'],
        'descripcion'  => $_POST['nombre'], // Mantenemos nombre como descripción
        'precio'       => $_POST['precio'],
        'stock'        => $_POST['stock'],
        'imagen_url'   => $nombreFoto,
        'id_categoria' => $_POST['id_categoria']
    ];

    // 3. Ejecutamos la actualización (esto dispara tus notificaciones si el stock sube)
    if ($productoCtrl->actualizar($id, $datos)) {
        echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
        exit;
    }
}
?>

<div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
    <h2 style="font-weight: 600;">Editar Artículo</h2>
    <p style="color: #666; font-size: 13px;">Modifica los detalles del producto #<?= $id ?></p>
</div>

<div style="background: #fff; border-radius: 40px; padding: 40px; max-width: 450px; margin: 0 auto; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
    <form action="index.php?p=editar_producto&id=<?= $id ?>&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 8px 0; outline: none;">
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 8px 0;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Stock</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 8px 0;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">ID Categoría</label>
            <input type="number" name="id_categoria" value="<?= $producto['id_categoria'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 8px 0;">
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 10px;">Imagen</label>
            <div style="text-align: center; background: #fafafa; padding: 15px; border-radius: 12px; border: 1px solid #eee;">
                <img src="../public/img/productos/<?= $producto['imagen_url'] ?>" style="max-height: 100px; border-radius: 8px; margin-bottom: 10px; display: block; margin: 0 auto;">
                <input type="file" name="imagen" style="font-size: 11px;">
            </div>
        </div>

        <button type="submit" style="width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 14px; border-radius: 12px; font-weight: bold; cursor: pointer;">
            Guardar Cambios
        </button>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php?p=gestion_productos" style="color: #bbb; text-decoration: none; font-size: 11px;">— Volver al listado</a>
        </div>
    </form>
</div>