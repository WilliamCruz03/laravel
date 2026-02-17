<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <!-- Los toasts se insertarán aquí dinámicamente -->
    </div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <script>
        setTimeout(() => {
            let alert = document.getElementById('success-alert');
            if (alert) new bootstrap.Alert(alert).close();
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Módulo de Pedidos</span>
            <div>
                <a href="{{ route('ventas.pedidos.create') }}" class="btn btn-success me-2">Nuevo Pedido</a>
                <a href="{{ route('ventas.dashboard') }}" class="btn btn-light">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <script>
                setTimeout(function() {
                    let alert = document.getElementById('success-alert');
                    if (alert) {
                        let bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 3000);
            </script>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h1 class="mb-4">Listado de Pedidos</h1>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        @php
                            $estadosNoEliminables = ['pendiente', 'entregado'];
                            $estadosInmutables = ['entregado', 'cancelado'];
                            $selectDisabled = in_array($pedido->estado, $estadosInmutables);
                            $noEliminable = in_array($pedido->estado, $estadosNoEliminables);
                            $editDisabled = in_array($pedido->estado, ['entregado', 'cancelado']);
                        @endphp
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td>{{ $pedido->cliente->nombre ?? 'N/A' }}</td>
                                <td>{{ $pedido->fecha }}</td>
                                <td>${{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <div class="estado-container">
                                        <select class="form-select form-select-sm estado-select" data-id="{{ $pedido->id }}" style="width: auto;" {{ $selectDisabled ? 'disabled' : '' }}>
                                            <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="despachado" {{ $pedido->estado == 'despachado' ? 'selected' : '' }}>Despachado</option>
                                            <option value="en_camino" {{ $pedido->estado == 'en_camino' ? 'selected' : '' }}>En camino</option>
                                            <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                            <option value="retrasado" {{ $pedido->estado == 'retrasado' ? 'selected' : '' }}>Retrasado</option>
                                            <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        </select>

                                        @if($pedido->estado == 'retrasado' && $pedido->motivo_retraso)
                                            <span class="badge bg-warning text-dark motivo-badge mt-1" data-id="{{ $pedido->id }}">
                                                Motivo: {{ $pedido->motivo_retraso }}
                                            </span>
                                        @endif

                                        <div class="motivo-container mt-1" style="display: none;">
                                            <input type="text" class="form-control form-control-sm motivo-input" placeholder="Motivo del retraso" value="{{ $pedido->motivo_retraso }}">
                                            <button class="btn btn-sm btn-primary mt-1 guardar-motivo" data-id="{{ $pedido->id }}">Guardar motivo</button>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ $editDisabled ? '#' : route('ventas.pedidos.edit', $pedido->id) }}"
                                    class="btn btn-sm editar-btn {{ $editDisabled ? 'btn-secondary disabled' : 'btn-primary' }}"
                                    data-href="{{ route('ventas.pedidos.edit', $pedido->id) }}"
                                    {{ $editDisabled ? 'aria-disabled="true"' : '' }}
                                    title="{{ $editDisabled ? 'No se puede editar un pedido ' . $pedido->estado : 'Editar pedido' }}">
                                        Editar
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm eliminar-btn {{ $noEliminable ? 'btn-secondary' : 'btn-danger' }}"
                                            {{ $noEliminable ? 'disabled' : '' }}
                                            data-bs-toggle="modal"
                                            data-bs-target="#eliminarModal"
                                            data-id="{{ $pedido->id }}"
                                            title="{{ $noEliminable ? 'No se puede eliminar un pedido en estado ' . $pedido->estado : 'Eliminar pedido' }}">
                                        Eliminar
                                    </button>

                                    <a href="https://wa.me/{{ $pedido->cliente->telefono }}?text={{ urlencode('Su pedido #'.$pedido->id.' está '.$pedido->estado.($pedido->motivo_retraso ? ' (Motivo: '.$pedido->motivo_retraso.')' : '')) }}" target="_blank" class="btn btn-sm btn-success">WhatsApp</a>
                                </td>
                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info mb-0">No hay pedidos registrados.</div>
                                        </td>
                                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este pedido?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Función para mostrar toasts
    function mostrarToast(mensaje, tipo = 'success') {
        const container = document.querySelector('.toast-container');
        if (!container) return;

        const toastId = 'toast-' + Date.now();
        const bgClass = tipo === 'success' ? 'bg-success text-white' : 'bg-danger text-white';
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        ${mensaje}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Modal de eliminación
        const eliminarModal = document.getElementById('eliminarModal');
        if (eliminarModal) {
            eliminarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const pedidoId = button.getAttribute('data-id');
                const form = document.getElementById('formEliminar');
                form.action = '{{ url("ventas/pedidos") }}/' + pedidoId;
            });
        }

        // Función para actualizar los botones según el estado
        function actualizarBotones(fila, estado) {
            // ----- SELECT de estado -----
            const select = fila.querySelector('.estado-select');
            const estadosInmutables = ['entregado', 'cancelado'];
            if (estadosInmutables.includes(estado)) {
                select.disabled = true;
            } else {
                select.disabled = false;
            }

            // ----- Botón Eliminar -----
            const btnEliminar = fila.querySelector('.eliminar-btn');
            const estadosNoEliminables = ['pendiente', 'entregado'];
            const noEliminable = estadosNoEliminables.includes(estado);
            
            if (btnEliminar) {
                if (noEliminable) {
                    btnEliminar.classList.remove('btn-danger');
                    btnEliminar.classList.add('btn-secondary');
                    btnEliminar.disabled = true;
                    btnEliminar.title = 'No se puede eliminar un pedido en estado ' + estado;
                } else {
                    btnEliminar.classList.remove('btn-secondary');
                    btnEliminar.classList.add('btn-danger');
                    btnEliminar.disabled = false;
                    btnEliminar.title = 'Eliminar pedido';
                }
            }

            // ----- Botón Editar -----
            const btnEditar = fila.querySelector('.editar-btn');
            const editDisabled = ['entregado', 'cancelado'].includes(estado);
            
            if (btnEditar) {
                const hrefOriginal = btnEditar.dataset.href;
                if (editDisabled) {
                    btnEditar.classList.remove('btn-primary');
                    btnEditar.classList.add('btn-secondary', 'disabled');
                    btnEditar.href = '#';
                    btnEditar.setAttribute('aria-disabled', 'true');
                    btnEditar.title = 'No se puede editar un pedido ' + estado;
                } else {
                    btnEditar.classList.remove('btn-secondary', 'disabled');
                    btnEditar.classList.add('btn-primary');
                    btnEditar.href = hrefOriginal;
                    btnEditar.removeAttribute('aria-disabled');
                    btnEditar.title = 'Editar pedido';
                }
            }
        }

        // Función principal para actualizar estado vía AJAX
        function actualizarEstado(pedidoId, estado, motivo, fila) {
            let data = { estado: estado };
            if (motivo !== null) {
                data.motivo_retraso = motivo;
            }

            fetch(`/ventas/pedidos/${pedidoId}/estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    // Actualizar la interfaz según el nuevo estado
                    const estadoContainer = fila.querySelector('.estado-container');
                    const motivoContainer = estadoContainer.querySelector('.motivo-container');
                    const motivoInput = motivoContainer.querySelector('.motivo-input');
                    const badge = estadoContainer.querySelector('.motivo-badge');

                    // Actualizar botones según el nuevo estado
                    actualizarBotones(fila, estado);

                    if (estado === 'retrasado') {
                        if (motivo) {
                            if (badge) {
                                badge.textContent = 'Motivo: ' + motivo;
                            } else {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'badge bg-warning text-dark motivo-badge mt-1';
                                newBadge.setAttribute('data-id', pedidoId);
                                newBadge.textContent = 'Motivo: ' + motivo;
                                estadoContainer.appendChild(newBadge);
                            }
                            motivoContainer.style.display = 'none';
                        } else {
                            motivoContainer.style.display = 'block';
                            motivoInput.value = '';
                        }
                    } else {
                        motivoContainer.style.display = 'none';
                        if (badge) {
                            badge.remove();
                        }
                    }

                    mostrarToast('Estado actualizado correctamente', 'success');
                } else {
                    mostrarToast('Error: ' + responseData.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarToast('Error de conexión', 'danger');
            });
        }

        // Evento change en los selects de estado
        document.querySelectorAll('.estado-select').forEach(select => {
            select.addEventListener('change', function() {
                const pedidoId = this.dataset.id;
                const nuevoEstado = this.value;
                const fila = this.closest('tr');
                const estadoContainer = fila.querySelector('.estado-container');
                const motivoContainer = estadoContainer.querySelector('.motivo-container');
                const motivoInput = motivoContainer.querySelector('.motivo-input');
                const badge = estadoContainer.querySelector('.motivo-badge');

                if (nuevoEstado === 'retrasado') {
                    motivoContainer.style.display = 'block';
                    const motivoExistente = badge ? badge.textContent.replace('Motivo: ', '') : motivoInput.value;
                    motivoInput.value = motivoExistente || '';
                    // No enviar todavía, esperar a que el usuario guarde
                } else {
                    motivoContainer.style.display = 'none';
                    actualizarEstado(pedidoId, nuevoEstado, null, fila);
                }
            });
        });

        // Evento click en botón "Guardar motivo"
        document.querySelectorAll('.guardar-motivo').forEach(btn => {
            btn.addEventListener('click', function() {
                const fila = this.closest('tr');
                const pedidoId = this.dataset.id;
                const motivoInput = fila.querySelector('.motivo-input');
                const motivo = motivoInput.value.trim();

                if (!motivo) {
                    mostrarToast('Debe ingresar un motivo', 'warning');
                    return;
                }

                actualizarEstado(pedidoId, 'retrasado', motivo, fila);
            });
        });

        // Precargar motivo en input si el estado es retrasado (al cargar la página)
        document.querySelectorAll('.estado-select').forEach(select => {
            if (select.value === 'retrasado') {
                const fila = select.closest('tr');
                const motivoInput = fila.querySelector('.motivo-input');
                const badge = fila.querySelector('.motivo-badge');
                if (badge) {
                    motivoInput.value = badge.textContent.replace('Motivo: ', '');
                }
                // El input permanece oculto inicialmente
            }
        });
    });
</script>

    <script>
        //Atajos de Teclado
        //Crear nuevo pedido
        document.addEventListener('keydown', function(e) {
            // Ctrl + N para nuevo pedido
            if (e.ctrlKey && e.altKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route("ventas.pedidos.create") }}';
            }
        });
    </script>
</body>
</html>