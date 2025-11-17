<?php
/**
 * Controlador de Habitaciones
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/TipoHabitacion.php';
require_once __DIR__ . '/../models/Reserva.php';

class HabitacionController extends Controller
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
     * Listar habitaciones
     */
    public function index()
    {
        try {
            // Obtener filtros
            $tipo_id = $_GET['tipo_id'] ?? '';
            $estado = $_GET['estado'] ?? '';
            $piso = $_GET['piso'] ?? '';
            
            // Construir query con filtros
            $sql = "SELECT h.*, 
                           th.nombre as tipo_nombre,
                           th.precio_base,
                           th.capacidad,
                           th.descripcion as tipo_descripcion
                    FROM habitaciones h
                    INNER JOIN tipos_habitacion th ON h.tipo_id = th.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($tipo_id)) {
                $sql .= " AND h.tipo_id = ?";
                $params[] = $tipo_id;
            }
            
            if (!empty($estado)) {
                $sql .= " AND h.estado = ?";
                $params[] = $estado;
            }
            
            if (!empty($piso)) {
                $sql .= " AND h.piso = ?";
                $params[] = $piso;
            }
            
            $sql .= " ORDER BY h.numero ASC";
            
            $habitaciones = Database::query($sql, $params);
            
            // Obtener todos los tipos para el filtro
            $tipos = $this->tipoHabitacionModel->all('nombre ASC');
            
            // Obtener estadísticas
            $estadisticas = [
                'total' => $this->habitacionModel->count([]),
                'disponibles' => $this->habitacionModel->count(['estado' => 'disponible']),
                'ocupadas' => $this->habitacionModel->count(['estado' => 'ocupada']),
                'mantenimiento' => $this->habitacionModel->count(['estado' => 'mantenimiento']),
                'limpieza' => $this->habitacionModel->count(['estado' => 'limpieza'])
            ];
            
            $data = [
                'title' => 'Habitaciones - ' . APP_NAME,
                'habitaciones' => $habitaciones,
                'tipos' => $tipos,
                'estadisticas' => $estadisticas,
                'filtros' => [
                    'tipo_id' => $tipo_id,
                    'estado' => $estado,
                    'piso' => $piso
                ]
            ];
            
            $this->view('habitaciones/index', $data);
            
        } catch (Exception $e) {
            error_log("Error en HabitacionController::index - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las habitaciones';
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Mostrar formulario de crear
     */
    public function create()
    {
        try {
            $tipos = $this->tipoHabitacionModel->all('nombre ASC');
            
            if (empty($tipos)) {
                $_SESSION['error'] = 'Primero debe crear tipos de habitación';
                $this->redirect('/habitaciones');
                return;
            }
            
            $data = [
                'title' => 'Nueva Habitación - ' . APP_NAME,
                'tipos' => $tipos
            ];
            
            $this->view('habitaciones/create', $data);
            
        } catch (Exception $e) {
            error_log("Error en HabitacionController::create - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el formulario';
            $this->redirect('/habitaciones');
        }
    }
    
    /**
     * Guardar nueva habitación
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/habitaciones');
        }
        
        try {
            $numero = trim($_POST['numero'] ?? '');
            $tipo_id = (int)($_POST['tipo_id'] ?? 0);
            $piso = trim($_POST['piso'] ?? '');
            $estado = trim($_POST['estado'] ?? 'disponible');
            $descripcion = trim($_POST['descripcion'] ?? '');
            
            // Validaciones
            $errores = [];
            
            if (empty($numero)) {
                $errores[] = 'El número de habitación es obligatorio';
            } else {
                // ✅ CORRECCIÓN: usar findBy('campo', 'valor')
                $existe = $this->habitacionModel->findBy('numero', $numero);
                if ($existe) {
                    $errores[] = 'Ya existe una habitación con ese número';
                }
            }
            
            if (empty($tipo_id)) {
                $errores[] = 'Debe seleccionar un tipo de habitación';
            }
            
            if (!in_array($estado, ['disponible', 'ocupada', 'mantenimiento', 'limpieza'])) {
                $errores[] = 'Estado inválido';
            }
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $_POST;
                $this->redirect('/habitaciones/crear');
                return;
            }
            
            // Crear habitación
            $datos = [
                'numero' => $numero,
                'tipo_id' => $tipo_id,
                'piso' => $piso,
                'estado' => $estado,
                'descripcion' => $descripcion
            ];
            
            $id = $this->habitacionModel->create($datos);
            
            if ($id) {
                $_SESSION['success'] = '✅ Habitación #' . $numero . ' creada exitosamente';
                $this->redirect('/habitaciones/' . $id);
            } else {
                throw new Exception('No se pudo crear la habitación');
            }
            
        } catch (Exception $e) {
            error_log("Error al crear habitación: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al crear la habitación: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/habitaciones/crear');
        }
    }
    
    /**
     * Ver detalle de habitación
     */
    public function show($id)
    {
        try {
            $habitacion = $this->habitacionModel->getConTipo($id);
            
            if (!$habitacion) {
                $_SESSION['error'] = 'Habitación no encontrada';
                $this->redirect('/habitaciones');
                return;
            }
            
            // Obtener reservas de la habitación
            $reservas = $this->reservaModel->getPorHabitacion($id);
            
            // Obtener reserva activa si existe
            $reservaActiva = null;
            foreach ($reservas as $reserva) {
                if ($reserva['estado'] == 'confirmada' && 
                    strtotime($reserva['fecha_entrada']) <= time() && 
                    strtotime($reserva['fecha_salida']) >= time()) {
                    $reservaActiva = $reserva;
                    break;
                }
            }
            
            $data = [
                'title' => 'Habitación ' . $habitacion['numero'] . ' - ' . APP_NAME,
                'habitacion' => $habitacion,
                'reservas' => $reservas,
                'reservaActiva' => $reservaActiva
            ];
            
            $this->view('habitaciones/show', $data);
            
        } catch (Exception $e) {
            error_log("Error al mostrar habitación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la habitación';
            $this->redirect('/habitaciones');
        }
    }
    
    /**
     * Mostrar formulario de editar
     */
    public function edit($id)
    {
        try {
            $habitacion = $this->habitacionModel->find($id);
            
            if (!$habitacion) {
                $_SESSION['error'] = 'Habitación no encontrada';
                $this->redirect('/habitaciones');
                return;
            }
            
            $tipos = $this->tipoHabitacionModel->all('nombre ASC');
            
            $data = [
                'title' => 'Editar Habitación ' . $habitacion['numero'] . ' - ' . APP_NAME,
                'habitacion' => $habitacion,
                'tipos' => $tipos
            ];
            
            $this->view('habitaciones/edit', $data);
            
        } catch (Exception $e) {
            error_log("Error al cargar edición: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el formulario';
            $this->redirect('/habitaciones');
        }
    }
    
    /**
     * Actualizar habitación
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/habitaciones');
        }
        
        try {
            $habitacion = $this->habitacionModel->find($id);
            
            if (!$habitacion) {
                $_SESSION['error'] = 'Habitación no encontrada';
                $this->redirect('/habitaciones');
                return;
            }
            
            $numero = trim($_POST['numero'] ?? '');
            $tipo_id = (int)($_POST['tipo_id'] ?? 0);
            $piso = trim($_POST['piso'] ?? '');
            $estado = trim($_POST['estado'] ?? 'disponible');
            $descripcion = trim($_POST['descripcion'] ?? '');
            
            // Validaciones
            $errores = [];
            
            if (empty($numero)) {
                $errores[] = 'El número de habitación es obligatorio';
            } else {
                // Verificar si ya existe (excluyendo la actual)
                $existe = Database::queryOne(
                    "SELECT id FROM habitaciones WHERE numero = ? AND id != ?",
                    [$numero, $id]
                );
                if ($existe) {
                    $errores[] = 'Ya existe otra habitación con ese número';
                }
            }
            
            if (empty($tipo_id)) {
                $errores[] = 'Debe seleccionar un tipo de habitación';
            }
            
            if (!in_array($estado, ['disponible', 'ocupada', 'mantenimiento', 'limpieza'])) {
                $errores[] = 'Estado inválido';
            }
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $_POST;
                $this->redirect('/habitaciones/' . $id . '/editar');
                return;
            }
            
            // Actualizar habitación
            $datos = [
                'numero' => $numero,
                'tipo_id' => $tipo_id,
                'piso' => $piso,
                'estado' => $estado,
                'descripcion' => $descripcion
            ];
            
            $success = $this->habitacionModel->update($id, $datos);
            
            if ($success) {
                $_SESSION['success'] = '✅ Habitación actualizada exitosamente';
                $this->redirect('/habitaciones/' . $id);
            } else {
                throw new Exception('No se pudo actualizar la habitación');
            }
            
        } catch (Exception $e) {
            error_log("Error al actualizar habitación: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al actualizar la habitación: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/habitaciones/' . $id . '/editar');
        }
    }
    
    /**
     * Eliminar habitación
     */
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/habitaciones');
        }
        
        try {
            $habitacion = $this->habitacionModel->find($id);
            
            if (!$habitacion) {
                $_SESSION['error'] = 'Habitación no encontrada';
                $this->redirect('/habitaciones');
                return;
            }
            
            // Verificar si tiene reservas activas
            $tieneReservas = Database::queryOne(
                "SELECT COUNT(*) as total FROM reservas 
                 WHERE habitacion_id = ? AND estado IN ('pendiente', 'confirmada')",
                [$id]
            );
            
            if ($tieneReservas && $tieneReservas['total'] > 0) {
                $_SESSION['error'] = '❌ No se puede eliminar: la habitación tiene reservas activas';
                $this->redirect('/habitaciones/' . $id);
                return;
            }
            
            $success = $this->habitacionModel->delete($id);
            
            if ($success) {
                $_SESSION['success'] = '✅ Habitación eliminada exitosamente';
                $this->redirect('/habitaciones');
            } else {
                throw new Exception('No se pudo eliminar la habitación');
            }
            
        } catch (Exception $e) {
            error_log("Error al eliminar habitación: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al eliminar la habitación: ' . $e->getMessage();
            $this->redirect('/habitaciones/' . $id);
        }
    }
    
    /**
     * Cambiar estado de habitación
     */
    public function cambiarEstado($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/habitaciones');
        }
        
        try {
            $habitacion = $this->habitacionModel->find($id);
            
            if (!$habitacion) {
                $_SESSION['error'] = 'Habitación no encontrada';
                $this->redirect('/habitaciones');
                return;
            }
            
            $nuevoEstado = trim($_POST['estado'] ?? '');
            
            if (!in_array($nuevoEstado, ['disponible', 'ocupada', 'mantenimiento', 'limpieza'])) {
                $_SESSION['error'] = 'Estado inválido';
                $this->redirect('/habitaciones/' . $id);
                return;
            }
            
            $success = $this->habitacionModel->update($id, ['estado' => $nuevoEstado]);
            
            if ($success) {
                $_SESSION['success'] = '✅ Estado actualizado a: ' . ucfirst($nuevoEstado);
            } else {
                throw new Exception('No se pudo actualizar el estado');
            }
            
            $this->redirect('/habitaciones/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al cambiar estado: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al cambiar el estado: ' . $e->getMessage();
            $this->redirect('/habitaciones/' . $id);
        }
    }
}