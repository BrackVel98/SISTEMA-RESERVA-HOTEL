/**
 * Funciones JavaScript para el módulo de Reservas
 */

// Variables globales
let precioNoche = 0;
let fechaEntrada = null;
let fechaSalida = null;

/**
 * Inicialización
 */
document.addEventListener('DOMContentLoaded', function() {
    initReservaForm();
    initBusquedaHabitaciones();
    initPagoForm();
});

/**
 * Inicializar formulario de reserva
 */
function initReservaForm() {
    const formReserva = document.getElementById('formReserva');
    if (!formReserva) return;
    
    const inputFechaEntrada = document.getElementById('fecha_entrada');
    const inputFechaSalida = document.getElementById('fecha_salida');
    const inputHabitacionId = document.querySelector('input[name="habitacion_id"]');
    
    // Obtener precio de la habitación
    if (inputHabitacionId) {
        const precioElement = document.querySelector('[data-precio-noche]');
        if (precioElement) {
            precioNoche = parseFloat(precioElement.dataset.precioNoche);
        }
    }
    
    // Event listeners para fechas
    if (inputFechaEntrada) {
        inputFechaEntrada.addEventListener('change', function() {
            validarFechaEntrada(this);
            calcularTotalReserva();
        });
    }
    
    if (inputFechaSalida) {
        inputFechaSalida.addEventListener('change', function() {
            validarFechaSalida(this);
            calcularTotalReserva();
        });
    }
    
    // Validación de número de huéspedes
    const inputHuespedes = document.getElementById('num_huespedes');
    if (inputHuespedes) {
        inputHuespedes.addEventListener('change', function() {
            validarNumeroHuespedes(this);
        });
    }
}

/**
 * Validar fecha de entrada
 */
function validarFechaEntrada(input) {
    const fechaSeleccionada = new Date(input.value);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    
    if (fechaSeleccionada < hoy) {
        mostrarError('La fecha de entrada no puede ser anterior a hoy');
        input.value = hoy.toISOString().split('T')[0];
        return false;
    }
    
    // Actualizar fecha mínima de salida
    const inputFechaSalida = document.getElementById('fecha_salida');
    if (inputFechaSalida) {
        const minFechaSalida = new Date(fechaSeleccionada);
        minFechaSalida.setDate(minFechaSalida.getDate() + 1);
        inputFechaSalida.min = minFechaSalida.toISOString().split('T')[0];
        
        // Si la fecha de salida es menor o igual a la de entrada, ajustarla
        if (inputFechaSalida.value && new Date(inputFechaSalida.value) <= fechaSeleccionada) {
            inputFechaSalida.value = minFechaSalida.toISOString().split('T')[0];
        }
    }
    
    fechaEntrada = fechaSeleccionada;
    return true;
}

/**
 * Validar fecha de salida
 */
function validarFechaSalida(input) {
    const inputFechaEntrada = document.getElementById('fecha_entrada');
    if (!inputFechaEntrada || !inputFechaEntrada.value) {
        mostrarError('Primero selecciona la fecha de entrada');
        input.value = '';
        return false;
    }
    
    const fechaEntradaVal = new Date(inputFechaEntrada.value);
    const fechaSalidaVal = new Date(input.value);
    
    if (fechaSalidaVal <= fechaEntradaVal) {
        mostrarError('La fecha de salida debe ser posterior a la fecha de entrada');
        const minFechaSalida = new Date(fechaEntradaVal);
        minFechaSalida.setDate(minFechaSalida.getDate() + 1);
        input.value = minFechaSalida.toISOString().split('T')[0];
        return false;
    }
    
    fechaSalida = fechaSalidaVal;
    return true;
}

/**
 * Calcular total de la reserva
 */
function calcularTotalReserva() {
    const inputFechaEntrada = document.getElementById('fecha_entrada');
    const inputFechaSalida = document.getElementById('fecha_salida');
    const elementoNoches = document.getElementById('totalNoches');
    const elementoPrecio = document.getElementById('totalPrecio');
    
    if (!inputFechaEntrada || !inputFechaSalida || !inputFechaEntrada.value || !inputFechaSalida.value) {
        return;
    }
    
    const entrada = new Date(inputFechaEntrada.value);
    const salida = new Date(inputFechaSalida.value);
    
    // Calcular noches
    const diffTime = Math.abs(salida - entrada);
    const noches = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (noches <= 0) {
        return;
    }
    
    // Calcular precio total
    const total = noches * precioNoche;
    
    // Actualizar elementos
    if (elementoNoches) {
        elementoNoches.textContent = noches;
    }
    
    if (elementoPrecio) {
        elementoPrecio.textContent = formatearMoneda(total);
    }
    
    // Actualizar campo oculto si existe
    const inputTotal = document.querySelector('input[name="precio_total"]');
    if (inputTotal) {
        inputTotal.value = total.toFixed(2);
    }
}

