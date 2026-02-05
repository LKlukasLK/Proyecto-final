<?php
require_once 'models/ProductoModel.php';

class CatalogoController {
    
    // Nueva función privada para no repetir código
    private function limpiarSesionAntigua() {
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            $segundos_en_30_dias = 15;
            $ahora = time();

            foreach ($_SESSION['carrito'] as $indice => $item) {
                // Si el item tiene fecha y superó los 30 días, lo borramos de la sesión
                if (isset($item['fecha']) && ($ahora - $item['fecha'] > $segundos_en_30_dias)) {
                    unset($_SESSION['carrito'][$indice]);
                }
            }
            // Reindexamos para que los índices (0, 1, 2...) sigan en orden
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }
    }

    public function verCatalogo() {
        $modelo = new ProductoModel();
        
        // 1. Limpiamos la Base de Datos
        $modelo->limpiarCarritoAntiguo();

        // 2. Limpiamos la Sesión del navegador
        $this->limpiarSesionAntigua();

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        if ($q !== '') {
            $productos = $modelo->buscarPorNombre($q);
        } else {
            $productos = $modelo->obtenerTodos();
        }
        require_once 'views/catalogo.php';
    }

    // Si tienes una función para ver el carrito en este mismo controlador:
    public function verCarrito() {
        $this->limpiarSesionAntigua(); // Limpiamos antes de mostrar la vista
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
                
                // Guardamos la fecha actual con time()
                $_SESSION['carrito'][] = [
                    'id'     => $producto['id_producto'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen_url'] ?? null,
                    'fecha'  => time() 
                ];

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