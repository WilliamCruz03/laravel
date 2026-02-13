<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Editar Artículo</span>
            <a href="{{ route('ventas.articulos.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title mb-0">Editando: {{ $articulo->nombre }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ventas.articulos.update', $articulo->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $articulo->nombre) }}" placeholder="Ej: Laptop" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                <textarea class="form-control form-control-lg @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3" placeholder="Características del artículo">{{ old('descripcion', $articulo->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="precio" class="form-label fw-bold">Precio <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control form-control-lg @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $articulo->precio) }}" placeholder="0.00" required>
                                        @error('precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="stock" class="form-label fw-bold">Stock</label>
                                        <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $articulo->stock) }}" min="0">
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ventas.articulos.index') }}" class="btn btn-secondary btn-lg me-2">Cancelar</a>
                                <button type="submit" class="btn btn-warning btn-lg">Actualizar Artículo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>