<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\models\TipoHabitacion.php

require_once __DIR__ . '/../core/Model.php';

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'capacidad'
    ];
    
    /**
     * Obtener habitaciones de este tipo
     */
    public function habitaciones($tipo_id)
    {
        try {
            $sql = "SELECT * FROM habitaciones WHERE tipo_id = ? ORDER BY numero ASC";
            return Database::query($sql, [$tipo_id]);
        } catch (Exception $e) {
            error_log("Error al obtener habitaciones del tipo {$tipo_id}: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar habitaciones disponibles de este tipo
     */
    public function contarDisponibles($tipo_id)
    {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM habitaciones 
                    WHERE tipo_id = ? 
                      AND estado = 'disponible'";
            $result = Database::queryOne($sql, [$tipo_id]);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            error_log("Error al contar habitaciones disponibles: " . $e->getMessage());
            return 0;
        }
    }
}