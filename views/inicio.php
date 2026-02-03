<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <!-- Vinculamos el CSS -->
    <link rel="stylesheet" href="public/css/style.css">
    <?php include 'views/layout/head.php'; ?>
</head>
<body>

    <?php include 'views/layout/header.php'; ?>

    <main>
        <div class="carousel-container">
            <!-- Contenedor de las fotos -->
            <div class="carousel-track" id="carousel-track">
                <div class="carousel-slide"><img src="public/img/imagen1.jpg" alt="Oferta 1"></div>
                <div class="carousel-slide"><img src="public/img/imagen2.jpg" alt="Oferta 2"></div>
                <div class="carousel-slide"><img src="public/img/imagen3.jpg" alt="Oferta 3"></div>
                <div class="carousel-slide"><img src="public/img/imagen4.jpg" alt="Oferta 4"></div>
                <div class="carousel-slide"><img src="public/img/imagen5.jpg" alt="Oferta 5"></div>
            </div>
        </div>
        <h2>Productos Recomendados</h2>
        <div class="grid-servicios">
            <?php if (empty($servicios)): ?>
                <p>No hay productos disponibles en este momento.</p>
            <?php else: ?>
                <!-- Bucle para mostrar cada producto de la Base de Datos (máximo 6) -->
                <?php foreach (array_slice($servicios, 0, 6) as $servicio): ?>
                    <div class="tarjeta">
                        <h3><?php echo htmlspecialchars($servicio['nombre']); ?></h3>
                        <p class="desc"><?php echo htmlspecialchars($servicio['descripcion']); ?></p>
                        <img src="public/img/<?php echo htmlspecialchars($servicio['imagen_url']); ?>" alt="<?php echo htmlspecialchars($servicio['nombre']); ?>">
                        <br>
                        <p class="precio">$<?php echo number_format($servicio['precio'], 2); ?></p>
                        <button>Comprar</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Tienda - Sistema MVC &race;</p>
    </footer>
 <script src="public/js/scrips.js"></script>
</body>
</html>