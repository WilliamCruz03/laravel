// resources/js/validaciones.js

// Esta función se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('Validaciones cargadas');
    
    // 1. Abrir modal de Clientes
    const btnClientes = document.getElementById('btn-abrir-modal-cliente');
    if (btnClientes) {
        btnClientes.addEventListener('click', function() {
            abrirModal('modalClientes');
        });
    }
    
    // 2. Abrir modal de Cotizaciones
    const btnCotizaciones = document.getElementById('btn-abrir-modal-cotizaciones');
    if (btnCotizaciones) {
        btnCotizaciones.addEventListener('click', function() {
            abrirModal('modalCotizaciones');
        });
    }
    
    // 3. Abrir modal de Ofertas
    const btnOfertas = document.getElementById('btn-abrir-modal-ofertas');
    if (btnOfertas) {
        btnOfertas.addEventListener('click', function() {
            abrirModal('modalOfertas');
        });
    }
    
    // 4. Abrir modal de Administrador
    const btnAdmin = document.getElementById('btn-abrir-modal-admin');
    if (btnAdmin) {
        btnAdmin.addEventListener('click', function() {
            abrirModal('modalAdmin');
        });
    }
});

/**
 * Función para abrir un modal
 * @param {string} modalId - El ID del modal a abrir
 */
function abrirModal(modalId) {
    console.log('Abriendo modal:', modalId);
    
    // Obtener el elemento del modal
    const modalElement = document.getElementById(modalId);
    
    // Verificar que el modal existe
    if (!modalElement) {
        console.error('Modal no encontrado:', modalId);
        return;
    }
    
    // Crear instancia de Bootstrap Modal
    const modal = new bootstrap.Modal(modalElement);
    
    // Mostrar el modal
    modal.show();
}

/**
 * Función para cerrar un modal
 * @param {string} modalId - El ID del modal a cerrar
 */
function cerrarModal(modalId) {
    console.log('Cerrando modal:', modalId);
    
    const modalElement = document.getElementById(modalId);
    
    if (!modalElement) {
        console.error('Modal no encontrado:', modalId);
        return;
    }
    
    const modal = bootstrap.Modal.getInstance(modalElement);
    
    if (modal) {
        modal.hide();
    }
}

// Exportar funciones si es necesario
export { abrirModal, cerrarModal };