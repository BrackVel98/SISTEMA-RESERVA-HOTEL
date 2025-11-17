<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\controllers\AuthController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends Controller
{
    private $usuarioModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function login()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Si es POST, procesar login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            return;
        }
        
        // Mostrar formulario de login
        $data = [
            'title' => 'Iniciar Sesión - ' . APP_NAME
        ];
        
        $this->view('auth/login', $data);
    }
    
    /**
     * Procesar login
     */
    private function processLogin()
    {
        try {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            // Validar campos vacíos
            if (empty($email) || empty($password)) {
                throw new Exception('Por favor completa todos los campos');
            }
            
            // Buscar usuario (USANDO EL MÉTODO CORRECTO)
            $usuario = $this->usuarioModel->buscarPorEmail($email);
            
            if (!$usuario) {
                throw new Exception('Credenciales incorrectas');
            }
            
            // Verificar contraseña
            if (!password_verify($password, $usuario['password'])) {
                throw new Exception('Credenciales incorrectas');
            }
            
            // Iniciar sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nombre'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_role'] = $usuario['rol'];
            
            $_SESSION['success'] = '¡Bienvenido ' . $usuario['nombre'] . '!';
            $this->redirect('/dashboard');
            
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/login');
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout()
    {
        // Limpiar variables de sesión
        $_SESSION = [];
        
        // Destruir la sesión
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Iniciar nueva sesión para el mensaje
        session_start();
        $_SESSION['success'] = 'Sesión cerrada correctamente';
        
        $this->redirect('/');
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function register()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Si es POST, procesar registro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
            return;
        }
        
        // Mostrar formulario de registro
        $data = [
            'title' => 'Registro - ' . APP_NAME
        ];
        
        $this->view('auth/register', $data);
    }
    
    /**
     * Procesar registro
     */
    private function processRegister()
    {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $password_confirm = trim($_POST['password_confirm'] ?? '');
            
            // Validaciones
            if (empty($nombre) || empty($email) || empty($password)) {
                throw new Exception('Todos los campos son obligatorios');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }
            
            if (strlen($password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }
            
            if ($password !== $password_confirm) {
                throw new Exception('Las contraseñas no coinciden');
            }
            
            // Verificar si el email ya existe
            if ($this->usuarioModel->emailExiste($email)) {
                throw new Exception('El email ya está registrado');
            }
            
            // Crear usuario
            $id = $this->usuarioModel->crearUsuario([
                'nombre' => $nombre,
                'email' => $email,
                'password' => $password,
                'rol' => 'usuario'
            ]);
            
            if ($id) {
                $_SESSION['success'] = 'Registro exitoso. Ya puedes iniciar sesión';
                $this->redirect('/login');
            } else {
                throw new Exception('Error al crear el usuario');
            }
            
        } catch (Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/register');
        }
    }
}