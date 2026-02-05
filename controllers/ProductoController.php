<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/gestion_mensajeria.php';

class ProductoController {

    // 1. LISTAR PRODUCTOS 
    public function listar() {
    $db = Database::conectar();
    $sql = "SELECT p.*, c.nombre AS categoria_nombre, d.nombre AS disenador_nombre 
            FROM Productos p 
            LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN Disenadores d ON p.id_disenador = d.id_disenador";
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

    // 2. OBTENER UN PRODUCTO POR ID
    public function obtenerPorId($id) {
        $db = Database::conectar();
        $stmt = $db->prepare("SELECT * FROM Productos WHERE id_producto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. CREAR PRODUCTO
    public function crear($datos) {
        $db = Database::conectar();
        $sql = "INSERT INTO Productos (nombre, descripcion, precio, stock, imagen_url, id_categoria) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $datos['nombre'], 
            $datos['descripcion'], 
            $datos['precio'], 
            $datos['stock'], 
            $datos['imagen_url'], 
            $datos['id_categoria']
        ]);
    }

    // 4. ACTUALIZAR PRODUCTO 
    public function actualizar($id, $datos) {
        $db = Database::conectar();
        
        // Consultamos el stock anterior para saber si estaba en 0
        $productoAntiguo = $this->obtenerPorId($id);
        $stockAnterior = $productoAntiguo['stock'];

        $sql = "UPDATE Productos SET 
                nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen_url = ?, id_categoria = ? 
                WHERE id_producto = ?";
        
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute([
            $datos['nombre'], 
            $datos['descripcion'], 
            $datos['precio'], 
            $datos['stock'], 
            $datos['imagen_url'], 
            $datos['id_categoria'],
            $id
        ]);

        // --- LÓGICA DE NOTIFICACIÓN DE STOCK ---
        // Si la actualización fue exitosa, el stock anterior era 0 y el nuevo es mayor a 0
        if ($resultado && $stockAnterior == 0 && $datos['stock'] > 0) {
            
            $url_producto = "http://localhost/Proyecto-final/views/detalle.php?id=" . $id;
            $img_completa = "http://localhost/Proyecto-final/assets/img/" . $datos['imagen_url'];

            // Llamamos a la función que busca en la tabla lista_espera
            notifyCustomers($id, $datos['nombre'], $url_producto, $img_completa);
        }

        return $resultado;
    }

    // 5. ELIMINAR PRODUCTO
    public function eliminar($id) {
        $db = Database::conectar();
        $stmt = $db->prepare("DELETE FROM Productos WHERE id_producto = ?");
        return $stmt->execute([$id]);
    }
}