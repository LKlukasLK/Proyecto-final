<?php
// Seguridad: solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}
?>

<div class="form-header-container">
    <h2>Nuevo Usuario</h2>
    <p class="form-subtitle">Crea una nueva cuenta de acceso</p>
</div>

<div class="form-card">
    <form action="../controllers/UsuarioController.php" method="POST">
        <input type="hidden" name="accion" value="crear_usuario">
        
        <div class="form-group">
            <label class="label-style">Nombre Completo</label>
            <input type="text" name="nombre" placeholder="Ej: Lucas" required class="input-underline">
        </div>

        <div class="form-row">
            <div style="flex: 1.5;">
                <label class="label-style">Email</label>
                <input type="email" name="email" placeholder="usuario@gmail.com" required class="input-underline">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Rol</label>
                <select name="rol" required class="input-underline" style="cursor: pointer;">
                    <option value="cliente">Cliente</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="label-style">Contraseña</label>
            <input type="password" name="password" placeholder="••••••••" required class="input-underline">
        </div>

        <button type="submit" class="btn-submit-black">Registrar Usuario</button>

        <a href="index.php?p=usuarios" class="link-back">— Volver al listado</a>
    </form>
</div>
<!-- \\192.168.0.106\pagweb\public\css\style.css -->