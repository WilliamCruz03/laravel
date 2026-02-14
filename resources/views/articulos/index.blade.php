<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Módulo de Artículos</span>
            <div>
                <a href="{{ route('ventas.articulos.create') }}" class="btn btn-success me-2">Nuevo Artículo</a>
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

        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('ventas.articulos.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar por nombre o descripción..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('ventas.articulos.index') }}" class="btn btn-secondary ms-2">Limpiar</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title mb-0">Listado de Artículos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($articulos as $articulo)
                                    <tr>
                                        <td>{{ $articulo->id }}</td>
                                        <td>{{ $articulo->nombre }}</td>
                                        <td>{{ $articulo->descripcion ?? 'Sin descripción' }}</td>
                                        <td>${{ number_format($articulo->precio, 2) }}</td>
                                        <td>{{ $articulo->stock }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('ventas.articulos.edit', $articulo->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#eliminarModal"
                                                    data-id="{{ $articulo->id }}">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                No hay artículos registrados.
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-end">
                        Total de artículos: <span class="badge bg-primary">{{ count($articulos) }}</span>
                    </div>
                </div>
            </div>
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
                    ¿Estás seguro de que deseas eliminar este artículo?
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
        document.addEventListener('DOMContentLoaded', function() {
            const eliminarModal = document.getElementById('eliminarModal');
            eliminarModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const articuloId = button.getAttribute('data-id');
                const form = document.getElementById('formEliminar');
                form.action = '{{ url("ventas/articulos") }}/' + articuloId;
            });
        });
    </script>
</body>
</html>