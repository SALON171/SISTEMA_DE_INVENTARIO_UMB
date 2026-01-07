-- Esquema de Inventario de Papelería UES Cuautitlán
CREATE DATABASE Inventario_UMB;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN','ENCARGADO','SOLICITANTE','AUDITOR') NOT NULL
);

CREATE TABLE areas (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL
);

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL
);

CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    contacto VARCHAR(150)
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    id_categoria INT NOT NULL,
    unidad ENUM('PZA','PQTE','CAJA') NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL,
    id_proveedor INT,
    precio_estimado DECIMAL(10,2),
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    id_proveedor INT NOT NULL,
    documento VARCHAR(200),
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    id_solicitante INT NOT NULL,
    id_area INT NOT NULL,
    estado ENUM('PENDIENTE','AUTORIZADA','RECHAZADA') DEFAULT 'PENDIENTE',
    FOREIGN KEY (id_solicitante) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_area) REFERENCES areas(id_area)
);

CREATE TABLE salidas (
    id_salida INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    id_solicitud INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    id_autorizo INT NOT NULL,
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_autorizo) REFERENCES usuarios(id_usuario)
);

CREATE TABLE bitacora (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    id_usuario INT NOT NULL,
    accion VARCHAR(200) NOT NULL,
    detalle TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

ALTER TABLE usuarios
ADD COLUMN num_empleado VARCHAR(20) AFTER nombre,
ADD COLUMN ues_adscripcion VARCHAR(100) AFTER num_empleado;


CREATE TABLE detalles_solicitud (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad_solicitada INT NOT NULL,
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL, -- Coincide con 'Nombre Completo'
    num_empleado VARCHAR(20) NOT NULL,     -- Coincide con 'Número de Empleado'
    correo_institucional VARCHAR(120) NOT NULL UNIQUE, -- Coincide con 'Correo Institucional'
    ues_adscripcion VARCHAR(100) NOT NULL, -- Coincide con el select de planteles
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN','ENCARGADO','SOLICITANTE','AUDITOR') NOT NULL DEFAULT 'SOLICITANTE'
);