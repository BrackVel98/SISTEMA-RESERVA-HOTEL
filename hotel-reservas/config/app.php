<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\config\app.php

/**
 * Configuración general de la aplicación
 */

// Definir constantes solo si no existen
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Hotel Reservas');
}

if (!defined('APP_URL')) {
    define('APP_URL', 'http://localhost/hotel-reservas/public');
}

if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}

if (!defined('APP_ENV')) {
    define('APP_ENV', 'development'); // production, development
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', true);
}

// Zona horaria
date_default_timezone_set('America/Lima');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);

// Configuración de errores según el entorno
// if (APP_DEBUG && APP_ENV === 'development') {
//     error_reporting(E_ALL);
//     ini_set('display_errors', '1');
//     ini_set('display_startup_errors', '1');
// } else {
//     error_reporting(0);
//     ini_set('display_errors', '0');
//     ini_set('display_startup_errors', '0');
// }

// Configuración de logs
if (!defined('LOG_PATH')) {
    define('LOG_PATH', __DIR__ . '/../logs');
}

if (!is_dir(LOG_PATH)) {
    @mkdir(LOG_PATH, 0755, true);
}

ini_set('log_errors', '1');
ini_set('error_log', LOG_PATH . '/php-error.log');

// Configuración de charset
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// URL base de la aplicación
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/hotel-reservas');
}

// Directorio de subida de archivos
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', __DIR__ . '/../public/images/habitaciones/');
}

if (!defined('MAX_UPLOAD_SIZE')) {
    define('MAX_UPLOAD_SIZE', 2097152); // 2MB
}

if (!defined('ALLOWED_EXTENSIONS')) {
    define('ALLOWED_EXTENSIONS', serialize(['jpg', 'jpeg', 'png', 'gif']));
}

// Roles de usuario
if (!defined('ROL_ADMIN')) {
    define('ROL_ADMIN', 'admin');
}

if (!defined('ROL_RECEPCIONISTA')) {
    define('ROL_RECEPCIONISTA', 'recepcionista');
}

if (!defined('ROL_CLIENTE')) {
    define('ROL_CLIENTE', 'cliente');
}

// Estados de reserva
if (!defined('RESERVA_PENDIENTE')) {
    define('RESERVA_PENDIENTE', 'pendiente');
}

if (!defined('RESERVA_CONFIRMADA')) {
    define('RESERVA_CONFIRMADA', 'confirmada');
}

if (!defined('RESERVA_CANCELADA')) {
    define('RESERVA_CANCELADA', 'cancelada');
}

if (!defined('RESERVA_COMPLETADA')) {
    define('RESERVA_COMPLETADA', 'completada');
}

// Estados de habitación
if (!defined('HABITACION_DISPONIBLE')) {
    define('HABITACION_DISPONIBLE', 'disponible');
}

if (!defined('HABITACION_OCUPADA')) {
    define('HABITACION_OCUPADA', 'ocupada');
}

if (!defined('HABITACION_MANTENIMIENTO')) {
    define('HABITACION_MANTENIMIENTO', 'mantenimiento');
}