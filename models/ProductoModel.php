<?php
require_once __DIR__ . '/../config/db.php';

class ProductoModel {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorNombre($q) {
        $sql = "SELECT * FROM productos WHERE nombre LIKE :busqueda";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['busqueda' => "%$q%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM productos WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- MÉTODOS PARA PERSISTENCIA EN BD ---

    public function guardarEnDB($id_usuario, $id_producto) {
        // 1. Insertamos el carrito si no existe. No forzamos actualización de fecha.
        $sql_c = "INSERT IGNORE INTO carritos (id_usuario, estado) VALUES (:u, 'activo')";
        $stmt_c = $this->db->prepare($sql_c);
        $stmt_c->execute(['u' => $id_usuario]);

        // 2. Obtenemos el id_carrito
        $stmt_id = $this->db->prepare("SELECT id_carrito FROM carritos WHERE id_usuario = :u");
        $stmt_id->execute(['u' => $id_usuario]);
        $id_carrito = $stmt_id->fetchColumn();

        // 3. Insertamos el producto o incrementamos cantidad
        $sql_d = "INSERT INTO detalles_carrito (id_carrito, id_producto, cantidad) 
                  VALUES (:idc, :idp, 1) 
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + 1";
        $stmt_d = $this->db->prepare($sql_d);
        return $stmt_d->execute(['idc' => $id_carrito, 'idp' => $id_producto]);
    }

    public function eliminarDeDB($id_usuario, $id_producto) {
        $sql = "DELETE dc FROM detalles_carrito dc 
                JOIN carritos c ON dc.id_carrito = c.id_carrito 
                WHERE c.id_usuario = :u AND dc.id_producto = :p";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['u' => $id_usuario, 'p' => $id_producto]);
    }

    public function obtenerCarritoUsuario($id_usuario) {
        $sql = "SELECT p.*, dc.cantidad FROM productos p 
                JOIN detalles_carrito dc ON p.id_producto = dc.id_producto 
                JOIN carritos c ON dc.id_carrito = c.id_carrito 
                WHERE c.id_usuario = :u";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['u' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function unirseAListaEspera($id_p, $email) {
        // 1. Consultamos el stock actual del producto
        $sqlStock = "SELECT stock FROM productos WHERE id_producto = ?";
        $stmtStock = $this->db->prepare($sqlStock);
        $stmtStock->execute([$id_p]);
        $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

        // 2. Definimos el estado basado en el stock
        // Si no hay stock (0 o menos), el estado es 'pendiente' (activo para aviso)
        // Si hay stock, lo marcamos como 'completado' o 'innecesario'
        $estado = ($producto['stock'] <= 0) ? 'pendiente' : 'con_stock';
        
        $fecha = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO lista_espera (id_producto, email, fecha_suscripcion, estado) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_p, $email, $fecha, $estado]);
    }

    public function obtenerDisenadores() {
        $sql = "SELECT * FROM disenadores";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}