<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Tienda</title>
    <link rel="stylesheet" href="public/css/style.css?v=1.4">
</head>
<body class="login-full-layout">

   <header class="header-full">
    <div class="header-content">
        <div class="login-title-row">
            <h1>Iniciar Sesión</h1>
        </div>
        <nav class="nav-simple">
            <a href="index.php?ver=inicio">Volver al Inicio</a>
        </nav>
    </div>
</header>

<main class="main-centered">
    <div class="login-card">
        <h2 class="section-title">Acceso Clientes</h2>
        
        <?php if (isset($_GET['error']) && ($_GET['error'] == '1' || $_GET['error'] == 'si')): ?>
            <div class="alerta alerta-error">
                <i class="fa-solid fa-circle-exclamation"></i> 
                El correo electrónico o la contraseña no son correctos.
            </div>
        <?php endif; ?>

        <form action="index.php?ver=autenticar" method="POST" class="form-login">
            <div class="field">
                <label>Correo Electrónico:</label>
                <input type="email" name="email" placeholder="ejemplo@correo.com" required>
            </div>
            
            <div class="field">
                <label>Contraseña:</label>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn-black">Entrar</button>
        </form>

        <div class="extra-actions">
            <a href="index.php?ver=registro" class="link-register">¿No tienes cuenta? Regístrate aquí</a>
        </div>
        
        <div class="test-info-box">
            <span class="label">Datos de prueba:</span>
            <p>admin@gmail.com | 123</p>
        </div>
    </div>
</main>

</body>
</html>