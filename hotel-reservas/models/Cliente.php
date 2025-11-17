<?php
/**
 * Modelo Cliente
 */

require_once __DIR__ . '/../core/Model.php';

class Cliente extends Model
{
    protected $table = 'clientes';
    
    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido',
        'documento',
        'tipo_documento',
        'email',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'nacionalidad'
    ];
    
    /**
     * Obtener todos los clientes con datos completos
     */
    public function getAllConDetalles()
    {
        try {
            $sql = "SELECT c.*,
                           COUNT(DISTINCT r.id) as total_reservas,
                           SUM(CASE WHEN r.estado = 'confirmada' THEN 1 ELSE 0 END) as reservas_confirmadas,
                           SUM(CASE WHEN r.estado = 'completada' THEN 1 ELSE 0 END) as reservas_completadas
                    FROM {$this->table} c
                    LEFT JOIN reservas r ON c.id = r.cliente_id
                    GROUP BY c.id
                    ORDER BY c.created_at DESC";
            
            return Database::query($sql, []);
        } catch (Exception $e) {
            error_log("Error al obtener clientes con detalles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar cliente por email
     */
    public function buscarPorEmail($email)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
            return Database::queryOne($sql, [$email]);
        } catch (Exception $e) {
            error_log("Error al buscar cliente por email: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar cliente por documento
     */
    public function buscarPorDocumento($documento)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE documento = ? LIMIT 1";
            return Database::queryOne($sql, [$documento]);
        } catch (Exception $e) {
            error_log("Error al buscar cliente por documento: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtener cliente con sus reservas
     */
    public function getConReservas($cliente_id)
    {
        try {
            $cliente = $this->find($cliente_id);
            
            if (!$cliente) {
                return null;
            }
            
            $sql = "SELECT r.*, 
                           h.numero as habitacion_numero,
                           h.piso as habitacion_piso,
                           th.nombre as tipo_habitacion_nombre,
                           th.precio_base
                    FROM reservas r
                    INNER JOIN habitaciones h ON r.habitacion_id = h.id
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE r.cliente_id = ?
                    ORDER BY r.fecha_entrada DESC";
            
            $reservas = Database::query($sql, [$cliente_id]);
            $cliente['reservas'] = $reservas ?: [];
            
            return $cliente;
            
        } catch (Exception $e) {
            error_log("Error al obtener cliente con reservas: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtener estadísticas del cliente
     */
    public function getEstadisticas($cliente_id)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_reservas,
                        SUM(CASE WHEN estado = 'confirmada' THEN 1 ELSE 0 END) as reservas_confirmadas,
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as reservas_pendientes,
                        SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) as reservas_canceladas,
                        SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as reservas_completadas,
                        COALESCE(SUM(precio_total), 0) as total_gastado,
                        COALESCE(AVG(precio_total), 0) as promedio_gasto
                    FROM reservas
                    WHERE cliente_id = ?";
            
            $result = Database::queryOne($sql, [$cliente_id]);
            
            return $result ?: [
                'total_reservas' => 0,
                'reservas_confirmadas' => 0,
                'reservas_pendientes' => 0,
                'reservas_canceladas' => 0,
                'reservas_completadas' => 0,
                'total_gastado' => 0,
                'promedio_gasto' => 0
            ];
            
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas del cliente: " . $e->getMessage());
            return [
                'total_reservas' => 0,
                'reservas_confirmadas' => 0,
                'reservas_pendientes' => 0,
                'reservas_canceladas' => 0,
                'reservas_completadas' => 0,
                'total_gastado' => 0,
                'promedio_gasto' => 0
            ];
        }
    }
    
    /**
     * Validar datos del cliente
     */
    public function validar($datos, $id = null)
    {
        $errores = [];
        
        // Validar nombre
        if (empty($datos['nombre'])) {
            $errores[] = 'El nombre es obligatorio';
        } elseif (strlen($datos['nombre']) < 2) {
            $errores[] = 'El nombre debe tener al menos 2 caracteres';
        }
        
        // Validar apellido
        if (empty($datos['apellido'])) {
            $errores[] = 'El apellido es obligatorio';
        } elseif (strlen($datos['apellido']) < 2) {
            $errores[] = 'El apellido debe tener al menos 2 caracteres';
        }
        
        // Validar documento
        if (empty($datos['documento'])) {
            $errores[] = 'El documento es obligatorio';
        } else {
            $sql = "SELECT id FROM {$this->table} WHERE documento = ?";
            $params = [$datos['documento']];
            
            if ($id) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            
            $existente = Database::queryOne($sql, $params);
            if ($existente) {
                $errores[] = 'Ya existe un cliente con ese documento';
            }
        }
        
        // Validar email
        if (empty($datos['email'])) {
            $errores[] = 'El email es obligatorio';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido';
        } else {
            $sql = "SELECT id FROM {$this->table} WHERE email = ?";
            $params = [$datos['email']];
            
            if ($id) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            
            $existente = Database::queryOne($sql, $params);
            if ($existente) {
                $errores[] = 'Ya existe un cliente con ese email';
            }
        }
        
        // Validar teléfono
        if (empty($datos['telefono'])) {
            $errores[] = 'El teléfono es obligatorio';
        } elseif (strlen($datos['telefono']) < 7) {
            $errores[] = 'El teléfono debe tener al menos 7 dígitos';
        }
        
        return $errores;
    }
}