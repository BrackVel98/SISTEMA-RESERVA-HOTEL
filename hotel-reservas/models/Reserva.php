<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\models\Reserva.php

require_once __DIR__ . '/../core/Model.php';

class Reserva extends Model
{
    protected $table = 'reservas';
    
    // AGREGAR precio_noche aquí
    protected $fillable = [
        'cliente_id',
        'habitacion_id',
        'fecha_entrada',
        'fecha_salida',
        'num_noches',
        'precio_noche',
        'precio_total',
        'estado',
        'observaciones'
    ];
    
    /**
     * Obtener todas las reservas con detalles
     */
    public function getAllConDetalles()
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, 
                       c.apellido as cliente_apellido,
                       c.email as cliente_email,
                       c.telefono as cliente_telefono,
                       h.numero as habitacion_numero,
                       h.estado as habitacion_estado,
                       th.nombre as tipo_habitacion,
                       th.precio_base
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                ORDER BY r.created_at DESC";
        
        return Database::query($sql, []);
    }
    
    /**
     * Obtener reserva por ID con detalles
     */
    public function getConDetalles($id)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.apellido as cliente_apellido,
                       c.email as cliente_email,
                       c.telefono as cliente_telefono,
                       c.documento as cliente_documento,
                       h.numero as habitacion_numero,
                       h.piso as habitacion_piso,
                       h.estado as habitacion_estado,
                       th.nombre as tipo_habitacion,
                       th.precio_base,
                       th.descripcion as tipo_descripcion
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE r.id = ?";
        
        return Database::queryOne($sql, [$id]);
    }
    
    /**
     * Obtener check-ins de hoy
     */
    public function getCheckInsHoy()
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.telefono as cliente_telefono,
                       h.numero as habitacion_numero,
                       th.nombre as tipo_habitacion
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE DATE(r.fecha_entrada) = CURDATE()
                  AND r.estado IN ('confirmada', 'pendiente')
                ORDER BY r.fecha_entrada ASC";
        
        return Database::query($sql, []);
    }
    
    /**
     * Obtener check-outs de hoy
     */
    public function getCheckOutsHoy()
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.telefono as cliente_telefono,
                       h.numero as habitacion_numero,
                       th.nombre as tipo_habitacion
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE DATE(r.fecha_salida) = CURDATE()
                  AND r.estado = 'confirmada'
                ORDER BY r.fecha_salida ASC";
        
        return Database::query($sql, []);
    }
    
    /**
     * Obtener reservas activas
     */
    public function getReservasActivas()
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       h.numero as habitacion_numero
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                WHERE r.estado = 'confirmada'
                  AND r.fecha_salida >= CURDATE()
                ORDER BY r.fecha_entrada ASC";
        
        return Database::query($sql, []);
    }
    
    /**
     * Contar reservas por estado
     */
    public function countByEstado($estado)
    {
        return $this->count(['estado' => $estado]);
    }
    
    /**
     * Confirmar reserva
     */
    public function confirmar($id)
    {
        return $this->update($id, ['estado' => 'confirmada']);
    }
    
    /**
     * Cancelar reserva
     */
    public function cancelar($id)
    {
        return $this->update($id, ['estado' => 'cancelada']);
    }
    
    /**
     * Completar reserva
     */
    public function completar($id)
    {
        return $this->update($id, ['estado' => 'completada']);
    }
    
    /**
     * Verificar disponibilidad de habitación
     */
    public function verificarDisponibilidad($habitacion_id, $fecha_entrada, $fecha_salida, $reserva_id = null)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} 
                WHERE habitacion_id = ? 
                  AND estado IN ('pendiente', 'confirmada')
                  AND (
                    (fecha_entrada <= ? AND fecha_salida >= ?) OR
                    (fecha_entrada <= ? AND fecha_salida >= ?) OR
                    (fecha_entrada >= ? AND fecha_salida <= ?)
                  )";
        
        $params = [
            $habitacion_id,
            $fecha_entrada, $fecha_entrada,
            $fecha_salida, $fecha_salida,
            $fecha_entrada, $fecha_salida
        ];
        
        if ($reserva_id) {
            $sql .= " AND id != ?";
            $params[] = $reserva_id;
        }
        
        $result = Database::queryOne($sql, $params);
        return $result ? (int)$result['total'] === 0 : true;
    }
    
    /**
     * Calcular número de noches
     */
    public function calcularNoches($fecha_entrada, $fecha_salida)
    {
        try {
            $entrada = new DateTime($fecha_entrada);
            $salida = new DateTime($fecha_salida);
            $noches = $entrada->diff($salida)->days;
            
            return $noches > 0 ? $noches : 1;
            
        } catch (Exception $e) {
            error_log("Error al calcular noches: " . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Calcular precio total de reserva
     */
    public function calcularPrecioTotal($habitacion_id, $fecha_entrada, $fecha_salida)
    {
        try {
            $sql = "SELECT th.precio_base 
                    FROM habitaciones h
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE h.id = ?";
            
            $result = Database::queryOne($sql, [$habitacion_id]);
            
            if (!$result) {
                return 0;
            }
            
            $precioBase = (float)$result['precio_base'];
            $noches = $this->calcularNoches($fecha_entrada, $fecha_salida);
            
            return $precioBase * $noches;
            
        } catch (Exception $e) {
            error_log("Error al calcular precio: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener precio base de habitación
     */
    public function obtenerPrecioNoche($habitacion_id)
    {
        try {
            $sql = "SELECT th.precio_base 
                    FROM habitaciones h
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE h.id = ?";
            
            $result = Database::queryOne($sql, [$habitacion_id]);
            
            return $result ? (float)$result['precio_base'] : 0;
            
        } catch (Exception $e) {
            error_log("Error al obtener precio: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener reservas por cliente
     */
    public function getPorCliente($cliente_id)
    {
        $sql = "SELECT r.*, 
                       h.numero as habitacion_numero,
                       th.nombre as tipo_habitacion
                FROM {$this->table} r
                INNER JOIN habitaciones h ON r.habitacion_id = h.id
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE r.cliente_id = ?
                ORDER BY r.fecha_entrada DESC";
        
        return Database::query($sql, [$cliente_id]);
    }
    
    /**
     * Obtener reservas por habitación
     */
    public function getPorHabitacion($habitacion_id)
    {
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre,
                       c.telefono as cliente_telefono
                FROM {$this->table} r
                INNER JOIN clientes c ON r.cliente_id = c.id
                WHERE r.habitacion_id = ?
                ORDER BY r.fecha_entrada DESC";
        
        return Database::query($sql, [$habitacion_id]);
    }
    
    /**
     * Obtener reservas con saldo pendiente
     */
    public function getConSaldoPendiente()
    {
        $sql = "SELECT r.id, 
                   r.codigo as codigo_reserva, 
                   CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                   r.precio_total,
                   COALESCE((SELECT SUM(p.monto) 
                             FROM pagos p 
                             WHERE p.reserva_id = r.id 
                             AND p.estado = 'completado'), 0) as pagado,
                   (r.precio_total - COALESCE((SELECT SUM(p.monto) 
                                                FROM pagos p 
                                                WHERE p.reserva_id = r.id 
                                                AND p.estado = 'completado'), 0)) as saldo
            FROM reservas r
            INNER JOIN clientes c ON r.cliente_id = c.id
            WHERE r.estado IN ('confirmada', 'pendiente')
            AND (r.precio_total - COALESCE((SELECT SUM(p.monto) 
                                            FROM pagos p 
                                            WHERE p.reserva_id = r.id 
                                            AND p.estado = 'completado'), 0)) > 0
            ORDER BY r.codigo ASC";
    
        try {
            return Database::query($sql, []);
        } catch (Exception $e) {
            error_log("Error en getConSaldoPendiente: " . $e->getMessage());
            return [];
        }
    }
}