<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'nexoia_admin');
define('DB_PASS', 'secure_password');
define('DB_NAME', 'nexoia_sandbox');

// Configuración de la aplicación
define('SITE_URL', 'http://localhost/admin-sandbox');
define('ADMIN_URL', SITE_URL . '/admin');
define('SANDBOX_URL', SITE_URL . '/sandbox');

// Configuración de seguridad
define('SALT', 'nexoia_secure_salt_2025');
define('SESSION_NAME', 'NEXOIA_ADMIN_SESSION');

// Configuración de rutas
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('PUBLIC_PATH', ROOT_PATH . '/public_html');
define('TEMPLATES_PATH', ROOT_PATH . '/includes/templates');

// Zona horaria
date_default_timezone_set('UTC');

// Configuración de errores (cambiar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
