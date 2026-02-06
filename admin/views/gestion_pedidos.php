<?php
try {
    // Vinculación directa a la tabla Pedidos
    $sql = "SELECT id_pedido, id_usuario, fecha_pedido, total, estado FROM Pedidos ORDER BY id_pedido DESC";
    $stmt = $conexion->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<style>
    .card-p { background: #fff; border-radius: 40px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); font-family: sans-serif; }
    .t-p { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .t-p th { text-align: left; color: #bbb; font-size: 11px; text-transform: uppercase; padding: 15px; border-bottom: 2px solid #f0f0f0; }
    .t-p td { padding: 15px; border-bottom: 1px solid #f9f9f9; font-size: 14px; }
    .badge-s { padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: bold; }
    .pendiente { background: #fff4e5; color: #f39c12; }
    .pagado { background: #e3f9eb; color: #2ecc71; }
    .btn-v { background:#1ABC9C; color: #fff; text-decoration: none; padding: 8px 15px; border-radius: 10px; font-size: 12px; }
</style>

<div class="card-p">
    <h2 style="font-weight: 800;">Gestión de Pedidos</h2>
    <table class="t-p">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $p): ?>
            <tr>
                <td><strong>#<?php echo $p['id_pedido']; ?></strong></td>
                <td>Cliente ID: <?php echo $p['id_usuario']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($p['fecha_pedido'])); ?></td>
                <td style="font-weight: 700;"><?php echo number_format($p['total'], 2); ?>€</td>
                <td><span class="badge-s <?php echo $p['estado']; ?>"><?php echo $p['estado']; ?></span></td>
                <td>
                    <a href="index.php?p=detalle_pedido&id=<?php echo $p['id_pedido']; ?>" class="btn-v">Ver Detalle</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>