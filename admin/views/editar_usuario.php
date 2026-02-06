<?php
// 1. Conexión y seguridad (al igual que en gestion_usuarios)
require_once __DIR__ . '/../../config/db.php'; 
$db = Database::conectar();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID de usuario no proporcionado.";
    exit;
}

// 2. Obtener los datos actuales del usuario
$stmt = $db->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<div class="form-container">
    <h2><i class="fa-solid fa-user-pen"></i> Editar Usuario #<?php echo $usuario['id_usuario']; ?></h2>
    
    <form action="../controllers/UsuarioController.php" method="POST" class="form-admin">
        
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
        <input type="hidden" name="accion" value="modificar_usuario">

        <div class="grupo-input">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>

        <div class="grupo-input">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>

        <div class="grupo-input">
            <label>Rol del Sistema:</label>
            <select name="rol">
                <option value="cliente" <?php echo ($usuario['rol'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                <option value="admin" <?php echo ($usuario['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>

        <div class="grupo-input">
            <label>Nueva Contraseña (dejar en blanco para mantener la actual):</label>
            <input type="password" name="password" placeholder="********">
        </div>

        <div class="botones-form">
            <button type="submit" class="btn-save">Actualizar Usuario</button>
            <a href="index.php?p=gestion_usuarios" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>