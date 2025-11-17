<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\reservas\create.php

$base = '/hotel-reservas/public';
$clientes = $data['clientes'] ?? [];
$habitacionesDisponibles = $data['habitacionesDisponibles'] ?? [];
$habitacion_id = $data['habitacion_id'] ?? '';
$cliente_id = $data['cliente_id'] ?? '';
$fecha_entrada = $data['fecha_entrada'] ?? date('Y-m-d');
$fecha_salida = $data['fecha_salida'] ?? date('Y-m-d', strtotime('+1 day'));
$habitacionSeleccionada = $data['habitacionSeleccionada'] ?? null;
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">
                <i class="fas fa-calendar-plus text-primary"></i>
                Nueva Reserva
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $base ?>/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= $base ?>/reservas">Reservas</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <form method="POST" action="<?= $base ?>/reservas/crear" id="formReserva">
        
        <div class="row g-4">
            
            <!-- Columna Izquierda: Selección de datos -->
            <div class="col-lg-7">
                
                <!-- Selección de Cliente -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i>
                            1. Seleccionar Cliente
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($clientes)): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay clientes registrados. 
                                <a href="<?= $base ?>/clientes/crear" class="alert-link">Crear nuevo cliente</a>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="cliente_id" class="form-label">
                                        Cliente <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="cliente_id" name="cliente_id" required>
                                        <option value="">-- Seleccione un cliente --</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= $cliente['id'] ?>" 
                                                    <?= ($old['cliente_id'] ?? $cliente_id) == $cliente['id'] ? 'selected' : '' ?>
                                                    data-email="<?= htmlspecialchars($cliente['email']) ?>"
                                                    data-telefono="<?= htmlspecialchars($cliente['telefono']) ?>"
                                                    data-documento="<?= htmlspecialchars($cliente['documento']) ?>">
                                                <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?> 
                                                - <?= htmlspecialchars($cliente['documento']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Info del cliente seleccionado -->
                                <div class="col-12" id="infoCliente" style="display: none;">
                                    <div class="alert alert-info mb-0">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Email:</strong><br>
                                                <span id="clienteEmail"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Teléfono:</strong><br>
                                                <span id="clienteTelefono"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Documento:</strong><br>
                                                <span id="clienteDocumento"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Fechas de Reserva -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar"></i>
                            2. Fechas de Estadía
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fecha_entrada" class="form-label">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Fecha de Entrada <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_entrada" 
                                       name="fecha_entrada" 
                                       value="<?= htmlspecialchars($old['fecha_entrada'] ?? $fecha_entrada) ?>"
                                       min="<?= date('Y-m-d') ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fecha_salida" class="form-label">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Fecha de Salida <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_salida" 
                                       name="fecha_salida" 
                                       value="<?= htmlspecialchars($old['fecha_salida'] ?? $fecha_salida) ?>"
                                       min="<?= date('Y-m-d', strtotime($fecha_entrada . ' +1 day')) ?>"
                                       required>
                            </div>
                            
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Noches de estadía:</strong> <span id="totalNoches">1</span> noche(s)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Selección de Habitación -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bed"></i>
                            3. Seleccionar Habitación
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($habitacionesDisponibles)): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay habitaciones disponibles para las fechas seleccionadas.
                                <button type="button" class="btn btn-sm btn-warning" onclick="buscarHabitaciones()">
                                    <i class="fas fa-sync"></i>
                                    Buscar Habitaciones
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="row g-3" id="habitacionesContainer">
                                <?php foreach ($habitacionesDisponibles as $habitacion): ?>
                                    <div class="col-12">
                                        <div class="card habitacion-card <?= $habitacion['id'] == $habitacion_id ? 'border-primary' : 'border' ?>">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input habitacion-radio" 
                                                               type="radio" 
                                                               name="habitacion_id" 
                                                               id="hab_<?= $habitacion['id'] ?>" 
                                                               value="<?= $habitacion['id'] ?>"
                                                               data-precio="<?= $habitacion['precio_base'] ?>"
                                                               <?= ($old['habitacion_id'] ?? $habitacion_id) == $habitacion['id'] ? 'checked' : '' ?>
                                                               required>
                                                    </div>
                                                    <label class="flex-grow-1 cursor-pointer" for="hab_<?= $habitacion['id'] ?>">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    <?= htmlspecialchars($habitacion['tipo_nombre']) ?>
                                                                    <span class="badge bg-secondary ms-2">Hab. <?= $habitacion['numero'] ?></span>
                                                                </h6>
                                                                <p class="text-muted small mb-2">
                                                                    <?= htmlspecialchars($habitacion['tipo_descripcion']) ?>
                                                                </p>
                                                                <div class="text-muted small">
                                                                    <i class="fas fa-users"></i>
                                                                    Capacidad: <?= $habitacion['capacidad'] ?> personas
                                                                    <?php if (!empty($habitacion['piso'])): ?>
                                                                        | <i class="fas fa-building"></i> Piso <?= $habitacion['piso'] ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="h5 text-primary mb-0">
                                                                    $<?= number_format($habitacion['precio_base'], 2) ?>
                                                                </div>
                                                                <small class="text-muted">por noche</small>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
            
            <!-- Columna Derecha: Resumen y observaciones -->
            <div class="col-lg-5">
                
                <!-- Resumen de la Reserva -->
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt"></i>
                            Resumen de la Reserva
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" 
                                      name="observaciones" 
                                      rows="3" 
                                      placeholder="Notas adicionales sobre la reserva..."><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Precio por noche:</span>
                            <strong id="precioNoche">$0.00</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Número de noches:</span>
                            <strong id="numeroNoches">0</strong>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total:</h5>
                            <h4 class="text-success mb-0" id="precioTotal">$0.00</h4>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="btnCrearReserva" disabled>
                                <i class="fas fa-check-circle"></i>
                                Crear Reserva
                            </button>
                            <a href="<?= $base ?>/reservas" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                        </div>
                        
                        <div class="alert alert-info mt-3 mb-0 small">
                            <i class="fas fa-info-circle"></i>
                            La reserva se creará en estado <strong>Pendiente</strong> y deberá ser confirmada posteriormente.
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </form>
    
