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
        echo "<div style='color:red; text-align:center;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    .wrapper-disenador {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 40px 20px;
    }
    .titulo-nuevo {
        text-align: center;
        margin-bottom: 30px;
        color: #1a1a1a;
        font-size: 24px;
        font-weight: 600;
    }
    .form-container-dis {
        background: #ffffff;
        border-radius: 40px; /* Bordes como en tu imagen de productos */
        padding: 45px;
        max-width: 450px;
        margin: 0 auto;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }
    .grupo-input {
        margin-bottom: 25px;
    }
    .grupo-input label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #555;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }
    .grupo-input input {
        width: 100%;
        border: none;
        border-bottom: 2px solid #eee;
        padding: 10px 0;
        outline: none;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    .grupo-input input:focus {
        border-bottom-color: #000;
    }
    .grupo-input textarea {
        width: 100%;
        border: 1px solid #eee;
        border-radius: 15px;
        padding: 15px;
        font-size: 14px;
        resize: none;
        background: #fafafa;
        outline: none;
    }
    .btn-guardar-dis {
        width: 100%;
        background: #000;
        color: #fff;
        border: none;
        padding: 18px;
        border-radius: 15px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        margin-top: 10px;
        transition: opacity 0.3s;
    }
    .btn-guardar-dis:hover {
        opacity: 0.8;
    }
    .link-cancelar {
        display: block;
        text-align: center;
        margin-top: 25px;
        color: #bbb;
        text-decoration: none;
        font-size: 12px;
    }
</style>

<div class="wrapper-disenador">
    <div class="titulo-nuevo">
        <h2>Nuevo Diseñador</h2>
    </div>

    <div class="form-container-dis">
        <form action="index.php?p=nuevo_disenador&accion=guardar" method="POST">
            
            <div class="grupo-input">
                <label>Nombre del Artista</label>
                <input type="text" name="nombre" required placeholder="Ej: Gianni Versace">
            </div>

            <div class="grupo-input">
                <label>Enlace Web Personal</label>
                <input type="url" name="web_url" placeholder="https://www.versace.com">
            </div>

            <div class="grupo-input">
                <label>Biografía e Historia</label>
                <textarea name="biografia" rows="5" placeholder="Escribe aquí la trayectoria..."></textarea>
            </div>

            <button type="submit" class="btn-guardar-dis">Guardar y Registrar</button>

            <a href="index.php?p=gestion_disenador" class="link-cancelar">— Volver a la gestión —</a>
        </form>
    </div>
</div>