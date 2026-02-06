<?php
// Usamos la conexión que ya viene abierta desde index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    $nombre = $_POST['nombre'] ?? '';
    $biografia = $_POST['biografia'] ?? '';
    $web_url = $_POST['web_url'] ?? '';

    try {
        $sql = "INSERT INTO Disenadores (nombre, biografia, web_url) VALUES (:nom, :bio, :url)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nom' => $nombre,
            ':bio' => $biografia,
            ':url' => $web_url
        ]);

        echo "<script>window.location.href='index.php?p=gestion_disenador';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<div style='color:red; text-align:center;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div style="background-color: #f8f9fa; min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
    
    <h2 style="font-family: sans-serif; font-weight: 600; margin-bottom: 25px; color: #1a1a1a;">Nuevo Diseñador</h2>

    <div style="background: #ffffff; border-radius: 35px; padding: 50px; width: 100%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        
        <form action="index.php?p=nuevo_disenador&accion=guardar" method="POST">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 10px;">Nombre</label>
                <input type="text" name="nombre" required placeholder="Ej: Gianni Versace" 
                       style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 10px 0; outline: none; font-size: 15px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 10px;">URL Sitio Web</label>
                <input type="url" name="web_url" placeholder="https://www.ejemplo.com" 
                       style="width: 100%; border: none; border-bottom: 1px solid #e0e0e0; padding: 10px 0; outline: none; font-size: 15px;">
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #444; text-transform: uppercase; margin-bottom: 10px;">Biografía / Historia</label>
                <textarea name="biografia" rows="3" placeholder="Breve descripción..." 
                          style="width: 100%; border: 1px solid #f0f0f0; border-radius: 12px; padding: 12px; outline: none; font-family: sans-serif; font-size: 14px; resize: none;"></textarea>
            </div>

            <button type="submit" style="width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 16px; border-radius: 15px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.3s;">
                Guardar Diseñador
            </button>

            <a href="index.php?p=gestion_disenador" style="display: block; text-align: center; margin-top: 25px; color: #bbb; text-decoration: none; font-size: 12px;">
                — Volver al listado
            </a>
        </form>
    </div>
</div>