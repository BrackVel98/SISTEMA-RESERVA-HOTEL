<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\reservas\show.php

$base = '/hotel-reservas/public';
$reserva = $data['reserva'] ?? null;
$pagos = $data['pagos'] ?? [];
$totalPagado = $data['totalPagado'] ?? 0;
$saldoPendiente = $data['saldoPendiente'] ?? 0;

if (!$reserva) {
    header("Location: {$base}/reservas");
    exit;
}

// Estados y sus colores
$estadosBadge = [
    'pendiente' => 'warning',
    'confirmada' => 'success',
    'cancelada' => 'danger',
    'completada' => 'info'
];

$estadoColor = $estadosBadge[$reserva['estado']] ?? 'secondary';

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2">
                <i class="fas fa-calendar-check text-primary"></i>
                Reserva #<?= $reserva['id'] ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/reservas">Reservas</a></li>
                    <li class="breadcrumb-item active">Reserva #<?= $reserva['id'] ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($reserva['estado'] == 'pendiente'): ?>
                <form method="POST" action="<?= $base ?>/reservas/<?= $reserva['id'] ?>/confirmar" style="display:inline;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        Confirmar Reserva
                    </button>
                </form>
            <?php endif; ?>
            
            <?php if (in_array($reserva['estado'], ['pendiente', 'confirmada'])): ?>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalCancelar">
                    <i class="fas fa-times-circle"></i>
                    Cancelar
                </button>
            <?php endif; ?>
            
            <a href="<?= $base ?>/reservas" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>
    
    <!-- Alerta de estado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-<?= $estadoColor ?> d-flex align-items-center">
                <div class="flex-grow-1">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Estado de la reserva:</strong> 
                    <span class="badge bg-<?= $estadoColor ?> ms-2">
                        <?= ucfirst($reserva['estado']) ?>
                    </span>
                </div>
                <?php if ($reserva['estado'] == 'confirmada'): ?>
                    <div>
                        <strong>Check-in:</strong> <?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?>
                        | 
                        <strong>Check-out:</strong> <?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        
        <!-- Información Principal -->
        <div class="col-lg-8">
            
            <!-- Detalles de la Reserva -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Detalles de la Reserva
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <!-- Cliente -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user"></i>
                                    Cliente
                                </h6>
                                <p class="mb-2">
                                    <strong>Nombre:</strong><br>
                                    <a href="<?= $base ?>/clientes/<?= $reserva['cliente_id'] ?>">
                                        <?= htmlspecialchars($reserva['cliente_nombre']) ?>
                                    </a>
                                </p>
                                <p class="mb-2">
                                    <strong>Email:</strong><br>
                                    <a href="mailto:<?= htmlspecialchars($reserva['cliente_email']) ?>">
                                        <?= htmlspecialchars($reserva['cliente_email']) ?>
                                    </a>
                                </p>
                                <p class="mb-2">
                                    <strong>Teléfono:</strong><br>
                                    <a href="tel:<?= htmlspecialchars($reserva['cliente_telefono']) ?>">
                                        <?= htmlspecialchars($reserva['cliente_telefono']) ?>
                                    </a>
                                </p>
                                <p class="mb-0">
                                    <strong>Documento:</strong><br>
                                    <?= htmlspecialchars($reserva['cliente_documento']) ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Habitación -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-bed"></i>
                                    Habitación
                                </h6>
                                <p class="mb-2">
                                    <strong>Tipo:</strong><br>
                                    <?= htmlspecialchars($reserva['tipo_habitacion']) ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Número:</strong><br>
                                    <span class="badge bg-secondary">Habitación <?= $reserva['habitacion_numero'] ?></span>
                                </p>
                                <p class="mb-2">
                                    <strong>Precio por noche:</strong><br>
                                    $<?= number_format($reserva['precio_base'], 2) ?>
                                </p>
                                <p class="mb-0">
                                    <strong>Descripción:</strong><br>
                                    <?= htmlspecialchars($reserva['tipo_descripcion'] ?? 'Sin descripción') ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Fechas -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fechas de Estadía
                                </h6>
                                <p class="mb-2">
                                    <strong>Check-in:</strong><br>
                                    <i class="fas fa-sign-in-alt text-success"></i>
                                    <?= date('d/m/Y', strtotime($reserva['fecha_entrada'])) ?>
                                    <small class="text-muted">(<?= date('l', strtotime($reserva['fecha_entrada'])) ?>)</small>
                                </p>
                                <p class="mb-2">
                                    <strong>Check-out:</strong><br>
                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                    <?= date('d/m/Y', strtotime($reserva['fecha_salida'])) ?>
                                    <small class="text-muted">(<?= date('l', strtotime($reserva['fecha_salida'])) ?>)</small>
                                </p>
                                <p class="mb-0">
                                    <strong>Noches:</strong><br>
                                    <?php
                                    // Usar num_noches de la BD si existe, sino calcular
                                    $noches = $reserva['num_noches'] ?? (new DateTime($reserva['fecha_entrada']))->diff(new DateTime($reserva['fecha_salida']))->days;
                                    ?>
                                    <span class="badge bg-info"><?= $noches ?> noche<?= $noches != 1 ? 's' : '' ?></span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info"></i>
                                    Información Adicional
                                </h6>
                                <p class="mb-2">
                                    <strong>Fecha de Registro:</strong><br>
                                    <?= date('d/m/Y H:i', strtotime($reserva['created_at'])) ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Última Actualización:</strong><br>
                                    <?= date('d/m/Y H:i', strtotime($reserva['updated_at'])) ?>
                                </p>
                                <p class="mb-0">
                                    <strong>Estado:</strong><br>
                                    <span class="badge bg-<?= $estadoColor ?>">
                                        <?= ucfirst($reserva['estado']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Observaciones -->
                        <?php if (!empty($reserva['observaciones'])): ?>
                        <div class="col-12">
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded">
                                <h6 class="text-warning mb-2">
                                    <i class="fas fa-sticky-note"></i>
                                    Observaciones
                                </h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($reserva['observaciones'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            
            <!-- Historial de Pagos -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign"></i>
                        Historial de Pagos
                    </h5>
                    <?php if ($saldoPendiente > 0 && in_array($reserva['estado'], ['confirmada', 'pendiente'])): ?>
                        <a href="<?= $base ?>/pagos/crear?reserva_id=<?= $reserva['id'] ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i>
                            Registrar Pago
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($pagos)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay pagos registrados</p>
                            <?php if ($saldoPendiente > 0 && in_array($reserva['estado'], ['confirmada', 'pendiente'])): ?>
                                <a href="<?= $base ?>/pagos/crear?reserva_id=<?= $reserva['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    Registrar Primer Pago
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Estado</th>
                                        <th>Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $pago): ?>
                                        <tr>
                                            <td><strong>#<?= $pago['id'] ?></strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                            <td>
                                                <strong class="text-success">
                                                    $<?= number_format($pago['monto'], 2) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php
                                                $metodoIconos = [
                                                    'efectivo' => 'money-bill',
                                                    'tarjeta' => 'credit-card',
                                                    'transferencia' => 'exchange-alt'
                                                ];
                                                $icono = $metodoIconos[$pago['metodo_pago']] ?? 'dollar-sign';
                                                ?>
                                                <i class="fas fa-<?= $icono ?> text-muted"></i>
                                                <?= ucfirst($pago['metodo_pago']) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoPagoBadge = [
                                                    'completado' => 'success',
                                                    'pendiente' => 'warning',
                                                    'reembolsado' => 'danger'
                                                ];
                                                $badgePago = $estadoPagoBadge[$pago['estado']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $badgePago ?>">
                                                    <?= ucfirst($pago['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($pago['comprobante'])): ?>
                                                    <span class="text-muted small"><?= htmlspecialchars($pago['comprobante']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="2" class="text-end">Total Pagado:</td>
                                        <td colspan="4" class="text-success">
                                            $<?= number_format($totalPagado, 2) ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
        
        <!-- Resumen Financiero -->
        <div class="col-lg-4">
            
            <!-- Resumen de Costos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator"></i>
                        Resumen Financiero
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <small class="text-muted d-block">Precio por noche</small>
                            <strong>$<?= number_format($reserva['precio_base'], 2) ?></strong>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Noches</small>
                            <strong><?= $noches ?></strong>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Subtotal:</span>
                        <strong>$<?= number_format($reserva['precio_total'], 2) ?></strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-success">Total Pagado:</span>
                        <strong class="text-success">$<?= number_format($totalPagado, 2) ?></strong>
                    </div>
                    
                    <?php if ($saldoPendiente > 0): ?>
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong>Saldo Pendiente:</strong></span>
                                <h4 class="mb-0 text-warning">$<?= number_format($saldoPendiente, 2) ?></h4>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle"></i>
                            <strong>Reserva Pagada Completamente</strong>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Total:</h5>
                            <h4 class="mb-0 text-primary">$<?= number_format($reserva['precio_total'], 2) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        
                        <?php if ($reserva['estado'] == 'pendiente'): ?>
                            <form method="POST" action="<?= $base ?>/reservas/<?= $reserva['id'] ?>/confirmar">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i>
                                    Confirmar Reserva
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if ($saldoPendiente > 0 && in_array($reserva['estado'], ['confirmada', 'pendiente'])): ?>
                            <a href="<?= $base ?>/pagos/crear?reserva_id=<?= $reserva['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-dollar-sign"></i>
                                Registrar Pago
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= $base ?>/clientes/<?= $reserva['cliente_id'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i>
                            Ver Cliente
                        </a>
                        
                        <a href="<?= $base ?>/habitaciones/<?= $reserva['habitacion_id'] ?>" class="btn btn-outline-info">
                            <i class="fas fa-bed"></i>
                            Ver Habitación
                        </a>
                        
                        <?php if (in_array($reserva['estado'], ['pendiente', 'confirmada'])): ?>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalCancelar">
                                <i class="fas fa-times-circle"></i>
                                Cancelar Reserva
                            </button>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            Imprimir
                        </button>
                        
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>

<!-- Modal Cancelar Reserva -->
<div class="modal fade" id="modalCancelar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Cancelar Reserva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>¿Estás seguro de cancelar esta reserva?</strong>
                </div>
                <p>Esta acción liberará la habitación y cambiará el estado de la reserva a <strong>Cancelada</strong>.</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-circle"></i>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    No, mantener reserva
                </button>
                <form method="POST" action="<?= $base ?>/reservas/<?= $reserva['id'] ?>/cancelar" style="display:inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i>
                        Sí, cancelar reserva
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .breadcrumb, nav, .modal {
        display: none !important;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>