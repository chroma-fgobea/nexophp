-- Base de datos para el sistema de administración de Nexo.ia Bot Sandbox

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS nexoia_sandbox;
USE nexoia_sandbox;

-- Tabla de administradores
CREATE TABLE IF NOT EXISTS administrators (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'support') NOT NULL DEFAULT 'support',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    unique_url_key VARCHAR(32) NOT NULL UNIQUE,
    status ENUM('active', 'inactive', 'pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de sandboxes
CREATE TABLE IF NOT EXISTS sandboxes (
    sandbox_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    script_code TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES administrators(admin_id)
);

-- Tabla de feedback de clientes
CREATE TABLE IF NOT EXISTS client_feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    sandbox_id INT NOT NULL,
    feedback_text TEXT NOT NULL,
    feedback_type ENUM('approval', 'correction', 'additional_info') NOT NULL,
    status ENUM('new', 'in_progress', 'resolved') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    resolved_by INT NULL,
    FOREIGN KEY (sandbox_id) REFERENCES sandboxes(sandbox_id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES administrators(admin_id)
);

-- Tabla de sesiones de prueba
CREATE TABLE IF NOT EXISTS test_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    sandbox_id INT NOT NULL,
    session_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    session_end TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (sandbox_id) REFERENCES sandboxes(sandbox_id) ON DELETE CASCADE
);

-- Tabla de mensajes de prueba
CREATE TABLE IF NOT EXISTS test_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    message_text TEXT NOT NULL,
    is_bot BOOLEAN NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES test_sessions(session_id) ON DELETE CASCADE
);

-- Insertar administrador por defecto (contraseña: admin123)
INSERT INTO administrators (username, password, email, full_name, role)
VALUES ('admin', '$2y$10$8KgMqN.XOlUPZGn7KzECVeGQnvq/jEIGOXIo0Yk9t3iqV8JWu2VYy', 'admin@nexoia.com', 'Administrador Nexo.ia', 'admin');
