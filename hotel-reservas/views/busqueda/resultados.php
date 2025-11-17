<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\busqueda\resultados.php

$base = '/hotel-reservas/public';
$tiposHabitacion = $data['tiposHabitacion'] ?? [];
$tipo_id = $data['tipo_id'] ?? '';
$fecha_entrada = $data['fecha_entrada'] ?? date('Y-m-d');
$fecha_salida = $data['fecha_salida'] ?? date('Y-m-d', strtotime('+1 day'));
$habitacionesDisponibles = $data['habitacionesDisponibles'] ?? [];
$busquedaActiva = $data['busquedaActiva'] ?? false;

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">
                <i class="fas fa-search text-primary"></i>
                Buscar Habitaciones Disponibles
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/">Inicio</a></li>
                    <li class="breadcrumb-item active">Búsqueda</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- DEBUG: Mostrar cuántos tipos hay -->
    <?php if (empty($tiposHabitacion)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Atención:</strong> No hay tipos de habitación registrados en la base de datos.
            <a href="<?= $base ?>/dashboard" class="alert-link">Ir al Dashboard</a>
        </div>
    <?php endif; ?>
    
    <!-- Formulario de Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= $base ?>/buscar" class="row g-3" id="formBusqueda">
                
                <div class="col-md-3">
                    <label for="tipo" class="form-label">
                        <i class="fas fa-bed"></i> Tipo de Habitación
                    </label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos los tipos (<?= count($tiposHabitacion) ?>)</option>
                        <?php foreach ($tiposHabitacion as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>" <?= $tipo_id == $tipo['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['nombre']) ?> - $<?= number_format($tipo['precio_base'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="fecha_entrada" class="form-label">
                        <i class="fas fa-calendar-alt"></i> Fecha de Entrada
                    </label>
                    <input type="date" 
                           name="fecha_entrada" 
                           id="fecha_entrada" 
                           class="form-control" 
                           value="<?= htmlspecialchars($fecha_entrada) ?>"
                           min="<?= date('Y-m-d') ?>"
                           required>
                </div>
                
                <div class="col-md-3">
                    <label for="fecha_salida" class="form-label">
                        <i class="fas fa-calendar-alt"></i> Fecha de Salida
                    </label>
                    <input type="date" 
                           name="fecha_salida" 
                           id="fecha_salida" 
                           class="form-control" 
                           value="<?= htmlspecialchars($fecha_salida) ?>"
                           min="<?= date('Y-m-d', strtotime($fecha_entrada . ' +1 day')) ?>"
                           required>
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="buscar" value="1" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    
    <!-- Resultados -->
    <?php if ($busquedaActiva): ?>
        
        <h4 class="mb-4">
            Habitaciones Disponibles 
            <?php if (!empty($habitacionesDisponibles)): ?>
                <span class="badge bg-primary"><?= count($habitacionesDisponibles) ?></span>
            <?php endif; ?>
        </h4>
        
        <?php if (empty($habitacionesDisponibles)): ?>
            
            <div class="alert alert-warning text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>No hay habitaciones disponibles</h5>
                <p class="mb-3">
                    No se encontraron habitaciones disponibles del 
                    <strong><?= date('d/m/Y', strtotime($fecha_entrada)) ?></strong> 
                    al 
                    <strong><?= date('d/m/Y', strtotime($fecha_salida)) ?></strong>
                    <?php if (!empty($tipo_id)): ?>
                        para el tipo seleccionado
                    <?php endif; ?>
                </p>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('tipo').value=''; document.getElementById('formBusqueda').submit();">
                    <i class="fas fa-redo"></i>
                    Buscar en todos los tipos
                </button>
            </div>
            
        <?php else: ?>
            
            <div class="row g-4">
                <?php foreach ($habitacionesDisponibles as $habitacion): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <?= htmlspecialchars($habitacion['tipo_nombre']) ?>
                                        </h5>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-door-closed"></i>
                                            Habitación #<?= htmlspecialchars($habitacion['numero']) ?>
                                        </p>
                                    </div>
                                    <span class="badge bg-success">Disponible</span>
                                </div>
                                
                                <p class="card-text text-muted small mb-3">
                                    <?= htmlspecialchars($habitacion['tipo_descripcion']) ?>
                                </p>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        Capacidad: <strong><?= $habitacion['capacidad'] ?></strong> personas
                                    </small>
                                </div>
                                
                                <div class="bg-primary bg-opacity-10 p-3 rounded mb-3 text-center">
                                    <h4 class="text-primary mb-0">
                                        $<?= number_format($habitacion['precio_base'], 2) ?>
                                    </h4>
                                    <small class="text-muted">por noche</small>
                                </div>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="<?= $base ?>/reservas/crear?habitacion_id=<?= $habitacion['id'] ?>&fecha_entrada=<?= urlencode($fecha_entrada) ?>&fecha_salida=<?= urlencode($fecha_salida) ?>" 
                                       class="btn btn-primary w-100">
                                        <i class="fas fa-calendar-plus"></i>
                                        Reservar Ahora
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $base ?>/login?redirect=<?= urlencode('/reservas/crear?habitacion_id=' . $habitacion['id'] . '&fecha_entrada=' . $fecha_entrada . '&fecha_salida=' . $fecha_salida) ?>" 
                                       class="btn btn-primary w-100">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Iniciar Sesión para Reservar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php endif; ?>
        
    <?php else: ?>
        
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-4 opacity-25"></i>
            <h5 class="text-muted">Selecciona las fechas para buscar disponibilidad</h5>
            <p class="text-muted">Puedes filtrar por tipo de habitación o ver todas las disponibles</p>
        </div>
        
    <?php endif; ?>
    
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
}
</style>

<script>
// Validar fechas en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaSalida = document.getElementById('fecha_salida');
    
    // Cuando cambia la fecha de entrada
    fechaEntrada.addEventListener('change', function() {
        const entrada = new Date(this.value);
        const salida = new Date(fechaSalida.value);
        
        // Si la salida es menor o igual, ajustar
        if (salida <= entrada) {
            const nuevaSalida = new Date(entrada);
            nuevaSalida.setDate(nuevaSalida.getDate() + 1);
            fechaSalida.value = nuevaSalida.toISOString().split('T')[0];
        }
        
        // Actualizar el mínimo de fecha_salida
        const minSalida = new Date(entrada);
        minSalida.setDate(minSalida.getDate() + 1);
        fechaSalida.min = minSalida.toISOString().split('T')[0];
    });
    
    // Validar antes de enviar
    document.getElementById('formBusqueda').addEventListener('submit', function(e) {
        const entrada = new Date(fechaEntrada.value);
        const salida = new Date(fechaSalida.value);
        
        if (salida <= entrada) {
            e.preventDefault();
            alert('La fecha de salida debe ser posterior a la fecha de entrada');
            return false;
        }
    });
    
    console.log('Tipos de habitación cargados:', <?= count($tiposHabitacion) ?>);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>