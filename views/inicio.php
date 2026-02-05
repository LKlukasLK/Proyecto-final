<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="public/css/style.css">
    <?php include 'views/layout/head.php'; ?>
</head>
<body>

    <?php include 'views/layout/header.php'; ?>

    <main class="info-page-main">

        <div class="info-page-header-wrapper">
            <div class="info-page-skew-bar">
                <h2>Información página</h2>
            </div>
        </div>

        <div class="info-page-intro">
            <p>Texto información página, este texto fluye naturalmente debajo de la gran franja negra inclinada.</p>
        </div>

        <div class="info-page-image-box">
            <img src="public/img/imagen3.jpg" alt="Superior">
        </div>

        <div class="info-page-section-center">
            <h2>Información</h2>
            <p>
                Este texto ahora tiene un tamaño más balanceado. Al reducir las dimensiones y quitar las cajas, 
                el diseño se vuelve más sofisticado y fácil de leer, permitiendo que las imágenes sigan siendo 
                el marco visual principal.
            </p>
            <a href="index.php?ver=catalogo" class="info-page-button">Comprar</a>
        </div>

        <div class="info-page-image-box">
            <img src="public/img/imagen1.jpg" alt="Inferior">
        </div>

    </main>

    <footer>
        <p>&copy; 2026 Tienda - Sistema MVC &race;</p>
    </footer>

    <script src="public/js/scrips.js"></script>
</body>
</html>