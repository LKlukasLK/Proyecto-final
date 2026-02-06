<?php
// Cargamos controlador y datos
require_once '../controllers/ProductoController.php';
$productoCtrl = new ProductoController();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>window.location.href='index.php?p=productos';</script>";
    exit;
}

// Datos del producto y categorías
$producto = $productoCtrl->obtenerPorId($id);
$queryCat = "SELECT * FROM categorias ORDER BY nombre ASC";
$stmtCat = $conexion->query($queryCat);
$categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    $nombreFoto = $producto['imagen_url'];

    // Lógica para nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $nombreFoto = time() . "_" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../public/img/productos/" . $nombreFoto);
    }

    $datos = [
        'nombre'       => $_POST['nombre'],
        'descripcion'  => $_POST['nombre'], 
        'precio'       => $_POST['precio'],
        'stock'        => $_POST['stock'],
        'imagen_url'   => $nombreFoto,
        'id_categoria' => $_POST['id_categoria']
    ];

    if ($productoCtrl->actualizar($id, $datos)) {
        header("Location: index.php?p=productos&msg=usuario_modificado");
        exit;
    }
}
?>

<div class="form-header-container">
    <h2>Editar Artículo</h2>
    <p class="form-subtitle">ID del Producto: #<?= $id ?></p>
</div>

<div class="form-card">
    <form action="index.php?p=editar_producto&id=<?= $id ?>&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="label-style">Nombre del Producto</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required class="input-underline">
        </div>

        <div class="form-row">
            <div style="flex: 1;">
                <label class="label-style">Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required class="input-underline">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Stock Actual</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" required class="input-underline">
            </div>
        </div>

        <div class="form-group">
            <label class="label-style">Categoría</label>
            <select name="id_categoria" required class="input-underline" style="cursor: pointer;">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="label-style">Imagen del producto</label>
            
            <div style="text-align: center; background: #fafafa; padding: 20px; border-radius: 20px; border: 1px solid #f0f0f0;">
                
                <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 15px auto;">
                    <img id="preview" src="../public/img/productos/<?= $producto['imagen_url'] ?>" 
                         data-original="../public/img/productos/<?= $producto['imagen_url'] ?>"
                         class="img-tabla" style="width: 100%; height: 100%; object-fit: cover;">
                    
                    <button type="button" id="btn-remove" title="Cancelar cambio"
                            style="position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: none; font-size: 10px;">✕</button>
                </div>

                <input type="file" name="imagen" id="input-img" accept="image/png, image/jpeg, image/jpg" style="font-size: 11px; width: 100%;">
                
                <p id="error-img" class="alerta-error" style="display: none; padding: 5px; font-size: 10px; margin-top: 10px;">⚠️ Formato no válido</p>
            </div>
        </div>

        <button type="submit" id="btn-submit" class="btn-submit-black">
            Guardar Cambios
        </button>

        <a href="index.php?p=productos" class="link-back">— Volver al listado</a>
    </form>
</div>

<script>
const inputImg = document.getElementById('input-img');
const preview = document.getElementById('preview');
const btnRemove = document.getElementById('btn-remove');
const errorMsg = document.getElementById('error-img');
const btnSubmit = document.getElementById('btn-submit');
const originalSrc = preview.getAttribute('data-original');

inputImg.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];

        if (!validTypes.includes(file.type)) {
            errorMsg.style.display = 'block';
            this.value = ''; 
            preview.src = originalSrc;
            btnRemove.style.display = 'none';
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            return;
        }

        errorMsg.style.display = 'none';
        btnSubmit.disabled = false;
        btnSubmit.style.opacity = '1';
        
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
    errorMsg.style.display = 'none';
    btnSubmit.disabled = false;
    btnSubmit.style.opacity = '1';
});
</script>