<div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
    <h2 style="font-weight: 600; color: #1a1a1a;">Nuevo Artículo</h2>
    <p style="color: #666; font-size: 14px;">Añade productos al catálogo de la boutique</p>
</div>

<div style="background: #ffffff; border-radius: 40px; padding: 50px; max-width: 450px; margin: 0 auto; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <span style="font-size: 40px;">🛍️</span>
    </div>

    <form action="controladores/gestion_controller.php?accion=guardar_producto" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 5px;">Nombre</label>
            <input type="text" name="nombre" placeholder="Ej: Vestido Seda" required 
                   style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 10px 0; outline: none; font-size: 15px;">
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 25px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 5px;">Categoría</label>
                <select name="id_categoria" required style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 10px 0; background: transparent; outline: none; cursor: pointer;">
                    <?php
                    $stmt_c = $conexion->query("SELECT * FROM categorias");
                    while($cat = $stmt_c->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div style="flex: 1;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 5px;">Precio (€)</label>
                <input type="number" name="precio" step="0.01" placeholder="0.00" required 
                       style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 10px 0; outline: none;">
            </div>
        </div>

        <div style="margin-bottom: 35px;">
            <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 10px;">Imagen del producto</label>
            
            <input type="file" name="imagen" id="file-upload" style="display: none;" accept="image/*">
            
            <label for="file-upload" id="drop-area" 
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #fdfdfd; border: 2px dashed #d1d1d1; border-radius: 20px; padding: 15px; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; min-height: 100px;">
                
                <i id="upload-icon" class="fa-solid fa-cloud-arrow-up" style="font-size: 16px; color: #aaa; margin-bottom: 5px;"></i>
                <span id="file-text" style="color: #999; font-size: 11px; font-weight: 500; text-align: center;">Añadir imagen</span>
                
                <img id="image-preview" src="" style="display: none; width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; z-index: 10; background: #fff;">
            </label>
        </div>

        <button type="submit" style="width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 16px; border-radius: 15px; font-weight: bold; font-size: 16px; cursor: pointer;">
            Guardar Producto
        </button>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php?p=productos" style="color: #bbb; text-decoration: none; font-size: 12px; font-weight: 600;">
                — Volver al listado
            </a>
        </div>
    </form>
</div>

<script>
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('file-upload');
const fileText = document.getElementById('file-text');
const uploadIcon = document.getElementById('upload-icon');
const imagePreview = document.getElementById('image-preview');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
    }, false);
});

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => {
        dropArea.style.borderColor = '#1a1a1a';
        dropArea.style.background = '#f9f9f9';
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => {
        dropArea.style.borderColor = '#d1d1d1';
        dropArea.style.background = '#fdfdfd';
    }, false);
});

dropArea.addEventListener('drop', e => {
    const files = e.dataTransfer.files;
    if (files.length) {
        fileInput.files = files;
        previewFile(files[0]);
    }
});

fileInput.addEventListener('change', function() {
    if (this.files.length) {
        previewFile(this.files[0]);
    }
});

function previewFile(file) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            uploadIcon.style.display = 'none';
            fileText.style.display = 'none';
        }
    }
}
</script>