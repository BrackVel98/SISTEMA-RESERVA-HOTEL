<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\config\database.php

/**
 * Configuración de la base de datos
 * 
 * Este archivo define las constantes de conexión a la base de datos
 * y retorna un array con la configuración.
 */

// Definir constantes solo si no existen
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'hotel_reservas');
}

if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', 'e9u8xaln');
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

if (!defined('PDO_OPTIONS')) {
    define('PDO_OPTIONS', serialize([
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]));
}

// Retornar configuración como array
return [
    'host' => DB_HOST,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => DB_CHARSET,
    'options' => unserialize(PDO_OPTIONS)
];
