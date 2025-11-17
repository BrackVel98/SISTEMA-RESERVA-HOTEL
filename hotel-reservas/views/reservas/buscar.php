<?php
?>
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-search"></i> Buscar Habitaciones Disponibles</h2>
        <p class="text-muted">Encuentra la habitación perfecta para tu estancia</p>
    </div>
</div>

<!-- Formulario de Búsqueda Avanzada -->
<div class="card shadow mb-4">
    <div class="card-body p-4">
        <form action="<?= url('/buscar') ?>" method="GET">
            <div class="row g-3">
                <!-- Fechas -->
                <div class="col-md-6">
                    <label for="fecha_entrada" class="form-label">
                        <i class="bi bi-calendar-check"></i> Fecha de Entrada
                        <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control form-control-lg" 
                           id="fecha_entrada" name="fecha_entrada" 
                           value="<?= $_GET['fecha_entrada'] ?? '' ?>"
                           min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="col-md-6">
                    <label for="fecha_salida" class="form-label">
                        <i class="bi bi-calendar-x"></i> Fecha de Salida
                        <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control form-control-lg" 
                           id="fecha_salida" name="fecha_salida" 
                           value="<?= $_GET['fecha_salida'] ?? '' ?>"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>
                
                <!-- Filtros -->
                <div class="col-md-4">
                    <label for="tipo_id" class="form-label">
                        <i class="bi bi-door-open"></i> Tipo de Habitación
                    </label>
                    <select class="form-select form-select-lg" id="tipo_id" name="tipo_id">
                        <option value="">Todos los tipos</option>
                        <?php 
                        $tipoHabitacion = new TipoHabitacion();
                        $tipos = $tipoHabitacion->all();
                        foreach ($tipos as $tipo): 
                        ?>
                            <option value="<?= $tipo['id'] ?>" 
                                    <?= ($_GET['tipo_id'] ?? '') == $tipo['id'] ? 'selected' : '' ?>>
                                <?= e($tipo['nombre']) ?> - 
                                <?= formatCurrency($tipo['precio_base']) ?>/noche
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="capacidad" class="form-label">
                        <i class="bi bi-people"></i> Número de Huéspedes
                    </label>
                    <select class="form-select form-select-lg" id="capacidad" name="capacidad">
                        <option value="">Cualquier capacidad</option>
                        <option value="1" <?= ($_GET['capacidad'] ?? '') == '1' ? 'selected' : '' ?>>
                            1 persona
                        </option>
                        <option value="2" <?= ($_GET['capacidad'] ?? '') == '2' ? 'selected' : '' ?>>
                            2 personas
                        </option>
                        <option value="3" <?= ($_GET['capacidad'] ?? '') == '3' ? 'selected' : '' ?>>
                            3 personas
                        </option>
                        <option value="4" <?= ($_GET['capacidad'] ?? '') == '4' ? 'selected' : '' ?>>
                            4+ personas
                        </option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="precio_max" class="form-label">
                        <i class="bi bi-currency-dollar"></i> Precio Máximo por Noche
                    </label>
                    <input type="number" class="form-control form-control-lg" 
                           id="precio_max" name="precio_max" 
                           value="<?= $_GET['precio_max'] ?? '' ?>"
                           placeholder="Sin límite" step="0.01" min="0">
                </div>
                
                <!-- Botón de Búsqueda -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search"></i> Buscar Habitaciones Disponibles
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tipos de Habitación Disponibles -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">Tipos de Habitación</h4>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($tipos as $tipo): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-door-open text-primary"></i> 
                            <?= e($tipo['nombre']) ?>
                        </h5>
                        <span class="badge bg-primary">
                            <?= formatCurrency($tipo['precio_base']) ?>
                        </span>
                    </div>
                    
                    <p class="card-text text-muted">
                        <?= e($tipo['descripcion']) ?>
                    </p>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-people text-primary"></i> 
                            Capacidad: <strong><?= $tipo['capacidad'] ?> persona(s)</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-door-open text-primary"></i> 
                            <?= $tipo['num_habitaciones'] ?? 0 ?> habitación(es) de este tipo
                        </li>
                    </ul>
                    
                    <button type="button" class="btn btn-outline-primary w-100"
                            onclick="document.getElementById('tipo_id').value='<?= $tipo['id'] ?>'; 
                                     window.scrollTo(0, 0);">
                        <i class="bi bi-filter"></i> Buscar este tipo
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Información Adicional -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary"></i> 
                    Información Importante
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-clock text-primary"></i> 
                                Check-in: 14:00 hrs
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock-history text-primary"></i> 
                                Check-out: 12:00 hrs
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-shield-check text-primary"></i> 
                                Reserva segura con protección de datos
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-wifi text-primary"></i> 
                                WiFi gratuito en todas las habitaciones
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-credit-card text-primary"></i> 
                                Múltiples métodos de pago
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-telephone text-primary"></i> 
                                Atención 24/7
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validación de fechas
document.getElementById('fecha_entrada').addEventListener('change', function() {
    const fechaEntrada = new Date(this.value);
    const fechaSalidaInput = document.getElementById('fecha_salida');
    const minFechaSalida = new Date(fechaEntrada);
    minFechaSalida.setDate(minFechaSalida.getDate() + 1);
    
    fechaSalidaInput.min = minFechaSalida.toISOString().split('T')[0];
    
    if (fechaSalidaInput.value && new Date(fechaSalidaInput.value) <= fechaEntrada) {
        fechaSalidaInput.value = minFechaSalida.toISOString().split('T')[0];
    }
});
</script>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>