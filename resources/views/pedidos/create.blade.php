<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Pedido</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Crear Nuevo Pedido</span>
            <a href="{{ route('ventas.pedidos.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title mb-0">Registrar Pedido</h3>
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('ventas.pedidos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cliente_busqueda" class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" 
                                            class="form-control @error('cliente_id') is-invalid @enderror" 
                                            id="cliente_busqueda" 
                                            placeholder="Buscar cliente por nombre, email o teléfono..." 
                                            value="{{ old('cliente_nombre') }}"
                                            autocomplete="off">
                                        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                                            + Nuevo
                                        </button>
                                    </div>
                                    <div id="sugerencias-cliente" class="list-group mt-1" style="position: absolute; z-index: 1000; max-width: 400px; display: none;"></div>
                                    @error('cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fecha" class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                    @error('fecha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label fw-bold">Estado</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="despachado" {{ old('estado') == 'despachado' ? 'selected' : '' }}>Despachado</option>
                                        <option value="en_camino" {{ old('estado') == 'en_camino' ? 'selected' : '' }}>En camino</option>
                                        <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                        <option value="retrasado" {{ old('estado') == 'retrasado' ? 'selected' : '' }}>Retrasado</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <h4 class="mb-3">Detalles del pedido</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabla-detalles">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unit.</th>
                                            <th>Subtotal</th>
                                            <th style="width: 80px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="detalles[0][articulo_id]" class="form-select articulo-select" required>
                                                    <option value="">Seleccionar</option>
                                                    @foreach($articulos as $articulo)
                                                        <option value="{{ $articulo->id }}" data-precio="{{ $articulo->precio }}">{{ $articulo->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[0][cantidad]" class="form-control cantidad" value="1" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="detalles[0][precio_unitario]" class="form-control precio" required>
                                            </td>
                                            <td>
                                                <span class="subtotal fw-bold">0.00</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="agregar-fila" class="btn btn-primary mb-3">Agregar artículo</button>

                            <hr>
                            <div class="text-end">
                                <h4>Total: $<span id="total">0.00</span></h4>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ventas.pedidos.index') }}" class="btn btn-secondary btn-lg me-2">Cancelar</a>
                                <button type="submit" class="btn btn-success btn-lg">Guardar Pedido</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let filaIndex = 1;
        const articulosData = @json($articulos); // pasamos los artículos al JS

        document.getElementById('agregar-fila').addEventListener('click', function() {
            const tbody = document.querySelector('#tabla-detalles tbody');
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
                <td>
                    <select name="detalles[${filaIndex}][articulo_id]" class="form-select articulo-select" required>
                        <option value="">Seleccionar</option>
                        ${articulosData.map(a => `<option value="${a.id}" data-precio="${a.precio}">${a.nombre}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="detalles[${filaIndex}][cantidad]" class="form-control cantidad" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="detalles[${filaIndex}][precio_unitario]" class="form-control precio" required>
                </td>
                <td>
                    <span class="subtotal fw-bold">0.00</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                </td>
            `;
            tbody.appendChild(nuevaFila);
            filaIndex++;
        });

        // Evento para cuando se selecciona un artículo, cargar su precio
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('articulo-select')) {
                const select = e.target;
                const precio = select.selectedOptions[0]?.getAttribute('data-precio') || 0;
                const fila = select.closest('tr');
                fila.querySelector('.precio').value = precio;
                calcularSubtotal(fila);
                calcularTotal();
            }
        });

        // Evento para cuando cambia cantidad o precio
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
                const fila = e.target.closest('tr');
                calcularSubtotal(fila);
                calcularTotal();
            }
        });

        // Eliminar fila
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('eliminar-fila')) {
                e.target.closest('tr').remove();
                calcularTotal();
            }
        });

        function calcularSubtotal(fila) {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(fila.querySelector('.precio').value) || 0;
            const subtotal = cantidad * precio;
            fila.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        }

        function calcularTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(span => {
                total += parseFloat(span.textContent) || 0;
            });
            document.getElementById('total').textContent = total.toFixed(2);
        }


            function mostrarToast(mensaje, tipo = 'success') {
                const container = document.querySelector('.toast-container');
                if (!container) return;

                const toastId = 'toast-' + Date.now();
                const bgClass = tipo === 'success' ? 'bg-success text-white' : 'bg-danger text-white';
                
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                        <div class="d-flex">
                            <div class="toast-body">${mensaje}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', toastHtml);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
            }
    </script>

        <!-- Modal Nuevo Cliente -->
        <div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNuevoClienteLabel">Registrar Nuevo Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formNuevoCliente">
                            @csrf
                            <div class="mb-3">
                                <label for="modal_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="modal_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="modal_email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="modal_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="modal_telefono" name="telefono">
                            </div>
                            <div class="mb-3">
                                <label for="modal_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="modal_direccion" name="direccion">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="guardarClienteModal">Guardar Cliente</button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Función para mostrar toasts
        function mostrarToast(mensaje, tipo = 'success') {
            const container = document.querySelector('.toast-container');
            if (!container) return;
            const toastId = 'toast-' + Date.now();
            const bgClass = tipo === 'success' ? 'bg-success text-white' : 'bg-danger text-white';
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                    <div class="d-flex">
                        <div class="toast-body">${mensaje}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
        }

        // Elementos del modal
        const modalNuevoCliente = document.getElementById('modalNuevoCliente');
        const btnGuardarCliente = document.getElementById('guardarClienteModal');
        const formNuevoCliente = document.getElementById('formNuevoCliente');
        const selectCliente = document.getElementById('cliente_id'); // Asegurar que existe

        // Función para limpiar backdrop y cerrar modal
        function limpiarBackdrop() {
            // Quitar todos los backdrops que hayan quedado
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            // Quitar clase que bloquea el scroll
            document.body.classList.remove('modal-open');
            // Restaurar estilos del body
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        // Función para cerrar modal y limpiar
        function cerrarModal() {
            if (modalNuevoCliente) {
                const modal = bootstrap.Modal.getInstance(modalNuevoCliente);
                if (modal) {
                    modal.hide();
                } else {
                    // Si no hay instancia, forzar cierre manual
                    modalNuevoCliente.classList.remove('show');
                    modalNuevoCliente.style.display = 'none';
                }
            }
            limpiarBackdrop();
        }

        if (btnGuardarCliente && modalNuevoCliente && formNuevoCliente && selectCliente) {
            btnGuardarCliente.addEventListener('click', function() {
                const formData = new FormData(formNuevoCliente);
                
                // Deshabilitar botón
                btnGuardarCliente.disabled = true;
                btnGuardarCliente.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...';

                fetch('{{ route("ventas.clientes.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        // Agregar nuevo cliente al input y seleccionarlo
                        inputBusqueda.value = data.cliente.nombre;
                        hiddenClienteId.value = data.cliente.id;

                        // Limpiar formulario del modal
                        formNuevoCliente.reset();

                        // Mostrar mensaje de éxito
                        mostrarToast(data.message, 'success');
                    } else {
                        // Mostrar errores de validación
                        let errores = '';
                        if (data.errors) {
                            for (let campo in data.errors) {
                                errores += data.errors[campo].join('\n') + '\n';
                            }
                        } else {
                            errores = 'Error desconocido';
                        }
                        mostrarToast('Errores:\n' + errores, 'danger');
                    }
                    // Cerrar modal pase lo que pase
                    cerrarModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    let mensaje = 'Error de conexión';
                    if (error.message) {
                        mensaje = error.message;
                    } else if (error.errors) {
                        mensaje = Object.values(error.errors).flat().join('\n');
                    } else if (typeof error === 'string') {
                        mensaje = error;
                    }
                    mostrarToast('Error: ' + mensaje, 'danger');
                    cerrarModal();
                })
                .finally(() => {
                    // Restaurar botón
                    btnGuardarCliente.disabled = false;
                    btnGuardarCliente.innerHTML = 'Guardar Cliente';
                });
            });

            // Al cerrar el modal por cualquier motivo, limpiar backdrop
            modalNuevoCliente.addEventListener('hidden.bs.modal', function () {
                formNuevoCliente.reset();
                limpiarBackdrop();
            });
        } else {
            console.error('Error: No se encontraron elementos del modal');
        }

        // Atajo de teclado
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.key === 'n') {
                e.preventDefault();
                const modal = new bootstrap.Modal(modalNuevoCliente);
                modal.show();
            }
        });

        // ----- BÚSQUEDA DE CLIENTES -----
        const inputBusqueda = document.getElementById('cliente_busqueda');
        const hiddenClienteId = document.getElementById('cliente_id');
        const sugerenciasContainer = document.getElementById('sugerencias-cliente');
        let timeoutId;

        inputBusqueda.addEventListener('input', function() {
            const termino = this.value.trim();
            
            if (termino === '') {
                hiddenClienteId.value = '';
                sugerenciasContainer.style.display = 'none';
                return;
            }

            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                fetch(`/ventas/clientes/buscar?q=${encodeURIComponent(termino)}`)
                    .then(response => response.json())
                    .then(data => {
                        sugerenciasContainer.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(cliente => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action';
                                item.innerHTML = `<strong>${cliente.nombre}</strong><br><small>${cliente.email} | ${cliente.telefono || 'Sin teléfono'}</small>`;
                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    inputBusqueda.value = cliente.nombre;
                                    hiddenClienteId.value = cliente.id;
                                    sugerenciasContainer.style.display = 'none';
                                });
                                sugerenciasContainer.appendChild(item);
                            });
                            sugerenciasContainer.style.display = 'block';
                        } else {
                            sugerenciasContainer.style.display = 'none';
                        }
                    });
            }, 300);
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!inputBusqueda.contains(e.target) && !sugerenciasContainer.contains(e.target)) {
                sugerenciasContainer.style.display = 'none';
            }
        });

        // Si hay un valor antiguo (por ejemplo, después de un error de validación), mostrarlo
        if (hiddenClienteId.value) {
            fetch(`/ventas/clientes/buscar?q=${hiddenClienteId.value}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        inputBusqueda.value = data[0].nombre;
                    }
                })
                }
        });
        </script>

        <script>
        // Atajos de Teclado
        document.addEventListener('keydown', function(e) {
            // Detectar si el modal de nuevo cliente está abierto
            const modalNuevoCliente = document.getElementById('modalNuevoCliente');
            const modalAbierto = modalNuevoCliente?.classList.contains('show');

            // Guardar con Ctrl+Enter (solo si no hay modal abierto)
            if (e.ctrlKey && e.key === 'Enter' && !modalAbierto) {
                e.preventDefault();
                document.querySelector('form').submit();
            }

            // Cancelar con Escape: si el modal está abierto, no hacer nada (Bootstrap lo maneja)
            if (e.key === 'Escape' && !modalAbierto) {
                e.preventDefault();
                window.location.href = '{{ route("ventas.pedidos.index") }}';
            }

            // Agregar fila: Ctrl+Alt++ (Ctrl+Alt+=)
            if (e.ctrlKey && e.altKey && (e.key === '+' || e.key === '=')) {
                e.preventDefault();
                document.getElementById('agregar-fila').click();
            }

            // Alternativa: Ctrl+Insert para agregar fila
            if (e.ctrlKey && e.key === 'Insert') {
                e.preventDefault();
                document.getElementById('agregar-fila').click();
            }

            // Eliminar fila actual: Ctrl+Delete
            if (e.ctrlKey && e.key === 'Delete') {
                e.preventDefault();
                const activeElement = document.activeElement;
                const fila = activeElement?.closest('tr');
                if (fila) {
                    const btnEliminar = fila.querySelector('.eliminar-fila');
                    if (btnEliminar) {
                        btnEliminar.click();
                    }
                }
            }

        });
</script>

</body>
</html>