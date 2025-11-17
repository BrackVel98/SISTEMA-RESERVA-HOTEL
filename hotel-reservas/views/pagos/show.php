<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\pagos\show.php

$base = '/hotel-reservas/public';
$pago = $data['pago'] ?? [];
$pagosSimilares = $data['pagosSimilares'] ?? [];
$saldo = $data['saldo'] ?? null;

// Badges de estado
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

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-receipt text-success"></i>
                Pago #<?= $pago['id'] ?>
                <span class="badge bg-<?= $badge ?> ms-2"><?= ucfirst($pago['estado']) ?></span>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/pagos">Pagos</a></li>
                    <li class="breadcrumb-item active">Pago #<?= $pago['id'] ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?= $base ?>/pagos/<?= $pago['id'] ?>/recibo" 
                   class="btn btn-primary" 
                   target="_blank">
                    <i class="fas fa-print"></i>
                    Imprimir Recibo
                </a>
                <?php if ($pago['estado'] == 'completado'): ?>
                    <!-- <button type="button" 
                            class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalAnular">
                        <i class="fas fa-ban"></i>
                        Anular Pago
                    </button> -->
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        
        <!-- Columna Izquierda: Información del Pago -->
        <div class="col-lg-8">
            
            <!-- Datos del Pago -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave"></i>
                        Detalles del Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-dollar-sign"></i>
                                    Monto Pagado
                                </h6>
                                <h2 class="mb-0 text-success">$<?= number_format($pago['monto'], 2) ?></h2>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-credit-card"></i>
                                    Método de Pago
                                </h6>
                                <h5 class="mb-0">
                                    <span class="badge bg-<?= $metodoColor ?> fs-6">
                                        <?= ucfirst($pago['metodo_pago']) ?>
                                    </span>
                                </h5>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-calendar"></i>
                                    Fecha de Pago
                                </h6>
                                <h5 class="mb-0"><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></h5>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-<?= $badge ?> bg-opacity-25 rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-info-circle"></i>
                                    Estado
                                </h6>
                                <h5 class="mb-0">
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst($pago['estado']) ?>
                                    </span>
                                </h5>
                            </div>
                        </div>
                        
                        <?php if (!empty($pago['referencia'])): ?>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-hashtag"></i>
                                    Referencia / Transacción
                                </h6>
                                <p class="mb-0 font-monospace"><?= htmlspecialchars($pago['referencia']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($pago['notas'])): ?>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-sticky-note"></i>
                                    Notas / Observaciones
                                </h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($pago['notas'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            
            <!-- Información de la Reserva -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt"></i>
                        Información de la Reserva
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6>
                                <i class="fas fa-code"></i>
                                Código: <strong><?= htmlspecialchars($pago['codigo_reserva']) ?></strong>
                            </h6>
                            <p class="mb-2">
                                <i class="fas fa-user"></i>
                                <strong>Cliente:</strong> <?= htmlspecialchars($pago['cliente_nombre']) ?>
                                <br>
                                <?php if (!empty($pago['cliente_email'])): ?>
                                    <i class="fas fa-envelope"></i>
                                    <?= htmlspecialchars($pago['cliente_email']) ?>
                                    <br>
                                <?php endif; ?>
                                <?php if (!empty($pago['cliente_telefono'])): ?>
                                    <i class="fas fa-phone"></i>
                                    <?= htmlspecialchars($pago['cliente_telefono']) ?>
                                <?php endif; ?>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-door-open"></i>
                                <strong>Habitación:</strong> #<?= htmlspecialchars($pago['habitacion_numero']) ?>
                                (<?= htmlspecialchars($pago['tipo_habitacion']) ?>)
                                <br>
                                <i class="fas fa-calendar-check"></i>
                                <strong>Entrada:</strong> <?= date('d/m/Y', strtotime($pago['fecha_entrada'])) ?>
                                <br>
                                <i class="fas fa-calendar-times"></i>
                                <strong>Salida:</strong> <?= date('d/m/Y', strtotime($pago['fecha_salida'])) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?= $base ?>/reservas/<?= $pago['reserva_id'] ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Ver Reserva
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historial de Pagos de esta Reserva -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i>
                        Todos los Pagos de esta Reserva (<?= count($pagosSimilares) ?>)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagosSimilares as $p): 
                                    $estadoP = $estadoBadge[$p['estado']] ?? 'secondary';
                                    $metodoP = $metodoBadge[$p['metodo_pago']] ?? 'secondary';
                                    $esteRegistro = $p['id'] == $pago['id'];
                                ?>
                                    <tr <?= $esteRegistro ? 'class="table-active"' : '' ?>>
                                        <td>
                                            <strong>#<?= $p['id'] ?></strong>
                                            <?= $esteRegistro ? '<span class="badge bg-info ms-1">Actual</span>' : '' ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($p['fecha_pago'])) ?></td>
                                        <td><strong class="text-success">$<?= number_format($p['monto'], 2) ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?= $metodoP ?>">
                                                <?= ucfirst($p['metodo_pago']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $estadoP ?>">
                                                <?= ucfirst($p['estado']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL PAGADO:</th>
                                    <th colspan="3">
                                        <h5 class="mb-0 text-success">
                                            $<?= number_format($saldo['total_pagado'] ?? 0, 2) ?>
                                        </h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Columna Derecha: Resumen Financiero -->
        <div class="col-lg-4">
            
            <!-- Resumen de Cuenta -->
            <?php if ($saldo): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator"></i>
                        Resumen de Cuenta
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Reserva:</span>
                            <strong>$<?= number_format($saldo['precio_total'], 2) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Total Pagado:</span>
                            <strong class="text-success">$<?= number_format($saldo['total_pagado'], 2) ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-danger"><strong>Saldo Pendiente:</strong></span>
                            <h4 class="mb-0 text-danger">$<?= number_format($saldo['saldo_pendiente'], 2) ?></h4>
                        </div>
                    </div>
                    
                    <?php if ($saldo['saldo_pendiente'] > 0): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <small><strong>Atención:</strong> Aún hay saldo pendiente</small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i>
                            <small><strong>Pagado Completamente</strong></small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($saldo['saldo_pendiente'] > 0 && $pago['estado'] == 'completado'): ?>
                        <hr>
                        <a href="<?= $base ?>/pagos/crear?reserva_id=<?= $pago['reserva_id'] ?>" 
                           class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                            Registrar Nuevo Pago
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Información del Registro -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Información del Registro
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <div class="mb-2">
                            <i class="fas fa-calendar-plus"></i>
                            <strong>Registrado:</strong>
                            <br>
                            <?= date('d/m/Y H:i:s', strtotime($pago['created_at'] ?? $pago['fecha_pago'])) ?>
                        </div>
                        <?php if (isset($pago['updated_at']) && $pago['updated_at'] != $pago['created_at']): ?>
                            <div>
                                <i class="fas fa-edit"></i>
                                <strong>Actualizado:</strong>
                                <br>
                                <?= date('d/m/Y H:i:s', strtotime($pago['updated_at'])) ?>
                            </div>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= $base ?>/pagos/<?= $pago['id'] ?>/recibo" 
                           class="btn btn-primary btn-sm" 
                           target="_blank">
                            <i class="fas fa-print"></i>
                            Imprimir Recibo
                        </a>
                        <a href="<?= $base ?>/reservas/<?= $pago['reserva_id'] ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-ticket-alt"></i>
                            Ver Reserva Completa
                        </a>
                        <a href="<?= $base ?>/clientes/<?= $pago['cliente_id'] ?? '#' ?>" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user"></i>
                            Ver Cliente
                        </a>
                        <hr class="my-2">
                        <a href="<?= $base ?>/pagos" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i>
                            Volver a Pagos
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>

<!-- Estado del Pago -->
<div class="col-md-6">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <h6 class="text-muted mb-3">
                <i class="fas fa-info-circle"></i>
                Estado del Pago
            </h6>
            
            <div class="mb-3">
                <strong>Estado:</strong>
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
                    'reembolsado' => 'Reembolsado/Anulado',
                    default => ucfirst($pago['estado'])
                };
                ?>
                <span class="badge bg-<?= $badgeClass ?> fs-6">
                    <?= $estadoTexto ?>
                </span>
            </div>
            
            <div class="mb-3">
                <strong>Fecha de Pago:</strong><br>
                <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?>
            </div>
            
            <?php if (!empty($pago['referencia'])): ?>
            <div class="mb-3">
                <strong>Referencia:</strong><br>
                <code><?= htmlspecialchars($pago['referencia']) ?></code>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($pago['observaciones'])): ?>
            <div class="mb-3">
                <strong>Observaciones:</strong><br>
                <?= nl2br(htmlspecialchars($pago['observaciones'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Botón de Anular - Solo si está completado -->
<?php if ($pago['estado'] === 'completado'): ?>
<!-- <div class="card border-danger mb-4">
    <div class="card-body">
        <h5 class="card-title text-danger">
            <i class="fas fa-exclamation-triangle"></i>
            Zona de Peligro
        </h5>
        
        <p class="text-muted mb-3">
            Esta acción cambiará el estado del pago a <strong>Reembolsado</strong>. 
            No se puede deshacer.
        </p>
        
        <button type="button" 
                class="btn btn-danger" 
                data-bs-toggle="modal" 
                data-bs-target="#anularModal">
            <i class="fas fa-ban"></i>
            Anular Pago
        </button>
    </div> -->
</div>

<!-- Modal de Anulación -->
<div class="modal fade" id="anularModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmar Anulación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="<?= BASE_URL ?>/pagos/<?= $pago['id'] ?>/anular">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>¿Está seguro?</strong><br>
                        El pago se marcará como <strong>Reembolsado</strong> y se actualizará el saldo de la reserva.
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo" class="form-label">
                            Motivo de la anulación <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="motivo" 
                                  name="motivo" 
                                  rows="3" 
                                  required 
                                  placeholder="Describa el motivo de la anulación..."></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="confirmarAnular" 
                               required>
                        <label class="form-check-label" for="confirmarAnular">
                            Confirmo que deseo anular este pago
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i>
                        Anular Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php elseif ($pago['estado'] === 'reembolsado'): ?>
<div class="alert alert-danger">
    <i class="fas fa-ban"></i>
    <strong>Este pago ha sido anulado/reembolsado</strong>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>