<?php
// Lógica de guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'guardar') {
    try {
        $sql = "INSERT INTO Disenadores (nombre, biografia, web_url) VALUES (:nom, :bio, :url)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nom' => $_POST['nombre'],
            ':bio' => $_POST['biografia'],
            ':url' => $_POST['web_url']
        ]);

        echo "<script>window.location.href='index.php?p=gestion_disenador';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<div style='color:red; text-align:center; padding:10px;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="titulo-seccion-admin">
    <h2>Nuevo Diseñador</h2>
</div>

<div class="card-admin-disenador">
    <form action="index.php?p=nuevo_disenador&accion=guardar" method="POST">
        
        <div class="form-group-admin">
            <label>Nombre</label>
            <input type="text" name="nombre" class="input-admin-minimal" required>
        </div>

        <div class="form-group-admin">
            <label>URL Web</label>
            <input type="url" name="web_url" class="input-admin-minimal">
        </div>

        <div class="form-group-admin">
            <label>Biografía</label>
            <textarea name="biografia" rows="4" class="textarea-admin-minimal"></textarea>
        </div>

        <button type="submit" class="btn-admin-guardar">Guardar Diseñador</button>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php?p=gestion_disenador" style="color: #bbb; text-decoration: none; font-size: 11px;">— Volver al listado</a>
        </div>
    </form>
</div>