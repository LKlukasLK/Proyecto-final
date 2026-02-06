<?php
require_once '../controllers/ProductoController.php';
require_once '../config/db.php';

$productoCtrl = new ProductoController();
$db = Database::conectar();

// Guardar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $nombreFoto = time() . "_" . uniqid() . "." . $ext;
            $destino = "../public/img/productos/" . $nombreFoto;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $datos = [
                    'nombre'       => $_POST['nombre'],
                    'descripcion'  => $_POST['nombre'], 
                    'precio'       => $_POST['precio'],
                    'stock'        => $_POST['stock'],
                    'imagen_url'   => $nombreFoto,
                    'id_categoria' => $_POST['id_categoria'],
                    'id_disenador' => $_POST['id_disenador'] // Usamos el ID del select
                ];

                if ($productoCtrl->crear($datos)) {
                    echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
                    exit;
                }
            }
        }
    }
}
?>
<link rel="stylesheet" href="../public/css/formularios.css">

<div class="form-header">
    <h2>Nuevo Artículo</h2>
</div>

<div class="form-card">
    <form action="index.php?p=nuevo_producto&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="label-style">Nombre</label>
            <input type="text" name="nombre" required class="input-underline">
        </div>

        <div class="form-row">
            <div style="flex: 1;">
                <label class="label-style">Categoría</label>
                <select name="id_categoria" required class="input-underline">
                    <?php
                    $stmt = $db->query("SELECT * FROM Categorias");
                    while($cat = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                    ?>
                </select>
            </div>
            <div style="flex: 1;">
                <label class="label-style">Diseñador</label>
                <select name="id_disenador" required class="input-underline">
                    <option value="" disabled selected>Selecciona uno</option>
                    <?php
                    $stmtD = $db->query("SELECT * FROM Disenadores ORDER BY nombre ASC");
                    while($dis = $stmtD->fetch(PDO::FETCH_ASSOC)) echo "<option value='{$dis['id_disenador']}'>{$dis['nombre']}</option>";
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div style="flex: 1;">
                <label class="label-style">Precio (€)</label>
                <input type="number" name="precio" step="0.01" required class="input-underline">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Stock</label>
                <input type="number" name="stock" required class="input-underline">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="label-style">Imagen del producto</label>
            <div id="upload-container">
                <input type="file" name="imagen" id="img-input" style="display: none;" accept=".png, .jpg, .jpeg" onchange="mostrarPreview(this)">
                <label for="img-input" class="upload-box">
                    <img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" style="width: 18px; opacity: 0.4;">
                    <span style="color: #999; font-size: 12px;">Seleccionar archivo</span>
                </label>
            </div>
            <div id="preview-container" style="display: none; position: relative; margin-top: 10px; text-align: center;">
                <img id="img-preview" src="#" style="max-width: 100%; max-height: 120px; border-radius: 12px; border: 1px solid #eee;">
                <button type="button" onclick="borrarFoto()" style="position: absolute; top: -5px; right: 5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer;">✕</button>
            </div>
        </div>

        <button type="submit" class="btn-submit">Guardar Producto</button>
        <a href="index.php?p=gestion_productos" class="link-back">— Volver al listado</a>
    </form>
</div>

<script>
function mostrarPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('img-preview').src = e.target.result;
            document.getElementById('upload-container').style.display = 'none';
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function borrarFoto() {
    document.getElementById('img-input').value = '';
    document.getElementById('preview-container').style.display = 'none';
    document.getElementById('upload-container').style.display = 'block';
}
</script>