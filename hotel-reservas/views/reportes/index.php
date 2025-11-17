<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\reportes\index.php

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-4 py-4">
    
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar text-primary"></i>
                Reportes y Estadísticas
            </h1>
            <p class="text-muted mb-0">Genera y visualiza reportes del sistema</p>
        </div>
    </div>

    <?php require_once __DIR__ . '/../components/alerts.php'; ?>

    <!-- Filtro de Fechas Global -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>/reportes" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_desde" 
                           name="fecha_desde" 
                           value="<?= $fecha_desde ?>" 
                           max="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_hasta" 
                           name="fecha_hasta" 
                           value="<?= $fecha_hasta ?>" 
                           max="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                        Aplicar Filtro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tipos de Reportes -->
    <div class="row g-4">
        
        <!-- Reporte de Ocupación -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-hotel fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Reporte de Ocupación</h5>
                    <p class="card-text text-muted">
                        Visualiza las estadísticas de ocupación de habitaciones por período y tipo
                    </p>
                    <a href="<?= BASE_URL ?>/reportes/ocupacion?fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-chart-pie"></i>
                        Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Ingresos -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Reporte de Ingresos</h5>
                    <p class="card-text text-muted">
                        Analiza los ingresos generados por pagos, métodos y períodos
                    </p>
                    <a href="<?= BASE_URL ?>/reportes/ingresos?fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>" 
                       class="btn btn-success">
                        <i class="fas fa-chart-line"></i>
                        Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Reservas -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Reporte de Reservas</h5>
                    <p class="card-text text-muted">
                        Revisa las estadísticas de reservas por estado, tipo y cliente
                    </p>
                    <a href="<?= BASE_URL ?>/reportes/reservas?fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>" 
                       class="btn btn-info text-white">
                        <i class="fas fa-chart-bar"></i>
                        Ver Reporte
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Información Adicional -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-info-circle text-info"></i>
                Información sobre los Reportes
            </h5>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <h6><i class="fas fa-file-pdf text-danger"></i> Exportar a PDF</h6>
                    <p class="text-muted small">
                        Puedes exportar cualquier reporte a formato PDF para imprimirlo o compartirlo
                    </p>
                </div>
                
                <div class="col-md-4">
                    <h6><i class="fas fa-file-excel text-success"></i> Exportar a Excel</h6>
                    <p class="text-muted small">
                        Descarga los datos en formato CSV compatible con Excel para análisis adicional
                    </p>
                </div>
                
                <div class="col-md-4">
                    <h6><i class="fas fa-calendar-alt text-primary"></i> Filtros Personalizados</h6>
                    <p class="text-muted small">
                        Selecciona rangos de fechas personalizados para generar reportes específicos
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>