-- Crear base de datos
CREATE DATABASE IF NOT EXISTS hotel_reservas 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE hotel_reservas;

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'recepcionista', 'cliente') DEFAULT 'cliente',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB;

-- Tabla de clientes (huéspedes)
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    documento VARCHAR(50) UNIQUE NOT NULL,
    tipo_documento ENUM('DNI', 'Pasaporte', 'CE') DEFAULT 'DNI',
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion TEXT,
    fecha_nacimiento DATE,
    nacionalidad VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_documento (documento),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Tabla de tipos de habitación
CREATE TABLE tipos_habitacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    capacidad INT NOT NULL,
    precio_base DECIMAL(10,2) NOT NULL,
    caracteristicas TEXT, -- JSON con amenidades
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de habitaciones
CREATE TABLE habitaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(10) UNIQUE NOT NULL,
    tipo_id INT NOT NULL,
    piso INT NOT NULL,
    precio_noche DECIMAL(10,2) NOT NULL,
    estado ENUM('disponible', 'ocupada', 'mantenimiento') DEFAULT 'disponible',
    descripcion TEXT,
    caracteristicas TEXT, -- Características específicas de esta habitación
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_id) REFERENCES tipos_habitacion(id) ON DELETE RESTRICT,
    INDEX idx_numero (numero),
    INDEX idx_estado (estado),
    INDEX idx_tipo (tipo_id)
) ENGINE=InnoDB;

-- Tabla de reservas
CREATE TABLE reservas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL, -- Código de reserva único
    cliente_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    usuario_id INT, -- Usuario que creó la reserva
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    num_huespedes INT NOT NULL DEFAULT 1,
    num_noches INT NOT NULL,
    precio_noche DECIMAL(10,2) NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'completada') DEFAULT 'pendiente',
    observaciones TEXT,
    fecha_checkin DATETIME,
    fecha_checkout DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_cliente (cliente_id),
    INDEX idx_habitacion (habitacion_id),
    INDEX idx_fechas (fecha_entrada, fecha_salida),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- Tabla de pagos
CREATE TABLE pagos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reserva_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'yape', 'plin') NOT NULL,
    estado ENUM('pendiente', 'completado', 'reembolsado') DEFAULT 'pendiente',
    referencia VARCHAR(100), -- Número de transacción/comprobante
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE RESTRICT,
    INDEX idx_reserva (reserva_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- Tabla de servicios adicionales (opcional)
CREATE TABLE servicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de servicios contratados en reservas (opcional)
CREATE TABLE reserva_servicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reserva_id INT NOT NULL,
    servicio_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    fecha_servicio DATE,
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Trigger para generar código de reserva automáticamente
DELIMITER //
CREATE TRIGGER before_reserva_insert 
BEFORE INSERT ON reservas
FOR EACH ROW
BEGIN
    IF NEW.codigo IS NULL OR NEW.codigo = '' THEN
        SET NEW.codigo = CONCAT('RES', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    END IF;
END//
DELIMITER ;

-- Vista para consultas frecuentes de reservas
CREATE VIEW vista_reservas AS
SELECT 
    r.id,
    r.codigo,
    r.fecha_entrada,
    r.fecha_salida,
    r.num_noches,
    r.precio_total,
    r.estado,
    CONCAT(c.nombre, ' ', c.apellido) AS cliente_nombre,
    c.email AS cliente_email,
    c.telefono AS cliente_telefono,
    c.documento AS cliente_documento,
    h.numero AS habitacion_numero,
    h.piso AS habitacion_piso,
    th.nombre AS tipo_habitacion,
    th.capacidad,
    r.created_at
FROM reservas r
INNER JOIN clientes c ON r.cliente_id = c.id
INNER JOIN habitaciones h ON r.habitacion_id = h.id
INNER JOIN tipos_habitacion th ON h.tipo_id = th.id;