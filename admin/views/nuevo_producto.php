<?php
// Controladores y DB
require_once '../controllers/ProductoController.php';
require_once '../config/db.php';

$productoCtrl = new ProductoController();
$db = Database::conectar();

// Guardar producto y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $nombreFoto = time() . "_" . uniqid() . "." . $ext;
            $directorio = "../public/img/productos/";

            if (!is_dir($directorio)) mkdir($directorio, 0777, true);

            $destino = $directorio . $nombreFoto;

            // Mover foto y guardar en BD
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $datos = [
                    'nombre'       => $_POST['nombre'],
                    'descripcion'  => $_POST['nombre'], 
                    'precio'       => $_POST['precio'],
                    'stock'        => $_POST['stock'],
                    'imagen_url'   => $nombreFoto,
                    'id_categoria' => $_POST['id_categoria'],
                    'disenador'    => $_POST['disenador']
                ];

                if ($productoCtrl->crear($datos)) {
                    header("Location: index.php?p=productos&msg=usuario_creado");
                    exit;
                }
            }
        }
    }
}
?>

<div class="form-header-container">
    <h2>Nuevo Artículo</h2>
    <p class="form-subtitle">Completa la ficha del nuevo producto</p>
</div>

<div class="form-card">
    <form action="index.php?p=nuevo_producto&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="label-style">Nombre del Producto</label>
            <input type="text" name="nombre" required class="input-underline" placeholder="Ej: Camiseta Oversize">
        </div>

        <div class="form-row">
            <div style="flex: 1;">
                <label class="label-style">Categoría</label>
                <select name="id_categoria" required class="input-underline" style="cursor: pointer;">
                    <?php
                    $stmt = $db->query("SELECT * FROM Categorias");
                    while($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="flex: 1;">
                <label class="label-style">Diseñador</label>
                <input type="text" name="disenador" placeholder="Ej: Gucci" class="input-underline">
            </div>
        </div>

        <div class="form-row">
            <div style="flex: 1;">
                <label class="label-style">Precio (€)</label>
                <input type="number" name="precio" step="0.01" required class="input-underline" placeholder="0.00">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Stock Inicial</label>
                <input type="number" name="stock" required class="input-underline" placeholder="0">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="label-style">Imagen del producto</label>
            
            <div id="upload-container">
                <input type="file" name="imagen" id="img-input" style="display: none;" accept=".png, .jpg, .jpeg" onchange="mostrarPreview(this)">
                <label for="img-input" style="display: flex; align-items: center; justify-content: center; background: #fafafa; border: 1.5px dashed #ccc; border-radius: 12px; padding: 15px; cursor: pointer; min-height: 60px; gap: 10px;">
                    <i class="fa-solid fa-cloud-arrow-up" style="color: #999;"></i>
                    <span style="color: #999; font-size: 12px; font-weight: 600;">Seleccionar archivo</span>
                </label>
            </div>

            <div id="preview-container" style="display: none; position: relative; margin-top: 10px; text-align: center;">
                <img id="img-preview" src="#" style="max-width: 100%; max-height: 120px; border-radius: 12px; border: 1px solid #eee;">
                <button type="button" onclick="borrarFoto()" style="position: absolute; top: -5px; right: 5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer;">✕</button>
            </div>
        </div>

        <button type="submit" class="btn-submit-black">
            Publicar Producto
        </button>

        <a href="index.php?p=productos" class="link-back">— Volver al listado</a>
    </form>
</div>

<script>
function mostrarPreview(input) {
    const previewContainer = document.getElementById('preview-container');
    const uploadContainer = document.getElementById('upload-container');
    const previewImg = document.getElementById('img-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadContainer.style.display = 'none';
            previewContainer.style.display = 'block';
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