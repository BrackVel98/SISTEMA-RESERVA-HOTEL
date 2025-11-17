<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\dashboard\index.php

$base = '/hotel-reservas/public';

// Extraer datos de forma segura
$title = $data['title'] ?? 'Dashboard';
$totalReservas = $data['totalReservas'] ?? 0;
$reservasConfirmadas = $data['reservasConfirmadas'] ?? 0;
$reservasPendientes = $data['reservasPendientes'] ?? 0;
$reservasHoy = $data['reservasHoy'] ?? 0;
$totalHabitaciones = $data['totalHabitaciones'] ?? 0;
$habitacionesDisponibles = $data['habitacionesDisponibles'] ?? 0;
$habitacionesOcupadas = $data['habitacionesOcupadas'] ?? 0;
$totalClientes = $data['totalClientes'] ?? 0;
$ingresosMes = $data['ingresosMes'] ?? 0;

$checkInsHoy = $data['checkInsHoy'] ?? [];
$checkOutsHoy = $data['checkOutsHoy'] ?? [];
$reservasRecientes = $data['reservasRecientes'] ?? [];
$reservasActivas = $data['reservasActivas'] ?? [];

// IMPORTANTE: Incluir header correcto
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- CONTENIDO DEL DASHBOARD -->
<div class="container-fluid py-4">
    
    <!-- Título y Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-tachometer-alt text-primary"></i>
                Dashboard - <?= APP_NAME ?>
            </h1>
            <p class="text-muted">
                Bienvenido, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong>
                <span class="ms-2">|</span>
                <span class="ms-2"><?= date('d/m/Y') ?></span>
            </p>
        </div>
    </div>
    
    <!-- Tarjetas de Estadísticas -->
    <div class="row g-3 mb-4">
        
        <!-- Total Reservas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Total Reservas</h6>
                            <h2 class="mb-0"><?= number_format($totalReservas) ?></h2>
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i>
                                <?= number_format($reservasConfirmadas) ?> confirmadas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reservas Hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-calendar-day fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Check-ins Hoy</h6>
                            <h2 class="mb-0"><?= number_format($reservasHoy) ?></h2>
                            <small class="text-warning">
                                <i class="fas fa-clock"></i>
                                <?= number_format($reservasPendientes) ?> pendientes
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Habitaciones Disponibles -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-door-open fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Habitaciones</h6>
                            <h2 class="mb-0"><?= number_format($habitacionesDisponibles) ?></h2>
                            <small class="text-muted">
                                de <?= number_format($totalHabitaciones) ?> disponibles
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ingresos del Mes -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Ingresos del Mes</h6>
                            <h2 class="mb-0">$<?= number_format($ingresosMes, 2) ?></h2>
                            <small class="text-muted">
                                <i class="fas fa-chart-line"></i>
                                <?= date('F Y') ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Tarjetas de Acceso Rápido -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text text-muted">Gestionar clientes</p>
                    <a href="<?= $base ?>/clientes" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Ir a Clientes
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-bed fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Habitaciones</h5>
                    <p class="card-text text-muted">Ver habitaciones</p>
                    <a href="<?= $base ?>/habitaciones" class="btn btn-success">
                        <i class="fas fa-arrow-right"></i> Ir a Habitaciones
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">Reservas</h5>
                    <p class="card-text text-muted">Gestionar reservas</p>
                    <a href="<?= $base ?>/reservas" class="btn btn-warning">
                        <i class="fas fa-arrow-right"></i> Ir a Reservas
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-money-bill-wave fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Pagos</h5>
                    <p class="card-text text-muted">Gestionar pagos</p>
                    <a href="<?= $base ?>/pagos" class="btn btn-info">
                        <i class="fas fa-arrow-right"></i> Ir a Pagos
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Check-ins y Check-outs de Hoy -->
    <div class="row g-3 mb-4">
        
        <!-- Check-ins de Hoy -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sign-in-alt text-success"></i>
                        Check-ins de Hoy (<?= count($checkInsHoy) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($checkInsHoy)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-check fa-3x mb-3 opacity-25"></i>
                            <p>No hay check-ins programados para hoy</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($checkInsHoy as $reserva): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="fas fa-user"></i>
                                                <?= htmlspecialchars($reserva['cliente_nombre'] ?? 'N/A') ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-door-closed"></i>
                                                Habitación: <?= htmlspecialchars($reserva['habitacion_numero'] ?? 'N/A') ?>
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="<?= $base ?>/reservas/<?= $reserva['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Check-outs de Hoy -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                        Check-outs de Hoy (<?= count($checkOutsHoy) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($checkOutsHoy)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                            <p>No hay check-outs programados para hoy</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($checkOutsHoy as $reserva): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="fas fa-user"></i>
                                                <?= htmlspecialchars($reserva['cliente_nombre'] ?? 'N/A') ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-door-closed"></i>
                                                Habitación: <?= htmlspecialchars($reserva['habitacion_numero'] ?? 'N/A') ?>
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="<?= $base ?>/reservas/<?= $reserva['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Reservas Recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history text-primary"></i>
                            Reservas Recientes
                        </h5>
                        <a href="<?= $base ?>/reservas" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list"></i>
                            Ver todas
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($reservasRecientes)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>No hay reservas registradas</p>
                            <a href="<?= $base ?>/reservas/crear" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Crear Primera Reserva
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Habitación</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservasRecientes as $reserva): ?>
                                        <tr>
                                            <td><strong>#<?= $reserva['id'] ?></strong></td>
                                            <td><?= htmlspecialchars($reserva['cliente_nombre'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($reserva['habitacion_numero'] ?? 'N/A') ?></td>
                                            <td><?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?></td>
                                            <td><strong>$<?= number_format($reserva['precio_total'], 2) ?></strong></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'secondary';
                                                switch ($reserva['estado']) {
                                                    case 'confirmada': $badgeClass = 'success'; break;
                                                    case 'pendiente': $badgeClass = 'warning'; break;
                                                    case 'cancelada': $badgeClass = 'danger'; break;
                                                    case 'completada': $badgeClass = 'info'; break;
                                                }
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= ucfirst($reserva['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= $base ?>/reservas/<?= $reserva['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
</div>

<style>
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}
</style>

<?php 
// IMPORTANTE: Incluir footer correcto
require_once __DIR__ . '/../layouts/footer.php'; 
?>