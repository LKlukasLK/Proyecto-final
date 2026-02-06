<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro | Mercado Ropa</title>
    <link rel="stylesheet" href="public/css/style.css?v=2.1">
    <?php include 'views/layout/head.php'; ?>
</head>
<body class="login-full-layout">

    <header class="header-full">
        <div class="header-content">
            <div class="login-title-row">
                <h1>Crear Cuenta</h1>
            </div>
            <nav class="nav-simple">
                <a href="index.php?ver=inicio">Volver al Inicio</a>
            </nav>
        </div>
    </header>

    <main class="main-centered">
        <div class="login-card">
            <h2 class="section-title">Regístrate</h2>
            
            <form action="index.php?ver=procesar_registro" method="POST" class="form-login">
                
                <div class="field">
                    <label>Nombre completo:</label>
                    <input type="text" name="nombre" placeholder="Tu nombre" required>
                </div>

                <div class="field">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" placeholder="jose@correo.com" required>
                </div>
                
                <div class="field">
                    <label>Contraseña:</label>
                    <input type="password" name="contrasena" placeholder="******" required>
                </div>

                <button type="submit" class="btn-black">Crear Cuenta</button>
            </form>

            <div class="extra-actions">
                <p>¿Ya tienes cuenta? <a href="index.php?ver=login" class="link-register">Inicia sesión</a></p>
            </div>
        </div>
    </main>
</body>
</html>