<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\clientes\show.php

$base = '/hotel-reservas/public';
$cliente = $data['cliente'] ?? null;
$estadisticas = $data['estadisticas'] ?? [];

if (!$cliente) {
    header("Location: {$base}/clientes");
    exit;
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-2">
                <i class="fas fa-user text-primary"></i>
                <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/clientes">Clientes</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($cliente['nombre']) ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= $base ?>/clientes/<?= $cliente['id'] ?>/editar" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Editar
            </a>
            <a href="<?= $base ?>/clientes" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>
    
    <div class="row g-4">
        
        <!-- Información del Cliente -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="avatar-xl bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3">
                        <span class="display-4">
                            <?= strtoupper(substr($cliente['nombre'], 0, 1) . substr($cliente['apellido'], 0, 1)) ?>
                        </span>
                    </div>
                    <h4 class="mb-1"><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-id-card"></i>
                        <?= htmlspecialchars($cliente['tipo_documento']) ?>: <?= htmlspecialchars($cliente['documento']) ?>
                    </p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong><br>
                        <a href="mailto:<?= htmlspecialchars($cliente['email']) ?>">
                            <?= htmlspecialchars($cliente['email']) ?>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <strong>Teléfono:</strong><br>
                        <a href="tel:<?= htmlspecialchars($cliente['telefono']) ?>">
                            <?= htmlspecialchars($cliente['telefono']) ?>
                        </a>
                    </li>
                    <?php if (!empty($cliente['fecha_nacimiento'])): ?>
                    <li class="list-group-item">
                        <i class="fas fa-birthday-cake text-primary me-2"></i>
                        <strong>F. Nacimiento:</strong><br>
                        <?= date('d/m/Y', strtotime($cliente['fecha_nacimiento'])) ?>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($cliente['nacionalidad'])): ?>
                    <li class="list-group-item">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <strong>Nacionalidad:</strong><br>
                        <?= htmlspecialchars($cliente['nacionalidad']) ?>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($cliente['direccion'])): ?>
                    <li class="list-group-item">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <strong>Dirección:</strong><br>
                        <?= htmlspecialchars($cliente['direccion']) ?>
                    </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        <strong>Registrado:</strong><br>
                        <?= date('d/m/Y H:i', strtotime($cliente['created_at'])) ?>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Estadísticas y Reservas -->
        <div class="col-lg-8">
            
            <!-- Estadísticas -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-primary mb-2">
                                <?= $estadisticas['total_reservas'] ?>
                            </div>
                            <small class="text-muted">Total Reservas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-success mb-2">
                                <?= $estadisticas['reservas_confirmadas'] ?>
                            </div>
                            <small class="text-muted">Confirmadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-6 text-info mb-2">
                                <?= $estadisticas['reservas_completadas'] ?>
                            </div>
                            <small class="text-muted">Completadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="h5 text-primary mb-2">
                                $<?= number_format($estadisticas['total_gastado'], 2) ?>
                            </div>
                            <small class="text-muted">Total Gastado</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historial de Reservas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i>
                        Historial de Reservas
                    </h5>
                    <a href="<?= $base ?>/reservas/crear?cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i>
                        Nueva Reserva
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($cliente['reservas'])): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay reservas registradas</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Habitación</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cliente['reservas'] as $reserva): ?>
                                        <tr>
                                            <td><strong>#<?= $reserva['id'] ?></strong></td>
                                            <td>
                                                <?= htmlspecialchars($reserva['tipo_habitacion_nombre']) ?><br>
                                                <small class="text-muted">Hab. <?= $reserva['habitacion_numero'] ?></small>
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
.avatar-xl {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>