/**
 * Validar número de huéspedes
 */
function validarNumeroHuespedes(input) {
    const max = parseInt(input.max);
    const valor = parseInt(input.value);
    
    if (valor > max) {
        mostrarError(`El número máximo de huéspedes es ${max}`);
        input.value = max;
        return false;
    }
    
    if (valor < 1) {
        input.value = 1;
        return false;
    }
    
    return true;
}

/**
 * Inicializar búsqueda de habitaciones
 */
function initBusquedaHabitaciones() {
    const formBusqueda = document.getElementById('formBusqueda');
    if (!formBusqueda) return;
    
    const inputFechaEntrada = document.getElementById('fecha_entrada');
    const inputFechaSalida = document.getElementById('fecha_salida');
    
    if (inputFechaEntrada) {
        inputFechaEntrada.addEventListener('change', function() {
            validarFechaEntrada(this);
        });
    }
    
    if (inputFechaSalida) {
        inputFechaSalida.addEventListener('change', function() {
            validarFechaSalida(this);
        });
    }
    
    // Validación antes de enviar
    formBusqueda.addEventListener('submit', function(e) {
        if (!inputFechaEntrada.value || !inputFechaSalida.value) {
            e.preventDefault();
            mostrarError('Debes seleccionar ambas fechas');
            return false;
        }
        
        const entrada = new Date(inputFechaEntrada.value);
        const salida = new Date(inputFechaSalida.value);
        
        if (salida <= entrada) {
            e.preventDefault();
            mostrarError('La fecha de salida debe ser posterior a la de entrada');
            return false;
        }
    });
}

/**
 * Inicializar formulario de pago
 */
function initPagoForm() {
    const formPago = document.querySelector('form[action*="/pago"]');
    if (!formPago) return;
    
    const inputMonto = formPago.querySelector('input[name="monto"]');
    const inputMetodo = formPago.querySelector('select[name="metodo_pago"]');
    const inputReferencia = formPago.querySelector('input[name="referencia"]');
    
    // Validar monto
    if (inputMonto) {
        inputMonto.addEventListener('input', function() {
            const max = parseFloat(this.max);
            const valor = parseFloat(this.value);
            
            if (valor > max) {
                this.value = max.toFixed(2);
                mostrarAdvertencia('El monto no puede ser mayor al saldo pendiente');
            }
            
            if (valor < 0) {
                this.value = 0;
            }
        });
    }
    
    // Mostrar/ocultar campo de referencia según método
    if (inputMetodo && inputReferencia) {
        inputMetodo.addEventListener('change', function() {
            if (this.value === 'efectivo') {
                inputReferencia.required = false;
                inputReferencia.parentElement.style.display = 'none';
            } else {
                inputReferencia.required = true;
                inputReferencia.parentElement.style.display = 'block';
            }
        });
        
        // Trigger inicial
        inputMetodo.dispatchEvent(new Event('change'));
    }
    
    // Validación antes de enviar
    formPago.addEventListener('submit', function(e) {
        const monto = parseFloat(inputMonto.value);
        
        if (monto <= 0) {
            e.preventDefault();
            mostrarError('El monto debe ser mayor a cero');
            return false;
        }
        
        if (!inputMetodo.value) {
            e.preventDefault();
            mostrarError('Selecciona un método de pago');
            return false;
        }
        
        if (inputMetodo.value !== 'efectivo' && !inputReferencia.value) {
            e.preventDefault();
            mostrarError('Ingresa el número de referencia');
            return false;
        }
        
        return confirm('¿Confirmas el registro de este pago?');
    });
}

/**
 * Confirmar acción de reserva
 */
function confirmarAccionReserva(accion, mensaje) {
    return confirm(mensaje || `¿Estás seguro de ${accion} esta reserva?`);
}

/**
 * Cancelar reserva
 */
