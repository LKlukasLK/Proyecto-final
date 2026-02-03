<header>
    <div class="box_reg">
        <?php if (isset($_SESSION['id_usuario'])): ?>
        <!-- SI ESTÁ LOGUEADO: Muestra Nombre + Desplegable -->
        <div class="usuario-menu">
            <span>👤
                <?php echo $_SESSION['nombre']; ?> ▼
            </span>

            <div class="contenido-desplegable">
                <a href="index.php?ver=reservar">📅 Reservar Cita</a>
                <a href="index.php?ver=carrito">🛒 Carrito</a>
                <!-- Aquí podrías poner 'Mis Citas' en el futuro -->
                <a href="index.php?ver=logout" class="btn-salir">🚪 Cerrar Sesión</a>
            </div>
        </div>
        <?php else: ?>
        <!-- SI NO ESTÁ LOGUEADO: Muestra botones normales -->
        <a href="index.php?ver=login">Iniciar Sesión</a>
        <?php endif; ?>

        <img src="public/img/icono.png" alt="Logo" class="logo-header">
        <h1>👕 Mercado Ropa</h1>
    </div>
    <nav>
        <a href="index.php?ver=catalogo"> Catalogo</a>
    </nav>
</header>