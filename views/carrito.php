<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Cesta | Mercado Ropa</title>
    <link rel="stylesheet" href="public/CSS/style.css">
</head>
<body>
    <?php include 'views/layout/header.php'; ?>

    <main class="catalogo-container">
        <header class="catalogo-header">
            <h2>TU CESTA</h2>
            <p class="articulos-count">
                <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?> ARTÍCULOS
            </p>
        </header>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <div class="carrito-lista">
                <?php 
                $total = 0;
                foreach ($_SESSION['carrito'] as $indice => $item): 
                    $total += $item['precio']; 
                ?>
                    <div class="carrito-item">
                        <div class="item-info">
                            <div class="item-img-placeholder">👕</div>
                            <div class="item-detalles">
                                <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                <p class="item-ref">REF: <?php echo $item['id']; ?></p>
                            </div>
                        </div>

                        <div class="item-precio-wrap">
                            <span class="item-precio"><?php echo number_format($item['precio'], 2); ?>€</span>
                            <a href="index.php?ver=eliminar_item&id=<?php echo $indice; ?>" class="btn-eliminar">Eliminar</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="carrito-resumen">
                    <div class="resumen-fila">
                        <span class="resumen-etiqueta">TOTAL (IVA INCLUIDO)</span>
                        <span class="resumen-total"><?php echo number_format($total, 2); ?>€</span>
                    </div>

                    <form action="index.php?ver=preparar_pago" method="POST">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" class="btn-pagar">Tramitar Pedido</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="carrito-vacio-container">
                <p>Tu cesta está vacía actualmente.</p>
                <div class="contenedor-boton-volver">
                    <a href="index.php?ver=catalogo" class="btn-volver-tienda">VOLVER A LA TIENDA</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>