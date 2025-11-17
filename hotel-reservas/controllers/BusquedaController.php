<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\controllers\BusquedaController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/TipoHabitacion.php';
require_once __DIR__ . '/../models/Reserva.php';

class BusquedaController extends Controller
{
    private $habitacionModel;
    private $tipoHabitacionModel;
    private $reservaModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->habitacionModel = new Habitacion();
        $this->tipoHabitacionModel = new TipoHabitacion();
        $this->reservaModel = new Reserva();
    }
    
    /**
     * Mostrar formulario de búsqueda y resultados
     */
    public function index()
    {
        try {
            $tipo_id = $_GET['tipo'] ?? '';
            $fecha_entrada = $_GET['fecha_entrada'] ?? date('Y-m-d');
            $fecha_salida = $_GET['fecha_salida'] ?? date('Y-m-d', strtotime('+1 day'));
            
            $tiposHabitacion = $this->tipoHabitacionModel->all('nombre ASC');
            
            error_log("Tipos de habitación encontrados: " . count($tiposHabitacion));
            
            $busquedaActiva = isset($_GET['buscar']) || !empty($tipo_id);
            
            $habitacionesDisponibles = [];
            if ($busquedaActiva) {
                $habitacionesDisponibles = $this->buscarDisponibles($tipo_id, $fecha_entrada, $fecha_salida);
                error_log("Habitaciones encontradas: " . count($habitacionesDisponibles));
            }
            
            $data = [
                'title' => 'Buscar Habitaciones - ' . APP_NAME,
                'tiposHabitacion' => $tiposHabitacion,
                'tipo_id' => $tipo_id,
                'fecha_entrada' => $fecha_entrada,
                'fecha_salida' => $fecha_salida,
                'habitacionesDisponibles' => $habitacionesDisponibles,
                'busquedaActiva' => $busquedaActiva
            ];
            
            $this->view('busqueda/resultados', $data);
            
        } catch (Exception $e) {
            error_log("Error en BusquedaController::index - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Error al realizar la búsqueda: ' . $e->getMessage();
            $this->redirect('/');
        }
    }
    
    /**
     * Buscar habitaciones disponibles según criterios
     */
    private function buscarDisponibles($tipo_id, $fecha_entrada, $fecha_salida)
    {
        try {
            if (empty($fecha_entrada) || empty($fecha_salida)) {
                throw new Exception('Las fechas son requeridas');
            }
            
            if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
                throw new Exception('La fecha de salida debe ser posterior a la fecha de entrada');
            }
            
            $sql = "SELECT h.*, 
                           th.nombre as tipo_nombre,
                           th.descripcion as tipo_descripcion,
                           th.precio_base,
                           th.capacidad
                    FROM habitaciones h
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE h.estado = 'disponible'";
            
            $params = [];
            
            if (!empty($tipo_id)) {
                $sql .= " AND h.tipo_id = ?";
                $params[] = $tipo_id;
            }
            
            $sql .= " AND h.id NOT IN (
                        SELECT r.habitacion_id 
                        FROM reservas r 
                        WHERE r.estado IN ('confirmada', 'pendiente')
                          AND (
                              (? >= r.fecha_entrada AND ? < r.fecha_salida)
                              OR
                              (? > r.fecha_entrada AND ? <= r.fecha_salida)
                              OR
                              (? <= r.fecha_entrada AND ? >= r.fecha_salida)
                          )
                      )";
            
            $params[] = $fecha_entrada;
            $params[] = $fecha_entrada;
            $params[] = $fecha_salida;
            $params[] = $fecha_salida;
            $params[] = $fecha_entrada;
            $params[] = $fecha_salida;
            
            $sql .= " ORDER BY th.precio_base ASC";
            
            error_log("SQL de búsqueda: " . $sql);
            error_log("Parámetros: " . json_encode($params));
            
            $resultados = Database::query($sql, $params);
            
            return $resultados ?: [];
            
        } catch (Exception $e) {
            error_log("Error al buscar habitaciones disponibles: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}