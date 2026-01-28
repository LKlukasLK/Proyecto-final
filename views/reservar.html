<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar | Tienda</title>
    <link rel="stylesheet" href="public/css/style.css">
        <?php include 'views/layout/head.html'; ?>

</head>
<body>
    <header>
    <!-- Lado Izquierdo: LOGO -->
    <div class="logo">
        <h1>💈 Barbería Estilo</h1>
    </div>

    <!-- Lado Derecho: NAVEGACIÓN -->
    <nav>
        <a href="index.php?ver=inicio">Inicio</a>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- SI ESTÁ LOGUEADO: Muestra Nombre + Desplegable -->
            <div class="usuario-menu">
                <span>👤 <?php echo $_SESSION['usuario_nombre']; ?> ▼</span>
                
                <div class="contenido-desplegable">
                    <a href="index.php?ver=reservar">📅 Reservar Cita</a>
                    <!-- Aquí podrías poner 'Mis Citas' en el futuro -->
                    <a href="index.php?ver=logout" class="btn-salir">🚪 Cerrar Sesión</a>
                </div>
            </div>

        <?php else: ?>
            <!-- SI NO ESTÁ LOGUEADO: Muestra botones normales -->
            <a href="index.php?ver=login">Iniciar Sesión</a>
            <a href="index.php?ver=registro" style="background: #d4af37; padding: 5px 10px; border-radius: 4px; color: black;">Crear Cuenta</a>
        <?php endif; ?>
    </nav>
</header>

    <main>
        <div class="formulario-caja">
            <form action="index.php?ver=guardar_cita" method="POST">
                
                <label>Selecciona Servicio:</label>
                <select name="servicio" required>
                    <?php foreach ($servicios as $serv): ?>
                        <option value="<?php echo $serv['id']; ?>">
                            <?php echo $serv['nombre'] . " - $" . $serv['precio']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Selecciona Barbero:</label>
                <select name="barbero" required>
                    <?php foreach ($barberos as $barbero): ?>
                        <option value="<?php echo $barbero['id']; ?>">
                            <?php echo $barbero['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Fecha:</label>
                <input type="date" name="fecha" required min="<?php echo date('Y-m-d'); ?>">

                <label>Hora:</label>
                <select name="hora" required>
                    <option value="" disabled selected>-- Selecciona hora --</option>
                    <!-- Mañana -->
                     <option value="" disabled>-- Hora Mañana --</option>
                    <option value="09:00">09:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">01:00 PM</option>
                    <!-- Tarde -->
                     <option value="" disabled>-- Horas Tarde --</option>
                    <option value="15:00">03:00 PM</option>
                    <option value="16:00">04:00 PM</option>
                    <option value="17:00">05:00 PM</option>
                    <option value="18:00">06:00 PM</option>
                    <option value="19:00">07:00 PM</option>
                </select>

                <button type="submit">Confirmar Reserva</button>
            </form>
        </div>
    </main>
</body>
</html>