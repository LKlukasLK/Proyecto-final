<div class="admin-header">
    <h1>Gestión de Stock</h1>
    <p>Añade tallas o edita las existencias actuales</p>
</div>

<div class="admin-actions">
    <button class="btn-black" onclick="toggleForm()">+ Añadir Nueva Talla</button>
</div>

<div id="form-nueva-talla" class="table-container" style="display: none; margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; font-weight: 400;">Nueva variante de producto</h3>
    
    <form action="index.php?ver=guardar_stock" method="POST" class="auth-form" style="display: flex; gap: 20px; align-items: flex-end;">
        
        <div class="input-field" style="flex: 2; margin-bottom: 0;">
            <label>Seleccionar Producto</label>
            <select name="producto_id" required style="width: 100%; padding: 10px; border: none; border-bottom: 1px solid #e0ddd5; background: transparent;">
                <option value="">Selecciona un artículo...</option>
                <option value="1">Vestido Seda Minimal</option>
                <option value="2">Pantalón Lino Beige</option>
            </select>
        </div>

        <div class="input-field" style="flex: 1; margin-bottom: 0;">
            <label>Talla</label>
            <input type="text" name="talla" placeholder="Ej: XL, 42, M" required>
        </div>

        <div class="input-field" style="flex: 1; margin-bottom: 0;">
            <label>Stock Inicial</label>
            <input type="number" name="stock" placeholder="0" min="0" required>
        </div>

        <button type="submit" class="btn-black" style="margin-top: 0; padding: 12px 25px; width: auto;">Guardar</button>
        <button type="button" class="btn-line" onclick="toggleForm()" style="border: none; text-decoration: underline;">Cancelar</button>
    </form>
</div>

<div class="table-container">
    <table class="boutique-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Vestido Seda Minimal</td>
                <td><span class="size-tag">S</span></td>
                <td>15</td>
                <td><span class="status-ok">Disponible</span></td>
                <td class="actions">
                    <button class="btn-line">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
function toggleForm() {
    var form = document.getElementById('form-nueva-talla');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>