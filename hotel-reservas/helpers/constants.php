<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\helpers\constants.php

/**
 * Constantes globales del sistema
 * Solo se definen si no existen previamente
 */

// ==================== ESTADOS DE RESERVA ====================
if (!defined('RESERVA_PENDIENTE')) define('RESERVA_PENDIENTE', 'pendiente');
if (!defined('RESERVA_CONFIRMADA')) define('RESERVA_CONFIRMADA', 'confirmada');
if (!defined('RESERVA_CANCELADA')) define('RESERVA_CANCELADA', 'cancelada');
if (!defined('RESERVA_COMPLETADA')) define('RESERVA_COMPLETADA', 'completada');

// ==================== ESTADOS DE HABITACIÓN ====================
if (!defined('HABITACION_DISPONIBLE')) define('HABITACION_DISPONIBLE', 'disponible');
if (!defined('HABITACION_OCUPADA')) define('HABITACION_OCUPADA', 'ocupada');
if (!defined('HABITACION_MANTENIMIENTO')) define('HABITACION_MANTENIMIENTO', 'mantenimiento');
if (!defined('HABITACION_LIMPIEZA')) define('HABITACION_LIMPIEZA', 'limpieza');

// ==================== ESTADOS DE PAGO ====================
if (!defined('PAGO_PENDIENTE')) define('PAGO_PENDIENTE', 'pendiente');
if (!defined('PAGO_COMPLETADO')) define('PAGO_COMPLETADO', 'completado');
if (!defined('PAGO_PARCIAL')) define('PAGO_PARCIAL', 'parcial');
if (!defined('PAGO_REEMBOLSADO')) define('PAGO_REEMBOLSADO', 'reembolsado');

// ==================== MÉTODOS DE PAGO ====================
if (!defined('METODO_EFECTIVO')) define('METODO_EFECTIVO', 'efectivo');
if (!defined('METODO_TARJETA')) define('METODO_TARJETA', 'tarjeta');
if (!defined('METODO_TRANSFERENCIA')) define('METODO_TRANSFERENCIA', 'transferencia');
if (!defined('METODO_YAPE')) define('METODO_YAPE', 'yape');
if (!defined('METODO_PLIN')) define('METODO_PLIN', 'plin');

// ==================== ROLES DE USUARIO ====================
if (!defined('ROL_ADMIN')) define('ROL_ADMIN', 'admin');
if (!defined('ROL_RECEPCIONISTA')) define('ROL_RECEPCIONISTA', 'recepcionista');
if (!defined('ROL_CLIENTE')) define('ROL_CLIENTE', 'cliente');

// ==================== TIPOS DE DOCUMENTO ====================
if (!defined('DOC_DNI')) define('DOC_DNI', 'DNI');
if (!defined('DOC_PASAPORTE')) define('DOC_PASAPORTE', 'Pasaporte');
if (!defined('DOC_CARNET_EXTRANJERIA')) define('DOC_CARNET_EXTRANJERIA', 'Carnet de Extranjería');
if (!defined('DOC_RUC')) define('DOC_RUC', 'RUC');

// ==================== CONFIGURACIÓN DE PAGINACIÓN ====================
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 20);
if (!defined('MAX_PAGINATION_LINKS')) define('MAX_PAGINATION_LINKS', 5);

// ==================== CONFIGURACIÓN DE ARCHIVOS ====================
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 2097152); // 2MB
if (!defined('ALLOWED_IMAGE_TYPES')) define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', __DIR__ . '/../public/images/habitaciones/');
if (!defined('UPLOAD_URL')) define('UPLOAD_URL', '/public/images/habitaciones/');

// ==================== CONFIGURACIÓN DE FECHAS ====================
if (!defined('DATE_FORMAT')) define('DATE_FORMAT', 'd/m/Y');
if (!defined('DATETIME_FORMAT')) define('DATETIME_FORMAT', 'd/m/Y H:i');
if (!defined('TIME_FORMAT')) define('TIME_FORMAT', 'H:i');
if (!defined('TIMEZONE')) define('TIMEZONE', 'America/Lima');

