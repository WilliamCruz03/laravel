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
                                    <label for="cliente_id" class="form-label fw-bold">Cliente <span class="text-danger">*</span></label>
                                    <select name="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                        <option value="">Seleccionar cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
    </script>
</body>
</html>