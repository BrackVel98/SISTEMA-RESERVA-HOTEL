USE hotel_reservas;

-- Insertar usuario administrador
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Admin', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: password
('Recepcionista', 'recepcion@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recepcionista'),
('Cliente Demo', 'cliente@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente');

-- Insertar tipos de habitación
INSERT INTO tipos_habitacion (nombre, descripcion, capacidad, precio_base, caracteristicas) VALUES
('Individual', 'Habitación cómoda para una persona', 1, 80.00, '{"wifi": true, "tv": true, "minibar": false, "aire_acondicionado": true}'),
('Doble', 'Habitación espaciosa con cama matrimonial', 2, 120.00, '{"wifi": true, "tv": true, "minibar": true, "aire_acondicionado": true}'),
('Suite Junior', 'Suite con sala de estar', 2, 180.00, '{"wifi": true, "tv": true, "minibar": true, "aire_acondicionado": true, "jacuzzi": false}'),
('Suite Ejecutiva', 'Suite de lujo con todas las comodidades', 3, 250.00, '{"wifi": true, "tv": true, "minibar": true, "aire_acondicionado": true, "jacuzzi": true}'),
('Familiar', 'Habitación amplia para familias', 4, 200.00, '{"wifi": true, "tv": true, "minibar": true, "aire_acondicionado": true}');

-- Insertar habitaciones
INSERT INTO habitaciones (numero, tipo_id, piso, precio_noche, estado) VALUES
-- Piso 1 - Individuales
('101', 1, 1, 80.00, 'disponible'),
('102', 1, 1, 80.00, 'disponible'),
('103', 2, 1, 120.00, 'disponible'),
('104', 2, 1, 120.00, 'disponible'),

-- Piso 2 - Dobles y Familiares
('201', 2, 2, 120.00, 'disponible'),
('202', 2, 2, 120.00, 'disponible'),
('203', 5, 2, 200.00, 'disponible'),
('204', 5, 2, 200.00, 'disponible'),

-- Piso 3 - Suites
('301', 3, 3, 180.00, 'disponible'),
('302', 3, 3, 180.00, 'disponible'),
('303', 4, 3, 250.00, 'disponible'),
('304', 4, 3, 250.00, 'disponible');

-- Insertar clientes de prueba
INSERT INTO clientes (nombre, apellido, documento, tipo_documento, email, telefono, direccion) VALUES
('Juan', 'Pérez', '12345678', 'DNI', 'juan.perez@email.com', '987654321', 'Av. Principal 123'),
('María', 'García', '87654321', 'DNI', 'maria.garcia@email.com', '987654322', 'Jr. Comercio 456'),
('Carlos', 'López', '11223344', 'DNI', 'carlos.lopez@email.com', '987654323', 'Calle Lima 789');

-- Insertar servicios adicionales
INSERT INTO servicios (nombre, descripcion, precio, activo) VALUES
('Desayuno Buffet', 'Desayuno buffet completo', 25.00, TRUE),
('Servicio de Lavandería', 'Lavado y planchado de ropa', 30.00, TRUE),
('Spa y Masajes', 'Sesión de masajes relajantes', 80.00, TRUE),
('Traslado Aeropuerto', 'Transporte desde/hacia el aeropuerto', 50.00, TRUE),
('Room Service', 'Servicio a la habitación 24 horas', 15.00, TRUE);

-- Insertar algunas reservas de ejemplo
INSERT INTO reservas (codigo, cliente_id, habitacion_id, usuario_id, fecha_entrada, fecha_salida, num_huespedes, num_noches, precio_noche, precio_total, estado) VALUES
('RES001', 1, 3, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 2, 3, 120.00, 360.00, 'confirmada'),
('RES002', 2, 7, 2, DATE_ADD(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 4, 3, 200.00, 600.00, 'confirmada'),
('RES003', 3, 11, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 4 DAY), 2, 3, 250.00, 750.00, 'pendiente');