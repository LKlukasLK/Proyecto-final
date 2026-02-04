CREATE DATABASE IF NOT EXISTS TiendaOnline;
USE TiendaOnline;


CREATE TABLE Usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE Categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);


CREATE TABLE Productos (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    stock INT NOT NULL CHECK (stock >= 0),
    imagen_url VARCHAR(255),
    id_categoria INT NOT NULL,

    FOREIGN KEY (id_categoria)
        REFERENCES Categorias(id_categoria)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_producto_categoria ON Productos(id_categoria);


CREATE TABLE Pedidos (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL DEFAULT 0 CHECK (total >= 0),
    estado ENUM('pendiente','pagado','enviado','cancelado','completado')
           NOT NULL DEFAULT 'pendiente',

    FOREIGN KEY (id_usuario)
        REFERENCES Usuarios(id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_pedido_usuario ON Pedidos(id_usuario);


CREATE TABLE Detalles_Pedido (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad > 0),
    precio_unitario DECIMAL(10,2) NOT NULL CHECK (precio_unitario >= 0),

    FOREIGN KEY (id_pedido)
        REFERENCES Pedidos(id_pedido)
        ON DELETE CASCADE,

    FOREIGN KEY (id_producto)
        REFERENCES Productos(id_producto)
        ON DELETE RESTRICT,

    UNIQUE (id_pedido, id_producto)
);

CREATE INDEX idx_detalle_pedido ON Detalles_Pedido(id_pedido);


CREATE TABLE Pagos (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10,2) NOT NULL CHECK (monto >= 0),
    metodo_pago ENUM('tarjeta','paypal','transferencia','bizum') NOT NULL,
    estado ENUM('pendiente','aprobado','rechazado') NOT NULL,

    FOREIGN KEY (id_pedido)
        REFERENCES Pedidos(id_pedido)
        ON DELETE CASCADE
);


CREATE TABLE Carritos (
    id_carrito INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo','abandonado','convertido') DEFAULT 'activo',

    FOREIGN KEY (id_usuario)
        REFERENCES Usuarios(id_usuario)
        ON DELETE CASCADE
);


CREATE TABLE Detalles_Carrito (
    id_detalle_carrito INT PRIMARY KEY AUTO_INCREMENT,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad > 0),

    FOREIGN KEY (id_carrito)
        REFERENCES Carritos(id_carrito)
        ON DELETE CASCADE,

    FOREIGN KEY (id_producto)
        REFERENCES Productos(id_producto)
        ON DELETE RESTRICT,

    UNIQUE (id_carrito, id_producto)
);


CREATE TABLE Historial_Estados_Pedido (
    id_historial INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    estado ENUM('pendiente','pagado','enviado','cancelado','completado') NOT NULL,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_pedido)
        REFERENCES Pedidos(id_pedido)
        ON DELETE CASCADE
);

CREATE TABLE lista_espera(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    fecha_suscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo','enviado') NOT NULL DEFAULT 'activo',
    FOREIGN KEY (id_producto)
        REFERENCES Productos(id_producto)
        ON DELETE CASCADE
        
);



DELIMITER $$

CREATE TRIGGER trg_total_insert
AFTER INSERT ON Detalles_Pedido
FOR EACH ROW
BEGIN
    UPDATE Pedidos
    SET total = (
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = NEW.id_pedido
    )
    WHERE id_pedido = NEW.id_pedido;
END$$

CREATE TRIGGER trg_total_update
AFTER UPDATE ON Detalles_Pedido
FOR EACH ROW
BEGIN
    UPDATE Pedidos
    SET total = (
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = NEW.id_pedido
    )
    WHERE id_pedido = NEW.id_pedido;
END$$

CREATE TRIGGER trg_total_delete
AFTER DELETE ON Detalles_Pedido
FOR EACH ROW
BEGIN
    UPDATE Pedidos
    SET total = IFNULL((
        SELECT SUM(cantidad * precio_unitario)
        FROM Detalles_Pedido
        WHERE id_pedido = OLD.id_pedido
    ),0)
    WHERE id_pedido = OLD.id_pedido;
END$$



CREATE TRIGGER trg_historial_estado
AFTER UPDATE ON Pedidos
FOR EACH ROW
BEGIN
    IF NEW.estado <> OLD.estado THEN
        INSERT INTO Historial_Estados_Pedido (id_pedido, estado)
        VALUES (NEW.id_pedido, NEW.estado);
    END IF;
END$$


CREATE TRIGGER trg_descontar_stock
BEFORE UPDATE ON Pedidos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'pagado' AND OLD.estado <> 'pagado' THEN

        IF EXISTS (
            SELECT 1
            FROM Detalles_Pedido dp
            JOIN Productos p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = NEW.id_pedido
            AND p.stock < dp.cantidad
        ) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuficiente';
        END IF;

        UPDATE Productos p
        JOIN Detalles_Pedido dp 
            ON p.id_producto = dp.id_producto
        SET p.stock = p.stock - dp.cantidad
        WHERE dp.id_pedido = NEW.id_pedido;

    END IF;
END$$



CREATE TRIGGER trg_devolver_stock
BEFORE UPDATE ON Pedidos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'cancelado' AND OLD.estado = 'pagado' THEN

        UPDATE Productos p
        JOIN Detalles_Pedido dp 
            ON p.id_producto = dp.id_producto
        SET p.stock = p.stock + dp.cantidad
        WHERE dp.id_pedido = NEW.id_pedido;

    END IF;
END$$



CREATE PROCEDURE ConfirmarPedido(
    IN p_id_pedido INT,
    IN p_metodo_pago VARCHAR(50)
)
BEGIN
    DECLARE total_pedido DECIMAL(10,2);

    START TRANSACTION;

    SELECT total INTO total_pedido
    FROM Pedidos
    WHERE id_pedido = p_id_pedido
    FOR UPDATE;

    UPDATE Pedidos
    SET estado = 'pagado'
    WHERE id_pedido = p_id_pedido;

    INSERT INTO Pagos (id_pedido, monto, metodo_pago, estado)
    VALUES (p_id_pedido, total_pedido, p_metodo_pago, 'aprobado');

    COMMIT;
END$$

DELIMITER ;



CREATE VIEW Vista_Reporte_Ventas AS
SELECT 
    p.id_pedido,
    u.nombre AS cliente,
    p.fecha_pedido,
    p.total,
    p.estado
FROM Pedidos p
JOIN Usuarios u ON p.id_usuario = u.id_usuario
WHERE p.estado = 'pagado';
