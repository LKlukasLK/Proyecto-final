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
            $directorio = "../public/img/productos";

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
                    echo "<script>window.location.href='index.php?p=gestion_productos';</script>";
                    exit;
                }
            }
        }
    }
}
?>

<div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
    <h2 style="font-weight: 600; color: #1a1a1a;">Nuevo Artículo</h2>
</div>

<div style="background: #ffffff; border-radius: 40px; padding: 40px; max-width: 450px; margin: 0 auto; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
    <form action="index.php?p=nuevo_producto&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase;">Nombre</label>
            <input type="text" name="nombre" required style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 8px 0; outline: none;">
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase;">Categoría</label>
                <select name="id_categoria" required style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 8px 0; background: transparent; width: 100%;">
                    <?php
                    $stmt = $db->query("SELECT * FROM Categorias");
                    while($cat = $stmt->fetch(PDO::FETCH_ASSOC)) echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                    ?>
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase;">Diseñador</label>
                <input type="text" name="disenador" placeholder="Ej: Gucci" style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 8px 0; outline: none;">
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase;">Precio (€)</label>
                <input type="number" name="precio" step="0.01" required style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 8px 0;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase;">Stock</label>
                <input type="number" name="stock" required style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 8px 0;">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 8px;">Imagen del producto</label>
            
            <div id="upload-container">
                <input type="file" name="imagen" id="img-input" style="display: none;" accept=".png, .jpg, .jpeg" onchange="mostrarPreview(this)">
                <label for="img-input" style="display: flex; align-items: center; justify-content: center; background: #fafafa; border: 1.5px dashed #ccc; border-radius: 12px; padding: 15px; cursor: pointer; min-height: 60px; gap: 10px;">
                    <img src="https://cdn-icons-png.flaticon.com/512/109/109612.png" style="width: 18px; opacity: 0.4;">
                    <span style="color: #999; font-size: 12px;">Seleccionar archivo</span>
                </label>
            </div>

            <div id="preview-container" style="display: none; position: relative; margin-top: 10px; text-align: center;">
                <img id="img-preview" src="#" style="max-width: 100%; max-height: 120px; border-radius: 12px; border: 1px solid #eee;">
                <button type="button" onclick="borrarFoto()" style="position: absolute; top: -5px; right: 5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer;">✕</button>
            </div>
        </div>

        <button type="submit" style="width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 14px; border-radius: 12px; font-weight: bold; cursor: pointer;">
            Guardar Producto
        </button>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php?p=gestion_productos" style="color: #bbb; text-decoration: none; font-size: 11px;">— Volver al listado</a>
        </div>
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