<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\reservas\index.php

$base = '/hotel-reservas/public';
$reservas = $data['reservas'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];

$estadoActual = $data['filtros']['estado'] ?? '';
$fechaDesde = $data['filtros']['fecha_desde'] ?? '';
$fechaHasta = $data['filtros']['fecha_hasta'] ?? '';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-calendar-check text-primary"></i>
                Gestión de Reservas
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reservas</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= $base ?>/reservas/crear" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Nueva Reserva
            </a>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Total Reservas</h6>
                            <h3 class="mb-0"><?= count($reservas) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Pendientes</h6>
                            <h3 class="mb-0"><?= $estadisticas['pendientes'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Confirmadas</h6>
                            <h3 class="mb-0"><?= $estadisticas['confirmadas'] ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Total Ingresos</h6>
                            <h3 class="mb-0">$<?= number_format($estadisticas['total_ingresos'] ?? 0, 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $base ?>/reservas" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?= $estadoActual == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= $estadoActual == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= $estadoActual == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="completada" <?= $estadoActual == 'completada' ? 'selected' : '' ?>>Completada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaDesde) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaHasta) ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="<?= $base ?>/reservas" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de reservas -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($reservas)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted">No hay reservas registradas</h5>
                    <p class="text-muted mb-4">Comienza creando tu primera reserva</p>
                    <a href="<?= $base ?>/reservas/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Primera Reserva
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Habitación</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><strong>#<?= $reserva['id'] ?></strong></td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($reserva['cliente_nombre']) ?></strong>
                                            <?php if (!empty($reserva['cliente_telefono'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-phone"></i>
                                                    <?= htmlspecialchars($reserva['cliente_telefono']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($reserva['tipo_habitacion']) ?>
                                        </span>
                                        Hab. <?= $reserva['habitacion_numero'] ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?></td>
                                    <td><strong>$<?= number_format($reserva['precio_total'], 2) ?></strong></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'pendiente' => 'warning',
                                            'confirmada' => 'success',
                                            'cancelada' => 'danger',
                                            'completada' => 'info'
                                        ];
                                        $badge = $badges[$reserva['estado']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= ucfirst($reserva['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= $base ?>/reservas/<?= $reserva['id'] ?>" 
                                               class="btn btn-outline-primary"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                <form method="POST" action="<?= $base ?>/reservas/<?= $reserva['id'] ?>/confirmar" style="display:inline;">
                                                    <button type="submit" class="btn btn-outline-success" title="Confirmar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if (in_array($reserva['estado'], ['pendiente', 'confirmada'])): ?>
                                                <form method="POST" action="<?= $base ?>/reservas/<?= $reserva['id'] ?>/cancelar" style="display:inline;">
                                                    <button type="submit" class="btn btn-outline-danger" title="Cancelar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>