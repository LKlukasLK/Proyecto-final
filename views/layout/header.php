<?php
// PHP: Lógica de sesión y detección de página
$nombre_user = $_SESSION['nombre'] ?? '';
$carrito_total = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
?>

<header class="main-header">
    <div class="nav-container">
        <div class="nav-group left">
            <a href="index.php?ver=catalogo" class="nav-btn">TIENDA</a>
            <!-- <button class="nav-btn" onclick="scrollearInfo()">COLECCIÓN</button> -->
        </div>

        <div class="nav-group center">
            <a href="index.php?ver=inicio" class="brand-link">
                <img src="public/img/icono.png" alt="Logo">
                <h1>👕 Mercado Ropa</h1>
            </a>
        </div>

        <div class="nav-group right">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <span class="nav-btn">👤 <?php echo htmlspecialchars($nombre_user); ?></span>
                <a href="index.php?ver=logout" class="nav-btn">🚪 SALIR</a>
            <?php else: ?>
                <a href="index.php?ver=login" class="nav-btn">ACCESO</a>
            <?php endif; ?>
            
            <a href="index.php?ver=carrito" class="nav-btn">
                🛒 CESTA (<?php echo $carrito_total; ?>)
            </a>
        </div>
    </div>
</header>

<script>
    function scrollearInfo() {
        const h2 = document.querySelector('h2');
        if (h2) window.scrollTo({ top: h2.offsetTop - 100, behavior: 'smooth' });
    }
</script>
<div style="height: 80px;"></div>