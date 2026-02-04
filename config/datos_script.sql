
INSERT INTO Categorias (nombre, descripcion) VALUES 
('Electrónica', 'Gadgets, smartphones y accesorios tecnológicos'),
('Ropa', 'Prendas de vestir para todas las edades'),
('Hogar', 'Muebles y decoración para el hogar');


INSERT INTO Usuarios (nombre, email, contrasena, rol) VALUES 
('Admin Principal', 'admin@tienda.com', 'admin123', 'admin'),
('Juan Pérez', 'juan.perez@email.com', 'juan123', 'cliente'),
('María García', 'm.garcia@email.com', 'maria123', 'cliente'),
('Carlos López', 'carlos@email.com', 'carlos123', 'cliente');
INSERT INTO Productos (nombre, descripcion, precio, stock, id_categoria) VALUES 
('Smartphone X', 'Teléfono de última generación', 799.99, 10, 1),
('Auriculares Pro', 'Cancelación de ruido activa', 199.50, 25, 1),
('Camiseta Algodón', '100% algodón orgánico', 19.95, 50, 2),
('Lámpara de Pie', 'Estilo nórdico, madera clara', 45.00, 5, 3),
('Monitor 4K', '27 pulgadas, 144Hz', 350.00, 0, 1);


INSERT INTO Pedidos (id_usuario, estado) VALUES (2, 'pendiente');


INSERT INTO Detalles_Pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES 
(1, 1, 1, 799.99), 
(1, 3, 2, 19.95);  


INSERT INTO Pedidos (id_usuario, estado) VALUES (3, 'pendiente');
INSERT INTO Detalles_Pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES 
(2, 2, 1, 199.50);


CALL ConfirmarPedido(1, 'tarjeta');


INSERT INTO lista_espera (id_producto, email) VALUES (5, 'cliente_interesado@email.com');


INSERT INTO Carritos (id_usuario, estado) VALUES (4, 'abandonado');
INSERT INTO Detalles_Carrito (id_carrito, id_producto, cantidad) VALUES (1, 4, 1);