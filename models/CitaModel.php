<?php
require_once 'config/db.php';

class CitaModel {
    public function crearCita($usuario_id, $barbero_id, $servicio_id, $fecha_hora) {
        $pdo = Database::conectar();
        $sql = "INSERT INTO citas (usuario_id, barbero_id, servicio_id, fecha_hora) VALUES (:uid, :bid, :sid, :fecha)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'uid' => $usuario_id,
            'bid' => $barbero_id,
            'sid' => $servicio_id,
            'fecha' => $fecha_hora
        ]);
    }
    public function verificarDisponibilidad($barbero_id, $fecha_hora) {
    $pdo = Database::conectar();
    
    // Buscamos si hay alguna cita con ese barbero a esa hora exacta
    // NOTA: Para un sistema real, aquí también deberíamos calcular la duración del servicio
    $sql = "SELECT id FROM citas WHERE barbero_id = :bid AND fecha_hora = :fecha AND estado != 'cancelada'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'bid' => $barbero_id,
        'fecha' => $fecha_hora
    ]);
    
    // Si encuentra algo devuelve TRUE (está ocupado), si no, FALSE (está libre)
    return $stmt->fetch(); 
}
}
?>