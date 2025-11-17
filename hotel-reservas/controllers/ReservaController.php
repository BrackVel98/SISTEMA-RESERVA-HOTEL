<?php
/**
 * Controlador de Reservas
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/TipoHabitacion.php';

class ReservaController extends Controller
{
    private $reservaModel;
    private $clienteModel;
    private $habitacionModel;
    private $tipoHabitacionModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->reservaModel = new Reserva();
        $this->clienteModel = new Cliente();
        $this->habitacionModel = new Habitacion();
        $this->tipoHabitacionModel = new TipoHabitacion();
    }
    
    /**
     * Listar reservas
     */
    public function index()
    {
        try {
            // Obtener filtros
            $estado = $_GET['estado'] ?? '';
            $fecha_desde = $_GET['fecha_desde'] ?? '';
            $fecha_hasta = $_GET['fecha_hasta'] ?? '';
            
            // Construir query con filtros
            $sql = "SELECT r.*, 
                           c.nombre as cliente_nombre,
                           c.apellido as cliente_apellido,
                           c.telefono as cliente_telefono,
                           c.email as cliente_email,
                           h.numero as habitacion_numero,
                           th.nombre as tipo_habitacion,
                           th.precio_base
                    FROM reservas r
                    INNER JOIN clientes c ON r.cliente_id = c.id
                    INNER JOIN habitaciones h ON r.habitacion_id = h.id
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($estado)) {
                $sql .= " AND r.estado = ?";
                $params[] = $estado;
            }
            
            if (!empty($fecha_desde)) {
                $sql .= " AND r.fecha_entrada >= ?";
                $params[] = $fecha_desde;
            }
            
            if (!empty($fecha_hasta)) {
                $sql .= " AND r.fecha_salida <= ?";
                $params[] = $fecha_hasta;
            }
            
            $sql .= " ORDER BY r.created_at DESC";
            
            $reservas = Database::query($sql, $params);
            
            // Obtener estadísticas
            $estadisticas = [
                'pendientes' => $this->reservaModel->countByEstado('pendiente'),
                'confirmadas' => $this->reservaModel->countByEstado('confirmada'),
                'completadas' => $this->reservaModel->countByEstado('completada'),
                'canceladas' => $this->reservaModel->countByEstado('cancelada'),
                'total_ingresos' => $this->calcularTotalIngresos()
            ];
            
            $data = [
                'title' => 'Reservas - ' . APP_NAME,
                'reservas' => $reservas,
                'estadisticas' => $estadisticas,
                'filtros' => [
                    'estado' => $estado,
                    'fecha_desde' => $fecha_desde,
                    'fecha_hasta' => $fecha_hasta
                ]
            ];
            
            $this->view('reservas/index', $data);
            
        } catch (Exception $e) {
            error_log("Error en ReservaController::index - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las reservas';
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Mostrar formulario de crear
     */
    public function create()
    {
        try {
            // Obtener parámetros de la URL
            $habitacion_id = $_GET['habitacion_id'] ?? '';
            $cliente_id = $_GET['cliente_id'] ?? '';
            $fecha_entrada = $_GET['fecha_entrada'] ?? date('Y-m-d');
            $fecha_salida = $_GET['fecha_salida'] ?? date('Y-m-d', strtotime('+1 day'));
            
            // Obtener todos los clientes
            $clientes = $this->clienteModel->all('nombre ASC, apellido ASC');
            
            // Obtener habitaciones disponibles
            $habitacionesDisponibles = $this->obtenerHabitacionesDisponibles($fecha_entrada, $fecha_salida);
            
            // Si se especificó una habitación, obtener sus datos
            $habitacionSeleccionada = null;
            if (!empty($habitacion_id)) {
                $habitacionSeleccionada = $this->habitacionModel->getConTipo($habitacion_id);
            }
            
            $data = [
                'title' => 'Nueva Reserva - ' . APP_NAME,
                'clientes' => $clientes,
                'habitacionesDisponibles' => $habitacionesDisponibles,
                'habitacion_id' => $habitacion_id,
                'cliente_id' => $cliente_id,
                'fecha_entrada' => $fecha_entrada,
                'fecha_salida' => $fecha_salida,
                'habitacionSeleccionada' => $habitacionSeleccionada
            ];
            
            $this->view('reservas/create', $data);
            
        } catch (Exception $e) {
            error_log("Error en ReservaController::create - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el formulario';
            $this->redirect('/reservas');
        }
    }
    
    /**
     * Guardar nueva reserva
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reservas');
        }
        
        try {
            $cliente_id = (int)($_POST['cliente_id'] ?? 0);
            $habitacion_id = (int)($_POST['habitacion_id'] ?? 0);
            $fecha_entrada = trim($_POST['fecha_entrada'] ?? '');
            $fecha_salida = trim($_POST['fecha_salida'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');
            
            // Validaciones
            $errores = [];
            
            if (empty($cliente_id)) {
                $errores[] = 'Debe seleccionar un cliente';
            }
            
            if (empty($habitacion_id)) {
                $errores[] = 'Debe seleccionar una habitación';
            }
            
            if (empty($fecha_entrada)) {
                $errores[] = 'La fecha de entrada es obligatoria';
            }
            
            if (empty($fecha_salida)) {
                $errores[] = 'La fecha de salida es obligatoria';
            }
            
            if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
                $errores[] = 'La fecha de salida debe ser posterior a la fecha de entrada';
            }
            
            // Verificar disponibilidad
            if (!$this->reservaModel->verificarDisponibilidad($habitacion_id, $fecha_entrada, $fecha_salida)) {
                $errores[] = 'La habitación no está disponible en las fechas seleccionadas';
            }
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $_POST;
                $this->redirect('/reservas/crear');
                return;
            }
            
            // Calcular valores
            $num_noches = $this->reservaModel->calcularNoches($fecha_entrada, $fecha_salida);
            $precio_noche = $this->reservaModel->obtenerPrecioNoche($habitacion_id);
            $precio_total = $precio_noche * $num_noches;
            
            if ($precio_total <= 0) {
                throw new Exception('Error al calcular el precio de la reserva');
            }
            
            // Crear reserva con TODOS los campos
            $datos = [
                'cliente_id' => $cliente_id,
                'habitacion_id' => $habitacion_id,
                'fecha_entrada' => $fecha_entrada,
                'fecha_salida' => $fecha_salida,
                'num_noches' => $num_noches,
                'precio_noche' => $precio_noche,
                'precio_total' => $precio_total,
                'estado' => 'pendiente',
                'observaciones' => $observaciones
            ];
            
            $id = $this->reservaModel->create($datos);
            
            if ($id) {
                // Actualizar estado de habitación
                $this->habitacionModel->update($habitacion_id, ['estado' => 'ocupada']);
                
                $_SESSION['success'] = '✅ Reserva #' . $id . ' creada exitosamente' . 
                                  'Total: $' . number_format($precio_total, 2) . 
                                  ' (' . $num_noches . ' noche' . ($num_noches != 1 ? 's' : '') . ')';
                
                $this->redirect('/reservas/' . $id);
            } else {
                throw new Exception('No se pudo crear la reserva');
            }
            
        } catch (Exception $e) {
            error_log("❌ Error al crear reserva: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/reservas/crear');
        }
    }
    
    /**
     * Ver detalle de reserva
     */
    public function show($id)
    {
        try {
            $reserva = $this->reservaModel->getConDetalles($id);
            
            if (!$reserva) {
                $_SESSION['error'] = 'Reserva no encontrada';
                $this->redirect('/reservas');
                return;
            }
            
            // Obtener pagos de la reserva
            $sql = "SELECT * FROM pagos WHERE reserva_id = ? ORDER BY fecha_pago DESC";
            $pagos = Database::query($sql, [$id]);
            
            // Calcular totales
            $totalPagado = 0;
            foreach ($pagos as $pago) {
                if ($pago['estado'] == 'completado') {
                    $totalPagado += $pago['monto'];
                }
            }
            
            $saldoPendiente = $reserva['precio_total'] - $totalPagado;
            
            $data = [
                'title' => 'Reserva #' . $id . ' - ' . APP_NAME,
                'reserva' => $reserva,
                'pagos' => $pagos,
                'totalPagado' => $totalPagado,
                'saldoPendiente' => $saldoPendiente
            ];
            
            $this->view('reservas/show', $data);
            
        } catch (Exception $e) {
            error_log("Error al mostrar reserva: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la reserva';
            $this->redirect('/reservas');
        }
    }
    
    /**
     * Confirmar reserva
     */
    public function confirm($id)
    {
        try {
            $reserva = $this->reservaModel->find($id);
            
            if (!$reserva) {
                $_SESSION['error'] = 'Reserva no encontrada';
                $this->redirect('/reservas');
                return;
            }
            
            if ($reserva['estado'] !== 'pendiente') {
                $_SESSION['error'] = 'Solo se pueden confirmar reservas pendientes';
                $this->redirect('/reservas/' . $id);
                return;
            }
            
            $success = $this->reservaModel->confirmar($id);
            
            if ($success) {
                $_SESSION['success'] = '✅ Reserva confirmada exitosamente';
            } else {
                throw new Exception('No se pudo confirmar la reserva');
            }
            
            $this->redirect('/reservas/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al confirmar reserva: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al confirmar la reserva: ' . $e->getMessage();
            $this->redirect('/reservas/' . $id);
        }
    }
    
    /**
     * Cancelar reserva
     */
    public function cancel($id)
    {
        try {
            $reserva = $this->reservaModel->find($id);
            
            if (!$reserva) {
                $_SESSION['error'] = 'Reserva no encontrada';
                $this->redirect('/reservas');
                return;
            }
            
            if (!in_array($reserva['estado'], ['pendiente', 'confirmada'])) {
                $_SESSION['error'] = 'No se puede cancelar esta reserva';
                $this->redirect('/reservas/' . $id);
                return;
            }
            
            $success = $this->reservaModel->cancelar($id);
            
            if ($success) {
                // Liberar habitación
                $this->habitacionModel->update($reserva['habitacion_id'], ['estado' => 'disponible']);
                
                $_SESSION['success'] = '✅ Reserva cancelada exitosamente';
            } else {
                throw new Exception('No se pudo cancelar la reserva');
            }
            
            $this->redirect('/reservas/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al cancelar reserva: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al cancelar la reserva: ' . $e->getMessage();
            $this->redirect('/reservas/' . $id);
        }
    }
    
    /**
     * Obtener habitaciones disponibles en un rango de fechas
     */
    private function obtenerHabitacionesDisponibles($fecha_entrada, $fecha_salida)
    {
        try {
            $sql = "SELECT h.*, 
                           th.nombre as tipo_nombre,
                           th.descripcion as tipo_descripcion,
                           th.precio_base,
                           th.capacidad
                    FROM habitaciones h
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE h.estado = 'disponible'
                      AND h.id NOT IN (
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
                      )
                    ORDER BY th.precio_base ASC";
            
            $params = [
                $fecha_entrada,
                $fecha_entrada,
                $fecha_salida,
                $fecha_salida,
                $fecha_entrada,
                $fecha_salida
            ];
            
            return Database::query($sql, $params);
            
        } catch (Exception $e) {
            error_log("Error al obtener habitaciones disponibles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calcular total de ingresos
     */
    private function calcularTotalIngresos()
    {
        try {
            $sql = "SELECT COALESCE(SUM(precio_total), 0) as total 
                    FROM reservas 
                    WHERE estado IN ('confirmada', 'completada')";
            
            $result = Database::queryOne($sql, []);
            return $result ? (float)$result['total'] : 0;
            
        } catch (Exception $e) {
            error_log("Error al calcular ingresos: " . $e->getMessage());
            return 0;
        }
    }
}