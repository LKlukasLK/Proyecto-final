<?php
require_once '../config/db.php';

if (isset($_POST['btn_interesa'])) {
    $id_producto = $_POST['id_producto'];
    $email = $_POST['email'];

    $db = Database::conectar();

    // 1. Verificar si el usuario ya está en la lista para ese producto
    $check = $db->prepare("SELECT id FROM lista_espera WHERE id_producto = ? AND email = ? AND estado = 'activo'");
    $check->execute([$id_producto, $email]);

    if ($check->rowCount() == 0) {
        // 2. Insertar en la tabla lista_espera
        $insert = $db->prepare("INSERT INTO lista_espera (id_producto, email) VALUES (?, ?)");
        $insert->execute([$id_producto, $email]);
        
        header("Location: ../index.php?msg=te_avisaremos");
    } else {
        header("Location: ../index.php?msg=ya_suscrito");
    }
}