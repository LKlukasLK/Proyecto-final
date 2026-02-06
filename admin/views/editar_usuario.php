<?php
// 1. Conexión y obtención de datos
require_once __DIR__ . '/../../config/db.php'; 
$db = Database::conectar();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alerta-error'>ID de usuario no proporcionado.</div>";
    exit;
}

$stmt = $db->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<div class='alerta-error'>Usuario no encontrado.</div>";
    exit;
}
?>

<div class="main-centered">
    <div class="login-card">
        <h2 class="section-title">Editar Usuario</h2>
        <p style="font-size: 12px; color: #888; margin-bottom: 20px; text-transform: uppercase;">
            ID de Registro: #<?php echo $usuario['id_usuario']; ?>
        </p>

        <form action="../controllers/UsuarioController.php" method="POST">
            
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
            <input type="hidden" name="accion" value="modificar_usuario">

            <div class="field">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="field">
                <label>Correo Electrónico</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="field">
                <label>Rol del Sistema</label>
                <select name="rol" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    <option value="cliente" <?php echo ($usuario['rol'] == 'cliente') ? 'selected' : ''; ?>>CLIENTE</option>
                    <option value="admin" <?php echo ($usuario['rol'] == 'admin') ? 'selected' : ''; ?>>ADMINISTRADOR</option>
                </select>
            </div>

            <div class="field">
                <label>Nueva Contraseña</label>
                <input type="password" name="password" placeholder="Dejar en blanco para no cambiar">
            </div>

            <button type="submit" class="btn-black">Guardar Cambios</button>
            
            <div class="extra-actions">
                <a href="index.php?p=usuarios" class="link-register">← Volver al listado</a>
            </div>
        </form>
    </div>
</div>