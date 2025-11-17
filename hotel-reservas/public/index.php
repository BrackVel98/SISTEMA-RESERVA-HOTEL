<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\public\index.php

/**
 * Punto de entrada principal de la aplicación
 */

// Evitar múltiples cargas
if (defined('APP_LOADED')) {
    exit;
}
define('APP_LOADED', true);

// 1. Cargar configuración
require_once __DIR__ . '/../config/app.php';

// 2. Cargar helpers
require_once __DIR__ . '/../helpers/constants.php';
require_once __DIR__ . '/../helpers/functions.php';

// 3. Cargar clases core (EN ORDEN)
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Validator.php';

// 4. Cargar middleware
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

// 5. Cargar modelos
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/TipoHabitacion.php';
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Pago.php';

// 6. Cargar controladores
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/ClienteController.php';
require_once __DIR__ . '/../controllers/HabitacionController.php';
require_once __DIR__ . '/../controllers/ReservaController.php';
require_once __DIR__ . '/../controllers/PagoController.php';
require_once __DIR__ . '/../controllers/BusquedaController.php';

// 7. Iniciar sesión
Session::start();

// 8. Crear router
$router = new Router();

// ==========================================
// RUTAS PÚBLICAS
// ==========================================
$router->get('/', 'HomeController@index');

// ==========================================
// RUTAS DE BÚSQUEDA
// ==========================================
$router->get('/buscar', 'BusquedaController@index');
$router->post('/buscar', 'BusquedaController@index');

// ==========================================
// RUTAS DE AUTENTICACIÓN
// ==========================================
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// ==========================================
// RUTAS PROTEGIDAS
// ==========================================
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// ==========================================
// CLIENTES
// ==========================================
$router->get('/clientes', 'ClienteController@index', ['auth']);
$router->get('/clientes/crear', 'ClienteController@create', ['auth']);
$router->post('/clientes/crear', 'ClienteController@store', ['auth']);
$router->get('/clientes/{id}', 'ClienteController@show', ['auth']);
$router->get('/clientes/{id}/editar', 'ClienteController@edit', ['auth']);
$router->post('/clientes/{id}/editar', 'ClienteController@update', ['auth']);
$router->post('/clientes/{id}/eliminar', 'ClienteController@destroy', ['auth']);

// ==========================================
// HABITACIONES
// ==========================================
$router->get('/habitaciones', 'HabitacionController@index', ['auth']);
$router->get('/habitaciones/crear', 'HabitacionController@create', ['auth']);
$router->post('/habitaciones/crear', 'HabitacionController@store', ['auth']);
$router->get('/habitaciones/{id}', 'HabitacionController@show', ['auth']);
$router->get('/habitaciones/{id}/editar', 'HabitacionController@edit', ['auth']);
$router->post('/habitaciones/{id}/editar', 'HabitacionController@update', ['auth']);
$router->post('/habitaciones/{id}/eliminar', 'HabitacionController@destroy', ['auth']);
$router->post('/habitaciones/{id}/cambiar-estado', 'HabitacionController@cambiarEstado', ['auth']);

// ==========================================
// RESERVAS
// ==========================================
$router->get('/reservas', 'ReservaController@index', ['auth']);
$router->get('/reservas/crear', 'ReservaController@create', ['auth']);
$router->post('/reservas/crear', 'ReservaController@store', ['auth']);
$router->get('/reservas/{id}', 'ReservaController@show', ['auth']);
$router->get('/reservas/{id}/editar', 'ReservaController@edit', ['auth']);
$router->post('/reservas/{id}/editar', 'ReservaController@update', ['auth']);
$router->post('/reservas/{id}/cancelar', 'ReservaController@cancel', ['auth']);
$router->post('/reservas/{id}/confirmar', 'ReservaController@confirm', ['auth']);
$router->post('/reservas/{id}/completar', 'ReservaController@complete', ['auth']);

// ==========================================
// PAGOS
// ==========================================
$router->get('/pagos', 'PagoController@index', ['auth']);
$router->get('/pagos/crear', 'PagoController@create', ['auth']);
$router->post('/pagos/crear', 'PagoController@store', ['auth']);
$router->get('/pagos/{id}', 'PagoController@show', ['auth']);
$router->post('/pagos/{id}/anular', 'PagoController@anular', ['auth']);
$router->get('/pagos/{id}/recibo', 'PagoController@recibo', ['auth']);

// ==========================================
// EJECUTAR ROUTER
// ==========================================
$router->run();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #667eea;
            font-size: 1.8rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-name {
            font-weight: 600;
            color: #333;
        }
        .btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 2.5rem;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #666;
            font-size: 1rem;
        }
        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .quick-actions h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .recent-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .recent-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        .reserva-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        .reserva-item:last-child {
            border-bottom: none;
        }
        .reserva-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏨 Dashboard - Hotel Reservas</h1>
        <div class="user-info">
            <span class="user-name">👤 <?= htmlspecialchars($usuario['nombre']) ?></span>
            <a href="<?= $base ?>/logout" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value"><?= $totalReservas ?></div>
                <div class="stat-label">Total Reservas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value"><?= $reservasHoy ?></div>
                <div class="stat-label">Reservas Hoy</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏨</div>
                <div class="stat-value"><?= $totalHabitaciones ?></div>
                <div class="stat-label">Total Habitaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value"><?= $habitacionesDisponibles ?></div>
                <div class="stat-label">Disponibles</div>
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="quick-actions">
            <h2>⚡ Acciones Rápidas</h2>
            <div class="actions-grid">
                <a href="<?= $base ?>/reservas/create" class="btn">➕ Nueva Reserva</a>
                <a href="<?= $base ?>/habitaciones" class="btn">🏨 Ver Habitaciones</a>
                <a href="<?= $base ?>/reservas" class="btn">📋 Ver Reservas</a>
                <a href="<?= $base ?>/clientes" class="btn">👥 Ver Clientes</a>
            </div>
        </div>
        
        <!-- Últimas reservas -->
        <?php if (!empty($ultimasReservas)): ?>
        <div class="recent-section">
            <h2>🕐 Últimas Reservas</h2>
            <?php foreach ($ultimasReservas as $reserva): ?>
            <div class="reserva-item">
                <div class="reserva-info">
                    <div>
                        <strong><?= htmlspecialchars($reserva['cliente_nombre'] ?? 'N/A') ?></strong><br>
                        <small>Habitación: <?= htmlspecialchars($reserva['habitacion_numero'] ?? 'N/A') ?></small>
                    </div>
                    <div style="text-align: right;">
                        <strong><?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?></strong><br>
                        <small style="color: <?= $reserva['estado'] === 'confirmada' ? 'green' : ($reserva['estado'] === 'cancelada' ? 'red' : 'orange') ?>">
                            <?= ucfirst($reserva['estado']) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>