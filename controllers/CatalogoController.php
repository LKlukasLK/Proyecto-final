<?php
require_once 'models/ProductoModel.php';

class CatalogoController {
    public function verCatalogo() {
        // Instanciamos el modelo
        $modelo = new ProductoModel();

        // Capturamos la búsqueda
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Decidimos qué datos pedir
        if ($q !== '') {
            $productos = $modelo->buscarPorNombre($q);
        } else {
            $productos = $modelo->obtenerTodos();
        }

        // Cargamos la vista. La variable $productos estará disponible en catalogo.php
        require_once 'views/catalogo.php';
    }
}