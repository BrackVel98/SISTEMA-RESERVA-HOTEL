/**
 * Validaciones del lado del cliente
 */

/**
 * Validar email
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validar teléfono (Perú)
 */
function validarTelefono(telefono) {
    const regex = /^9\d{8}$/;
    return regex.test(telefono);
}

/**
 * Validar DNI (Perú)
 */
function validarDNI(dni) {
    const regex = /^\d{8}$/;
    return regex.test(dni);
}

/**
 * Validar RUC (Perú)
 */
function validarRUC(ruc) {
    const regex = /^\d{11}$/;
    return regex.test(ruc);
}

/**
 * Validar contraseña
 */
function validarPassword(password) {
    // Mínimo 6 caracteres
    if (password.length < 6) {
        return {
            valido: false,
            mensaje: 'La contraseña debe tener al menos 6 caracteres'
        };
    }
    
    // Al menos una letra
    if (!/[a-zA-Z]/.test(password)) {
        return {
            valido: false,
            mensaje: 'La contraseña debe contener al menos una letra'
        };
    }
    
    // Al menos un número
    if (!/\d/.test(password)) {
        return {
            valido: false,
            mensaje: 'La contraseña debe contener al menos un número'
        };
    }
    
    return {
        valido: true,
        mensaje: 'Contraseña válida'
    };
}

/**
 * Validar coincidencia de contraseñas
 */
function validarPasswordMatch(password, confirmPassword) {
    return password === confirmPassword;
}

/**
 * Validar fecha
 */
function validarFecha(fecha) {
    const date = new Date(fecha);
    return date instanceof Date && !isNaN(date);
}

/**
 * Validar rango de fechas
 */
function validarRangoFechas(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    return inicio < fin;
}

/**
 * Validar número positivo
 */
function validarNumeroPositivo(numero) {
    return !isNaN(numero) && parseFloat(numero) > 0;
}

/**
 * Validar archivo de imagen
 */
function validarImagen(archivo) {
    const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const tamañoMaximo = 2 * 1024 * 1024; // 2MB
    
    if (!tiposPermitidos.includes(archivo.type)) {
        return {
            valido: false,
            mensaje: 'Solo se permiten imágenes JPG, PNG o GIF'
        };
    }
    
    if (archivo.size > tamañoMaximo) {
        return {
            valido: false,
            mensaje: 'La imagen no debe superar los 2MB'
        };
    }
    
    return {
        valido: true,
        mensaje: 'Imagen válida'
    };
}

/**
 * Validación en tiempo real de formularios
 */
document.addEventListener('DOMContentLoaded', function() {
    // Email
    document.querySelectorAll('input[type="email"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validarEmail(this.value)) {
                this.classList.add('is-invalid');
                mostrarErrorCampo(this, 'Email no válido');
            } else {
                this.classList.remove('is-invalid');
                ocultarErrorCampo(this);
            }
        });
    });
    
    // Teléfono
    document.querySelectorAll('input[name="telefono"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validarTelefono(this.value)) {
                this.classList.add('is-invalid');
                mostrarErrorCampo(this, 'Teléfono no válido (9 dígitos)');
            } else {
                this.classList.remove('is-invalid');
                ocultarErrorCampo(this);
            }
        });
    });
    
    // DNI
    document.querySelectorAll('input[name="documento"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validarDNI(this.value)) {
                this.classList.add('is-invalid');
                mostrarErrorCampo(this, 'DNI no válido (8 dígitos)');
            } else {
                this.classList.remove('is-invalid');
                ocultarErrorCampo(this);
            }
        });
    });
    
    // Contraseña
    const inputPassword = document.getElementById('password');
    const inputPasswordConfirm = document.getElementById('password_confirmation');
    
    if (inputPassword) {
        inputPassword.addEventListener('blur', function() {
            const resultado = validarPassword(this.value);
            if (this.value && !resultado.valido) {
                this.classList.add('is-invalid');
                mostrarErrorCampo(this, resultado.mensaje);
            } else {
                this.classList.remove('is-invalid');
                ocultarErrorCampo(this);
            }
        });
    }
    
    if (inputPasswordConfirm && inputPassword) {
        inputPasswordConfirm.addEventListener('blur', function() {
            if (this.value && !validarPasswordMatch(inputPassword.value, this.value)) {
                this.classList.add('is-invalid');
                mostrarErrorCampo(this, 'Las contraseñas no coinciden');
            } else {
                this.classList.remove('is-invalid');
                ocultarErrorCampo(this);
            }
        });
    }
    
    // Validar imágenes
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                const resultado = validarImagen(this.files[0]);
                if (!resultado.valido) {
                    this.classList.add('is-invalid');
                    mostrarErrorCampo(this, resultado.mensaje);
                    this.value = '';
                } else {
                    this.classList.remove('is-invalid');
                    ocultarErrorCampo(this);
                }
            }
        });
    });
});

/**
 * Mostrar error en campo
 */
function mostrarErrorCampo(campo, mensaje) {
    ocultarErrorCampo(campo);
    
    const divError = document.createElement('div');
    divError.className = 'invalid-feedback';
    divError.textContent = mensaje;
    divError.id = `error-${campo.name}`;
    
    campo.parentNode.appendChild(divError);
}

/**
 * Ocultar error de campo
 */
function ocultarErrorCampo(campo) {
    const errorExistente = document.getElementById(`error-${campo.name}`);
    if (errorExistente) {
        errorExistente.remove();
    }
}

/**
 * Validar formulario completo
 */
function validarFormulario(form) {
    let valido = true;
    
    // Validar campos requeridos
    form.querySelectorAll('[required]').forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            mostrarErrorCampo(campo, 'Este campo es obligatorio');
            valido = false;
        }
    });
    
    return valido;
}

console.log('Módulo de Validaciones cargado');