// ==================== CONFIGURACIÓN DE MONEDA ====================
if (!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', 'S/');
if (!defined('CURRENCY_CODE')) define('CURRENCY_CODE', 'PEN');
if (!defined('CURRENCY_DECIMALS')) define('CURRENCY_DECIMALS', 2);
if (!defined('DECIMAL_SEPARATOR')) define('DECIMAL_SEPARATOR', '.');
if (!defined('THOUSANDS_SEPARATOR')) define('THOUSANDS_SEPARATOR', ',');

// ==================== CONFIGURACIÓN DE RESERVAS ====================
if (!defined('MIN_DIAS_RESERVA')) define('MIN_DIAS_RESERVA', 1);
if (!defined('MAX_DIAS_RESERVA')) define('MAX_DIAS_RESERVA', 30);
if (!defined('DIAS_ANTICIPACION_MINIMA')) define('DIAS_ANTICIPACION_MINIMA', 0);
if (!defined('DIAS_ANTICIPACION_MAXIMA')) define('DIAS_ANTICIPACION_MAXIMA', 90);
if (!defined('HORA_CHECKIN')) define('HORA_CHECKIN', '14:00');
if (!defined('HORA_CHECKOUT')) define('HORA_CHECKOUT', '12:00');
if (!defined('TOLERANCIA_CHECKOUT_MINUTOS')) define('TOLERANCIA_CHECKOUT_MINUTOS', 60);

// ==================== CONFIGURACIÓN DE PRECIOS ====================
if (!defined('PRECIO_MINIMO')) define('PRECIO_MINIMO', 50.00);
if (!defined('PRECIO_MAXIMO')) define('PRECIO_MAXIMO', 5000.00);
if (!defined('IVA_PORCENTAJE')) define('IVA_PORCENTAJE', 18);
if (!defined('DESCUENTO_MAXIMO')) define('DESCUENTO_MAXIMO', 50);

// ==================== CONFIGURACIÓN DE SEGURIDAD ====================
if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', 'csrf_token');
if (!defined('CSRF_TOKEN_LIFETIME')) define('CSRF_TOKEN_LIFETIME', 3600);
if (!defined('MAX_LOGIN_ATTEMPTS')) define('MAX_LOGIN_ATTEMPTS', 5);
if (!defined('LOGIN_LOCKOUT_TIME')) define('LOGIN_LOCKOUT_TIME', 900);
if (!defined('PASSWORD_MIN_LENGTH')) define('PASSWORD_MIN_LENGTH', 6);
if (!defined('PASSWORD_REQUIRE_UPPERCASE')) define('PASSWORD_REQUIRE_UPPERCASE', false);
if (!defined('PASSWORD_REQUIRE_LOWERCASE')) define('PASSWORD_REQUIRE_LOWERCASE', false);
if (!defined('PASSWORD_REQUIRE_NUMBERS')) define('PASSWORD_REQUIRE_NUMBERS', true);
if (!defined('PASSWORD_REQUIRE_SPECIAL')) define('PASSWORD_REQUIRE_SPECIAL', false);

// ==================== CONFIGURACIÓN DE EMAIL ====================
if (!defined('EMAIL_FROM')) define('EMAIL_FROM', 'noreply@hotel.com');
if (!defined('EMAIL_FROM_NAME')) define('EMAIL_FROM_NAME', 'Hotel Reservas');
if (!defined('EMAIL_REPLY_TO')) define('EMAIL_REPLY_TO', 'info@hotel.com');
if (!defined('EMAIL_ENABLED')) define('EMAIL_ENABLED', false);

// ==================== CONFIGURACIÓN DE NOTIFICACIONES ====================
if (!defined('NOTIFICAR_NUEVA_RESERVA')) define('NOTIFICAR_NUEVA_RESERVA', true);
if (!defined('NOTIFICAR_CONFIRMACION_RESERVA')) define('NOTIFICAR_CONFIRMACION_RESERVA', true);
if (!defined('NOTIFICAR_CANCELACION_RESERVA')) define('NOTIFICAR_CANCELACION_RESERVA', true);
if (!defined('NOTIFICAR_CHECKIN')) define('NOTIFICAR_CHECKIN', true);
if (!defined('NOTIFICAR_CHECKOUT')) define('NOTIFICAR_CHECKOUT', true);
if (!defined('NOTIFICAR_PAGO_RECIBIDO')) define('NOTIFICAR_PAGO_RECIBIDO', true);

// ==================== MENSAJES DEL SISTEMA ====================
if (!defined('MSG_LOGIN_SUCCESS')) define('MSG_LOGIN_SUCCESS', 'Bienvenido al sistema');
if (!defined('MSG_LOGIN_ERROR')) define('MSG_LOGIN_ERROR', 'Email o contraseña incorrectos');
if (!defined('MSG_LOGOUT_SUCCESS')) define('MSG_LOGOUT_SUCCESS', 'Has cerrado sesión correctamente');
if (!defined('MSG_UNAUTHORIZED')) define('MSG_UNAUTHORIZED', 'No tienes permisos para acceder a esta sección');
if (!defined('MSG_NOT_FOUND')) define('MSG_NOT_FOUND', 'El recurso solicitado no fue encontrado');
if (!defined('MSG_SERVER_ERROR')) define('MSG_SERVER_ERROR', 'Ocurrió un error en el servidor');
if (!defined('MSG_VALIDATION_ERROR')) define('MSG_VALIDATION_ERROR', 'Por favor, corrige los errores del formulario');
if (!defined('MSG_SUCCESS_CREATE')) define('MSG_SUCCESS_CREATE', 'Registro creado exitosamente');
if (!defined('MSG_SUCCESS_UPDATE')) define('MSG_SUCCESS_UPDATE', 'Registro actualizado exitosamente');
if (!defined('MSG_SUCCESS_DELETE')) define('MSG_SUCCESS_DELETE', 'Registro eliminado exitosamente');
if (!defined('MSG_ERROR_CREATE')) define('MSG_ERROR_CREATE', 'Error al crear el registro');
if (!defined('MSG_ERROR_UPDATE')) define('MSG_ERROR_UPDATE', 'Error al actualizar el registro');
if (!defined('MSG_ERROR_DELETE')) define('MSG_ERROR_DELETE', 'Error al eliminar el registro');

// ==================== ESTADOS DE ALERTA ====================
if (!defined('ALERTA_BAJA_OCUPACION')) define('ALERTA_BAJA_OCUPACION', 30);
if (!defined('ALERTA_ALTA_OCUPACION')) define('ALERTA_ALTA_OCUPACION', 90);
if (!defined('ALERTA_PAGO_PENDIENTE_DIAS')) define('ALERTA_PAGO_PENDIENTE_DIAS', 3);
if (!defined('ALERTA_MANTENIMIENTO_DIAS')) define('ALERTA_MANTENIMIENTO_DIAS', 7);

// ⚠️ ELIMINAR ESTAS CONSTANTES (ya están en config/app.php)
// define('LOG_ENABLED', true);
// define('LOG_LEVEL', 'info');
// define('LOG_PATH', __DIR__ . '/../logs/');
// define('LOG_FILE', 'app.log');

if (!defined('LOG_MAX_SIZE')) define('LOG_MAX_SIZE', 10485760);

// ==================== CONFIGURACIÓN DE CACHÉ ====================
if (!defined('CACHE_ENABLED')) define('CACHE_ENABLED', false);
if (!defined('CACHE_LIFETIME')) define('CACHE_LIFETIME', 3600);
if (!defined('CACHE_PATH')) define('CACHE_PATH', __DIR__ . '/../storage/cache/');

// ⚠️ ELIMINAR BASE_URL (ya se define en config/app.php o se calcula dinámicamente)
// Esta función puede causar conflictos
/*
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/public/index.php', '', $scriptName);
    return $protocol . '://' . $host . $basePath;
}
define('BASE_URL', getBaseUrl());
*/

if (!defined('ASSET_URL')) define('ASSET_URL', BASE_URL . '/public');
if (!defined('API_URL')) define('API_URL', BASE_URL . '/api');

// ==================== CONFIGURACIÓN DE RESPALDO ====================
if (!defined('BACKUP_ENABLED')) define('BACKUP_ENABLED', false);
if (!defined('BACKUP_PATH')) define('BACKUP_PATH', __DIR__ . '/../backups/');
if (!defined('BACKUP_FREQUENCY')) define('BACKUP_FREQUENCY', 'daily');
if (!defined('BACKUP_RETENTION_DAYS')) define('BACKUP_RETENTION_DAYS', 30);

// ==================== CONFIGURACIÓN DE REPORTES ====================
if (!defined('REPORT_PATH')) define('REPORT_PATH', __DIR__ . '/../storage/reports/');
if (!defined('REPORT_FORMATS')) define('REPORT_FORMATS', ['pdf', 'excel', 'csv']);

// ==================== CONFIGURACIÓN DE API ====================
if (!defined('API_ENABLED')) define('API_ENABLED', false);
if (!defined('API_VERSION')) define('API_VERSION', 'v1');
if (!defined('API_RATE_LIMIT')) define('API_RATE_LIMIT', 100);
if (!defined('API_KEY_REQUIRED')) define('API_KEY_REQUIRED', true);

// ==================== CONFIGURACIÓN DE MANTENIMIENTO ====================
if (!defined('MAINTENANCE_MODE')) define('MAINTENANCE_MODE', false);
if (!defined('MAINTENANCE_MESSAGE')) define('MAINTENANCE_MESSAGE', 'Estamos realizando mantenimiento. Volveremos pronto.');
if (!defined('MAINTENANCE_ALLOWED_IPS')) define('MAINTENANCE_ALLOWED_IPS', ['127.0.0.1', '::1']);

// ==================== VALIDACIONES ====================
if (!defined('VALIDATE_EMAIL')) define('VALIDATE_EMAIL', true);
if (!defined('VALIDATE_PHONE')) define('VALIDATE_PHONE', true);
if (!defined('VALIDATE_DNI')) define('VALIDATE_DNI', true);
if (!defined('VALIDATE_DATES')) define('VALIDATE_DATES', true);

// ==================== LÍMITES DEL SISTEMA ====================
if (!defined('MAX_HABITACIONES_POR_TIPO')) define('MAX_HABITACIONES_POR_TIPO', 100);
if (!defined('MAX_RESERVAS_POR_CLIENTE_ACTIVAS')) define('MAX_RESERVAS_POR_CLIENTE_ACTIVAS', 5);
if (!defined('MAX_PAGOS_POR_RESERVA')) define('MAX_PAGOS_POR_RESERVA', 10);
if (!defined('MAX_IMAGENES_POR_HABITACION')) define('MAX_IMAGENES_POR_HABITACION', 5);

// ==================== CONFIGURACIÓN DE BÚSQUEDA ====================
if (!defined('SEARCH_MIN_CHARACTERS')) define('SEARCH_MIN_CHARACTERS', 3);
if (!defined('SEARCH_MAX_RESULTS')) define('SEARCH_MAX_RESULTS', 50);

// ==================== TIEMPOS DE EXPIRACIÓN ====================
if (!defined('RESERVA_PENDIENTE_EXPIRACION_HORAS')) define('RESERVA_PENDIENTE_EXPIRACION_HORAS', 24);
if (!defined('TOKEN_RECUPERACION_EXPIRACION_MINUTOS')) define('TOKEN_RECUPERACION_EXPIRACION_MINUTOS', 30);
if (!defined('CODIGO_VERIFICACION_EXPIRACION_MINUTOS')) define('CODIGO_VERIFICACION_EXPIRACION_MINUTOS', 10);

// ==================== CONFIGURACIÓN DE NOTIFICACIONES PUSH ====================
if (!defined('PUSH_NOTIFICATIONS_ENABLED')) define('PUSH_NOTIFICATIONS_ENABLED', false);
if (!defined('FIREBASE_API_KEY')) define('FIREBASE_API_KEY', '');
if (!defined('FIREBASE_PROJECT_ID')) define('FIREBASE_PROJECT_ID', '');

// ==================== REDES SOCIALES ====================
if (!defined('FACEBOOK_URL')) define('FACEBOOK_URL', 'https://facebook.com/hotelreservas');
if (!defined('TWITTER_URL')) define('TWITTER_URL', 'https://twitter.com/hotelreservas');
if (!defined('INSTAGRAM_URL')) define('INSTAGRAM_URL', 'https://instagram.com/hotelreservas');
if (!defined('WHATSAPP_NUMBER')) define('WHATSAPP_NUMBER', '+51999999999');

// ==================== INFORMACIÓN DEL HOTEL ====================
if (!defined('HOTEL_NAME')) define('HOTEL_NAME', 'Hotel Reservas');
if (!defined('HOTEL_ADDRESS')) define('HOTEL_ADDRESS', 'Av. Principal 123, Lima, Perú');
if (!defined('HOTEL_PHONE')) define('HOTEL_PHONE', '+51 1 234-5678');
if (!defined('HOTEL_EMAIL')) define('HOTEL_EMAIL', 'info@hotel.com');
if (!defined('HOTEL_WEBSITE')) define('HOTEL_WEBSITE', 'www.hotel.com');
if (!defined('HOTEL_RUC')) define('HOTEL_RUC', '20123456789');
if (!defined('HOTEL_RAZONSOCIAL')) define('HOTEL_RAZONSOCIAL', 'Hotel Reservas S.A.C.');

// ==================== CONFIGURACIÓN DE DESARROLLO ====================
// ⚠️ No redefinir estas constantes si ya existen
if (!defined('DISPLAY_ERRORS')) {
    if (APP_ENV === 'development') {
        define('DISPLAY_ERRORS', true);
        define('ERROR_REPORTING', E_ALL);
        define('DEBUG_MODE', true);
    } else {
        define('DISPLAY_ERRORS', false);
        define('ERROR_REPORTING', E_ERROR | E_WARNING);
        define('DEBUG_MODE', false);
    }
    
    // Aplicar configuración de errores
    error_reporting(ERROR_REPORTING);
    ini_set('display_errors', DISPLAY_ERRORS ? 1 : 0);
}

// Configurar zona horaria (solo si no se ha configurado antes)
if (!ini_get('date.timezone')) {
    date_default_timezone_set(TIMEZONE);
}