</div>

<style>
.habitacion-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.habitacion-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.habitacion-card.border-primary {
    background-color: #f0f8ff;
    border-width: 2px !important;
}

.cursor-pointer {
    cursor: pointer;
}

.sticky-top {
    z-index: 1020;
}

.form-check-input.habitacion-radio:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const clienteSelect = document.getElementById('cliente_id');
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaSalida = document.getElementById('fecha_salida');
    const habitacionRadios = document.querySelectorAll('.habitacion-radio');
    const btnCrearReserva = document.getElementById('btnCrearReserva');
    
    // Elementos de visualización
    const totalNochesSpan = document.getElementById('totalNoches');
    const numeroNochesSpan = document.getElementById('numeroNoches');
    const precioNocheSpan = document.getElementById('precioNoche');
    const precioTotalSpan = document.getElementById('precioTotal');
    
    console.log('🚀 Script inicializado');
    console.log('Habitaciones encontradas:', habitacionRadios.length);
    
    /**
     * Mostrar información del cliente seleccionado
     */
    if (clienteSelect) {
        clienteSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('infoCliente');
            
            if (this.value) {
                document.getElementById('clienteEmail').textContent = option.dataset.email || '-';
                document.getElementById('clienteTelefono').textContent = option.dataset.telefono || '-';
                document.getElementById('clienteDocumento').textContent = option.dataset.documento || '-';
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
            
            validarFormulario();
        });
    }
    
    /**
     * Calcular número de noches entre dos fechas
     */
    function calcularNoches() {
        if (!fechaEntrada.value || !fechaSalida.value) {
            console.log('❌ Faltan fechas');
            return 0;
        }
        
        const entrada = new Date(fechaEntrada.value + 'T00:00:00');
        const salida = new Date(fechaSalida.value + 'T00:00:00');
        const diffTime = salida - entrada;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        console.log('📅 Entrada:', fechaEntrada.value);
        console.log('📅 Salida:', fechaSalida.value);
        console.log('🌙 Noches calculadas:', diffDays);
        
        return diffDays > 0 ? diffDays : 0;
    }
    
    /**
     * Obtener precio de la habitación seleccionada
     */
    function obtenerPrecioHabitacion() {
        const habitacionSeleccionada = document.querySelector('.habitacion-radio:checked');
        
        if (!habitacionSeleccionada) {
            console.log('❌ No hay habitación seleccionada');
            return 0;
        }
        
        const precio = parseFloat(habitacionSeleccionada.dataset.precio);
        console.log('💰 Precio habitación:', precio);
        
        return precio;
    }
    
    /**
     * Actualizar todos los precios y totales
     */
    function actualizarPrecios() {
        console.log('🔄 Actualizando precios...');
        
        const noches = calcularNoches();
        const precioNoche = obtenerPrecioHabitacion();
        
        // Actualizar noches
        totalNochesSpan.textContent = noches;
        numeroNochesSpan.textContent = noches;
        
        // Actualizar precio por noche
        precioNocheSpan.textContent = '$' + precioNoche.toFixed(2);
        
        // Calcular y actualizar total
        const total = precioNoche * noches;
        precioTotalSpan.textContent = '$' + total.toFixed(2);
        
        console.log('✅ Precios actualizados:');
        console.log('   - Noches:', noches);
        console.log('   - Precio/noche:', precioNoche);
        console.log('   - Total:', total);
        
        // Validar formulario
        validarFormulario();
    }
    
    /**
     * Validar que todos los campos requeridos estén completos
     */
    function validarFormulario() {
        const clienteOk = clienteSelect ? clienteSelect.value !== '' : false;
        const fechasOk = fechaEntrada.value !== '' && fechaSalida.value !== '' && calcularNoches() > 0;
        const habitacionOk = document.querySelector('.habitacion-radio:checked') !== null;
        
        const formularioValido = clienteOk && fechasOk && habitacionOk;
        
        console.log('✔️ Validación formulario:');
        console.log('   - Cliente:', clienteOk);
        console.log('   - Fechas:', fechasOk);
        console.log('   - Habitación:', habitacionOk);
        console.log('   - Válido:', formularioValido);
        
        btnCrearReserva.disabled = !formularioValido;
    }
    
    /**
     * Event listener: Cambio de fecha de entrada
     */
    fechaEntrada.addEventListener('change', function() {
        console.log('📅 Fecha entrada cambiada:', this.value);
        
        // Actualizar fecha mínima de salida
        const entrada = new Date(this.value);
        const minSalida = new Date(entrada);
        minSalida.setDate(minSalida.getDate() + 1);
        
        const minSalidaStr = minSalida.toISOString().split('T')[0];
        fechaSalida.min = minSalidaStr;
        
        // Si la fecha de salida es menor, actualizarla
        if (new Date(fechaSalida.value) <= entrada) {
            fechaSalida.value = minSalidaStr;
            console.log('📅 Fecha salida ajustada a:', minSalidaStr);
        }
        
        actualizarPrecios();
    });
    
    /**
     * Event listener: Cambio de fecha de salida
     */
    fechaSalida.addEventListener('change', function() {
        console.log('📅 Fecha salida cambiada:', this.value);
        actualizarPrecios();
    });
    
    /**
     * Event listener: Selección de habitación
     */
    habitacionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('🏨 Habitación seleccionada:', this.value, 'Precio:', this.dataset.precio);
            
            // Actualizar estilos visuales
            document.querySelectorAll('.habitacion-card').forEach(card => {
                card.classList.remove('border-primary');
            });
            
            const cardSeleccionada = this.closest('.habitacion-card');
            if (cardSeleccionada) {
                cardSeleccionada.classList.add('border-primary');
            }
            
            actualizarPrecios();
        });
    });
    
    /**
     * Permitir clic en toda la tarjeta para seleccionar
     */
    document.querySelectorAll('.habitacion-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // No hacer nada si se hace clic en el radio button directamente
            if (e.target.classList.contains('habitacion-radio')) {
                return;
            }
            
            const radio = this.querySelector('.habitacion-radio');
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
    });
    
    /**
     * Inicialización al cargar la página
     */
    console.log('🎯 Inicializando valores...');
    
    // Si hay cliente seleccionado, mostrar info
    if (clienteSelect && clienteSelect.value) {
        clienteSelect.dispatchEvent(new Event('change'));
    }
    
    // Calcular y mostrar valores iniciales
    actualizarPrecios();
});

/**
 * Función para buscar habitaciones con nuevas fechas
 */
function buscarHabitaciones() {
    const fechaEntrada = document.getElementById('fecha_entrada').value;
    const fechaSalida = document.getElementById('fecha_salida').value;
    
    if (!fechaEntrada || !fechaSalida) {
        alert('Por favor seleccione las fechas primero');
        return;
    }
    
    const entrada = new Date(fechaEntrada);
    const salida = new Date(fechaSalida);
    
    if (salida <= entrada) {
        alert('La fecha de salida debe ser posterior a la fecha de entrada');
        return;
    }
    
    console.log('🔍 Buscando habitaciones disponibles...');
    window.location.href = '<?= $base ?>/reservas/crear?fecha_entrada=' + fechaEntrada + '&fecha_salida=' + fechaSalida;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>