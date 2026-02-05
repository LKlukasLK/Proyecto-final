<?php
require_once 'models/UsuarioModel.php';
require_once 'models/ProductoModel.php'; // Necesario para recuperar el carrito

class LoginController
{
    public function index()
    {
        require_once 'views/login.php';
    }

    public function login()
    {
        // Aseguramos que la sesión esté activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['contrasena'] ?? '';

        $modelo = new UsuarioModel();
        $usuario = $modelo->verificarUsuario($email, $password);

        if ($usuario) {
            // 1. Guardar datos básicos en la sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = trim($usuario['rol']);

            // 2. SINCRONIZACIÓN: Cargar carrito persistente de la BD
            $modeloProd = new ProductoModel();
            $itemsGuardados = $modeloProd->obtenerCarritoUsuario($_SESSION['id_usuario']);

            // Inicializamos el carrito de sesión si no existe
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            // Volcamos los productos de detalles_carrito a la sesión
            foreach ($itemsGuardados as $item) {
                $_SESSION['carrito'][] = [
                    'id'     => $item['id_producto'],
                    'nombre' => $item['nombre'],
                    'precio' => $item['precio'],
                    'imagen' => $item['imagen_url'] ?? null
                ];
            }

            // 3. Redirección según rol
            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php?ver=inicio");
            }
            exit();
        } else {
            header("Location: index.php?ver=login&error=1");
            exit();
        }
    }
}