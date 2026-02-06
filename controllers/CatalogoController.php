<?php
require_once 'models/ProductoModel.php';

class CatalogoController {
    
    public function verCatalogo() {
        $modelo = new ProductoModel();
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        if ($q !== '') {
            $productos = $modelo->buscarPorNombre($q);
        } else {
            $productos = $modelo->obtenerTodos();
        }
        require_once 'views/catalogo.php';
    }

    public function verDisenadores() {
        $modelo = new ProductoModel();
        // Traemos los datos de la tabla 'disenadores'
        $productos = $modelo->obtenerDisenadores(); 
        
        require_once 'views/disenadores.php';
    }

    public function verCarrito() {
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
                
                $_SESSION['carrito'][] = [
                    'id'     => $producto['id_producto'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen_url'] ?? null
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

    public function marcarFavorito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
            $id_p = intval($_POST['id_producto']);
            
            $email = $_SESSION['email'] ?? 'usuario_interesado@tienda.com'; 

            $modelo = new ProductoModel();
            $modelo->unirseAListaEspera($id_p, $email);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}