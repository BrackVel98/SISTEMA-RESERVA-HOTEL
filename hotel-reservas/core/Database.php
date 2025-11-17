<?php

/**
 * Clase para manejo de conexión a base de datos
 * Implementa patrón Singleton para una única instancia de conexión
 */
class Database
{
    private static $instance = null;
    private $connection;
    
    /**
     * Constructor privado (patrón Singleton)
     */
    private function __construct()
    {
        try {
            // Usar require_once para evitar múltiples inclusiones
            $config = require_once __DIR__ . '/../config/database.php';
            
            // Construir DSN
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['database'],
                $config['charset']
            );
            
            // Crear conexión PDO
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options'] ?? [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
        } catch (PDOException $e) {
            // Registrar error en log
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            error_log("DSN utilizado: mysql:host={$config['host']};dbname={$config['database']}");
            
            // Mostrar error en desarrollo, mensaje genérico en producción
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            } else {
                die("Error de conexión a la base de datos. Por favor, contacta al administrador.");
            }
        }
    }
    
    /**
     * Obtener instancia única de la conexión
     * 
     * @return PDO Instancia de conexión PDO
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance->connection;
    }
    
    /**
     * Ejecutar query y obtener múltiples resultados
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parámetros de la consulta
     * @return array Resultados de la consulta
     */
    public static function query($sql, $params = [])
    {
        try {
            $db = self::getInstance();
            
            // Asegurar que params sea un array
            if (!is_array($params)) {
                $params = [$params];
            }
            
            // Ejecutar query según tenga o no parámetros
            if (empty($params)) {
                $stmt = $db->query($sql);
            } else {
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error en Database::query()");
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            error_log("Error: " . $e->getMessage());
            
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                throw $e;
            }
            
            return [];
        }
    }
    
    /**
     * Ejecutar query y obtener UN SOLO resultado
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parámetros de la consulta
     * @return array|null Resultado de la consulta o null
     */
    public static function queryOne($sql, $params = [])
    {
        try {
            $db = self::getInstance();
            
            // Asegurar que params sea un array
            if (!is_array($params)) {
                $params = [$params];
            }
            
            // Ejecutar query según tenga o no parámetros
            if (empty($params)) {
                $stmt = $db->query($sql);
            } else {
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : null;
            
        } catch (PDOException $e) {
            error_log("Error en Database::queryOne()");
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            error_log("Error: " . $e->getMessage());
            
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                throw $e;
            }
            
            return null;
        }
    }
    
    /**
     * Ejecutar query sin retorno (INSERT, UPDATE, DELETE)
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parámetros de la consulta
     * @return bool True si tuvo éxito, false en caso contrario
     */
    public static function execute($sql, $params = [])
    {
        try {
            $db = self::getInstance();
            
            // Asegurar que params sea un array
            if (!is_array($params)) {
                $params = [$params];
            }
            
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("Error en Database::execute()");
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            error_log("Error: " . $e->getMessage());
            
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                throw $e;
            }
            
            return false;
        }
    }
    
    /**
     * Obtener el último ID insertado
     * 
     * @return int ID del último registro insertado
     */
    public static function lastInsertId()
    {
        try {
            $db = self::getInstance();
            return (int) $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en Database::lastInsertId(): " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Iniciar transacción
     * 
     * @return bool
     */
    public static function beginTransaction()
    {
        try {
            $db = self::getInstance();
            return $db->beginTransaction();
        } catch (PDOException $e) {
            error_log("Error al iniciar transacción: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Confirmar transacción
     * 
     * @return bool
     */
    public static function commit()
    {
        try {
            $db = self::getInstance();
            return $db->commit();
        } catch (PDOException $e) {
            error_log("Error al confirmar transacción: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Revertir transacción
     * 
     * @return bool
     */
    public static function rollback()
    {
        try {
            $db = self::getInstance();
            return $db->rollBack();
        } catch (PDOException $e) {
            error_log("Error al revertir transacción: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Prevenir clonación de la instancia
     */
    private function __clone() 
    {
        // No hacer nada, evita clonar la instancia
    }
    
    /**
     * Prevenir deserialización de la instancia
     */
    public function __wakeup()
    {
        throw new Exception("No se puede deserializar un singleton.");
    }

    
}