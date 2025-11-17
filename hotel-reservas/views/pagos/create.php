<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\pagos\create.php

$base = '/hotel-reservas/public';
$reserva = $data['reserva'] ?? null;
$saldo = $data['saldo'] ?? null;
$reservasPendientes = $data['reservasPendientes'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-plus-circle text-success"></i>
                Registrar Nuevo Pago
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/pagos">Pagos</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Formulario -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave"></i>
                        Datos del Pago
                    </h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="<?= $base ?>/pagos/crear" id="formPago">
                        
                        <div class="row g-3">
                            
                            <!-- Seleccionar Reserva -->
                            <div class="col-12">
                                <label for="reserva_id" class="form-label">
                                    <i class="fas fa-ticket-alt text-muted"></i>
                                    Reserva <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="reserva_id" name="reserva_id" required>
                                    <option value="">-- Seleccione una reserva --</option>
                                    <?php foreach ($reservasPendientes as $res): ?>
                                        <option value="<?= $res['id'] ?>" 
                                                <?= ($old['reserva_id'] ?? $reserva['id'] ?? '') == $res['id'] ? 'selected' : '' ?>
                                                data-total="<?= $res['precio_total'] ?>"
                                                data-pagado="<?= $res['pagado'] ?>"
                                                data-saldo="<?= $res['saldo'] ?>">
                                            <?= htmlspecialchars($res['codigo_reserva']) ?> 
                                            - <?= htmlspecialchars($res['cliente_nombre']) ?>
                                            - Saldo: $<?= number_format($res['saldo'], 2) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Seleccione la reserva a la cual aplicar el pago</small>
                            </div>
                            
                            <!-- Información de la Reserva -->
                            <?php if ($reserva && $saldo): ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle"></i>
                                            Información de la Reserva
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Cliente:</strong><br>
                                                <?= htmlspecialchars($reserva['cliente_nombre']) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Total Reserva:</strong><br>
                                                $<?= number_format($saldo['precio_total'], 2) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Pagado:</strong><br>
                                                $<?= number_format($saldo['total_pagado'], 2) ?>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="text-center">
                                            <strong class="text-danger">Saldo Pendiente:</strong>
                                            <h4 class="mb-0 text-danger">$<?= number_format($saldo['saldo_pendiente'], 2) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div id="infoReserva" class="col-12" style="display: none;">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Total:</strong>
                                            <h5 id="reservaTotal" class="mb-0">$0.00</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Pagado:</strong>
                                            <h5 id="reservaPagado" class="mb-0">$0.00</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Saldo Pendiente:</strong>
                                            <h4 id="reservaSaldo" class="mb-0 text-danger">$0.00</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Monto -->
                            <div class="col-md-6">
                                <label for="monto" class="form-label">
                                    <i class="fas fa-dollar-sign text-muted"></i>
                                    Monto a Pagar <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="monto" 
                                           name="monto" 
                                           step="0.01" 
                                           min="0.01" 
                                           placeholder="0.00" 
                                           value="<?= htmlspecialchars($old['monto'] ?? ($saldo['saldo_pendiente'] ?? '')) ?>"
                                           required 
                                           autofocus>
                                </div>
                                <small class="text-muted">Ingrese el monto exacto del pago</small>
                            </div>
                            
                            <!-- Método de Pago -->
                            <div class="col-md-6">
                                <label for="metodo_pago" class="form-label">
                                    <i class="fas fa-credit-card text-muted"></i>
                                    Método de Pago <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="efectivo" <?= ($old['metodo_pago'] ?? '') == 'efectivo' ? 'selected' : '' ?>>
                                        💵 Efectivo
                                    </option>
                                    <option value="tarjeta" <?= ($old['metodo_pago'] ?? '') == 'tarjeta' ? 'selected' : '' ?>>
                                        💳 Tarjeta (Débito/Crédito)
                                    </option>
                                    <option value="transferencia" <?= ($old['metodo_pago'] ?? '') == 'transferencia' ? 'selected' : '' ?>>
                                        🏦 Transferencia Bancaria
                                    </option>
                                    <option value="otro" <?= ($old['metodo_pago'] ?? '') == 'otro' ? 'selected' : '' ?>>
                                        📋 Otro
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Fecha de Pago -->
                            <div class="col-md-6">
                                <label for="fecha_pago" class="form-label">
                                    <i class="fas fa-calendar text-muted"></i>
                                    Fecha de Pago <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_pago" 
                                       name="fecha_pago" 
                                       value="<?= htmlspecialchars($old['fecha_pago'] ?? date('Y-m-d')) ?>"
                                       max="<?= date('Y-m-d') ?>"
                                       required>
                            </div>
                            
                            <!-- Referencia -->
                            <div class="col-md-6">
                                <label for="referencia" class="form-label">
                                    <i class="fas fa-hashtag text-muted"></i>
                                    Referencia / Número de Transacción
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="referencia" 
                                       name="referencia" 
                                       placeholder="Ej: Nro. de cheque, transacción, etc."
                                       value="<?= htmlspecialchars($old['referencia'] ?? '') ?>">
                                <small class="text-muted">Opcional - Para transferencias o tarjetas</small>
                            </div>
                            
                            <!-- Notas -->
                            <div class="col-12">
                                <label for="observaciones" class="form-label">
                                    <i class="fas fa-sticky-note text-muted"></i>
                                    Notas / Observaciones
                                </label>
                                <textarea class="form-control" 
                                          id="observaciones" 
                                          name="observaciones" 
                                          rows="3" 
                                          placeholder="Información adicional sobre el pago..."><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
                                <small class="text-muted">Opcional</small>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= $base ?>/pagos" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle"></i>
                                Registrar Pago
                            </button>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
        
        <!-- Información Lateral -->
        <div class="col-lg-4">
            
            <!-- Ayuda -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle"></i>
                        Ayuda
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Métodos de Pago:</h6>
                    <ul class="small mb-0">
                        <li><strong>Efectivo:</strong> Pago en dinero en efectivo</li>
                        <li><strong>Tarjeta:</strong> Débito o crédito</li>
                        <li><strong>Transferencia:</strong> Depósito o transferencia bancaria</li>
                        <li><strong>Otro:</strong> Cualquier otro método</li>
                    </ul>
                    <hr>
                    <h6>Importante:</h6>
                    <ul class="small mb-0">
                        <li>Verifique el monto antes de registrar</li>
                        <li>Los pagos completados no se pueden editar</li>
                        <li>Solo se pueden anular pagos erróneos</li>
                    </ul>
                </div>
            </div>
            
            <!-- Información de Pagos Recientes -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= $base ?>/pagos" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list"></i>
                            Ver Todos los Pagos
                        </a>
                        <a href="<?= $base ?>/reservas" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-calendar"></i>
                            Ver Reservas
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservaSelect = document.getElementById('reserva_id');
    const infoReserva = document.getElementById('infoReserva');
    const montoInput = document.getElementById('monto');
    
    reservaSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        
        if (this.value) {
            const total = parseFloat(option.dataset.total || 0);
            const pagado = parseFloat(option.dataset.pagado || 0);
            const saldo = parseFloat(option.dataset.saldo || 0);
            
            document.getElementById('reservaTotal').textContent = '$' + total.toFixed(2);
            document.getElementById('reservaPagado').textContent = '$' + pagado.toFixed(2);
            document.getElementById('reservaSaldo').textContent = '$' + saldo.toFixed(2);
            
            // Sugerir el saldo pendiente como monto
            montoInput.value = saldo.toFixed(2);
            montoInput.max = saldo.toFixed(2);
            
            infoReserva.style.display = 'block';
        } else {
            infoReserva.style.display = 'none';
            montoInput.value = '';
            montoInput.removeAttribute('max');
        }
    });
    
    // Trigger al cargar si hay reserva seleccionada
    if (reservaSelect.value) {
        reservaSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>