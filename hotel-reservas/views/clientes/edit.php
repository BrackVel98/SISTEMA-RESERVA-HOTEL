<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\clientes\edit.php

$base = '/hotel-reservas/public';
$cliente = $data['cliente'] ?? null;
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

if (!$cliente) {
    header("Location: {$base}/clientes");
    exit;
}

// Usar valores antiguos si existen, sino usar los del cliente
$valores = !empty($old) ? $old : $cliente;

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-user-edit text-primary"></i>
                Editar Cliente
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/clientes">Clientes</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/clientes/<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i>
                        Actualizar Información
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= $base ?>/clientes/<?= $cliente['id'] ?>/editar">
                        
                        <div class="row g-3">
                            
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= htmlspecialchars($valores['nombre'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="<?= htmlspecialchars($valores['apellido'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="tipo_documento" class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Tipo Documento <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                    <option value="DNI" <?= ($valores['tipo_documento'] ?? '') == 'DNI' ? 'selected' : '' ?>>DNI</option>
                                    <option value="Pasaporte" <?= ($valores['tipo_documento'] ?? '') == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                                    <option value="CE" <?= ($valores['tipo_documento'] ?? '') == 'CE' ? 'selected' : '' ?>>Carnet de Extranjería</option>
                                </select>
                            </div>
                            
                            <div class="col-md-8">
                                <label for="documento" class="form-label">
                                    <i class="fas fa-id-badge"></i>
                                    Número de Documento <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="documento" 
                                       name="documento" 
                                       value="<?= htmlspecialchars($valores['documento'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($valores['email'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?= htmlspecialchars($valores['telefono'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_nacimiento" 
                                       name="fecha_nacimiento" 
                                       value="<?= htmlspecialchars($valores['fecha_nacimiento'] ?? '') ?>"
                                       max="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="nacionalidad" class="form-label">
                                    <i class="fas fa-globe"></i>
                                    Nacionalidad
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nacionalidad" 
                                       name="nacionalidad" 
                                       value="<?= htmlspecialchars($valores['nacionalidad'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="direccion" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección
                                </label>
                                <textarea class="form-control" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="2"><?= htmlspecialchars($valores['direccion'] ?? '') ?></textarea>
                            </div>
                            
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= $base ?>/clientes/<?= $cliente['id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fas fa-save"></i>
                                Guardar Cambios
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>