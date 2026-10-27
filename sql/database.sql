-- Base de datos Key Metrix (SGDK)
CREATE DATABASE IF NOT EXISTS keymetrix_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keymetrix_db;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    estado ENUM("activo","inactivo") DEFAULT "activo",
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);

CREATE TABLE IF NOT EXISTS rol_permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    permiso_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_permiso (rol_id, permiso_id)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    estado ENUM("activo","inactivo") DEFAULT "activo",
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_sesion DATETIME NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    responsable_id INT,
    estado ENUM("activo","inactivo") DEFAULT "activo",
    FOREIGN KEY (responsable_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS indicadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    formula VARCHAR(255),
    unidad_medida VARCHAR(50),
    tipo ENUM("cuantitativo","cualitativo") DEFAULT "cuantitativo",
    periodicidad ENUM("diario","semanal","mensual","trimestral","anual") DEFAULT "mensual",
    area_id INT,
    responsable_id INT,
    estado ENUM("activo","inactivo") DEFAULT "activo",
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (area_id) REFERENCES areas(id),
    FOREIGN KEY (responsable_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS metas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    indicador_id INT NOT NULL,
    meta_valor DECIMAL(10,2) NOT NULL,
    umbral_minimo DECIMAL(10,2),
    umbral_maximo DECIMAL(10,2),
    periodo VARCHAR(20) NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado ENUM("vigente","cumplida","vencida","cancelada") DEFAULT "vigente",
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (indicador_id) REFERENCES indicadores(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    indicador_id INT NOT NULL,
    meta_id INT NOT NULL,
    valor_real DECIMAL(10,2) NOT NULL,
    periodo VARCHAR(20) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    registrado_por INT,
    observaciones TEXT,
    FOREIGN KEY (indicador_id) REFERENCES indicadores(id),
    FOREIGN KEY (meta_id) REFERENCES metas(id),
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS alertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    indicador_id INT NOT NULL,
    tipo ENUM("riesgo","critico","cumplido") NOT NULL,
    mensaje TEXT NOT NULL,
    leida TINYINT(1) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (indicador_id) REFERENCES indicadores(id)
);

CREATE TABLE IF NOT EXISTS auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion VARCHAR(255) NOT NULL,
    ip VARCHAR(45),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Datos iniciales
INSERT IGNORE INTO roles (id, nombre, descripcion) VALUES
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Analista', 'Gestión y análisis de indicadores'),
(3, 'Consultor', 'Solo consulta de información');

INSERT IGNORE INTO permisos (id, nombre, descripcion) VALUES
(1, 'gestionar_usuarios', 'Crear, editar, eliminar usuarios'),
(2, 'gestionar_roles', 'Gestionar roles y permisos'),
(3, 'gestionar_areas', 'Gestionar áreas organizativas'),
(4, 'gestionar_indicadores', 'Gestionar indicadores KPI'),
(5, 'gestionar_metas', 'Gestionar metas de indicadores'),
(6, 'registrar_resultados', 'Registrar resultados de indicadores'),
(7, 'ver_dashboard', 'Visualizar dashboard'),
(8, 'ver_reportes', 'Generar y ver reportes'),
(9, 'gestionar_alertas', 'Gestionar alertas'),
(10, 'ver_auditoria', 'Ver auditoría del sistema'),
(11, 'gestionar_copias', 'Gestionar copias de seguridad');

INSERT IGNORE INTO rol_permisos (rol_id, permiso_id) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),
(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),
(3,7),(3,8);

INSERT IGNORE INTO usuarios (id, nombre_completo, usuario, email, password, rol_id) VALUES
(1, 'Administrador del Sistema', 'admin', 'admin@keymetrix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

INSERT IGNORE INTO areas (id, nombre, descripcion) VALUES
(1, 'Administración', 'Área administrativa'),
(2, 'Ventas', 'Área comercial'),
(3, 'Recursos Humanos', 'Gestión de talento humano'),
(4, 'Operaciones', 'Área operativa');
