<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\models\Habitacion.php

require_once __DIR__ . '/../core/Model.php';

class Habitacion extends Model
{
    protected $table = 'habitaciones';
    protected $fillable = ['numero', 'tipo_id', 'piso', 'estado', 'descripcion'];
    
    /**
     * Obtener habitación con información del tipo
     */
    public function getConTipo($id)
    {
        $sql = "SELECT h.*, 
                       th.nombre as tipo_nombre,
                       th.precio_base,
                       th.capacidad,
                       th.descripcion as tipo_descripcion
                FROM habitaciones h
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE h.id = ?
                LIMIT 1";
        
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener habitaciones disponibles para fechas
     */
    public function getDisponibles($fecha_entrada, $fecha_salida)
    {
        $sql = "SELECT h.*, 
                       th.nombre as tipo_nombre,
                       th.precio_base,
                       th.capacidad,
                       th.descripcion as tipo_descripcion
                FROM habitaciones h
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                WHERE h.estado = 'disponible'
                AND h.id NOT IN (
                    SELECT habitacion_id 
                    FROM reservas 
                    WHERE estado IN ('pendiente', 'confirmada')
                    AND (
                        (fecha_entrada <= ? AND fecha_salida >= ?)
                        OR (fecha_entrada <= ? AND fecha_salida >= ?)
                        OR (fecha_entrada >= ? AND fecha_salida <= ?)
                    )
                )
                ORDER BY th.nombre ASC, h.numero ASC";
        
        return $this->query($sql, [
            $fecha_entrada, $fecha_entrada,
            $fecha_salida, $fecha_salida,
            $fecha_entrada, $fecha_salida
        ]);
    }
    
    /**
     * Obtener todas las habitaciones con su tipo
     */
    public function getAllConTipo()
    {
        $sql = "SELECT h.*, 
                       th.nombre as tipo_nombre,
                       th.precio_base,
                       th.capacidad,
                       th.descripcion as tipo_descripcion
                FROM habitaciones h
                INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                ORDER BY h.numero ASC";
        
        return $this->query($sql, []);
    }
}