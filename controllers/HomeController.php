<?php
require_once 'models/ServicioModel.htm';

class HomeController {
    public function index() {
        // 1. Llamar al modelo
        $servicioModel = new ServicioModel();
        $servicios = $servicioModel->obtenerTodos();

        // 2. Cargar la vista y enviarle los datos
        require_once 'views/inicio.html';
    }
}
?>