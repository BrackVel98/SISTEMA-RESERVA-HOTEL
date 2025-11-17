<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\clientes\index.php

$base = '/hotel-reservas/public';
$clientes = $data['clientes'] ?? [];

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2">
                <i class="fas fa-users text-primary"></i>
                Gestión de Clientes
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= $base ?>/clientes/crear" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Nuevo Cliente
            </a>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Total Clientes</h6>
                            <h3 class="mb-0"><?= count($clientes) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de clientes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Lista de Clientes
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="buscarCliente" 
                               placeholder="Buscar por nombre, documento o email...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($clientes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-slash fa-4x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted">No hay clientes registrados</h5>
                    <p class="text-muted mb-4">Comienza agregando tu primer cliente</p>
                    <a href="<?= $base ?>/clientes/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Primer Cliente
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablaClientes">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Documento</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Reservas</th>
                                <th>Registro</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><strong>#<?= $cliente['id'] ?></strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                                <?= strtoupper(substr($cliente['nombre'], 0, 1) . substr($cliente['apellido'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?>
                                                </div>
                                                <?php if (!empty($cliente['nacionalidad'])): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-globe"></i>
                                                        <?= htmlspecialchars($cliente['nacionalidad']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($cliente['tipo_documento']) ?>
                                        </span>
                                        <?= htmlspecialchars($cliente['documento']) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        <?= htmlspecialchars($cliente['email']) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted me-1"></i>
                                        <?= htmlspecialchars($cliente['telefono']) ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $totalReservas = $cliente['total_reservas'] ?? 0;
                                        $confirmadas = $cliente['reservas_confirmadas'] ?? 0;
                                        ?>
                                        <span class="badge bg-info">
                                            <?= $totalReservas ?> total
                                        </span>
                                        <?php if ($confirmadas > 0): ?>
                                            <span class="badge bg-success">
                                                <?= $confirmadas ?> activas
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($cliente['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= $base ?>/clientes/<?= $cliente['id'] ?>" 
                                               class="btn btn-outline-primary"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= $base ?>/clientes/<?= $cliente['id'] ?>/editar" 
                                               class="btn btn-outline-warning"
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger"
                                                    onclick="confirmarEliminacion(<?= $cliente['id'] ?>, '<?= htmlspecialchars($cliente['nombre']) ?>')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al cliente <strong id="nombreClienteEliminar"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-info-circle"></i>
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Eliminar Cliente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}
</style>

<script>
// Búsqueda en tiempo real
document.getElementById('buscarCliente')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#tablaClientes tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Confirmar eliminación
function confirmarEliminacion(id, nombre) {
    document.getElementById('nombreClienteEliminar').textContent = nombre;
    document.getElementById('formEliminar').action = '<?= $base ?>/clientes/' + id + '/eliminar';
    
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>