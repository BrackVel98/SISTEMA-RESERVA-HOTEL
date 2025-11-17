<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\pagos\index.php

$base = '/hotel-reservas/public';
$pagos = $data['pagos'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$filtros = $data['filtros'] ?? [];

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-money-bill-wave text-success"></i>
                Gestión de Pagos
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pagos</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= $base ?>/pagos/crear" class="btn btn-success">
                <i class="fas fa-plus"></i>
                Registrar Pago
            </a>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Total Ingresos</h6>
                            <h3 class="mb-0">$<?= number_format($estadisticas['total_ingresos'] ?? 0, 2) ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Pagos</h6>
                            <h3 class="mb-0"><?= $estadisticas['total_pagos'] ?? 0 ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-receipt fa-3x text-muted opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Completados</h6>
                            <h3 class="mb-0 text-success"><?= $estadisticas['completados'] ?? 0 ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x text-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pendientes</h6>
                            <h3 class="mb-0 text-warning"><?= $estadisticas['pendientes'] ?? 0 ?></h3>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x text-warning opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Métodos de Pago -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-alt fa-2x text-success mb-2"></i>
                    <h6 class="text-muted">Efectivo</h6>
                    <h4 class="mb-0"><?= $estadisticas['pagos_efectivo'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                    <h6 class="text-muted">Tarjeta</h6>
                    <h4 class="mb-0"><?= $estadisticas['pagos_tarjeta'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-university fa-2x text-info mb-2"></i>
                    <h6 class="text-muted">Transferencia</h6>
                    <h4 class="mb-0"><?= $estadisticas['pagos_transferencia'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $base ?>/pagos" class="row g-3">
                <div class="col-lg-2 col-md-4">
                    <label class="form-label small">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="completado" <?= $filtros['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                        <option value="pendiente" <?= $filtros['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="cancelado" <?= $filtros['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label small">Método</label>
                    <select name="metodo_pago" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="efectivo" <?= $filtros['metodo_pago'] == 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                        <option value="tarjeta" <?= $filtros['metodo_pago'] == 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                        <option value="transferencia" <?= $filtros['metodo_pago'] == 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        <option value="otro" <?= $filtros['metodo_pago'] == 'otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label small">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label small">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                </div>
                <div class="col-lg-2 col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="<?= $base ?>/pagos" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de Pagos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i>
                Lista de Pagos (<?= count($pagos) ?>)
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($pagos)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted">No hay pagos registrados</p>
                    <a href="<?= $base ?>/pagos/crear" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Registrar Primer Pago
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Reserva</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagos as $pago): 
                                $estadoBadge = [
                                    'completado' => 'success',
                                    'pendiente' => 'warning',
                                    'cancelado' => 'danger'
                                ];
                                $badge = $estadoBadge[$pago['estado']] ?? 'secondary';
                                
                                $metodoBadge = [
                                    'efectivo' => 'success',
                                    'tarjeta' => 'primary',
                                    'transferencia' => 'info',
                                    'otro' => 'secondary'
                                ];
                                $metodoColor = $metodoBadge[$pago['metodo_pago']] ?? 'secondary';
                            ?>
                                <tr>
                                    <td><strong>#<?= $pago['id'] ?></strong></td>
                                    <td>
                                        <i class="fas fa-calendar text-muted"></i>
                                        <?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?>
                                        <br>
                                        <small class="text-muted"><?= date('H:i', strtotime($pago['fecha_pago'])) ?></small>
                                    </td>
                                    <td>
                                        <a href="<?= $base ?>/reservas/<?= $pago['reserva_id'] ?>" class="text-decoration-none">
                                            <i class="fas fa-ticket-alt"></i>
                                            <?= htmlspecialchars($pago['codigo_reserva']) ?>
                                        </a>
                                        <br>
                                        <small class="text-muted">Hab. <?= htmlspecialchars($pago['habitacion_numero']) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($pago['cliente_nombre']) ?></strong>
                                        <?php if (!empty($pago['cliente_documento'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($pago['cliente_documento']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 text-success">
                                            $<?= number_format($pago['monto'], 2) ?>
                                        </h5>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $metodoColor ?>">
                                            <i class="fas fa-<?= $pago['metodo_pago'] == 'efectivo' ? 'money-bill-alt' : ($pago['metodo_pago'] == 'tarjeta' ? 'credit-card' : 'university') ?>"></i>
                                            <?= ucfirst($pago['metodo_pago']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = match($pago['estado']) {
                                            'completado' => 'success',
                                            'pendiente' => 'warning',
                                            'reembolsado' => 'danger',
                                            default => 'secondary'
                                        };
                                        $estadoTexto = match($pago['estado']) {
                                            'completado' => 'Completado',
                                            'pendiente' => 'Pendiente',
                                            'reembolsado' => 'Reembolsado',
                                            default => ucfirst($pago['estado'])
                                        };
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= $estadoTexto ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= $base ?>/pagos/<?= $pago['id'] ?>" 
                                               class="btn btn-outline-primary" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= $base ?>/pagos/<?= $pago['id'] ?>/recibo" 
                                               class="btn btn-outline-secondary" 
                                               title="Imprimir recibo"
                                               target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
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