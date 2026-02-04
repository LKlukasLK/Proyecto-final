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
        <main style="background-color: #f4f4f4; padding: 40px 0;">
    
    <main style="background-color: #f4f4f4; padding: 40px 0;">
    
    <h2 style="text-align: center; font-size: 4rem; color: #222; margin-bottom: 20px; font-family: sans-serif; font-weight: 800;">
        Informacion pagina
    </h2>

    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">

        <div class="tarjeta" style="display: grid; grid-template-columns: 240px 1fr 240px; grid-template-rows: auto auto auto; gap: 25px; width: 100%; max-width: 1300px; background: #fff; padding: 60px; border-radius: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); align-items: center;">

            <div style="grid-column: 2; justify-self: center;">
                <img src="public/img/imagen3.jpg" alt="Superior" style="width: 400px; height: 120px; object-fit: cover; border-radius: 15px;">
            </div>

            <div style="grid-column: 1; grid-row: 1 / 4; height: 100%;">
                <img src="public/img/imagen2.jpg" alt="Izquierda" style="width: 100%; height: 100%; min-height: 600px; object-fit: cover; border-radius: 20px;">
            </div>

            <div style="grid-column: 2; grid-row: 2; text-align: center; padding: 80px 50px; background: #fafafa; border-radius: 30px; border: 1px solid #eee; margin: 20px;">
                
                <h2 style="margin-bottom: 25px; font-size: 4rem; color: #111; font-weight: 700;">Información</h2>
                
                <p style="font-size: 1.6rem; color: #444; line-height: 1.6; font-weight: 400; margin-bottom: 40px;">
                    Este texto es el corazón del diseño. Está rodeado por imágenes que se extienden para crear un marco perfecto. 
                    Ahora todo es mucho más grande y legible para captar la atención de inmediato.
                </p>
                
                <button style="margin-top: 20px; padding: 20px 60px; font-size: 1.8rem; font-weight: bold; cursor: pointer; border-radius: 15px;">
                    Comprar
                </button>
            </div>

            <div style="grid-column: 3; grid-row: 1 / 4; height: 100%;">
                <img src="public/img/imagen4.jpg" alt="Derecha" style="width: 100%; height: 100%; min-height: 600px; object-fit: cover; border-radius: 20px;">
            </div>

            <div style="grid-column: 2; grid-row: 3; justify-self: center;">
                <img src="public/img/imagen1.jpg" alt="Inferior" style="width: 400px; height: 120px; object-fit: cover; border-radius: 15px;">
            </div>

        </div>
    </div>
    </main>

    <footer>
        <p>&copy; 2026 Tienda - Sistema MVC &race;</p>
    </footer>
 <script src="public/js/scrips.js"></script>
</body>
</html>