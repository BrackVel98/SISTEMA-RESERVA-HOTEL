<?php
/**
 * Modelo Pago
 */

require_once __DIR__ . '/../core/Model.php';

class Pago extends Model
{
    protected $table = 'pagos';
    
    // Campos que se pueden llenar
    protected $fillable = [
        'reserva_id',
        'monto',
        'metodo_pago',
        'estado',
        'fecha_pago',
        'referencia',
        'observaciones'
    ];
    
    // AGREGAR: Desactivar timestamps automáticos
    protected $timestamps = false;
    
    /**
     * Obtener todos los pagos con detalles
     */
    public function getAllConDetalles($filtros = [])
    {
        try {
            $sql = "SELECT p.*,
                           r.codigo as codigo_reserva,
                           CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                           c.documento as cliente_documento,
                           h.numero as habitacion_numero
                    FROM pagos p
                    INNER JOIN reservas r ON p.reserva_id = r.id
                    INNER JOIN clientes c ON r.cliente_id = c.id
                    INNER JOIN habitaciones h ON r.habitacion_id = h.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($filtros['estado'])) {
                $sql .= " AND p.estado = ?";
                $params[] = $filtros['estado'];
            }
            
            if (!empty($filtros['metodo_pago'])) {
                $sql .= " AND p.metodo_pago = ?";
                $params[] = $filtros['metodo_pago'];
            }
            
            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND DATE(p.fecha_pago) >= ?";
                $params[] = $filtros['fecha_desde'];
            }
            
            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND DATE(p.fecha_pago) <= ?";
                $params[] = $filtros['fecha_hasta'];
            }
            
            $sql .= " ORDER BY p.fecha_pago DESC, p.id DESC";
            
            return Database::query($sql, $params);
            
        } catch (Exception $e) {
            error_log("Error en Pago::getAllConDetalles - " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener pago con detalles
     */
    public function getConDetalles($id)
    {
        $sql = "SELECT p.*,
                   r.codigo as codigo_reserva,
                   r.fecha_entrada,
                   r.fecha_salida,
                   r.precio_total,
                   r.cliente_id,
                   CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                   c.email as cliente_email,
                   c.telefono as cliente_telefono,
                   c.documento as cliente_documento,
                   h.numero as habitacion_numero,
                   th.nombre as tipo_habitacion
            FROM pagos p
            INNER JOIN reservas r ON p.reserva_id = r.id
            INNER JOIN clientes c ON r.cliente_id = c.id
            INNER JOIN habitaciones h ON r.habitacion_id = h.id
            LEFT JOIN tipos_habitacion th ON h.tipo_id = th.id
            WHERE p.id = ?
            LIMIT 1";
    
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener pagos de una reserva
     */
    public function getPorReserva($reserva_id)
    {
        $sql = "SELECT * FROM pagos 
                WHERE reserva_id = ? 
                ORDER BY fecha_pago DESC";
        
        return $this->query($sql, [$reserva_id]);
    }
    
    /**
     * Obtener saldo pendiente de una reserva
     */
    public function getSaldoPendiente($reserva_id)
    {
        $sql = "SELECT 
                    r.precio_total,
                    COALESCE(SUM(p.monto), 0) as total_pagado,
                    (r.precio_total - COALESCE(SUM(p.monto), 0)) as saldo_pendiente
                FROM reservas r
                LEFT JOIN pagos p ON r.id = p.reserva_id AND p.estado = 'completado'
                WHERE r.id = ?
                GROUP BY r.id, r.precio_total";
        
        return $this->queryOne($sql, [$reserva_id]);
    }
    
    /**
     * Estadísticas de pagos
     */
    public function getEstadisticas($fecha_desde = null, $fecha_hasta = null)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_pagos,
                        COUNT(CASE WHEN estado = 'completado' THEN 1 END) as completados,
                        COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
                        COUNT(CASE WHEN estado = 'reembolsado' THEN 1 END) as reembolsados,
                        COALESCE(SUM(CASE WHEN estado = 'completado' THEN monto END), 0) as total_ingresos,
                        COUNT(CASE WHEN metodo_pago = 'efectivo' THEN 1 END) as pagos_efectivo,
                        COUNT(CASE WHEN metodo_pago = 'tarjeta' THEN 1 END) as pagos_tarjeta,
                        COUNT(CASE WHEN metodo_pago = 'transferencia' THEN 1 END) as pagos_transferencia
                    FROM pagos
                    WHERE 1=1";
            
            $params = [];
            
            if ($fecha_desde) {
                $sql .= " AND DATE(fecha_pago) >= ?";
                $params[] = $fecha_desde;
            }
            
            if ($fecha_hasta) {
                $sql .= " AND DATE(fecha_pago) <= ?";
                $params[] = $fecha_hasta;
            }
            
            $result = $this->queryOne($sql, $params);
            
            if ($result) {
                return [
                    'total_pagos' => (int)($result['total_pagos'] ?? 0),
                    'completados' => (int)($result['completados'] ?? 0),
                    'pendientes' => (int)($result['pendientes'] ?? 0),
                    'reembolsados' => (int)($result['reembolsados'] ?? 0),
                    'total_ingresos' => (float)($result['total_ingresos'] ?? 0),
                    'pagos_efectivo' => (int)($result['pagos_efectivo'] ?? 0),
                    'pagos_tarjeta' => (int)($result['pagos_tarjeta'] ?? 0),
                    'pagos_transferencia' => (int)($result['pagos_transferencia'] ?? 0)
                ];
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("Error en Pago::getEstadisticas - " . $e->getMessage());
            return null;
        }
    }
}