<div class="admin-header">
    <h1>Gestión de Stock</h1>
    <p>Añade tallas o edita las existencias actuales</p>
</div>

<div class="admin-actions">
    <button class="btn-black" onclick="toggleForm()">+ Añadir Nueva Talla</button>
</div>

<div id="form-nueva-talla" class="table-container" style="display: none; margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; font-weight: 400;">Nueva variante de producto</h3>
    
    <form action="controladores/gestion_controller.php?accion=guardar_stock" method="POST" class="auth-form" style="display: flex; gap: 20px; align-items: flex-end;">
        
        <div class="input-field" style="flex: 2; margin-bottom: 0;">
            <label>Seleccionar Producto</label>
            <select name="producto_id" required style="width: 100%; padding: 10px; border: none; border-bottom: 1px solid #e0ddd5; background: transparent;">
                <option value="">Selecciona un artículo...</option>
                <?php
                // Pillamos los productos para el select
                $stmt_p = $conexion->query("SELECT id_producto, nombre FROM productos");
                while($p = $stmt_p->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <option value="<?php echo $p['id_producto']; ?>"><?php echo htmlspecialchars($p['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="input-field" style="flex: 1; margin-bottom: 0;">
            <label>Talla</label>
            <input type="text" name="talla" placeholder="Ej: XL, M, 42" required>
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
            <?php
            // Consulta para sacar el stock real (ajusta nombres de tablas si hace falta)
            $sql_stock = "SELECT s.*, p.nombre FROM stock s JOIN productos p ON s.id_producto = p.id_producto";
            $stmt_s = $conexion->query($sql_stock);
            
            while($row = $stmt_s->fetch(PDO::FETCH_ASSOC)):
                // Lógica de colores para el estado
                $status = ($row['cantidad'] <= 5) ? 'status-low' : 'status-ok';
                $texto = ($row['cantidad'] <= 5) ? 'Stock Bajo' : 'Disponible';
                if($row['cantidad'] == 0) { $status = 'status-out'; $texto = 'Agotado'; }
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><span class="size-tag"><?php echo htmlspecialchars($row['talla']); ?></span></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td><span class="<?php echo $status; ?>"><?php echo $texto; ?></span></td>
                <td class="actions">
                    <button class="btn-line">Editar</button>
                    
                    <a href="controladores/gestion_controller.php?accion=eliminar_stock&id=<?php echo $row['id_stock']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('¿Seguro que quieres borrar esta talla?')">
                       Eliminar
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function toggleForm() {
    var form = document.getElementById('form-nueva-talla');
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>