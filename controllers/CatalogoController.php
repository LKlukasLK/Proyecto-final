<?php
require_once 'models/ProductoModel.php';

class CatalogoController {
    
    // Se ha eliminado la función limpiarSesionAntigua para que los productos no caduquen.

    public function verCatalogo() {
        $modelo = new ProductoModel();
        
        // Eliminada la llamada a limpiarCarritoAntiguo para mantener la persistencia total.

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        if ($q !== '') {
            $productos = $modelo->buscarPorNombre($q);
        } else {
            $productos = $modelo->obtenerTodos();
        }
        require_once 'views/catalogo.php';
    }

    public function verCarrito() {
        // Ahora simplemente carga la vista sin realizar comprobaciones de tiempo.
        require_once 'views/carrito.php';
    }

    public function añadirAlCarrito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
            $id = intval($_POST['id_producto']);
            $modelo = new ProductoModel();
            $producto = $modelo->obtenerPorId($id); 

            if ($producto) {
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }
                
                // Añadimos el producto a la sesión (hemos quitado el campo 'fecha' porque ya no es necesario).
                $_SESSION['carrito'][] = [
                    'id'     => $producto['id_producto'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen_url'] ?? null
                ];

                // Guardamos en la base de datos para usuarios registrados.
                if (isset($_SESSION['id_usuario'])) {
                    $modelo->guardarEnDB($_SESSION['id_usuario'], $id);
                }
            }
        }
        header("Location: index.php?ver=catalogo");
        exit();
    }

    public function eliminarDelCarrito() {
        if (isset($_GET['id'])) {
            $indice = $_GET['id'];
            
            if (isset($_SESSION['carrito'][$indice])) {
                $id_producto = $_SESSION['carrito'][$indice]['id'];
                $modelo = new ProductoModel();

                if (isset($_SESSION['id_usuario'])) {
                    $modelo->eliminarDeDB($_SESSION['id_usuario'], $id_producto);
                }

                unset($_SESSION['carrito'][$indice]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
        }
        header("Location: index.php?ver=carrito");
        exit();
    }
}