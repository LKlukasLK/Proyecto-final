<?php
$id_pedido = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $sql = "SELECT d.*, p.nombre 
            FROM Detalles_Pedido d 
            JOIN Productos p ON d.id_producto = p.id_producto 
            WHERE d.id_pedido = :id";
            
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id_pedido]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total_caja = 0;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="contenedor-detalle">
    <div class="caja-detalle-pedido">
        <div class="encabezado-caja">
            <h2>Pedido #<?php echo $id_pedido; ?></h2>
            <p class="subtitulo-caja">Detalles de Compra</p>
        </div>

        <div class="lista-productos">
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): 
                    $subtotal = $item['cantidad'] * $item['precio_unitario'];
                    $total_caja += $subtotal;
                ?>
                    <div class="producto-item">
                        <div class="producto-info">
                            <b><?php echo htmlspecialchars($item['nombre']); ?></b>
                            <span>Cantidad: <?php echo $item['cantidad']; ?></span>
                        </div>
                        <div class="producto-precio">
                            <?php echo number_format($subtotal, 2); ?>€
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="resumen-total">
            <span class="total-label">Total Final</span>
            <span class="total-monto"><?php echo number_format($total_caja, 2); ?>€</span>
        </div>

        <div class="footer-caja">
            <a href="index.php?p=pedidos" class="btn-volver-caja">
                — Volver al listado —
            </a>
        </div>
    </div>
</div>