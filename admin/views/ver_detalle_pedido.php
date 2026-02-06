<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Vinculación a Detalles_Pedido y unión con Productos para traer el nombre
    $sql = "SELECT d.*, p.nombre FROM Detalles_Pedido d 
            JOIN Productos p ON d.id_producto = p.id_producto 
            WHERE d.id_pedido = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<style>
    .card-d { background: #fff; border-radius: 40px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); font-family: sans-serif; max-width: 600px; margin: auto; }
    .item-d { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
    .vol { color: #aaa; text-decoration: none; font-size: 12px; margin-bottom: 20px; display: inline-block; }
</style>

<div class="card-d">
    <a href="index.php?p=pedidos" class="vol">← Volver a la lista</a>
    <h2 style="font-weight: 800;">Detalle Pedido #<?php echo $id; ?></h2>
    
    <div style="margin-top: 20px;">
        <?php foreach ($detalles as $d): ?>
        <div class="item-d">
            <div>
                <div style="font-weight: bold;"><?php echo $d['nombre']; ?></div>
                <div style="font-size: 12px; color: #888;">Cantidad: <?php echo $d['cantidad']; ?></div>
            </div>
            <div style="font-weight: 700;"><?php echo number_format($d['precio_unitario'], 2); ?>€ / ud</div>
        </div>
        <?php endforeach; ?>
    </div>
</div>