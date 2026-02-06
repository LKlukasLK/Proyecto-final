<?php
// 1. Conexión (Asegúrate de que la ruta a db.php es correcta)
require_once '../config/db.php';
$db = Database::conectar();

// 2. Lógica de guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    $nombre = $_POST['nombre'] ?? '';
    $biografia = $_POST['biografia'] ?? '';
    $web_url = $_POST['web_url'] ?? '';

    try {
        $sql = "INSERT INTO Disenadores (nombre, biografia, web_url) VALUES (:nom, :bio, :url)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom' => $nombre,
            ':bio' => $biografia,
            ':url' => $web_url
        ]);
        echo "<script>window.location.href='index.php?p=gestion_disenador';</script>";
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<style>
    .admin-wrapper {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 50px;
    }
    .form-card {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .form-card h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #1a1a1a;
        font-size: 1.8rem;
    }
    .input-group {
        margin-bottom: 20px;
    }
    .input-group label {
        display: block;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        margin-bottom: 8px;
    }
    .input-group input, .input-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
        transition: border-color 0.3s;
    }
    .input-group input:focus {
        border-color: #000;
    }
    .btn-save {
        width: 100%;
        background: #000;
        color: #fff;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-save:hover { background: #333; }
    .btn-back {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #999;
        text-decoration: none;
        font-size: 13px;
    }
</style>

<div class="admin-wrapper">
    <div class="form-card">
        <h2>Nuevo Diseñador</h2>

        <?php if(isset($error)): ?>
            <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:5px; margin-bottom:20px;">
                Error: <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?p=nuevo_disenador&accion=guardar" method="POST">
            <div class="input-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" required placeholder="Nombre del diseñador">
            </div>

            <div class="input-group">
                <label>Sitio Web Oficial</label>
                <input type="url" name="web_url" placeholder="https://ejemplo.com">
            </div>

            <div class="input-group">
                <label>Biografía / Historia</label>
                <textarea name="biografia" rows="5" placeholder="Cuéntanos sobre el diseñador..."></textarea>
            </div>

            <button type="submit" class="btn-save">Guardar Diseñador</button>
            <a href="index.php?p=gestion_disenador" class="btn-back">← Volver al listado</a>
        </form>
    </div>
</div>