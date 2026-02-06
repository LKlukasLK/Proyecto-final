<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diseñadores | Mercado Ropa</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include 'views/layout/header.php'; ?>

    <main class="catalogo-container">
        <header class="catalogo-header">
            <h2>NUESTROS DISEÑADORES</h2>
        </header>

        <div class="productos-grid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): ?>
                    <div class="card-producto">
                        <div class="card-imagen-wrapper">
                             <span style="font-size: 60px;">👤</span>
                        </div>
                        <h3 class="card-titulo"><?php echo $p['nombre']; ?></h3>
                        <p class="card-descripcion"><?php echo $p['descripcion']; ?></p>
                        <button class="btn-add-cart">Ver Portfolio 🎨</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay diseñadores disponibles.</p>
            <?php endif; ?>
        </div>
    </main>

    <a href="index.php?ver=inicio" class="btn-pagina-inicio">
        VOLVER AL INICIO
    </a>

</body>
</html>