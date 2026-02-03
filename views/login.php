<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Tienda</title>
    <link rel="stylesheet" href="public/css/style.css">


</head>
<body>
    <header>
        <h1>🔐 Iniciar Sesión</h1>
        <nav>
            <a href="index.php?ver=inicio">Volver al Inicio me cago en dios</a>
        </nav>
    </header>

    <main>
        <!-- Caja del Formulario -->
        <div class="formulario-caja">
            <h2>Acceso Clientes</h2>
            
             <!-- BLOQUE DE MENSAJES -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alerta alerta-error">
                <i class="fa-solid fa-circle-exclamation"></i> 
                El correo electrónico o la contraseña no son correctos.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
            <div class="alerta alerta-exito">
                <i class="fa-solid fa-circle-check"></i> 
                ¡Registro completado! Ya puedes iniciar sesión.
            </div>
        <?php endif; ?>
        <!-- FIN BLOQUE DE MENSAJES -->

        <form action="index.php?ver=autenticar" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com" required>
            
            <label>Contraseña:</label>
            <input type="password" name="contrasena" placeholder="Contraseña" required>

            <button type="submit">Entrar</button>
        </form>
            </form>

            <br>
            <button><a href="index.php?ver=registro">Registrarse</a></button>
            
            <!-- Datos de prueba para que no se te olviden -->
            <div style="background: #444; padding: 10px; margin-top: 20px; font-size: 0.9em;">
                <strong>Datos de prueba:</strong><br>
                User: admin@tienda.com<br>
                Pass: 123
                <br>
                <br>
                User: juan@correo.com<br>
                Pass: 123
            </div>
        </div>
    </main>
</body>
</html>