<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\habitaciones\create.php

$base = '/hotel-reservas/public';
$tipos = $data['tipos'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-plus-circle text-primary"></i>
                Nueva Habitación
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/habitaciones">Habitaciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-door-open"></i>
                        Datos de la Habitación
                    </h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="<?= $base ?>/habitaciones/crear">
                        
                        <div class="row g-3">
                            
                            <!-- Número -->
                            <div class="col-md-6">
                                <label for="numero" class="form-label">
                                    <i class="fas fa-hashtag text-muted"></i>
                                    Número de Habitación <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="numero" 
                                       name="numero" 
                                       placeholder="Ej: 101, 201, A-1" 
                                       value="<?= htmlspecialchars($old['numero'] ?? '') ?>"
                                       required 
                                       autofocus>
                                <small class="text-muted">Identificador único de la habitación</small>
                            </div>
                            
                            <!-- Tipo de Habitación -->
                            <div class="col-md-6">
                                <label for="tipo_id" class="form-label">
                                    <i class="fas fa-bed text-muted"></i>
                                    Tipo de Habitación <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipo_id" name="tipo_id" required>
                                    <option value="">-- Seleccione un tipo --</option>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?= $tipo['id'] ?>" 
                                                <?= ($old['tipo_id'] ?? '') == $tipo['id'] ? 'selected' : '' ?>
                                                data-precio="<?= $tipo['precio_base'] ?>"
                                                data-capacidad="<?= $tipo['capacidad'] ?>">
                                            <?= htmlspecialchars($tipo['nombre']) ?> 
                                            - $<?= number_format($tipo['precio_base'], 2) ?>/noche
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Piso -->
                            <div class="col-md-6">
                                <label for="piso" class="form-label">
                                    <i class="fas fa-building text-muted"></i>
                                    Piso
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="piso" 
                                       name="piso" 
                                       placeholder="Ej: 1, 2, PB" 
                                       value="<?= htmlspecialchars($old['piso'] ?? '') ?>">
                                <small class="text-muted">Opcional</small>
                            </div>
                            
                            <!-- Estado -->
                            <div class="col-md-6">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-info-circle text-muted"></i>
                                    Estado Inicial <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="disponible" <?= ($old['estado'] ?? 'disponible') == 'disponible' ? 'selected' : '' ?>>
                                        Disponible
                                    </option>
                                    <option value="ocupada" <?= ($old['estado'] ?? '') == 'ocupada' ? 'selected' : '' ?>>
                                        Ocupada
                                    </option>
                                    <option value="mantenimiento" <?= ($old['estado'] ?? '') == 'mantenimiento' ? 'selected' : '' ?>>
                                        Mantenimiento
                                    </option>
                                    <option value="limpieza" <?= ($old['estado'] ?? '') == 'limpieza' ? 'selected' : '' ?>>
                                        Limpieza
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Descripción -->
                            <div class="col-12">
                                <label for="descripcion" class="form-label">
                                    <i class="fas fa-file-alt text-muted"></i>
                                    Descripción / Características
                                </label>
                                <textarea class="form-control" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3" 
                                          placeholder="Características especiales, vista, ubicación, etc."><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                                <small class="text-muted">Opcional - Información adicional sobre la habitación</small>
                            </div>
                            
                            <!-- Vista previa del tipo seleccionado -->
                            <div class="col-12">
                                <div id="tipoInfo" class="alert alert-info" style="display: none;">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle"></i>
                                        Información del Tipo Seleccionado
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Precio por noche:</strong> <span id="tipoPrecio">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Capacidad:</strong> <span id="tipoCapacidad">-</span> personas
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= $base ?>/habitaciones" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Crear Habitación
                            </button>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo_id');
    const tipoInfo = document.getElementById('tipoInfo');
    const tipoPrecio = document.getElementById('tipoPrecio');
    const tipoCapacidad = document.getElementById('tipoCapacidad');
    
    tipoSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        
        if (this.value) {
            const precio = option.dataset.precio;
            const capacidad = option.dataset.capacidad;
            
            tipoPrecio.textContent = '$' + parseFloat(precio).toFixed(2);
            tipoCapacidad.textContent = capacidad;
            
            tipoInfo.style.display = 'block';
        } else {
            tipoInfo.style.display = 'none';
        }
    });
    
    // Trigger si ya hay un tipo seleccionado
    if (tipoSelect.value) {
        tipoSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>