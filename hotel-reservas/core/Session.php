<?php
/**
 * Clase Session - Manejo de sesiones
 */

class Session
{
    /**
     * Iniciar sesión
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Establecer valor en sesión
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtener valor de sesión
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verificar si existe una clave
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Eliminar valor de sesión
     */
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Establecer mensaje flash
     */
    public static function flash($key, $message = null)
    {
        self::start();
        if ($message === null) {
            // Obtener y eliminar el mensaje flash
            $value = self::get($key);
            self::remove($key);
            return $value;
        } else {
            // Establecer mensaje flash
            self::set($key, $message);
        }
    }

    /**
     * Destruir sesión completa
     */
    public static function destroy()
    {
        self::start();

        // Limpiar todas las variables de sesión
        $_SESSION = array();

        // Destruir la cookie de sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destruir la sesión
        session_destroy();
    }

    /**
     * Verificar si está autenticado
     */
    public static function isAuthenticated()
    {
        return self::has('user_id');
    }

    /**
     * Obtener ID de usuario autenticado
     */
    public static function getUserId()
    {
        return self::get('user_id');
    }

    /**
     * Obtener nombre de usuario autenticado
     */
    public static function getUserName()
    {
        return self::get('user_name');
    }

    /**
     * Obtener rol de usuario autenticado
     */
    public static function getUserRole()
    {
        return self::get('user_role');
    }
}