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

    <main style="background-color: #fff; margin: 0; padding: 0; font-family: sans-serif;">
<div style="position: relative; width: 100%; padding: 60px 0; overflow: hidden;">
        
        <div style="
            width: 110%;             /* Un poco más ancha para cubrir los bordes al girar */
            margin-left: -5%;        /* La centramos para que no se salga de un solo lado */
            background-color: #000; 
            padding: 15px 0;         /* Padding reducido para que no sea tan alta */
            transform: rotate(-2deg); /* Inclinación suave para que no se salga tanto */
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        ">
            <h2 style="
                font-size: 5vw;      /* Tamaño ajustado para que no sea excesivo */
                color: #fff; 
                margin: 0; 
                text-transform: uppercase;
                font-weight: 800; 
                letter-spacing: 1px;
                white-space: nowrap;
            ">
                Información página
            </h2>
        </div>
    </div>
    <div style="text-align: center; padding: 60px 20px;">
        <p style="max-width: 800px; margin: 0 auto; font-size: 1.2rem; color: #555; line-height: 1.6;">
            Texto información página, este texto fluye naturalmente debajo de la gran franja negra inclinada.
        </p>
    </div>

    <div style="width: 100%; height: 50vh; overflow: hidden;">
        <img src="public/img/imagen3.jpg" alt="Superior" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <div style="max-width: 800px; margin: 0 auto; padding: 80px 20px; text-align: center;">
        <h2 style="font-size: 2.2rem; color: #111; margin-bottom: 20px; font-weight: 700;">
            Información
        </h2>
        <p style="font-size: 1.1rem; color: #555; line-height: 1.6; margin-bottom: 35px;">
            Este texto ahora tiene un tamaño más balanceado. Al reducir las dimensiones y quitar las cajas, 
            el diseño se vuelve más sofisticado y fácil de leer, permitiendo que las imágenes sigan siendo 
            el marco visual principal.
        </p>
        
        <a href="index.php?ver=catalogo" style="
            display: inline-block; 
            background: #000; 
            color: #fff; 
            padding: 12px 40px; 
            font-size: 1rem; 
            font-weight: 600; 
            border-radius: 8px; 
            text-decoration: none;
            transition: opacity 0.3s ease;">
            Comprar
        </a>
    </div>

    <div style="width: 100%; height: 50vh; overflow: hidden;">
        <img src="public/img/imagen1.jpg" alt="Inferior" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

</main>

    <footer>
        <p>&copy; 2026 Tienda - Sistema MVC &race;</p>
    </footer>
 <script src="public/js/scrips.js"></script>
</body>
</html>