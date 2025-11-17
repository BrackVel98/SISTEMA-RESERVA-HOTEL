// public/js/main.js

/**
 * Funciones JavaScript globales
 */

// Confirmar eliminación
function confirmarEliminacion(mensaje = '¿Estás seguro de eliminar este elemento?') {
    return confirm(mensaje);
}

// Formatear moneda
function formatearMoneda(numero) {
    return 'S/ ' + parseFloat(numero).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Auto-ocultar alertas
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Tooltip Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Validación de formularios
(function() {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
})();

// Confirmar antes de salir si hay cambios sin guardar
let formModificado = false;

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('change', function() {
            formModificado = true;
        });
        
        form.addEventListener('submit', function() {
            formModificado = false;
        });
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formModificado) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Previsualización de imágenes
function previsualizarImagen(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Búsqueda en tiempo real
function busquedaEnTiempoReal(inputId, tablaId) {
    document.getElementById(inputId).addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        const tabla = document.getElementById(tablaId);
        const filas = tabla.getElementsByTagName('tr');
        
        for (let i = 1; i < filas.length; i++) {
            const fila = filas[i];
            const texto = fila.textContent || fila.innerText;
            
            if (texto.toLowerCase().indexOf(filtro) > -1) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        }
    });
}

// Copiar al portapapeles
function copiarAlPortapapeles(texto) {
    navigator.clipboard.writeText(texto).then(function() {
        alert('Copiado al portapapeles');
    }).catch(function() {
        alert('Error al copiar');
    });
}

// Imprimir
function imprimirPagina() {
    window.print();
}

// Exportar tabla a CSV
function exportarTablaCSV(tablaId, nombreArchivo = 'datos.csv') {
    const tabla = document.getElementById(tablaId);
    let csv = [];
    const filas = tabla.querySelectorAll('tr');
    
    for (let i = 0; i < filas.length; i++) {
        const fila = [];
        const columnas = filas[i].querySelectorAll('td, th');
        
        for (let j = 0; j < columnas.length; j++) {
            fila.push(columnas[j].innerText);
        }
        
        csv.push(fila.join(','));
    }
    
    descargarCSV(csv.join('\n'), nombreArchivo);
}

function descargarCSV(csv, nombreArchivo) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = nombreArchivo;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

console.log('Sistema de Reservas de Hotel - Cargado correctamente');