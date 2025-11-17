<?php
/**
 * Controlador de Clientes
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController extends Controller
{
    private $clienteModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Listar clientes
     */
    public function index()
    {
        try {
            $clientes = $this->clienteModel->getAllConDetalles();
            
            $data = [
                'title' => 'Clientes - ' . APP_NAME,
                'clientes' => $clientes
            ];
            
            $this->view('clientes/index', $data);
            
        } catch (Exception $e) {
            error_log("Error en ClienteController::index - " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar los clientes';
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Mostrar formulario de crear
     */
    public function create()
    {
        $data = [
            'title' => 'Nuevo Cliente - ' . APP_NAME
        ];
        
        $this->view('clientes/create', $data);
    }
    
    /**
     * Guardar nuevo cliente
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/clientes');
        }
        
        try {
            $datos = [
                'usuario_id' => $_SESSION['user_id'],
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'documento' => trim($_POST['documento'] ?? ''),
                'tipo_documento' => trim($_POST['tipo_documento'] ?? 'DNI'),
                'email' => trim($_POST['email'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'fecha_nacimiento' => !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null,
                'nacionalidad' => trim($_POST['nacionalidad'] ?? 'Peruana')
            ];
            
            // Validar datos
            $errores = $this->clienteModel->validar($datos);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $datos;
                $this->redirect('/clientes/crear');
                return;
            }
            
            // Crear cliente
            $id = $this->clienteModel->create($datos);
            
            if ($id) {
                $_SESSION['success'] = '✅ Cliente creado exitosamente';
                $this->redirect('/clientes/' . $id);
            } else {
                throw new Exception('No se pudo crear el cliente');
            }
            
        } catch (Exception $e) {
            error_log("Error al crear cliente: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al crear el cliente: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/clientes/crear');
        }
    }
    
    /**
     * Ver detalle de cliente
     */
    public function show($id)
    {
        try {
            $cliente = $this->clienteModel->getConReservas($id);
            
            if (!$cliente) {
                $_SESSION['error'] = 'Cliente no encontrado';
                $this->redirect('/clientes');
                return;
            }
            
            $estadisticas = $this->clienteModel->getEstadisticas($id);
            
            $data = [
                'title' => $cliente['nombre'] . ' ' . $cliente['apellido'] . ' - ' . APP_NAME,
                'cliente' => $cliente,
                'estadisticas' => $estadisticas
            ];
            
            $this->view('clientes/show', $data);
            
        } catch (Exception $e) {
            error_log("Error al mostrar cliente: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el cliente';
            $this->redirect('/clientes');
        }
    }
    
    /**
     * Mostrar formulario de editar
     */
    public function edit($id)
    {
        try {
            $cliente = $this->clienteModel->find($id);
            
            if (!$cliente) {
                $_SESSION['error'] = 'Cliente no encontrado';
                $this->redirect('/clientes');
                return;
            }
            
            $data = [
                'title' => 'Editar Cliente - ' . APP_NAME,
                'cliente' => $cliente
            ];
            
            $this->view('clientes/edit', $data);
            
        } catch (Exception $e) {
            error_log("Error al cargar formulario de edición: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el cliente';
            $this->redirect('/clientes');
        }
    }
    
    /**
     * Actualizar cliente
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/clientes/' . $id);
        }
        
        try {
            $cliente = $this->clienteModel->find($id);
            
            if (!$cliente) {
                $_SESSION['error'] = 'Cliente no encontrado';
                $this->redirect('/clientes');
                return;
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'documento' => trim($_POST['documento'] ?? ''),
                'tipo_documento' => trim($_POST['tipo_documento'] ?? 'DNI'),
                'email' => trim($_POST['email'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'fecha_nacimiento' => !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null,
                'nacionalidad' => trim($_POST['nacionalidad'] ?? '')
            ];
            
            // Validar datos
            $errores = $this->clienteModel->validar($datos, $id);
            
            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                $_SESSION['old'] = $datos;
                $this->redirect('/clientes/' . $id . '/editar');
                return;
            }
            
            // Actualizar cliente
            $success = $this->clienteModel->update($id, $datos);
            
            if ($success) {
                $_SESSION['success'] = '✅ Cliente actualizado exitosamente';
            } else {
                throw new Exception('No se pudo actualizar el cliente');
            }
            
            $this->redirect('/clientes/' . $id);
            
        } catch (Exception $e) {
            error_log("Error al actualizar cliente: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al actualizar el cliente: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/clientes/' . $id . '/editar');
        }
    }
    
    /**
     * Eliminar cliente
     */
    public function destroy($id)
    {
        try {
            $cliente = $this->clienteModel->find($id);
            
            if (!$cliente) {
                $_SESSION['error'] = 'Cliente no encontrado';
                $this->redirect('/clientes');
                return;
            }
            
            // Verificar si tiene reservas
            $sql = "SELECT COUNT(*) as total FROM reservas WHERE cliente_id = ?";
            $result = Database::queryOne($sql, [$id]);
            
            if ($result && $result['total'] > 0) {
                $_SESSION['error'] = 'No se puede eliminar. El cliente tiene ' . $result['total'] . ' reserva(s) registrada(s)';
                $this->redirect('/clientes/' . $id);
                return;
            }
            
            // Eliminar cliente
            $success = $this->clienteModel->delete($id);
            
            if ($success) {
                $_SESSION['success'] = '✅ Cliente eliminado exitosamente';
            } else {
                throw new Exception('No se pudo eliminar el cliente');
            }
            
            $this->redirect('/clientes');
            
        } catch (Exception $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            $_SESSION['error'] = '❌ Error al eliminar el cliente: ' . $e->getMessage();
            $this->redirect('/clientes/' . $id);
        }
    }
}