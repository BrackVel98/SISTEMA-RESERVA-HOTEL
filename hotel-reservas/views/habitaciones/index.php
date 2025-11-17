<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\habitaciones\index.php

$base = '/hotel-reservas/public';
$habitaciones = $data['habitaciones'] ?? [];
$tipos = $data['tipos'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$filtros = $data['filtros'] ?? [];

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-bed text-primary"></i>
                Gestión de Habitaciones
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Habitaciones</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= $base ?>/habitaciones/crear" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nueva Habitación
            </a>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $estadisticas['total'] ?></h3>
                    <small>Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $estadisticas['disponibles'] ?></h3>
                    <small>Disponibles</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $estadisticas['ocupadas'] ?></h3>
                    <small>Ocupadas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $estadisticas['mantenimiento'] ?></h3>
                    <small>Mantenimiento</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $estadisticas['limpieza'] ?></h3>
                    <small>Limpieza</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $base ?>/habitaciones" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Tipo</label>
                    <select name="tipo_id" class="form-select form-select-sm">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>" <?= $filtros['tipo_id'] == $tipo['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="disponible" <?= $filtros['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="ocupada" <?= $filtros['estado'] == 'ocupada' ? 'selected' : '' ?>>Ocupada</option>
                        <option value="mantenimiento" <?= $filtros['estado'] == 'mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                        <option value="limpieza" <?= $filtros['estado'] == 'limpieza' ? 'selected' : '' ?>>Limpieza</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Piso</label>
                    <input type="text" name="piso" class="form-control form-control-sm" 
                           placeholder="Ej: 1, 2, 3..." value="<?= htmlspecialchars($filtros['piso']) ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="<?= $base ?>/habitaciones" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de Habitaciones -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i>
                Lista de Habitaciones (<?= count($habitaciones) ?>)
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($habitaciones)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-bed fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted">No hay habitaciones registradas</p>
                    <a href="<?= $base ?>/habitaciones/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Primera Habitación
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th>Piso</th>
                                <th>Capacidad</th>
                                <th>Precio/Noche</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($habitaciones as $habitacion): 
                                $estadoBadge = [
                                    'disponible' => 'success',
                                    'ocupada' => 'danger',
                                    'mantenimiento' => 'warning',
                                    'limpieza' => 'info'
                                ];
                                $badge = $estadoBadge[$habitacion['estado']] ?? 'secondary';
                            ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary">
                                            <i class="fas fa-door-open"></i>
                                            <?= htmlspecialchars($habitacion['numero']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($habitacion['tipo_nombre']) ?></strong>
                                            <?php if (!empty($habitacion['tipo_descripcion'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($habitacion['tipo_descripcion']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($habitacion['piso'])): ?>
                                            <i class="fas fa-building text-muted"></i>
                                            Piso <?= htmlspecialchars($habitacion['piso']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-users text-muted"></i>
                                        <?= $habitacion['capacidad'] ?> personas
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            $<?= number_format($habitacion['precio_base'], 2) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= ucfirst($habitacion['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <!-- ✅ BOTÓN VER DETALLES CORREGIDO -->
                                            <a href="<?= $base ?>/habitaciones/<?= $habitacion['id'] ?>" 
                                               class="btn btn-outline-primary" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= $base ?>/habitaciones/<?= $habitacion['id'] ?>/editar" 
                                               class="btn btn-outline-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- ✅ BOTÓN CREAR RESERVA CORREGIDO -->
                                            <a href="<?= $base ?>/reservas/crear?habitacion_id=<?= $habitacion['id'] ?>" 
                                               class="btn btn-outline-success" 
                                               title="Crear reserva"
                                               <?= $habitacion['estado'] != 'disponible' ? 'onclick="return confirm(\'La habitación está ' . $habitacion['estado'] . '. ¿Desea continuar?\')"' : '' ?>>
                                                <i class="fas fa-calendar-plus"></i>
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