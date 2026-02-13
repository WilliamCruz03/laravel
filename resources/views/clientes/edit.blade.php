<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Editar Cliente</span>
            <a href="{{ route('ventas.clientes.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title mb-0">Editando: {{ $cliente->nombre }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ventas.clientes.update', $cliente->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-footer text-muted">
                                <small>Los campos marcados con <span class="text-danger">*</span> son obligatorios</small>
                            </div>

                            <div class="mb-4">
                                <label for="nombre" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" 
                                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
                                       title="Solo se permiten letras y espacios"
                                       oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')" 
                                       placeholder="Ej: Juan Pérez" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}" placeholder="ejemplo@correo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="telefono" class="form-label fw-bold">Número de Teléfono</label>
                                <input type="tel" class="form-control form-control-lg @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" 
                                       pattern="[0-9\s\-\(\)]+" 
                                       title="Solo se permiten números, espacios, guiones y paréntesis"
                                       oninput="this.value = this.value.replace(/[^0-9\s\-\(\)]/g, '')"
                                       placeholder="Ej: 55-1234-5678">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="direccion" class="form-label fw-bold">Dirección</label>
                                <input type="text" class="form-control form-control-lg @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" placeholder="Calle, número, colonia, ciudad">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('ventas.clientes.index') }}" class="btn btn-secondary btn-lg me-2">Cancelar</a>
                                <button type="submit" class="btn btn-warning btn-lg">Actualizar Cliente</button>
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