<?php
// Usamos tu controlador y cargamos la BD
require_once '../controllers/ProductoController.php';
$productoCtrl = new ProductoController();

$id = $_GET['id'] ?? null;

// Si no hay ID, volvemos a la tabla
if (!$id) {
    echo "<script>window.location.href='index.php?p=productos';</script>";
    exit;
}

// Sacamos los datos actuales
$producto = $productoCtrl->obtenerPorId($id);

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    
    $nombreFoto = $producto['imagen_url']; // Foto actual por defecto

    // Si se sube una nueva
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
        echo "<script>window.location.href='index.php?p=productos';</script>";
        exit;
    }
}
?>

<div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
    <h2 style="font-weight: 600;">Editar Artículo</h2>
    <p style="color: #666; font-size: 13px;">Modificando producto ID: #<?= $id ?></p>
</div>

<div style="background: #fff; border-radius: 40px; padding: 40px; max-width: 450px; margin: 0 auto; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
    <form action="index.php?p=editar_producto&id=<?= $id ?>&accion=guardar" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 10px 0; outline: none;">
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 10px 0;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">Stock</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 10px 0;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase;">ID Categoría</label>
            <input type="number" name="id_categoria" value="<?= $producto['id_categoria'] ?>" required style="width: 100%; border: none; border-bottom: 1px solid #eee; padding: 10px 0;">
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 10px;">Imagen del producto</label>
            <div style="text-align: center; background: #fafafa; padding: 20px; border-radius: 12px; border: 1px solid #eee; position: relative;">
                
                <div style="position: relative; display: inline-block;">
                    <img id="preview" src="../public/img/productos/<?= $producto['imagen_url'] ?>" 
                         data-original="../public/img/productos/<?= $producto['imagen_url'] ?>"
                         style="max-height: 120px; border-radius: 8px; margin-bottom: 10px; display: block;">
                    
                    <button type="button" id="btn-remove" title="Quitar imagen seleccionada"
                            style="position: absolute; top: -10px; right: -10px; background: #d32f2f; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer; display: none; font-size: 12px; font-weight: bold;">✕</button>
                </div>

                <input type="file" name="imagen" id="input-img" accept="image/*" style="font-size: 11px; display: block; margin: 10px auto 0 auto;">
                
                <p id="error-img" style="color: #d32f2f; font-size: 11px; font-weight: bold; margin-top: 10px; display: none;">⚠️ Formato no válido. Usa PNG, JPG o JPEG.</p>
            </div>
        </div>

        <button type="submit" id="btn-submit" style="width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 14px; border-radius: 12px; font-weight: bold; cursor: pointer;">
            Guardar Cambios
        </button>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php?p=productos" style="color: #bbb; text-decoration: none; font-size: 11px; font-weight: 600;">— VOLVER AL LISTADO</a>
        </div>
    </form>
</div>

<script>
// Lógica para previsualizar y validar imagen
const inputImg = document.getElementById('input-img');
const preview = document.getElementById('preview');
const errorMsg = document.getElementById('error-img');
const btnRemove = document.getElementById('btn-remove');
const btnSubmit = document.getElementById('btn-submit');
const originalSrc = preview.getAttribute('data-original');

inputImg.addEventListener('change', function() {
    const file = this.files[0];
    
    if (file) {
        const fileType = file.type;
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];

        // Validar formato
        if (!validTypes.includes(fileType)) {
            errorMsg.style.display = 'block';
            this.value = ''; // Limpiar input
            preview.src = originalSrc;
            btnRemove.style.display = 'none';
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            return;
        }

        // Si es válido, mostrar preview
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

// Botón para borrar la selección actual
btnRemove.addEventListener('click', function() {
    inputImg.value = ''; // Reset input
    preview.src = originalSrc; // Volver a la original
    this.style.display = 'none';
    errorMsg.style.display = 'none';
    btnSubmit.disabled = false;
    btnSubmit.style.opacity = '1';
});
</script>