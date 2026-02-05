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

    <<footer class="main-footer">
    <div class="footer-columns">
        <div class="footer-column">
            <h3>Recursos</h3>
            <ul>
                <li><a href="#">Tarjetas de regalo</a></li>
                <li><a href="#">Tarjetas de regalo corporativas</a></li>
                <li><a href="#">Buscar una tienda</a></li>
                <li><a href="#">Nike Journal</a></li>
                <li><a href="#">Hazte Member</a></li>
                <li><a href="#">Comentarios</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Ayuda</h3>
            <ul>
                <li><a href="#">Obtener ayuda</a></li>
                <li><a href="#">Estado del pedido</a></li>
                <li><a href="#">Envíos y entregas</a></li>
                <li><a href="#">Devoluciones</a></li>
                <li><a href="#">Opciones de pago</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Empresa</h3>
            <ul>
                <li><a href="#">Acerca de Nike</a></li>
                <li><a href="#">Novedades</a></li>
                <li><a href="#">Empleo</a></li>
                <li><a href="#">Inversores</a></li>
                <li><a href="#">Sostenibilidad</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Descuentos de la comunidad</h3>
            <ul>
                <li><a href="#">Estudiante</a></li>
                <li><a href="#">Docente</a></li>
                <li><a href="#">Servicios de emergencias</a></li>
                <li><a href="#">Atención sanitaria</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <span class="footer-copy">© 2026 Nike, Inc. Todos los derechos reservados</span>
        <div class="footer-legal-links">
            <a href="#">Guías</a>
            <a href="#">Términos de uso</a>
            <a href="#">Términos de venta</a>
            <a href="#">Aviso legal</a>
            <a href="#">Política de privacidad y cookies</a>
            <a href="#">Configuración de privacidad y cookies</a>
        </div>
    </div>
</footer>

    <script src="public/js/scrips.js"></script>
</body>
</html>