<?php
// Seguridad y carga de datos
$id_usuario = $_GET['id'] ?? null;

if (!$id_usuario) {
    echo "<script>window.location.href='index.php?p=usuarios';</script>";
    exit;
}

// Consulta usando la conexión del index
$stmt = $conexion->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) { die("Usuario no encontrado."); }
?>

<div class="form-header-container">
    <h2>Editar Usuario</h2>
    <p class="form-subtitle">Modificando perfil de: #<?= $u['id_usuario'] ?></p>
</div>

<div class="form-card">
    <form action="../controllers/UsuarioController.php" method="POST">
        <input type="hidden" name="accion" value="modificar_usuario">
        <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
        
        <div class="form-group">
            <label class="label-style">Nombre Completo</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($u['nombre']) ?>" required class="input-underline">
        </div>

        <div class="form-row">
            <div style="flex: 1.5;">
                <label class="label-style">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required class="input-underline">
            </div>
            <div style="flex: 1;">
                <label class="label-style">Rol</label>
                <select name="rol" required class="input-underline" style="cursor: pointer;">
                    <option value="cliente" <?= $u['rol'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="admin" <?= $u['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label class="label-style">Nueva Contraseña</label>
            <input type="password" name="password" placeholder="Dejar en blanco para no cambiar" class="input-underline">
        </div>

        <button type="submit" class="btn-submit-black">Guardar Cambios</button>

        <a href="index.php?p=usuarios" class="link-back">— Volver al listado</a>
    </form>
</div>