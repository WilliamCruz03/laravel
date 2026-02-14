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
    <!-- Los toasts se agregarán aquí dinámicamente -->
    </div>

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
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente->nombre ?? 'N/A' }}</td>
                        <td>{{ $pedido->fecha }}</td>
                        <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <div class="estado-container">
                                    <select class="form-select form-select-sm estado-select" data-id="{{ $pedido->id }}" style="width: auto;">
                                        <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="despachado" {{ $pedido->estado == 'despachado' ? 'selected' : '' }}>Despachado</option>
                                        <option value="en_camino" {{ $pedido->estado == 'en_camino' ? 'selected' : '' }}>En camino</option>
                                        <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                        <option value="retrasado" {{ $pedido->estado == 'retrasado' ? 'selected' : '' }}>Retrasado</option>
                                    </select>
                                    
                                    {{-- Badge que muestra el motivo si el estado es retrasado --}}
                                    @if($pedido->estado == 'retrasado' && $pedido->motivo_retraso)
                                        <span class="badge bg-warning text-dark motivo-badge mt-1" data-id="{{ $pedido->id }}">
                                            Motivo: {{ $pedido->motivo_retraso }}
                                        </span>
                                    @endif
                                    
                                    {{-- Contenedor del input y botón para motivo (siempre existe pero oculto) --}}
                                    <div class="motivo-container mt-1" style="display: none;">
                                        <input type="text" class="form-control form-control-sm motivo-input" placeholder="Motivo del retraso" value="{{ $pedido->motivo_retraso }}">
                                        <button class="btn btn-sm btn-primary mt-1 guardar-motivo" data-id="{{ $pedido->id }}">Guardar motivo</button>
                                    </div>
                                </div>
                            </td>
                        <td class="text-center">
                            <a href="{{ route('ventas.pedidos.edit', $pedido->id) }}" class="btn btn-sm btn-primary">Editar</a>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#eliminarModal"
                                    data-id="{{ $pedido->id }}">
                                Eliminar
                            </button>
                            <a href="https://wa.me/{{ $pedido->cliente->telefono }}?text={{ urlencode('Su pedido #'.$pedido->id.' está '.$pedido->estado.($pedido->motivo_retraso ? ' (Motivo: '.$pedido->motivo_retraso.')' : '')) }}" target="_blank" class="btn btn-sm btn-success">WhatsApp</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="alert alert-info mb-0">
                                No hay pedidos registrados.
                            </div>
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

        // Función principal para actualizar estado vía AJAX
        function actualizarEstado(pedidoId, estado, motivo, fila) {
            // Construir objeto de datos dinámicamente
            let data = { estado: estado };
            // Solo incluir motivo si es diferente de null (para evitar sobrescribir con null)
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

                    if (estado === 'retrasado') {
                        if (motivo) {
                            // Actualizar o crear badge
                            if (badge) {
                                badge.textContent = 'Motivo: ' + motivo;
                            } else {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'badge bg-warning text-dark motivo-badge mt-1';
                                newBadge.setAttribute('data-id', pedidoId);
                                newBadge.textContent = 'Motivo: ' + motivo;
                                estadoContainer.appendChild(newBadge);
                            }
                            // Ocultar input después de guardar
                            motivoContainer.style.display = 'none';
                        } else {
                            // Si no hay motivo, mostrar input para ingresarlo
                            motivoContainer.style.display = 'block';
                            motivoInput.value = '';
                        }
                    } else {
                        // Si el estado no es retrasado, ocultar input y badge (el motivo persiste en BD)
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
                    // Mostrar el input para ingresar motivo
                    motivoContainer.style.display = 'block';
                    // Precargar motivo existente (desde badge o desde el input)
                    const motivoExistente = badge ? badge.textContent.replace('Motivo: ', '') : motivoInput.value;
                    motivoInput.value = motivoExistente || '';
                    // No enviar todavía, esperar a que el usuario guarde
                } else {
                    // Ocultar input si estaba visible
                    motivoContainer.style.display = 'none';
                    // Enviar actualización sin motivo (no se incluye para no sobrescribir)
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