<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/mensajeriaController.php';

class ProductoController {

    // 1. LISTAR PRODUCTOS 
    public function listar() {
        $db = Database::conectar();
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM Productos p 
                LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria";
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
        // Añadimos id_disenador a la consulta
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
        
        // Stock anterior para avisos
        $productoAntiguo = $this->obtenerPorId($id);
        $stockAnterior = $productoAntiguo['stock'];

        // Añadimos id_disenador al UPDATE
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

        // Lógica de notificación
        if ($resultado && $stockAnterior == 0 && $datos['stock'] > 0) {
            $url_producto = "http://localhost/Proyecto-final/views/detalle.php?id=" . $id;
            $img_completa = "http://localhost/Proyecto-final/assets/img/" . $datos['imagen_url'];
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