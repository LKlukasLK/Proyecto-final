<!-- views/registro.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro | Mercado Ropa</title>
    <link rel="stylesheet" href="public/css/style.css">
        <?php include 'views/layout/head.php'; ?>

</head>
<body>
    <header>
        <h1>📝 Crear Cuenta</h1>
        <nav>
            <a href="index.php?ver=inicio">Volver al Inicio</a>
        </nav>
    </header>

    <main>
        <div class="formulario-caja">
            <h2>Regístrate</h2>
            
            <form action="index.php?ver=procesar_registro" method="POST">
                
                <label>Nombre completo:</label>
                <input type="text" name="nombre" placeholder="Tu nombre" required>

                <label>Correo Electrónico:</label>
                <input type="email" name="email" placeholder="ejemplo@correo.com" required>
                
                <label>Contraseña:</label>
                <input type="password" name="password" placeholder="******" required>

                <button type="submit">Crear Cuenta</button>
            
            </form>

            <br>
            <p>¿Ya tienes cuenta? <a href="index.php?ver=login" style="color: #d4af37;">Inicia sesión</a></p>
        </div>
    </main>
</body>
</html>