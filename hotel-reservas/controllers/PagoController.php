<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\controllers\PagoController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Cliente.php';

class PagoController extends Controller
{
    private $pagoModel;
    private $reservaModel;
    private $clienteModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->pagoModel = new Pago();
        $this->reservaModel = new Reserva();
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Listar pagos
     */
    public function index()
    {
        try {
            // Obtener filtros
            $filtros = [
                'estado' => $_GET['estado'] ?? '',
                'metodo_pago' => $_GET['metodo_pago'] ?? '',
                'fecha_desde' => $_GET['fecha_desde'] ?? '',
                'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
            ];
            
            // Obtener pagos con filtros
            $pagos = $this->pagoModel->getAllConDetalles($filtros);
            
            // Obtener estadísticas (con valores por defecto si falla)
            try {
                $estadisticas = $this->pagoModel->getEstadisticas(
                    $filtros['fecha_desde'] ?: null,
                    $filtros['fecha_hasta'] ?: null
                );
            } catch (Exception $e) {
                error_log("Error al obtener estadísticas de pagos: " . $e->getMessage());
                $estadisticas = [
                    'total_pagos' => 0,
                    'completados' => 0,
                    'pendientes' => 0,
                    'cancelados' => 0,
                    'total_ingresos' => 0,
                    'pagos_efectivo' => 0,
                    'pagos_tarjeta' => 0,
                    'pagos_transferencia' => 0
                ];
            }
            
            // Si no hay estadísticas, usar valores por defecto
            if (!$estadisticas) {
                $estadisticas = [
                    'total_pagos' => 0,
                    'completados' => 0,
                    'pendientes' => 0,
                    'cancelados' => 0,
                    'total_ingresos' => 0,
                    'pagos_efectivo' => 0,
                    'pagos_tarjeta' => 0,
                    'pagos_transferencia' => 0
                ];
            }
            
            $data = [
                'title' => 'Pagos - ' . APP_NAME,
                'pagos' => $pagos ?? [],
                'estadisticas' => $estadisticas,
                'filtros' => $filtros
            ];
            
            $this->view('pagos/index', $data);
            
        } catch (Exception $e) {
            error_log("Error en PagoController::index - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Mostrar vista con datos vacíos en lugar de redirigir
            $data = [
                'title' => 'Pagos - ' . APP_NAME,
                'pagos' => [],
                'estadisticas' => [
                    'total_pagos' => 0,
                    'completados' => 0,
                    'pendientes' => 0,
                    'cancelados' => 0,
                    'total_ingresos' => 0,
                    'pagos_efectivo' => 0,
                    'pagos_tarjeta' => 0,
                    'pagos_transferencia' => 0
                ],
                'filtros' => [
                    'estado' => '',
                    'metodo_pago' => '',
                    'fecha_desde' => '',
                    'fecha_hasta' => ''
                ]
            ];
            
            $_SESSION['error'] = 'Error al cargar los pagos: ' . $e->getMessage();
            $this->view('pagos/index', $data);
        }
    }
    
    /**
     * Mostrar formulario de crear pago
     */
    public function create()
    {
        try {
            $reserva_id = $_GET['reserva_id'] ?? null;
            $reserva = null;
            $saldo = null;
            
            // Si viene reserva_id, cargar sus datos
            if ($reserva_id) {
                try {
                    $reserva = $this->reservaModel->getConDetalles($reserva_id);
                    
                    if (!$reserva) {
                        $_SESSION['error'] = 'Reserva no encontrada';
                        $this->redirect('/pagos');
                        return;
                    }
                    
                    // Obtener saldo pendiente
                    $saldo = $this->pagoModel->getSaldoPendiente($reserva_id);
                } catch (Exception $e) {
                    error_log("Error al cargar reserva: " . $e->getMessage());
                    $_SESSION['warning'] = 'No se pudo cargar la información de la reserva';
                }
            }
            
            // Obtener reservas con saldo pendiente usando el modelo
            $reservasPendientes = [];
            try {
                $reservasPendientes = $this->reservaModel->getConSaldoPendiente();
            } catch (Exception $e) {
                error_log("Error al cargar reservas pendientes: " . $e->getMessage());
                $_SESSION['warning'] = 'No se pudieron cargar las reservas con saldo pendiente';
                $reservasPendientes = [];
            }
            
            $data = [
                'title' => 'Registrar Pago - ' . APP_NAME,
                'reserva' => $reserva,
                'saldo' => $saldo,
                'reservasPendientes' => $reservasPendientes
            ];
            
            $this->view('pagos/create', $data);
            
        } catch (Exception $e) {
            error_log("Error en PagoController::create - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $_SESSION['error'] = 'Error al cargar el formulario: ' . $e->getMessage();
            $this->redirect('/pagos');
        }
    }
    
    /**
     * Guardar nuevo pago
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pagos');
            return;
        }
        
        try {
            $reserva_id = (int)($_POST['reserva_id'] ?? 0);
            $monto = (float)($_POST['monto'] ?? 0);
            $metodo_pago = trim($_POST['metodo_pago'] ?? '');
            $fecha_pago = trim($_POST['fecha_pago'] ?? date('Y-m-d'));
            $referencia = trim($_POST['referencia'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? ''); // CAMBIO: notas → observaciones
            
            // Validaciones
            $errores = [];
            
            if (empty($reserva_id)) {
                $errores[] = 'Debe seleccionar una reserva';
            } else {
                // Verificar que la reserva existe
                $reserva = $this->reservaModel->find($reserva_id);
                if (!$reserva) {
                    $errores[] = 'La reserva seleccionada no existe';
                } else {
                    // Verificar saldo pendiente
                    $saldo = $this->pagoModel->getSaldoPendiente($reserva_id);
                    if ($saldo && $monto > $saldo['saldo_pendiente']) {
                        $errores[] = 'El monto excede el saldo pendiente de $' . number_format($saldo['saldo_pendiente'], 2);
                    }
                }
            }
            
            if ($monto <= 0) {
                $errores[] = 'El monto debe ser mayor a cero';
            }
            
            if (!in_array($metodo_pago, ['efectivo', 'tarjeta', 'transferencia', 'otro'])) {
                $errores[] = 'Método de pago inválido';
            }
            
            if (empty($fecha_pago)) {
                $errores[] = 'La fecha de pago es obligatoria';
            }
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $_POST;
                $this->redirect('/pagos/crear' . ($reserva_id ? '?reserva_id=' . $reserva_id : ''));
                return;
            }
            
            // Crear pago
            $datos = [
                'reserva_id' => $reserva_id,
                'monto' => $monto,
                'metodo_pago' => $metodo_pago,
                'estado' => 'completado',
                'fecha_pago' => $fecha_pago . ' ' . date('H:i:s'),
                'referencia' => $referencia,
                'observaciones' => $observaciones // CAMBIO: notas → observaciones
            ];
            
            $id = $this->pagoModel->create($datos);
            
            if ($id) {
                $_SESSION['success'] = '✅ Pago registrado exitosamente por $' . number_format($monto, 2);
                $this->redirect('/pagos/' . $id);
            } else {
                throw new Exception('No se pudo registrar el pago');
            }
            
        } catch (Exception $e) {
            error_log("Error al crear pago: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al registrar el pago: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/pagos/crear');
        }
    }
    
    /**
     * Ver detalle de pago
     */
    public function show($id)
    {
        try {
            $pago = $this->pagoModel->getConDetalles($id);
            
            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado';
                $this->redirect('/pagos');
                return;
            }
            
            // Obtener todos los pagos de la reserva
            $pagosSimilares = $this->pagoModel->getPorReserva($pago['reserva_id']);
            
            // Calcular saldo
            $saldo = $this->pagoModel->getSaldoPendiente($pago['reserva_id']);
            
            $data = [
                'title' => 'Pago #' . $id . ' - ' . APP_NAME,
                'pago' => $pago,
                'pagosSimilares' => $pagosSimilares ?? [],
                'saldo' => $saldo
            ];
            
            $this->view('pagos/show', $data);
            
        } catch (Exception $e) {
            error_log("Error al mostrar pago: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el pago: ' . $e->getMessage();
            $this->redirect('/pagos');
        }
    }
    
    /**
     * Anular pago
     */
    public function anular($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pagos');
            return;
        }
        
        try {
            $pago = $this->pagoModel->find($id);
            
            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado';
                $this->redirect('/pagos');
                return;
            }
            
            if ($pago['estado'] == 'reembolsado') {
                $_SESSION['error'] = 'El pago ya está reembolsado';
                $this->redirect('/pagos/' . $id);
                return;
            }
            
            // Solo actualizar el estado (sin tocar observaciones)
            $datos = [
                'estado' => 'reembolsado'
            ];
            
            $success = $this->pagoModel->update($id, $datos);
            
            if ($success) {
                // Guardar motivo en la sesión para mostrarlo
                $motivo = trim($_POST['motivo'] ?? 'Sin motivo especificado');
                $_SESSION['success'] = '✅ Pago anulado exitosamente. Motivo: ' . htmlspecialchars($motivo);
            } else {
                throw new Exception('No se pudo anular el pago');
            }
            
            $this->redirect('/pagos/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al anular pago: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al anular el pago: ' . $e->getMessage();
            $this->redirect('/pagos/' . $id);
        }
    }
    
    /**
     * Generar recibo PDF
     */
    public function recibo($id)
    {
        try {
            $pago = $this->pagoModel->getConDetalles($id);
            
            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado';
                $this->redirect('/pagos');
                return;
            }
            
            $data = [
                'title' => 'Recibo de Pago #' . $id,
                'pago' => $pago
            ];
            
            $this->view('pagos/recibo', $data);
            
        } catch (Exception $e) {
            error_log("Error al generar recibo: " . $e->getMessage());
            $_SESSION['error'] = 'Error al generar el recibo: ' . $e->getMessage();
            $this->redirect('/pagos/' . $id);
        }
    }
}