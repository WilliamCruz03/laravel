<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Editar Pedido #{{ $pedido->id }}</span>
            <a href="{{ route('ventas.pedidos.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title mb-0">Editando pedido</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ventas.pedidos.update', $pedido->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cliente_id" class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                                    <select name="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $pedido->cliente_id) == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fecha" class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $pedido->fecha) }}" required>
                                    @error('fecha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label fw-bold">Estado</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="pendiente" {{ old('estado', $pedido->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="despachado" {{ old('estado', $pedido->estado) == 'despachado' ? 'selected' : '' }}>Despachado</option>
                                        <option value="en_camino" {{ old('estado', $pedido->estado) == 'en_camino' ? 'selected' : '' }}>En Camino</option>
                                        <option value="entregado" {{ old('estado', $pedido->estado) == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                        <option value="retrasado" {{ old('estado', $pedido->estado) == 'retrasado' ? 'selected' : '' }}>Retrasado</option>
                                    </select>
                                </div>
                                </div>
                                    <div class="mb-3">
                                    <label for="motivo_retraso" class="form-label fw-bold">Motivo de retraso (solo si aplica)</label>
                                    <input type="text" class="form-control" id="motivo_retraso" name="motivo_retraso" value="{{ old('motivo_retraso', $pedido->motivo_retraso) }}">
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
                                        @forelse($pedido->detalles as $index => $detalle)
                                        <tr>
                                            <td>
                                                <select name="detalles[{{ $index }}][articulo_id]" class="form-select articulo-select" required>
                                                    <option value="">Seleccionar</option>
                                                    @foreach($articulos as $articulo)
                                                        <option value="{{ $articulo->id }}" data-precio="{{ $articulo->precio }}" {{ $detalle->articulo_id == $articulo->id ? 'selected' : '' }}>{{ $articulo->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="detalles[{{ $index }}][cantidad]" class="form-control cantidad" value="{{ $detalle->cantidad }}" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="detalles[{{ $index }}][precio_unitario]" class="form-control precio" value="{{ $detalle->precio_unitario }}" required>
                                            </td>
                                            <td>
                                                <span class="subtotal fw-bold">{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No hay detalles. Agrega al menos uno.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="agregar-fila" class="btn btn-primary mb-3">Agregar artículo</button>

                            <hr>
                            <div class="text-end">
                                <h4>Total: $<span id="total">{{ $pedido->total ?? '0.00' }}</span></h4>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ventas.pedidos.index') }}" class="btn btn-secondary btn-lg me-2">Cancelar</a>
                                <button type="submit" class="btn btn-warning btn-lg">Actualizar Pedido</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let filaIndex = {{ $pedido->detalles->count() ?? 0 }};
        const articulosData = @json($articulos);

        function recalcularTotales() {
            let total = 0;
            document.querySelectorAll('#tabla-detalles tbody tr').forEach(fila => {
                const cantidad = parseFloat(fila.querySelector('.cantidad')?.value) || 0;
                const precio = parseFloat(fila.querySelector('.precio')?.value) || 0;
                const subtotal = cantidad * precio;
                const spanSubtotal = fila.querySelector('.subtotal');
                if (spanSubtotal) spanSubtotal.textContent = subtotal.toFixed(2);
                total += subtotal;
            });
            document.getElementById('total').textContent = total.toFixed(2);
        }

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

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('articulo-select')) {
                const select = e.target;
                const precio = select.selectedOptions[0]?.getAttribute('data-precio') || 0;
                const fila = select.closest('tr');
                fila.querySelector('.precio').value = precio;
                recalcularTotales();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
                recalcularTotales();
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('eliminar-fila')) {
                e.target.closest('tr').remove();
                recalcularTotales();
            }
        });

        // Inicializar total al cargar
        recalcularTotales();
    </script>
</body>
</html>