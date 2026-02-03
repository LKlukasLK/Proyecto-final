<?php
class AdminController {
    public function index() {
        // Verificamos si es admin antes de mostrar nada
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: index.php?ver=login");
            exit();
        }

        // Si es admin, cargamos la vista del panel
        // (Supongamos que tu vista está en views/admin/dashboard.php)
        require_once 'views/admin/dashboard.php';
    }
}