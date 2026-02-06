<?php
// Cargamos controlador y datos
require_once '../controllers/ProductoController.php';
require_once '../config/db.php'; // Necesario para la conexión directa
$productoCtrl = new ProductoController();
$db = Database::conectar();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
    exit;
}

// Datos del producto
$producto = $productoCtrl->obtenerPorId($id);

// Cargar categorías y diseñadores para los select
$categorias = $db->query("SELECT * FROM Categorias ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$disenadores = $db->query("SELECT * FROM Disenadores ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    $nombreFoto = $producto['imagen_url'];

    // Lógica para nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $nombreFoto = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/img/productos/" . $nombreFoto);
        }
    }

    $datos = [
        'nombre'       => $_POST['nombre'],
        'descripcion'  => $_POST['nombre'], 
        'precio'       => $_POST['precio'],
        'stock'        => $_POST['stock'],
        'imagen_url'   => $nombreFoto,
        'id_categoria' => $_POST['id_categoria'],
        'id_disenador' => $_POST['id_disenador'] // Nuevo campo enviado al controlador
    ];

    if ($productoCtrl->actualizar($id, $datos)) {
        echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
        exit;
    }
}
?>

<div class="form-header-container">
    <h2 style="font-weight: 600; color: #1a1a1a;">Editar Artículo</h2>
    <p style="font-size: 11px; color: #999; font-weight: 700;">ID PRODUCTO: #<?= $id ?></p>
</div>

<div class="form-card">
    <form action="index.php?p=editar_producto&id=<?= $id ?>&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 20px;">
            <label class="label-style">Nombre del Producto</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required class="input-underline">
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label class="label-style">Categoría</label>
                <select name="id_categoria" required class="input-underline" style="cursor: pointer; background: transparent;">
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1;">
                <label class="label-style">Diseñador</label>
                <select name="id_disenador" required class="input-underline" style="cursor: pointer; background: transparent;">
                    <?php foreach ($disenadores as $dis): ?>
                        <option value="<?= $dis['id_disenador'] ?>" <?= ($dis['id_disenador'] == $producto['id_disenador']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dis['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label class="label-style">Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required class="input-underline">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Stock Actual</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" required class="input-underline">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label class="label-style">Imagen del producto</label>
            <div style="text-align: center; background: #fafafa; padding: 20px; border-radius: 20px; border: 1px solid #f0f0f0; margin-top: 10px;">
                <div style="position: relative; width: 100px; height: 100px; margin: 0 auto 15px auto;">
                    <img id="preview" src="../public/img/productos/<?= $producto['imagen_url'] ?>" 
                         data-original="../public/img/productos/<?= $producto['imagen_url'] ?>"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <button type="button" id="btn-remove" 
                            style="position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer; display: none; font-size: 10px;">✕</button>
                </div>
                <input type="file" name="imagen" id="input-img" accept="image/png, image/jpeg, image/jpg" style="font-size: 11px; width: 100%;">
            </div>
        </div>

        <button type="submit" id="btn-submit" class="btn-submit-black">Guardar Cambios</button>
        <a href="index.php?p=gestion_productos" class="link-back">— Volver al listado</a>
    </form>
</div>

<script>
// Lógica de preview de imagen (se mantiene igual)
const inputImg = document.getElementById('input-img');
const preview = document.getElementById('preview');
const btnRemove = document.getElementById('btn-remove');
const originalSrc = preview.getAttribute('data-original');

inputImg.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            btnRemove.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

btnRemove.addEventListener('click', function() {
    inputImg.value = '';
    preview.src = originalSrc;
    this.style.display = 'none';
});
</script>