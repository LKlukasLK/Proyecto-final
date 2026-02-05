USE TiendaOnline;

-- 1. Insertar Usuarios (Admins y Clientes)
INSERT INTO Usuarios (nombre, email, contrasena, rol) VALUES 
('Ana García', 'ana.admin@tienda.com', 'hash_password_123', 'admin'),
('Carlos Ruiz', 'carlos.cliente@email.com', 'hash_password_456', 'cliente'),
('Laura Beltrán', 'laura.compras@email.com', 'hash_password_789', 'cliente');

-- 2. Insertar Categorías
INSERT INTO Categorias (nombre, descripcion) VALUES 
('Ropa', 'Prendas de vestir para todas las estaciones'),
('Accesorios', 'Bolsos, cinturones y joyería'),
('Calzado', 'Zapatos, zapatillas y botas');

-- 3. Insertar Diseñadores
INSERT INTO Disenadores (nombre, biografia, web_url) VALUES 
('Elena Modista', 'Diseñadora especializada en tejidos sostenibles.', 'https://elenamoda.com'),
('Marco Zapatero', 'Artesano del cuero con 20 años de experiencia.', 'https://marcoleather.it'),
('Sonia Joyas', 'Joyera contemporánea basada en Barcelona.', NULL);

-- 4. Insertar Productos
-- Importante: El id_categoria y id_disenador deben existir arriba.
INSERT INTO Productos (nombre, descripcion, precio, stock, id_categoria, id_disenador) VALUES 
('Camiseta Algodón Orgánico', 'Camiseta básica blanca de alta calidad.', 25.50, 50, 1, 1),
('Botas de Cuero', 'Botas resistentes hechas a mano.', 120.00, 15, 3, 2),
('Collar de Plata', 'Collar minimalista de plata de ley.', 45.00, 10, 2, 3),
('Pantalón Denim', 'Vaqueros de corte recto.', 60.00, 30, 1, 1);

-- 5. Insertar Carritos
INSERT INTO Carritos (id_usuario, estado) VALUES 
(2, 'activo'),
(3, 'convertido');

-- 6. Insertar Detalles de Carrito
INSERT INTO Detalles_Carrito (id_carrito, id_producto, cantidad) VALUES 
(1, 1, 2), -- Carlos tiene 2 camisetas en su carrito activo
(2, 2, 1); -- Laura tenía unas botas (ya convertido a pedido)

-- 7. Insertar Pedidos
-- El total se actualizará automáticamente mediante los triggers que creamos antes.
INSERT INTO Pedidos (id_usuario, total, estado) VALUES 
(3, 0, 'pendiente'), -- Pedido iniciado por Laura
(2, 0, 'pendiente'); -- Pedido iniciado por Carlos

-- 8. Insertar Detalles de Pedido
-- Al insertar aquí, los triggers 'trg_total_insert' calcularán el total en la tabla Pedidos.
INSERT INTO Detalles_Pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES 
(1, 2, 1, 120.00), -- Laura pidió las botas
(1, 3, 1, 45.00),   -- Laura también pidió el collar
(2, 1, 3, 25.50);   -- Carlos pidió 3 camisetas

-- 9. Usar el Procedimiento para Confirmar un Pedido
-- Esto actualizará el estado a 'pagado', insertará en la tabla Pagos y descontará stock vía trigger.
CALL ConfirmarPedido(1, 'tarjeta');

-- 10. Insertar en Lista de Espera (Ejemplo de producto agotado o interés)
INSERT INTO lista_espera (id_producto, email) VALUES 
(2, 'esperando_botas@email.com');

-- 11. Historial de Estados (Se llena solo por el trigger trg_historial_estado)
-- Pero podemos insertar uno manualmente si fuera necesario.