<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\habitaciones\show.php

$base = '/hotel-reservas/public';
$habitacion = $data['habitacion'] ?? [];
$reservas = $data['reservas'] ?? [];
$reservaActiva = $data['reservaActiva'] ?? null;

// Badges de estado
$estadoBadges = [
    'disponible' => 'success',
    'ocupada' => 'danger',
    'mantenimiento' => 'warning',
    'limpieza' => 'info'
];
$badge = $estadoBadges[$habitacion['estado']] ?? 'secondary';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-door-open text-primary"></i>
                Habitación #<?= htmlspecialchars($habitacion['numero']) ?>
                <span class="badge bg-<?= $badge ?> ms-2"><?= ucfirst($habitacion['estado']) ?></span>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/habitaciones">Habitaciones</a></li>
                    <li class="breadcrumb-item active">Habitación #<?= htmlspecialchars($habitacion['numero']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?= $base ?>/habitaciones/<?= $habitacion['id'] ?>/editar" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    Editar
                </a>
                <a href="<?= $base ?>/reservas/crear?habitacion_id=<?= $habitacion['id'] ?>" class="btn btn-success">
                    <i class="fas fa-calendar-plus"></i>
                    Nueva Reserva
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        
        <!-- Columna Izquierda: Información -->
        <div class="col-lg-8">
            
            <!-- Información General -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-hashtag"></i>
                                    Número
                                </h6>
                                <h4 class="mb-0 text-primary"><?= htmlspecialchars($habitacion['numero']) ?></h4>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-bed"></i>
                                    Tipo
                                </h6>
                                <h5 class="mb-0"><?= htmlspecialchars($habitacion['tipo_nombre']) ?></h5>
                                <?php if (!empty($habitacion['tipo_descripcion'])): ?>
                                    <small class="text-muted"><?= htmlspecialchars($habitacion['tipo_descripcion']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($habitacion['piso'])): ?>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-building"></i>
                                    Piso
                                </h6>
                                <h5 class="mb-0">Piso <?= htmlspecialchars($habitacion['piso']) ?></h5>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-users"></i>
                                    Capacidad
                                </h6>
                                <h5 class="mb-0"><?= $habitacion['capacidad'] ?> personas</h5>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-dollar-sign"></i>
                                    Precio por Noche
                                </h6>
                                <h4 class="mb-0 text-success">$<?= number_format($habitacion['precio_base'], 2) ?></h4>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-<?= $badge ?> bg-opacity-25 rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-info-circle"></i>
                                    Estado Actual
                                </h6>
                                <h5 class="mb-0">
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst($habitacion['estado']) ?>
                                    </span>
                                </h5>
                            </div>
                        </div>
                        
                        <?php if (!empty($habitacion['descripcion'])): ?>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-file-alt"></i>
                                    Descripción
                                </h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($habitacion['descripcion'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            
            <!-- Reserva Activa -->
            <?php if ($reservaActiva): ?>
            <div class="card border-0 shadow-sm mb-4 border-start border-danger border-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Reserva Activa Actual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6>
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($reservaActiva['cliente_nombre']) ?>
                            </h6>
                            <p class="mb-2">
                                <i class="fas fa-calendar"></i>
                                <strong>Check-in:</strong> <?= date('d/m/Y', strtotime($reservaActiva['fecha_entrada'])) ?>
                                <br>
                                <i class="fas fa-calendar"></i>
                                <strong>Check-out:</strong> <?= date('d/m/Y', strtotime($reservaActiva['fecha_salida'])) ?>
                            </p>
                            <?php if (!empty($reservaActiva['cliente_telefono'])): ?>
                                <p class="mb-0">
                                    <i class="fas fa-phone"></i>
                                    <?= htmlspecialchars($reservaActiva['cliente_telefono']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?= $base ?>/reservas/<?= $reservaActiva['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Ver Reserva
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Historial de Reservas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i>
                        Historial de Reservas (<?= count($reservas) ?>)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($reservas)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay reservas registradas para esta habitación</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Entrada</th>
                                        <th>Salida</th>
                                        <th>Noches</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $reserva): 
                                        $estadoReserva = [
                                            'pendiente' => 'warning',
                                            'confirmada' => 'success',
                                            'cancelada' => 'danger',
                                            'completada' => 'secondary'
                                        ];
                                        $badgeReserva = $estadoReserva[$reserva['estado']] ?? 'secondary';
                                        
                                        $entrada = new DateTime($reserva['fecha_entrada']);
                                        $salida = new DateTime($reserva['fecha_salida']);
                                        $noches = $entrada->diff($salida)->days;
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($reserva['cliente_nombre']) ?></strong>
                                                <?php if (!empty($reserva['cliente_telefono'])): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($reserva['cliente_telefono']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?></td>
                                            <td><?= $noches ?></td>
                                            <td><strong class="text-success">$<?= number_format($reserva['precio_total'], 2) ?></strong></td>
                                            <td>
                                                <span class="badge bg-<?= $badgeReserva ?>">
                                                    <?= ucfirst($reserva['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
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
        
        <!-- Columna Derecha: Acciones Rápidas -->
        <div class="col-lg-4">
            
            <!-- Cambiar Estado -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exchange-alt"></i>
                        Cambiar Estado
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= $base ?>/habitaciones/<?= $habitacion['id'] ?>/cambiar-estado">
                        <div class="mb-3">
                            <label class="form-label">Estado Actual:</label>
                            <div>
                                <span class="badge bg-<?= $badge ?> fs-6">
                                    <?= ucfirst($habitacion['estado']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nuevoEstado" class="form-label">Cambiar a:</label>
                            <select class="form-select" id="nuevoEstado" name="estado" required>
                                <option value="disponible" <?= $habitacion['estado'] == 'disponible' ? 'selected' : '' ?>>
                                    Disponible
                                </option>
                                <option value="ocupada" <?= $habitacion['estado'] == 'ocupada' ? 'selected' : '' ?>>
                                    Ocupada
                                </option>
                                <option value="mantenimiento" <?= $habitacion['estado'] == 'mantenimiento' ? 'selected' : '' ?>>
                                    Mantenimiento
                                </option>
                                <option value="limpieza" <?= $habitacion['estado'] == 'limpieza' ? 'selected' : '' ?>>
                                    Limpieza
                                </option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i>
                            Actualizar Estado
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar"></i>
                        Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                    $totalReservas = count($reservas);
                    $confirmadas = count(array_filter($reservas, fn($r) => $r['estado'] == 'confirmada'));
                    $completadas = count(array_filter($reservas, fn($r) => $r['estado'] == 'completada'));
                    $canceladas = count(array_filter($reservas, fn($r) => $r['estado'] == 'cancelada'));
                    $ingresos = array_sum(array_map(fn($r) => $r['estado'] == 'completada' ? $r['precio_total'] : 0, $reservas));
                    ?>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Reservas:</span>
                            <strong><?= $totalReservas ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-success">Confirmadas:</span>
                            <strong class="text-success"><?= $confirmadas ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary">Completadas:</span>
                            <strong class="text-secondary"><?= $completadas ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-danger">Canceladas:</span>
                            <strong class="text-danger"><?= $canceladas ?></strong>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <small class="text-muted">Ingresos Totales</small>
                        <h3 class="text-success mb-0">$<?= number_format($ingresos, 2) ?></h3>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar la habitación <strong>#<?= htmlspecialchars($habitacion['numero']) ?></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <form method="POST" action="<?= $base ?>/habitaciones/<?= $habitacion['id'] ?>/eliminar" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>