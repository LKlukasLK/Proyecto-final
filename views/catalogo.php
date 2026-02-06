<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catalogo | Tienda</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <?php include 'views/layout/header.php'; ?>

    <main class="catalogo-container">

        <header class="catalogo-header">
            <div style="display: flex; align-items: center; gap: 30px;">
                <h2>TIENDA</h2>

                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="ver" value="catalogo">
                    <input type="text" name="q" placeholder="BUSCAR PRODUCTO..." class="search-input"
                        value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit" class="search-button">🔍</button>
                </form>
            </div>
            <p style="font-weight: 700; font-size: 13px; color: #666;">
                <?php echo count($productos); ?> PRODUCTOS ENCONTRADOS
            </p>
        </header>

        <div class="productos-grid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): ?>
                    <div class="card-producto">
                        <div class="item-img-placeholder">
                            <?php
                            // 1. Recuperamos el nombre. Asegúrate de que en la sesión se guardó como 'imagen_url'
                            $nombre_imagen = $item['imagen_url'] ?? '';

                            // 2. Ruta para el HTML (Navegador): "public/img/productos/foto.jpg"
                            // Esta es la que se imprime en el src=""
                            $ruta_web = "public/img/productos/" . $nombre_imagen;

                            // 3. Ruta para PHP (Comprobación): "/var/www/.../views/../public/img/productos/foto.jpg"
                            // Usamos __DIR__ para ubicarnos donde está este archivo (carpeta views) y salir fuera
                            $ruta_fisica = __DIR__ . "/../" . $ruta_web;

                            if (!empty($nombre_imagen) && file_exists($ruta_fisica)): ?>

                                <!-- Aquí usamos la ruta web, sin el ../ al principio -->
                                <img src="<?php echo $ruta_web; ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">

                            <?php else: ?>
                                <span style="font-size: 60px;">👕</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="card-titulo"><?php echo $p['nombre']; ?></h3>
                        <p class="card-precio"><?php echo number_format($p['precio'], 2); ?>€</p>
                        <p class="card-descripcion"><?php echo $p['descripcion']; ?></p>

                        <div class="contenedor-interes">
                            <form action="index.php?ver=marcar_favorito" method="POST" style="margin: 0;">
                                <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
                                <button type="submit" class="btn-interes">
                                    <span class="corazon-interes">❤</span> Me interesa
                                </button>
                            </form>
                        </div>

                        <form action="index.php?ver=añadir_carrito" method="POST">
                            <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
                            <button type="submit" class="btn-add-cart">
                                Añadir a la Cesta 🛒
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <p style="font-size: 18px; color: #999;">No hay productos que coincidan con tu búsqueda.</p>
                    <a href="index.php?ver=catalogo" style="color: #000; font-weight: 700;">VOLVER A LA TIENDA</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>