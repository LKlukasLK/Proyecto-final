<?php
require_once 'models/BarberoModel.php';
require_once 'models/ServicioModel.php';
require_once 'models/CitaModel.php';

class CitaController {
    public function index() {
        // Verificar si está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?ver=login");
            exit;
        }

        // Cargar datos para los desplegables (Selects)
        $barberosModel = new BarberoModel();
        $barberos = $barberosModel->obtenerTodos();

        $serviciosModel = new ServicioModel();
        $servicios = $serviciosModel->obtenerTodos();

        require_once 'views/reservar.html';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
            $citaModel = new CitaModel();
            
            $barbero_id = $_POST['barbero'];
            $servicio_id = $_POST['servicio'];
            // Añadimos ':00' segundos al final para que coincida con el formato SQL
            $fecha_hora = $_POST['fecha'] . ' ' . $_POST['hora'] . ':00';
            
            // --- 1. VERIFICACIÓN DE SEGURIDAD ---
            $estaOcupado = $citaModel->verificarDisponibilidad($barbero_id, $fecha_hora);

            if ($estaOcupado) {
                // Si está ocupado, avisamos y no guardamos nada
                echo "<script>
                        alert('❌ Lo sentimos, ese barbero ya tiene una cita a esa hora. Por favor elige otra.'); 
                        window.location.href='index.php?ver=reservar';
                    </script>";
            } else {
                // --- 2. SI ESTÁ LIBRE, GUARDAMOS ---
                $citaModel->crearCita(
                    $_SESSION['usuario_id'],
                    $barbero_id,
                    $servicio_id,
                    $fecha_hora
                );

                echo "<script>
                        alert('✅ ¡Cita reservada con éxito!'); 
                        window.location.href='index.php';
                    </script>";
            }
        }
    }
}   
?>