<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\clientes\create.php

$base = '/hotel-reservas/public';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-user-plus text-primary"></i>
                Nuevo Cliente
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/clientes">Clientes</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i>
                        Información del Cliente
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?= $base ?>/clientes/crear" id="formCliente">
                        
                        <div class="row g-3">
                            
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="<?= htmlspecialchars($old['apellido'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <!-- Tipo Documento -->
                            <div class="col-md-4">
                                <label for="tipo_documento" class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Tipo Documento <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                    <option value="DNI" <?= ($old['tipo_documento'] ?? '') == 'DNI' ? 'selected' : '' ?>>DNI</option>
                                    <option value="Pasaporte" <?= ($old['tipo_documento'] ?? '') == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                                    <option value="CE" <?= ($old['tipo_documento'] ?? '') == 'CE' ? 'selected' : '' ?>>Carnet de Extranjería</option>
                                </select>
                            </div>
                            
                            <!-- Documento -->
                            <div class="col-md-8">
                                <label for="documento" class="form-label">
                                    <i class="fas fa-id-badge"></i>
                                    Número de Documento <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="documento" 
                                       name="documento" 
                                       value="<?= htmlspecialchars($old['documento'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?= htmlspecialchars($old['telefono'] ?? '') ?>"
                                       required>
                            </div>
                            
                            <!-- Fecha Nacimiento -->
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_nacimiento" 
                                       name="fecha_nacimiento" 
                                       value="<?= htmlspecialchars($old['fecha_nacimiento'] ?? '') ?>"
                                       max="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <!-- Nacionalidad -->
                            <div class="col-md-6">
                                <label for="nacionalidad" class="form-label">
                                    <i class="fas fa-globe"></i>
                                    Nacionalidad
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nacionalidad" 
                                       name="nacionalidad" 
                                       value="<?= htmlspecialchars($old['nacionalidad'] ?? 'Peruana') ?>"
                                       placeholder="Ej: Peruana, Argentina, etc.">
                            </div>
                            
                            <!-- Dirección -->
                            <div class="col-12">
                                <label for="direccion" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección
                                </label>
                                <textarea class="form-control" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="2"><?= htmlspecialchars($old['direccion'] ?? '') ?></textarea>
                            </div>
                            
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= $base ?>/clientes" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Guardar Cliente
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>