function cancelarReserva(reservaId) {
    if (!confirmarAccionReserva('cancelar', '¿Estás seguro de cancelar esta reserva?')) {
        return false;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/reservas/${reservaId}/cancelar`;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'csrf_token';
        input.value = csrfToken.content;
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}

/**
 * Realizar check-in
 */
function realizarCheckIn(reservaId) {
    if (!confirmarAccionReserva('realizar el check-in de')) {
        return false;
    }
    
    window.location.href = `/reservas/${reservaId}/checkin`;
}

/**
 * Realizar check-out
 */
function realizarCheckOut(reservaId) {
    if (!confirmarAccionReserva('realizar el check-out de')) {
        return false;
    }
    
    window.location.href = `/reservas/${reservaId}/checkout`;
}

/**
 * Imprimir comprobante de reserva
 */
function imprimirReserva() {
    window.print();
}

/**
 * Exportar reserva a PDF (requiere backend)
 */
function exportarReservaPDF(reservaId) {
    window.location.href = `/reservas/${reservaId}/pdf`;
}

/**
 * Filtrar reservas por estado
 */
function filtrarPorEstado(estado) {
    const url = new URL(window.location.href);
    url.searchParams.set('estado', estado);
    window.location.href = url.toString();
}

/**
 * Limpiar filtros de búsqueda
 */
function limpiarFiltros() {
    const form = document.getElementById('formBusqueda');
    if (form) {
        form.reset();
        // Remover parámetros de URL
        window.location.href = window.location.pathname;
    }
}

/**
 * Formatear moneda
 */
function formatearMoneda(numero) {
    return 'S/ ' + parseFloat(numero).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

/**
 * Mostrar mensaje de error
 */
function mostrarError(mensaje) {
    mostrarMensaje(mensaje, 'danger');
}

/**
 * Mostrar mensaje de advertencia
 */
function mostrarAdvertencia(mensaje) {
    mostrarMensaje(mensaje, 'warning');
}

/**
 * Mostrar mensaje de éxito
 */
function mostrarExito(mensaje) {
    mostrarMensaje(mensaje, 'success');
}

/**
 * Mostrar mensaje
 */
function mostrarMensaje(mensaje, tipo) {
    // Buscar contenedor de alertas
    let contenedor = document.querySelector('.alert-container');
    
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.className = 'alert-container';
        contenedor.style.position = 'fixed';
        contenedor.style.top = '20px';
        contenedor.style.right = '20px';
        contenedor.style.zIndex = '9999';
        contenedor.style.maxWidth = '400px';
        document.body.appendChild(contenedor);
    }
    
    // Crear alerta
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.role = 'alert';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    contenedor.appendChild(alerta);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
        bsAlert.close();
    }, 5000);
}

/**
 * Calcular duración de estadía
 */
function calcularDuracionEstadia(fechaEntrada, fechaSalida) {
    const entrada = new Date(fechaEntrada);
    const salida = new Date(fechaSalida);
    const diffTime = Math.abs(salida - entrada);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

/**
 * Validar disponibilidad en tiempo real
 */
async function validarDisponibilidad(habitacionId, fechaEntrada, fechaSalida) {
    try {
        const response = await fetch('/api/habitaciones/disponibilidad', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                habitacion_id: habitacionId,
                fecha_entrada: fechaEntrada,
                fecha_salida: fechaSalida
            })
        });
        
        const data = await response.json();
        return data.disponible;
    } catch (error) {
        console.error('Error al validar disponibilidad:', error);
        return false;
    }
}

/**
 * Actualizar estado de reserva en tiempo real
 */
function actualizarEstadoReserva(reservaId) {
    fetch(`/api/reservas/${reservaId}/estado`)
        .then(response => response.json())
        .then(data => {
            const badgeEstado = document.querySelector(`#estado-${reservaId}`);
            if (badgeEstado) {
                badgeEstado.className = `badge bg-${obtenerColorEstado(data.estado)}`;
                badgeEstado.textContent = data.estado.toUpperCase();
            }
        })
        .catch(error => console.error('Error al actualizar estado:', error));
}

/**
 * Obtener color según estado
 */
function obtenerColorEstado(estado) {
    const colores = {
        'pendiente': 'warning',
        'confirmada': 'info',
        'cancelada': 'danger',
        'completada': 'success'
    };
    return colores[estado] || 'secondary';
}

/**
 * Validar formulario de reserva antes de enviar
 */
function validarFormularioReserva(form) {
    const clienteId = form.querySelector('[name="cliente_id"]').value;
    const habitacionId = form.querySelector('[name="habitacion_id"]').value;
    const fechaEntrada = form.querySelector('[name="fecha_entrada"]').value;
    const fechaSalida = form.querySelector('[name="fecha_salida"]').value;
    const numHuespedes = form.querySelector('[name="num_huespedes"]').value;
    
    if (!clienteId) {
        mostrarError('Debes seleccionar un cliente');
        return false;
    }
    
    if (!habitacionId) {
        mostrarError('Debes seleccionar una habitación');
        return false;
    }
    
    if (!fechaEntrada || !fechaSalida) {
        mostrarError('Debes seleccionar las fechas de estadía');
        return false;
    }
    
    if (!numHuespedes || numHuespedes < 1) {
        mostrarError('Debes indicar el número de huéspedes');
        return false;
    }
    
    return true;
}

// Log de inicialización
console.log('Módulo de Reservas cargado correctamente');