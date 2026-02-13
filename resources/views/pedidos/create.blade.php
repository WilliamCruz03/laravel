<form action="{{ route('ventas.pedidos.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <label>Cliente</label>
            <select name="cliente_id" class="form-control" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="col-md-4">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="pendiente">Pendiente</option>
                <option value="pagado">Pagado</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
    </div>

    <hr>
    <h4>Detalles del pedido</h4>
    <table class="table" id="tabla-detalles">
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="detalles[0][articulo_id]" class="form-control articulo-select" required>
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
                    <span class="subtotal">0.00</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
    <button type="button" id="agregar-fila" class="btn btn-primary">Agregar artículo</button>

    <hr>
    <div class="text-end">
        <h4>Total: $<span id="total">0.00</span></h4>
    </div>

    <button type="submit" class="btn btn-success">Guardar Pedido</button>
</form>

<script>
    let filaIndex = 1;

    document.getElementById('agregar-fila').addEventListener('click', function() {
        const tbody = document.querySelector('#tabla-detalles tbody');
        const nuevaFila = document.createElement('tr');
        nuevaFila.innerHTML = `
            <td>
                <select name="detalles[${filaIndex}][articulo_id]" class="form-control articulo-select" required>
                    <option value="">Seleccionar</option>
                    @foreach($articulos as $articulo)
                        <option value="{{ $articulo->id }}" data-precio="{{ $articulo->precio }}">{{ $articulo->nombre }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="detalles[${filaIndex}][cantidad]" class="form-control cantidad" value="1" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="detalles[${filaIndex}][precio_unitario]" class="form-control precio" required>
            </td>
            <td>
                <span class="subtotal">0.00</span>
            </td>
            <td>
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
        const cantidad = fila.querySelector('.cantidad').value || 0;
        const precio = fila.querySelector('.precio').value || 0;
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