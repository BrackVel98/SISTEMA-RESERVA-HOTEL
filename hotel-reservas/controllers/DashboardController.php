<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\controllers\DashboardController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Pago.php';

class DashboardController extends Controller
{
    private $reservaModel;
    private $habitacionModel;
    private $clienteModel;
    private $pagoModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->reservaModel = new Reserva();
        $this->habitacionModel = new Habitacion();
        $this->clienteModel = new Cliente();
        $this->pagoModel = new Pago();
    }
    
    /**
     * Mostrar dashboard principal
     */
    public function index()
    {
        try {
            // Estadísticas básicas
            $totalReservas = $this->reservaModel->count();
            $reservasConfirmadas = $this->reservaModel->count(['estado' => 'confirmada']);
            $reservasPendientes = $this->reservaModel->count(['estado' => 'pendiente']);
            $totalHabitaciones = $this->habitacionModel->count();
            $habitacionesDisponibles = $this->habitacionModel->count(['estado' => 'disponible']);
            $habitacionesOcupadas = $this->habitacionModel->count(['estado' => 'ocupada']);
            $totalClientes = $this->clienteModel->count();
            
            // Check-ins de hoy (con manejo de errores)
            try {
                $checkInsHoy = $this->reservaModel->getCheckInsHoy();
            } catch (Exception $e) {
                error_log("Error al obtener check-ins: " . $e->getMessage());
                $checkInsHoy = [];
            }
            $reservasHoy = count($checkInsHoy);
            
            // Check-outs de hoy (con manejo de errores)
            try {
                $checkOutsHoy = $this->reservaModel->getCheckOutsHoy();
            } catch (Exception $e) {
                error_log("Error al obtener check-outs: " . $e->getMessage());
                $checkOutsHoy = [];
            }
            
            // Ingresos del mes (con manejo de errores)
            try {
                $sql = "SELECT COALESCE(SUM(monto), 0) as total 
                        FROM pagos 
                        WHERE MONTH(fecha_pago) = MONTH(CURDATE()) 
                          AND YEAR(fecha_pago) = YEAR(CURDATE())";
                $resultIngresos = Database::queryOne($sql, []);
                $ingresosMes = $resultIngresos ? (float)$resultIngresos['total'] : 0;
            } catch (Exception $e) {
                error_log("Error al obtener ingresos: " . $e->getMessage());
                $ingresosMes = 0;
            }
            
            // Reservas recientes (con manejo de errores)
            try {
                $sql = "SELECT r.*, 
                               c.nombre as cliente_nombre, 
                               h.numero as habitacion_numero
                        FROM reservas r
                        INNER JOIN clientes c ON r.cliente_id = c.id
                        INNER JOIN habitaciones h ON r.habitacion_id = h.id
                        ORDER BY r.created_at DESC
                        LIMIT 10";
                $reservasRecientes = Database::query($sql, []);
            } catch (Exception $e) {
                error_log("Error al obtener reservas recientes: " . $e->getMessage());
                $reservasRecientes = [];
            }
            
            // Reservas activas (con manejo de errores)
            try {
                $reservasActivas = $this->reservaModel->getReservasActivas();
            } catch (Exception $e) {
                error_log("Error al obtener reservas activas: " . $e->getMessage());
                $reservasActivas = [];
            }
            
            // Preparar datos para la vista
            $data = [
                'title' => 'Dashboard - ' . APP_NAME,
                'totalReservas' => $totalReservas,
                'reservasConfirmadas' => $reservasConfirmadas,
                'reservasPendientes' => $reservasPendientes,
                'reservasHoy' => $reservasHoy,
                'totalHabitaciones' => $totalHabitaciones,
                'habitacionesDisponibles' => $habitacionesDisponibles,
                'habitacionesOcupadas' => $habitacionesOcupadas,
                'totalClientes' => $totalClientes,
                'ingresosMes' => $ingresosMes,
                'checkInsHoy' => $checkInsHoy,
                'checkOutsHoy' => $checkOutsHoy,
                'reservasRecientes' => $reservasRecientes,
                'reservasActivas' => $reservasActivas
            ];
            
            $this->view('dashboard/index', $data);
            
        } catch (Exception $e) {
            error_log("Error CRÍTICO en Dashboard::index - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // NO mostrar error al usuario, mostrar dashboard vacío
            $data = [
                'title' => 'Dashboard - ' . APP_NAME,
                'totalReservas' => 0,
                'reservasConfirmadas' => 0,
                'reservasPendientes' => 0,
                'reservasHoy' => 0,
                'totalHabitaciones' => 0,
                'habitacionesDisponibles' => 0,
                'habitacionesOcupadas' => 0,
                'totalClientes' => 0,
                'ingresosMes' => 0,
                'checkInsHoy' => [],
                'checkOutsHoy' => [],
                'reservasRecientes' => [],
                'reservasActivas' => []
            ];
            
            $this->view('dashboard/index', $data);
        }
    }
}